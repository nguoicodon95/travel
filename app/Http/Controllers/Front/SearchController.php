<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchController extends BaseFrontController
{
	public function __construct() {
		parent::__construct();
        $this->_loadFrontMenu('', 'page', 'menu-left-on-search-page', 'list-group');
	}
    public function searchlable (Request $request) {
        $q = $request->keyword;
        $posts = Post::SearchByKeyword($q)->get();
        if(count($posts) < 1) {
        	$getByFields['status'] = ['compare' => '=', 'value' => 1];
        	$getByFields['title'] = ['compare' => 'LIKE', 'value' => $q];
        	$getByFields['description'] = ['compare' => 'LIKE', 'value' => $q];
	        $posts = Post::select('slug', 'title', 'description', 'thumbnail')
	        			->where('title', 'LIKE', '%'.$q.'%')
	        			->orwhere('description', 'LIKE', '%'.$q.'%')
	        			->orwhere('tags', 'LIKE', '%'.$q.'%')->get();
        }
        $this->dis['count_posts'] = count($posts);

        /*Tạo phân trang */
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $total = new Collection($posts);
        $perPage = 10;
        $currentPageSearchResults = $total->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $this->dis['posts'] = new LengthAwarePaginator($currentPageSearchResults, count($total), $perPage);

        return $this->_viewFront('search.index', $this->dis);
    }
}
