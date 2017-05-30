<?php namespace App\Http\Controllers\Front;

use App\Models\Review;
use Illuminate\Http\Request;

class TestimonialController extends BaseFrontController
{
    public function __construct()
    {
        $this->showSidebar = true;

        parent::__construct();
        $this->bodyClass = 'review';
        $this->_loadFrontMenu('', 'category', 'left-menu-default', 'menu');
    }

    public function _handle(Request $request, Review $object, $slug)
    {
        $item = $object->getBySlug($slug);

        if (!$item) {
            return $this->_showErrorPage(404, 'Page not found');
        }

        $this->_setCurrentEditLink('Edit this review', 'reviews/edit/' . $item->id );

        $this->_loadFrontMenu($item->id, 'customs-link');

        $this->_setPageTitle($item->title);
        $this->_setMetaSEO($item->tags, $item->description, $item->thumbnail);

        $this->dis['object'] = $item;
        $field['id'] = ['compare' => 'NOT_IN', 'value' => $item->id];
        $field['status'] = [ 'compare' => '=', 'value' => 1 ];
        $this->dis['reviews'] = get_reviews($field);
        return $this->_showItem($item);
    }

    private function _showItem(Review $item)
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

    private function _defaultItem(Review $object)
    {
        return $this->_viewFront('reviews-templates.detail', $this->dis);
    }

    public function getAll() {
        $field['status'] = [ 'compare' => '=', 'value' => 1 ];
        $this->dis['reviews'] = get_reviews($field);
        return $this->_viewFront('reviews-templates.default', $this->dis);
    }

}
