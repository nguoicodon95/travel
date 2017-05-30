<?php namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryController extends BaseFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'category';
        $this->dis['show_blog'] = false;
    }

    public function _handle(Request $request, Category $object, CategoryMeta $objectMeta)
    {
        $segments = $request->segments();
        $slug = end($segments);
        $item = $object->getBySlug($slug);

        if (!$item) {
            return $this->_showErrorPage(404, 'Page not found');
        }

        $this->_setCurrentEditLink('Edit this category', 'categories/edit/' . $item->id . '/');

        $this->_loadFrontMenu($item->id, 'category');
        $this->_loadFrontMenu($item->id, 'category', 'left-menu-default', 'menu');
        $this->_setPageTitle($item->title);
        $this->_setMetaSEO($item->tags, $item->description, $item->thumbnail);

        /**
         * Get catalog child of catalog current
         */
         $this->dis['catalog_current'] = $item->slug;
         $child_catalog = $item->child()->get();

        /* GET POSTS IN CATEGORY WHERE STATUS ACTIVE */
        $getByFields['posts.status'] = ['compare' => '=', 'value' => 1];
        $select = [
          'posts.title',
          'posts.description',
          'posts.thumbnail',
          'posts.slug',
          'categories.title as cate_title',
          'categories.slug as cate_slug',
          'categories.id as cate_id',
        ];
        $posts = $post_in_subcate = $post_in_cate = [];
        if(!empty($child_catalog)) {
            foreach($child_catalog as $sub_cate) {
                $post_in_subcate[] = Post::getNoContentByCategory($sub_cate->id, $getByFields, ['posts.id' => 'desc'], $select, 0);
            }
        }
        $post_in_cate[] = Post::getNoContentByCategory($item->id, $getByFields, ['posts.id' => 'desc'], $select, 0);
        /* Gộp các post của con và cha lại */
        $posts = collect($post_in_subcate)->merge($post_in_cate);
        $temp = [];
        foreach($posts as $post) {
            foreach($post as $p) {
                if(!in_array($p, $temp))
                    $temp = array_prepend($temp, $p);
            }
        }
        $relatedPosts = collect($temp);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $total = new Collection($relatedPosts);
        $perPage = 10;
        $currentPageSearchResults = $total->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Return data
        $this->dis['posts'] = new LengthAwarePaginator($currentPageSearchResults, count($total), $perPage);
        $this->dis['object'] = $item;
        $this->_getAllCustomFields($objectMeta, $item->id);
        if(!isset($this->dis['currentObjectCustomFields']['slideshow_box'])) {
            $this->_setSlideshow();
        }
         else {
            $this->_setSlideshow($objectMeta, $item->id);
        }

        $field['status'] = [ 'compare' => '=', 'value' => 1 ];
        $this->dis['reviews'] = get_reviews($field);
        
        return $this->_showItem($item);
    }

    private function _showItem(Category $item)
    {
        $page_template = $item->page_template;
        if (trim($page_template) != '') {
            $function = '_category_' . str_replace(' ', '', trim($page_template));
            if (method_exists($this, $function)) {
                return $this->{$function}($item);
            }
        }
        return $this->_defaultItem($item);
    }

    private function _defaultItem(Category $object)
    {
        $this->_setBodyClass($this->bodyClass . ' category-default');
        return $this->_viewFront('category-templates.default', $this->dis);
    }

    private function _setCatalog ($item) {
        $parent = $item->parent->get();
        foreach ($parent as $row) {
            if($row->parent_id == 0) {
                $child_catalog = $row->child;
                $this->dis['catalog']['root'] = $row->title;
                $this->dis['catalog']['child_catalog'] = $child_catalog;
            }
        }
        return $this->dis['catalog'];
    }
}
