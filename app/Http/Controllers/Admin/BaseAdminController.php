<?php namespace App\Http\Controllers\Admin;

use Acme;
use App\Http\Controllers\BaseController;
use App\Models;

abstract class BaseAdminController extends BaseController
{
    protected $dis = [], $bodyClass = '';

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth_admin');
        $this->middleware('is_staff');

        $count = $this->_countUnreadEmail();
        if ($count) {
            view()->share('unreadMailCount', $count);
        }
        $order = $this->_countUnreadOrder();
        if ($order) {
            view()->share([
                'unreadTransCount' => $order['count'],
                'lists' => $order['list'],
            ]);
        }
    }

    protected function _setPageTitle($title, $subTitle = '')
    {
        view()->share([
            'pageTitle' => $title,
            'subPageTitle' => $subTitle,
        ]);
    }

    protected function _loadAdminMenu($menuActive = '')
    {
        $menu = new Acme\CmsMenu();
        $menu->args = array(
            'menuName' => 'admin-menu',
            'menuClass' => 'page-sidebar-menu page-header-fixed',
            'container' => 'div',
            'containerClass' => 'page-sidebar navbar-collapse collapse',
            'containerId' => '',
            'containerTag' => 'ul',
            'childTag' => 'li',
            'itemHasChildrenClass' => 'menu-item-has-children',
            'subMenuClass' => 'sub-menu',
            'menuActive' => [
                'type' => 'custom-link',
                'related_id' => $menuActive,
            ],
            'activeClass' => 'active',
            'isAdminMenu' => true,
        );
        view()->share('CMSMenuHtml', $menu->getNavMenu());
    }

    protected function _loggedIn_userHasRole($role)
    {
        if ($this->loggedInAdminUserRole->slug == $role) {
            return true;
        }

        return false;
    }

    protected function _userHasRole(Models\AdminUser $user, $role)
    {
        if ($user->adminUserRole->slug == $role) {
            return true;
        }

        return false;
    }

    protected function _countUnreadEmail()
    {
        return Models\Contact::where('status', '<>', 1)->count();
    }

    protected function _countUnreadOrder()
    {
        $trans = Models\Booking::where('viewed', '<>', 1);
        $data['count'] = $trans->count();
        $data['list'] = $trans->get();
        return $data;
    }
}
