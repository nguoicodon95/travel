<?php namespace App\Http\Controllers\Front;

use App\Models\Product;
use App\Models\ProductMeta;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends BaseFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'product';
    }

    public function _handle(Request $request, Product $object, ProductMeta $objectMeta, $slug)
    {
        $item = $object->getBySlug($slug);

        if (!$item) {
            return $this->_showErrorPage(404, 'Page not found');
        }

        $this->_setCurrentEditLink('Edit this product', 'products/edit/' . $item->id);

        $relatedCategoryIds = $item->category()->getRelatedIds();
        if($relatedCategoryIds) {
            $relatedCategoryIds = $relatedCategoryIds->toArray();
        } else {
            $relatedCategoryIds = [];
        }
        $this->_loadFrontMenu($relatedCategoryIds, 'product-category');
        $this->_loadFrontMenu($relatedCategoryIds, 'product-category', 'danh-muc-san-pham', null);
        $this->_setPageTitle($item->title);
        $this->_setMetaSEO($item->tags, $item->description, $item->thumbnail);
        $this->dis['object'] = $item;
        /* Get products same category */
        $getByFields['products.status'] = ['compare' => '=', 'value' => 1];
        $getByFields['products.id'] = ['compare' => '!=', 'value' => $item->id];
        $pr_sm = $products = $products_in_subcate = $product_in_cate = [];

        foreach($relatedCategoryIds as $cateId) {
            $_getcate = ProductCategory::getById($cateId);
            $child = $_getcate->child()->get();
             if(!empty($child)) {
                foreach($child as $sub_cate) {
                    $products_in_subcate[] = Product::getNoContentByCategory($sub_cate->id, $getByFields, [], null, 0);
                }
            }

            $product_in_cate[] = Product::getNoContentByCategory($cateId, $getByFields, [], null, 0);
        }
        $products = collect($products_in_subcate)->merge($product_in_cate);
        foreach($products as $product) {
            foreach($product->sortByDesc('id') as $p) {
                $pr_sm[] = $p;
            }
        }
        $all_product = _unique_multidim_array($pr_sm, 'id');
        $this->dis['same_product'] = $all_product;

        // Product of brand
        $getByFieldswithBrand['status'] = ['compare' => '=', 'value' => 1];
        $getByFieldswithBrand['brand_id'] = ['compare' => '=', 'value' => $item->brand_id];
        $product_s_brand = $object->searchBy($getByFieldswithBrand, ['id' => 'desc'], true, 8);
        $p_of_brand = [];
        foreach ($product_s_brand as $r) {
            $r = $r->productContent;
            $p_of_brand[] = [
              'title' => $r->title,
              'slug' => $r->slug,
              'thumbnail' => $r->thumbnail,
              'price' => $r->price,
              'old_price' => $r->old_price,
            ];
        }
        $this->dis['product_s_brand'] = collect($p_of_brand);

        $this->_getAllCustomFields($objectMeta, $item->content_id);

        return $this->_showItem($item);
    }

    private function _showItem(Product $item)
    {
        $page_template = $item->page_template;
        if (trim($page_template) != '') {
            $function = '_product_' . str_replace(' ', '', trim($page_template));
            if (method_exists($this, $function)) {
                return $this->{$function}($item);
            }
        }
        return $this->_defaultItem($item);
    }

    private function _defaultItem(Product $object)
    {
        /* Get Gallery of product */
        if(isset($this->dis['currentObjectCustomFields']['19_images'])) {
            $gals = $this->dis['currentObjectCustomFields']['19_images'];
            $gals = json_decode($gals, true);
            $thumbs = [];
            foreach($gals as $gal) {
                $thumbs[] = [
                    'src' => $gal[0]['field_value'],
                    'title' => $gal[1]['field_value']
                ];
            }
            $this->dis['thumbs'] = $thumbs;
        }

        /* Get Attribute of product */
        if(isset($this->dis['currentObjectCustomFields']['29_thuoc_tinh_san_pham'])) {
            $attr = $this->dis['currentObjectCustomFields']['29_thuoc_tinh_san_pham'];
            $attr = json_decode($attr, true);
            $attributes = [];
            foreach($attr as $a) {
                $attributes[$a[0]['field_value']] = $a[1]['field_value'];
            }
            $this->dis['attributes'] = $attributes;
        }

        $this->_setBodyClass($this->bodyClass . ' product-default');
        return $this->_viewFront('product-templates.default', $this->dis);
    }
}
