<?php namespace Acme;

use App\Models;

class MegaMenu
{
    public $localeObj;
    public $args = array(
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
            'type' => 'custom-link',
            'related_id' => 0,
        ],
        'activeClass' => 'active',
    );

    public function getNavMenu()
    {
        $defaultArgs = array(
            'menuName' => '',
            'menuClass' => '',
            'container' => 'nav',
            'containerClass' => '',
            'containerId' => '',
            'containerTag' => 'ul',
            'childTag' => 'li',
            'itemHasChildrenClass' => '',
            'subMenuClass' => '',
            'menuActive' => [
                'type' => 'custom-link',
                'related_id' => 0,
            ],
            'activeClass' => 'active',
        );
        $defaultArgs = array_merge($defaultArgs, $this->args);

        $output = '';
        $menu = Models\Menu::where('slug', '=', ltrim($defaultArgs['menuName']))->first();
        // Menu exists
        if (!is_null($menu)) {
            if ($defaultArgs['container'] != '') {
                $output .= '<' . $defaultArgs['container'] . ' class="' . $defaultArgs['containerClass'] . '" id="' . $defaultArgs['containerId'] . '">';
            }
            //<nav>
            $output .= '<' . $defaultArgs['containerTag'] . ' class="' . $defaultArgs['menuClass'] . '">'; //<ul>
            $child_args = array(
                'menuContentId' => $menu->id,
                'parentId' => 0,
                'containerTag' => $defaultArgs['containerTag'],
                'childTag' => $defaultArgs['childTag'],
                'itemHasChildrenClass' => $defaultArgs['itemHasChildrenClass'],
                'subMenuClass' => $defaultArgs['subMenuClass'],
                'containerTagAttr' => '',
                'menuActive' => $defaultArgs['menuActive'],
                'defaultActiveClass' => $defaultArgs['activeClass'], //default active class
            );
          
            $output .= $this->getMenuItems($child_args);
            $output .= '</' . $defaultArgs['containerTag'] . '>'; //</ul>
            if ($defaultArgs['container'] != '') {
                $output .= '</' . $defaultArgs['container'] . '>';
            }
            //</nav>
        }
        return $output;
    }

    // Function get all menu items
    private function getMenuItems($item_args)
    {
        $output = '';
        $menuItems = Models\MenuNode::getBy([
            'menu_id' => $item_args['menuContentId'],
            'parent_id' => $item_args['parentId'],
        ], ['position' => 'ASC'], true);

        if ($menuItems) {
            (sizeof($menuItems) > 0 && $item_args['parentId'] != 0) ? $output .= '<' . $item_args['containerTag'] . ' class="' . $item_args['subMenuClass'] . '"' . $item_args['containerTagAttr'] . '>' : $output .= ''; // <ul> will be printed if current is not level 0
            foreach ($menuItems as $key => $row) {
                $arrow = '';

                // Get menu active class
                $active_args = array(
                    'menuActive' => $item_args['menuActive'],
                    'item' => $row,
                    'defaultActiveClass' => $item_args['defaultActiveClass'],
                );
                // dd($row->parent_id);
                $activeClass = $this->getActiveItems($active_args);
                if ($this->checkChildItemIsActive(array('parent' => $row, 'menuActive' => $item_args['menuActive'], 'defaultActiveClass' => $item_args['defaultActiveClass']))) {
                    if (trim($activeClass) == '') {
                        $activeClass = 'active';
                    }
                }

                $menu_title = $this->getMenuItemTitle($row);
                $menu_link = $this->getMenuItemLink($row);
                $parent_class = $row->css_class;
                if ($this->checkItemHasChildren($row)) {
                    $parent_class .= $item_args['itemHasChildrenClass'];
                }

                $child_args = array(
                    'menuContentId' => $item_args['menuContentId'],
                    'parentId' => $row->id,
                    'containerTag' => $item_args['containerTag'],
                    'childTag' => $item_args['childTag'],
                    'itemHasChildrenClass' => $item_args['itemHasChildrenClass'],
                    'subMenuClass' => $item_args['subMenuClass'],
                    'containerTagAttr' => '',
                    'menuActive' => $item_args['menuActive'],
                    'defaultActiveClass' => $item_args['defaultActiveClass'], //default active class
                );

                $menu_icon = $menu_title;
                $linkClass = '';
                if ( !empty($parent_class) && !empty($activeClass) ) {
                    $class_child_tag = ' class="' . $parent_class . ' ' . $activeClass .'"';
                } elseif (!empty($parent_class)) {
                    $class_child_tag = ' class="' . $parent_class .'"';
                } elseif (!empty($activeClass)) {
                    $class_child_tag = ' class="' . $activeClass .'"';
                } else {
                    $class_child_tag = '';
                }

                if( $row->parent_id != 0 ) {
                    $child_args['subMenuClass'] = '';
                }
                if( $row->parent_id == 0 && count($row->child) > 0) {
                    $arrow = '<span class="caret"></span>';//<span class="caret"></span>
                }

                $output .= '<' . $item_args['childTag'] . $class_child_tag . '>'; #<li>
                $output .= '<a class="' . $linkClass . '" href="' . $menu_link . '">' . $menu_title . $arrow . '</a>';
                $child_args['itemHasChildrenClass'] = 'col-sm-4';
                // $child_args['subMenuClass'] = '';
                $output .= $this->getMenuItems($child_args);
                $output .= '</' . $item_args['childTag'] . '>'; #</li>
            }
            (sizeof($menuItems) > 0 && $item_args['parentId'] != 0) ? $output .= '</' . $item_args['containerTag'] . '>' : $output .= ''; // </ul>
        }
        return $output;
    }

    // Menu active
    private function getActiveItems($args)
    {
        $temp = $args['menuActive'];
        $result = '';
        if ($args['item']->type == $args['menuActive']['type']) {
            if (is_array($args['menuActive']['related_id'])) {
                switch ($args['menuActive']['type']) {
                    case 'category': {
                        if (in_array($args['item']->related_id, $args['menuActive']['related_id'])) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                    case 'product-category': {
                        if (in_array($args['item']->related_id, $args['menuActive']['related_id'])) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                    default: {
                        if (in_array($args['item']->related_id, $args['menuActive']['related_id'])) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                }
            } else {
                switch ($args['menuActive']['type']) {
                    case 'category': {
                        if ($args['menuActive']['related_id'] == $args['item']->related_id) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                    case 'product-category': {
                        if ($args['menuActive']['related_id'] == $args['item']->related_id) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                    case 'custom-link': {
                        $currentUrl = \Request::url();
                        if (asset($args['item']->url) == asset($currentUrl) || asset($args['item']->url) == asset($currentUrl . '/')) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                    default: {
                        if ($args['menuActive']['related_id'] == $args['item']->related_id) {
                            $result = $args['defaultActiveClass'];
                        }
                    }
                        break;
                }
            }
        }
        return $result;
    }

    // Check children active
    private function checkChildItemIsActive($args)
    {
        return $this->_recursiveIsChildItemActive($args);
    }

    private function _recursiveIsChildItemActive($args)
    {
        if ($this->getActiveItems(array('menuActive' => $args['menuActive'], 'item' => $args['parent'], 'defaultActiveClass' => $args['defaultActiveClass']))) {
            return true;
        }
        $result = false;
        $menuNodes = Models\MenuNode::getBy([
            'parent_id' => $args['parent']->id,
        ], ['position' => 'ASC'], true);
        foreach ($menuNodes as $key => $row) {
            $childArgs = $args;
            $childArgs['parent'] = $row;
            $result = $this->_recursiveIsChildItemActive($childArgs);
            if ($result) {
                return true;
            }

        }
        return $result;
    }

    // Get item title
    private function getMenuItemTitle($item)
    {
        $data_title = '';
        switch ($item->type) {
            case 'page': {
                $title = $item->title;
                if (!$title) {
                    $page = Models\Page::getBy([
                        'id' => $item->related_id,
                    ]);
                    if ($page) {
                        $title = ((trim($page->title) != '') ? trim($page->title) : '');
                    } else {
                        $title = '';
                    }
                }
                $data_title = $title;
            }
                break;
            case 'category': {
                $title = $item->title;
                if (!$title) {
                    $cat = Models\Category::getWithContent([
                        'categories.id' => [
                            'compare' => '=',
                            'value' => $item->related_id,
                        ]
                    ]);
                    if ($cat) {
                        $title = ((trim($cat->title) != '') ? trim($cat->title) : trim($cat->global_title));
                        if( $item->parent_id != 0 && $cat->thumbnail ) {
                            if( $this->checkItemHasChildren($item) ) {
                                $title .= '<div class="thumb"><img src="'. $cat->thumbnail .'"></div>';
                            }
                        }
                    } else {
                        $title = '';
                    }
                }
                $data_title = $title;
            }
                break;
            case 'product-category': {
                $title = $item->title;
                if (!$title) {
                    $cat = Models\ProductCategory::getWithContent([
                        'product_categories.id' => [
                            'compare' => '=',
                            'value' => $item->related_id,
                        ]
                    ]);
                    if ($cat) {
                        $title = ((trim($cat->title) != '') ? trim($cat->title) : trim($cat->global_title));
                    } else {
                        $title = '';
                    }
                }
                $data_title = $title;
            }
                break;
            case 'custom-link': {
                $data_title = $item->title;
                if (!$data_title) {
                    $data_title = '';
                }

            }
                break;
            default: {
                $data_title = $item->title;
                if (!$data_title) {
                    $data_title = '';
                }

            }
                break;
        }
        // $data_title = htmlentities($data_title);
        return $data_title;
    }

    // Get item links
    private function getMenuItemLink($item)
    {
        $result = '';
        switch ($item->type) {
            case 'page': {
                $slug = '';
                $page = Models\Page::getWithContent([
                    'pages.id' => [
                        'compare' => '=',
                        'value' => $item->related_id,
                    ]
                ]);
                if ($page) {
                    $slug = (trim($page->slug) != '') ? trim($page->slug) : '';
                }

                $result = _getPageLink($slug);
            }
                break;
            case 'category': {
                $result = _getCategoryLinkWithParentSlugs($item->related_id);
            }
                break;
            case 'product-category': {
                $result = _getProductCategoryLinkWithParentSlugs($item->related_id);
            }
                break;
            case 'custom-link': {
                $result = $item->url;
            }
                break;
            default: {
                $result = $item->url;
            }
                break;
        }
        return $result;
    }

    // Check menu has children or not
    private function checkItemHasChildren($item)
    {
        if (count($item->child) > 0) {
            return true;
        }

        return false;
    }
}
