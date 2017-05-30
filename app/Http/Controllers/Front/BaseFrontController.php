<?php namespace App\Http\Controllers\Front;

use Acme;
use App\Http\Controllers\BaseController;
// use App\Http\Controllers\Front\FrontFoundation\Cart;
use App\Models;

use Request;

abstract class BaseFrontController extends BaseController
{
    //To use cart functions, uncomment this line
    // use Cart;

    protected $dis = [], $bodyClass = '';

    public function __construct()
    {
        parent::__construct();
        if ($this->_showConstructionMode()) {
            abort(503);
        }
        $this->_setMetaSEO();
        //To use cart functions, uncomment this line
        // $this->_getCart();
        $this->_loadFrontMenu('/', 'page');
        $this->_loadFrontMenu('', 'page', 'a-votre-ecoute', 'footer-list');
        $this->_loadFrontMenu('', 'page', 'site-a-decouvrir', 'footer-list');
        $this->_loadFrontMenu('', 'page', 'preparez-votre-voyage', 'footer-list');
        $this->_setSlideshow();
    }

    protected function _loadFrontMenu($menuActive = '', $type = 'custom-link', $menu_name = 'main-menu', $menu_class = "nav navbar-nav")
    {
        $menu = new Acme\CmsMenu();
        $menu->args = array(
            'menuName' => $menu_name,
            'menuClass' => $menu_class,
            'container' => '',
            'containerClass' => '',
            'containerId' => '',
            'containerTag' => 'ul',
            'childTag' => 'li',
            'itemHasChildrenClass' => '',
            'subMenuClass' => '',
            'menuActive' => [
                'type' => $type,
                'related_id' => $menuActive,
            ],
            'activeClass' => 'active',
            'isAdminMenu' => false,
        );

        $megaMenu = new Acme\MegaMenu();
        $megaMenu->args = array(
            'menuName' => 'main-menu',
            'menuClass' => 'nav navbar-nav',
            'container' => '',
            'containerClass' => '',
            'containerId' => '',
            'containerTag' => 'ul',
            'childTag' => 'li',
            'itemHasChildrenClass' => '',
            'subMenuClass' => 'dropdown-menu mega-dropdown-menu',
            'menuActive' => [
                'type' => $type,
                'related_id' => $menuActive,
            ],
            'activeClass' => 'active',
        );
        view()->share('mega_menu', $megaMenu->getNavMenu());
        view()->share(str_replace('-', '_', $menu_name), $menu->getNavMenu());
    }

    protected function _setPageTitle($title)
    {
        view()->share([
            'pageTitle' => $title,
        ]);
    }

    protected function _setCurrentEditLink($title, $link)
    {
        view()->share([
            'currentFrontEditLink' => [
                'title' => $title,
                'link' => '/' . $this->adminCpAccess . '/' . $link,
            ],
        ]);
    }

    /**
     * @param Models\Foundation\MetaFunctions $modelObject
     * @param int $rules: $contentId
     **/
    protected function _getAllCustomFields($modelObject, $contentId)
    {
        $this->dis['currentObjectCustomFields'] = $modelObject->getAllContentMeta($contentId);
    }

    protected function _setMetaSEO($keywords = null, $description = null, $image = null)
    {
        $data = [];
        if ($keywords) {
            $data['keywords'] = $keywords;
        } else {
            $data['keywords'] = $this->_getSetting('site_keywords');
        }
        if ($description) {
            $data['description'] = $description;
        } else {
            $data['description'] = $this->_getSetting('site_description');
        }
        if ($image) {
            $data['image'] = asset($image);
        } else {
            $data['image'] = asset($this->_getSetting('site_logo'));
        }
        view()->share([
            'metaSEO' => $data,
        ]);
    }

    protected function _setSlideshow($objectMeta = null, $id = null) {
        $this->dis['show_slide'] = true;
        if($objectMeta == null &&  $id == null) {
            $id = $this->CMSSettings['default_homepage'];
            $objectMeta = new Models\PageMeta;
        }
        $this->_getAllCustomFields($objectMeta, $id);
        $field_decode = json_decode($this->dis['currentObjectCustomFields']['slideshow_box']);
        $slides_default = [];
        foreach ($field_decode as $value) {
            $slides_default [] = [
                'title' => $value[0]->field_value,
                'caption' => $value[1]->field_value,
                'link' => $value[2]->field_value,
                'image' => $value[3]->field_value
            ];
        }
        view()->share('slides_default', $slides_default);
    }

    // protected function getAllCountry () {
    //     $select = ['id', 'country_name'];
    //     return Models\Country::getAll($select);
    // }
}
