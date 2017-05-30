<?php namespace App\Http\Controllers\Front;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryMeta;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use Acme;

class ProductCategoryController extends BaseFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'product-category';

    }

    public function index () {
        // dd(1);
    }

    public function _handle(Request $request, ProductCategory $object, ProductCategoryMeta $objectMeta)
    {
        $segments = $request->segments();
        $slug = end($segments);

        $item = $object->getBySlug($slug);

        if (!$item) {
            return $this->_showErrorPage(404, 'Page not found');
        }

        $this->_setCurrentEditLink('Edit this product category', 'product-categories/edit/' . $item->id);

        $this->_loadFrontMenu($item->id, 'product-category');
        $this->_loadFrontMenu($item->id, 'product-category', 'danh-muc-san-pham', null);
        $this->_setPageTitle($item->title);
        $this->_setMetaSEO($item->tags, $item->description, $item->thumbnail);

        /* Điều kiện tình trạng sản phẩm đã được active  */
        $getByFields['products.status'] = ['compare' => '=', 'value' => 1];

        /* Lấy ra các danh mục con */
        $child = $item->child()->get();

        $ps = $products = $products_in_subcate = $product_in_cate = [];

        /* Lấy ra các sản phẩm trong danh mục con nếu có */
        if(!empty($child)) {
            foreach($child as $sub_cate) {
                $products_in_subcate[] = Product::getNoContentByCategory($sub_cate->id, $getByFields, [], null, 0);
            }
        }

        /* Lấy ra các sản phẩm các sản phẩm trong danh mục hiện tại */
        $product_in_cate[] = Product::getNoContentByCategory($item->id, $getByFields, [], null, 0);

        /* Gộp các sản phẩm của con và cha lại */
        $products = collect($products_in_subcate)->merge($product_in_cate);
        $sort_by_el = [ 'asc', 'desc' ];
        $brands = [];
        foreach($products as $product) {
            foreach($product as $p) {
                $ps[] = $p;
                if(!in_array($p->brand, $brands))
                  $brands = array_prepend($brands, $p->brand);
            }
        }
        // Get nha san xuat
        $this->dis['brands'] = $brands;

        /* Convert to collection */
        $ps = collect($ps);
        $ps_sort_filter = null;

        /* Tạo tìm kiếm theo giá */
        $max = $ps->max('price');
        if($max != 0) {
            switch ($max) {
                case (int) $max < 10000000 :
                    $step = $max/2;
                    $per = 2;
                    break;
                case (int) $max > 10000000 && (int) $max <30000000 :
                    $step = $max/3;
                    $per = 3;
                    break;
                case (int) $max > 30000000 :
                    $step = $max/5;
                    $per = 5;
                    break;
                default:
                    $step = $max;
                    $per = 1;
                    break;
            }

            $betweenPrice = [];
            for($i = 0; $i <= $per; $i++ ) {
                $betweenPrice[] = (int) ($i * $step);
            }
            $this->dis['rangePrice'] = $this->createRange($betweenPrice);
        }

        /* Tìm kiếm theo khoảng giá */
        if($_getPrice = $request->get('price')) {
            $this->dis['price_filter'] = (string) $_getPrice;
            if($_getPrice == 'default') $ps_sort_filter = $ps;
            else {
                $price_convert_array = explode('-', $_getPrice);
                $ps_sort_filter = (!empty($ps_sort_filter)) ? $ps_sort_filter : $ps;
                $ps_sort_filter = $ps_sort_filter->filter(function ($value, $key) use ($price_convert_array) {
                    if($value->price >= (double) $price_convert_array[0] && $value->price <= (double) $price_convert_array[1] ) {
                        return true;
                    }
                    return false;
                });
            }
        }

        /* Tìm theo nhà sản xuất */
        if($f_brand = $request->brands) {
            $f_brand = explode(',', $f_brand);
            $this->dis['f_brand'] = (array)$f_brand;
            $ps_sort_filter = (!empty($ps_sort_filter)) ? $ps_sort_filter : $ps;
            $ps_sort_filter = $ps_sort_filter->whereInLoose('brand_id', $f_brand);
        }

        /* Sắp xếp */
        /* Nếu có request get sortby thì sắp xếp theo request */
        if( $sortBy = $request->get('sortby') ) {
            $this->dis['sort_by'] = $sortBy;
            switch ($sortBy) {
                case 'asc':
                    $ps_sort_filter = (!empty($ps_sort_filter)) ? $ps_sort_filter->sortBy('price') : $ps->sortBy('price');
                    break;

                case 'desc':
                    $ps_sort_filter = (!empty($ps_sort_filter)) ? $ps_sort_filter->sortByDesc('price') : $ps->sortByDesc('price');
                    break;
            }
        } else {
            $ps_sort_filter = (!empty($ps_sort_filter)) ? $ps_sort_filter->sortByDesc('id') : $ps->sortByDesc('id');
        }

        /* Mặc định sắp xếp thep id giảm dần (Mới nhất) */
        $all_product = _unique_multidim_array($ps_sort_filter, 'id');

        /*Tạo phân trang */
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $total = new Collection($all_product);
        $perPage = 15;
        $currentPageSearchResults = $total->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $this->dis['all_product'] = new LengthAwarePaginator($currentPageSearchResults, count($total), $perPage);

        /* Trả lại kết quả*/
        $this->dis['object'] = $item;
        $this->_getAllCustomFields($objectMeta, $item->content_id);

        return $this->_showItem($item);
    }

    private function _showItem($item)
    {
        $page_template = $item->page_template;
        if (trim($page_template) != '') {
            $function = '_productCategory_' . str_replace(' ', '', trim($page_template));
            if (method_exists($this, $function)) {
                return $this->{$function}($item);
            }
        }
        return $this->_defaultItem($item);
    }

    private function _defaultItem(ProductCategory $object)
    {
        $this->_setBodyClass($this->bodyClass . ' product-category-default');

        return $this->_viewFront('product-category-templates.default', $this->dis);
    }

    /* Tạo range giá tiền */
    private function createRange($array)
    {
        sort($array);

        $rangeLimits = $array;

        $ranges = array();

        for($i = 0; $i < count($rangeLimits); $i++){
            if($i == count($rangeLimits)-1){
                break;
            }
            $lowLimit = $rangeLimits[$i];
            $highLimit = $rangeLimits[$i+1];

            $ranges[$i]['min'] = round($lowLimit, -3);
            $ranges[$i]['max'] = round($highLimit, -3);

        }
        return $ranges;
    }

}
