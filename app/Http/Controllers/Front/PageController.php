<?php namespace App\Http\Controllers\Front;

use App\Models;
use App\Models\Page;
use App\Models\PageMeta;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class PageController extends BaseFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'page';
    }

    public function _handle(Request $request, Page $object, PageMeta $objectMeta, $slug = '')
    {
        $item = $object->getBySlug($slug);
        if(empty($slug)) {
            $item = $object->getById($this->_getSetting('default_homepage'));
        }
        if (!$item) {
            return $this->_showErrorPage(404, 'Page not found');
        }
        $this->_setCurrentEditLink('Edit this page', 'pages/edit/' . $item->id);
        $this->_loadFrontMenu($item->id, 'page');
        $this->_setPageTitle($item->title);
        $this->_setMetaSEO($item->tags, $item->description, $item->thumbnail);
        $this->dis['object'] = $item;

        $this->_getAllCustomFields($objectMeta, $item->id, 'page');

        if(!isset($this->dis['currentObjectCustomFields']['slideshow_box'])) {
            $this->_setSlideshow();
        }
        $field['status'] = [ 'compare' => '=', 'value' => 1 ];
        $this->dis['reviews'] = get_reviews($field);
        return $this->_showItem($item);
    }

    private function _showItem(Page $item)
    {
        $page_template = $item->page_template;
        if (trim($page_template) != '') {
            $function = '_page_' . str_replace(' ', '', trim($page_template));
            if (method_exists($this, $function)) {
                return $this->{$function}($item);
            }
        }
        return $this->_defaultItem($item);
    }

    private function _defaultItem(Page $object)
    {
        $this->_loadFrontMenu('', 'page', 'left-menu-on-page', 'list-group');

        $this->_setBodyClass($this->bodyClass . ' page-default');
        return $this->_viewFront('page-templates.default', $this->dis);
    }

    /* Template Name: Homepage*/
    private function _page_Homepage(Page $object)
    {
        $this->_setBodyClass($this->bodyClass . ' page-homepage');

        /* get setting homepage customfield */
        if(isset($this->dis['currentObjectCustomFields']['title'])) {
            $this->dis['customfield']['title'] = $this->dis['currentObjectCustomFields']['title'];
        }
        if(isset($this->dis['currentObjectCustomFields']['body'])) {
            $this->dis['customfield']['body'] = $this->dis['currentObjectCustomFields']['body'];
        }
        if(isset($this->dis['currentObjectCustomFields']['about_post'])) {
            $rather_post = [];
            $about_post = $this->dis['currentObjectCustomFields']['about_post'];
            foreach (json_decode($about_post) as $row) {
                $rather_post [] = [
                    'title' => $row[0]->field_value,
                    'image' => $row[1]->field_value,
                    'link' => $row[2]->field_value,
                ];
            }
            $this->dis['customfield']['about_post'] = $rather_post;
        }

        /* Get post favourite */
        $fieldWhere['status'] = ['compare' => '=', 'value' => 1];
        $fieldWhere['is_favourite'] = ['compare' => '=', 'value' => 1];
        $select_for_favourite = [
            'posts.title',
            'posts.slug',
            'posts.description',
            'posts.thumbnail',
            'posts.tags',
            'posts.id',
        ];
        $post_favourite = Models\Post::getWithContent($fieldWhere, $select_for_favourite, ['id' => 'desc'], true, 0);

        /* Get NOS DESTINATIONS */
        $cate_fieldWhere['status'] = ['compare' => '=', 'value' => 1];
        $cate_fieldWhere['show_child'] = ['compare' => '=', 'value' => 1];
        $select_for_cate = [];
        $catalog_show = Models\Category::getWithContent($cate_fieldWhere, $select_for_cate, ['id' => 'desc'], true, 0);
        $temp_catalog_child = [];
        foreach ($catalog_show as $row) {
            $temp_catalog_child[$row->title] = $row->child()->select('id', 'title', 'thumbnail', 'slug')->get();
        }

        /* Get catalog positon */
        $post_in_cate_fieldWhere['status'] = ['compare' => '=', 'value' => 1];
        $post_in_cate_fieldWhere['order'] = ['compare' => '!=', 'value' => 0];
        $select_for_post_in_cate = ['id', 'title', 'thumbnail', 'slug'];
        $catalogs = Models\Category::getWithContent($post_in_cate_fieldWhere, $select_for_post_in_cate, ['id' => 'desc'], true, 0);
        $temp_post_in_cate = [];
        foreach ($catalogs as $row) {
            $temp_post_in_cate[] = [
              'title' => $row->title, 'id' => $row->id,
              'posts' => $row->post()->select('posts.id', 'posts.title', 'posts.thumbnail', 'posts.slug', 'posts.description')->where('posts.is_popular', 1)->get()
            ];
        }



        // Return data
        $this->dis['post_favourite'] = $post_favourite;
        $this->dis['catalog_child'] = $temp_catalog_child;
        $this->dis['post_in_cate'] = $temp_post_in_cate;
        return $this->_viewFront('page-templates.homepage', $this->dis);
    }

    /* Template Name: Contact Us*/
    private function _page_ContactUs(Page $object)
    {
        $this->dis['show_slide'] = false;
        if(isset($this->dis['currentObjectCustomFields']['map_for_contact_page'])) {
            $this->dis['map'] = $this->dis['currentObjectCustomFields']['map_for_contact_page'];
        }
        $this->_setBodyClass($this->bodyClass . ' page-contact');
        return $this->_viewFront('page-templates.contact-us', $this->dis);
    }
}
