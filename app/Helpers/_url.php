<?php

if (!function_exists('_getPageLink')) {
    function _getPageLink($page)
    {
        if (!is_string($page)) {
            $page = $page->slug;
        }

        return '/'.$page;
    }
}

if (!function_exists('_getPostLink')) {
    function _getPostLink($slug)
    {
        return route('post.link', $slug);
    }
}

if (!function_exists('_getProductLink')) {
    function _getProductLink($slug)
    {
        return route('product.link', $slug);
    }
}

if (!function_exists('_getCategoryLink')) {
    function _getCategoryLink($slug)
    {
        return route('category.link', $slug);
    }
}

/* if (!function_exists('_getProductCategoryLink')) {
    function _getProductCategoryLink($slug)
    {
        return route('productcategory.link', $slug);
    }
}*/

/*Category link with parent slugs*/
if (!function_exists('_getCategorySlugs')) {
    function _getCategorySlugs($type, $categoryId)
    {
        $slug = '';
        switch ($type) {
            /* case 'productCategory': {
                $category = \App\Models\ProductCategory::getById($categoryId, [], [
                    'product_categories.parent_id',
                    'product_categories.slug',
                ]);
            }
            break;*/
            default: {
                $category = \App\Models\Category::getById($categoryId, [], [
                    'categories.parent_id',
                    'categories.slug',
                ]);
            }
                break;
        }
        if ($category) {
            $slug = $category->slug;
            $parentId = $category->parent_id;
            if ($parentId) {
                $parentSlug = _getCategorySlugs($type, $parentId);
                $slug = $parentSlug . '/' . $slug;
            }
        }
        return $slug;
    }
}

if (!function_exists('_getCategoryLinkWithParentSlugs')) {
    function _getCategoryLinkWithParentSlugs($categoryId)
    {
        return '/category/'._getCategorySlugs('category', $categoryId);
    }
}

/* if (!function_exists('_getProductCategoryLinkWithParentSlugs')) {
    function _getProductCategoryLinkWithParentSlugs($categoryId)
    {

        return '/danh-muc-san-pham/' . _getCategorySlugs('productCategory', $categoryId);
    }
} */

/*CART*/
/*
if (!function_exists('_getAddToCartLink')) {
    function _getAddToCartLink($productContentId, $quantity = 1)
    {
        return '/cart/add-to-cart/' . $productContentId . '/' . $quantity;
    }
} */
