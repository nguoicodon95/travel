<?php namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostMeta;
use Illuminate\Http\Request;

class PostController extends BaseFrontController
{
    public function __construct()
    {
        $this->showSidebar = true;

        parent::__construct();
        $this->bodyClass = 'post';
    }

    public function _handle(Request $request, Post $object, PostMeta $objectMeta, $slug)
    {
        $item = $object->getBySlug($slug);

        if (!$item) {
            return $this->_showErrorPage(404, 'Page not found');
        }

        $this->_setCurrentEditLink('Edit this post', 'posts/edit/' . $item->id );

        $relatedCategoryIds = $item->category()->getRelatedIds();

        if($relatedCategoryIds) {
            $relatedCategoryIds = $relatedCategoryIds->toArray();
        }

        $this->_loadFrontMenu($relatedCategoryIds, 'category');

        $this->_setPageTitle($item->title);
        $this->_setMetaSEO($item->tags, $item->description, $item->thumbnail);

        $this->dis['object'] = $item;

        $this->_getAllCustomFields($objectMeta, $item->id);
        $custom_field_gallery_post = '';
        if(isset($this->dis['currentObjectCustomFields']['them_anh_bai_viet'])) {
            $custom_field_gallery_post = json_decode($this->dis['currentObjectCustomFields']['them_anh_bai_viet']);
        }
        $temp_gallery = [];
        if(is_array($custom_field_gallery_post) && !empty($custom_field_gallery_post)) {
            foreach ($custom_field_gallery_post as $row) {
                $temp_gallery[] = [
                    'title' => $row[0]->field_value,
                    'image' => $row[1]->field_value
                ];
            }
        }
        $this->dis['gallery_post'] = $temp_gallery;

        $getByFields['posts.status'] = ['compare' => '=', 'value' => 1];
        $getByFields['posts.id'] = ['compare' => '!=', 'value' => $item->id];
        $post_in_category = [];
        $select_post_same_cate = [
            'posts.title',
            'posts.description',
            'posts.thumbnail',
            'posts.slug',
            'categories.title as cate_title',
            'categories.slug as cate_slug',
            'categories.id as cate_id',
        ];
        foreach ($relatedCategoryIds as $k => $v) {
            $post_in_category[] = $item->getNoContentByCategory($v, $getByFields, ['posts.id' => 'desc'], $select_post_same_cate, 0);
            $category = Category::getById($v);
            $this->dis['catalog_current'][] = $category->slug;
            $child_catalog = $category->child()->get();
            if((int)$category->parent_id == 0) {
                $this->dis['catalog']['root'] = $category->title;
                $this->dis['catalog']['child_catalog'] = $child_catalog;
            } else {
                $this->_setCatalog($category);
            }
            $categoryRelated[] = '<a href="'._getCategoryLinkWithParentSlugs($category->id).'">'.$category->title.'</a>';
        }
        $categoryRelated = implode($categoryRelated, ', ');
        $this->dis['public_in'] = $categoryRelated;

        $catalog_current = $this->dis['catalog_current'];
        // Get post same
        $post_same_category = collect($post_in_category);
        $temp_post_same_category = [];
        foreach($post_same_category as $post) {
            foreach($post as $p) {
                if(!in_array($p, $temp_post_same_category))
                    $temp_post_same_category = array_prepend($temp_post_same_category, $p);
            }
        }
        $this->dis['post_same_category'] = $temp_post_same_category;

        $this->dis['activity_type'] = config('booking.default.activity_type');
        $this->dis['travel_type'] = config('booking.default.travel_type');
        $this->dis['type_eat'] = config('booking.default.type_eat');
        return $this->_showItem($item);
    }

    private function _showItem(Post $item)
    {
        $page_template = $item->page_template;
        if (trim($page_template) != '') {
            $function = '_post_' . str_replace(' ', '', trim($page_template));
            if (method_exists($this, $function)) {
                return $this->{$function}($item);
            }
        }
        return $this->_defaultItem($item);
    }

    private function _defaultItem(Post $object)
    {
        $this->_setBodyClass($this->bodyClass . ' post-default');
        return $this->_viewFront('post-templates.default', $this->dis);
    }

    /* Template Name: Nos circuits*/
    private function _post_NosCircuits(Post $object)
    {
        $this->_setBodyClass($this->bodyClass . ' page-nos-circuits');
        return $this->_viewFront('post-templates.noscircuits', $this->dis);
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
