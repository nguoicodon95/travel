<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ReviewController extends BaseAdminController
{
	public $bodyClass = 'review-controller', $routeLink = 'reviews';

	public function __construct()
	{
		parent::__construct();

		$this->middleware('is_staff');

		$this->_setPageTitle('Reviews', 'Ý kiến khách hàng');
		$this->_setBodyClass($this->bodyClass);

		$this->_loadAdminMenu($this->routeLink);
	}

	public function getIndex(Request $request, Review $object)
	{
		$this->_setBodyClass($this->bodyClass . ' reviews-list-page');
		return $this->_viewAdmin('reviews.index');
	}

	public function postIndex(Request $request, Review $object)
	{
		/**
		 * Paging
		 **/
		$offset = $request->get('start', 0);
		$limit = $request->get('length', 10);
		$paged = ($offset + $limit) / $limit;
		Paginator::currentPageResolver(function () use ($paged) {
			return $paged;
		});

		$records = [];
		$records["data"] = [];

		/*Group actions*/
		if ($request->get('customActionType', null) == 'group_action') {
			\DB::beginTransaction();

			$records["customActionStatus"] = "danger";
			$records["customActionMessage"] = "Group action did not completed. Some error occurred.";
			$ids = (array)$request->get('id', []);
			$customActionValue = $request->get('customActionValue', 0);
			switch ($customActionValue) {
				case 'deleted': {
					$result = ['error' => !$object->whereIn('id', $ids)->delete()];
					$object->whereIn('parent_id', $ids)->delete();
				}
					break;
				default: {
					$result = $object->updateMultiple($ids, [
						'status' => $customActionValue,
					], true);
				}
					break;
			}
			if (!$result['error']) {
				$records["customActionStatus"] = "success";
				$records["customActionMessage"] = "Group action has been completed.";
				\DB::commit();
			} else {
				\DB::rollBack();
			}
		}

		/*
		 * Sortable data
		 */
		$orderBy = $request->get('order')[0]['column'];
		switch ($orderBy) {
			case 1: {
				$orderBy = 'id';
			}
				break;
			case 2: {
				$orderBy = 'title';
			}
				break;
			case 3: {
				$orderBy = 'customer';
			}
			case 4: {
				$orderBy = 'status';
			}
				break;
			default: {
				$orderBy = 'created_at';
			}
				break;
		}
		$orderType = $request->get('order')[0]['dir'];

		$getByFields = [];
		if ($request->get('title', null) != null) {
			$getByFields['title'] = ['compare' => 'LIKE', 'value' => $request->get('title')];
		}
		if ($request->get('customer', null) != null) {
			$getByFields['customer'] = ['compare' => 'LIKE', 'value' => $request->get('customer')];
		}
		if ($request->get('status', null) != null) {
			$getByFields['status'] = ['compare' => '=', 'value' => $request->get('status')];
		}

		$items = $object->searchBy($getByFields, [$orderBy => $orderType], true, $limit);

		$iTotalRecords = $items->total();
		$sEcho = intval($request->get('sEcho'));

		foreach ($items as $key => $row) {
			$status = '<span class="label label-success label-sm">Activeted</span>';
			if ($row->status != 1) {
				$status = '<span class="label label-danger label-sm">Disabled</span>';
			}
			/*Edit link*/
			$link = asset($this->adminCpAccess . '/' . $this->routeLink . '/edit/' . $row->id);
			$removeLink = asset($this->adminCpAccess . '/' . $this->routeLink . '/delete/' . $row->id);

			$records["data"][] = array(
				'<input type="checkbox" name="id[]" value="' . $row->id . '">',
				$row->id,
				$row->title,
				$row->customer,
				$status,
				$row->created_at->toDateTimeString(),
				'<a href="' . $link . '" class="btn btn-outline green btn-sm"><i class="icon-pencil"></i></a>' .
				'<button type="button" data-ajax="' . $removeLink . '" data-method="DELETE" data-toggle="confirmation" class="btn btn-outline red-sunglo btn-sm ajax-link"><i class="fa fa-trash"></i></button>',
			);
		}

		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;

		return response()->json($records);
	}

	public function postFastEdit(Request $request, Review $object)
	{
		$data = [
			'id' => $request->get('args_0', null),
			'global_title' => $request->get('args_1', null),
		];

		$result = $object->fastEdit($data, false, true);
		return response()->json($result, $result['response_code']);
	}

	public function getEdit(Request $request, Review $object, $id)
	{
		$oldInputs = old();
	        if ($oldInputs && $id == 0) {
	            $oldObject = new \stdClass();
	            foreach ($oldInputs as $key => $row) {
	                $oldObject->$key = $row;
	            }
	            $this->dis['object'] = $oldObject;
	        }

	        if (!$id == 0) {
	            $item = $object->find($id);
	            /*No page with this id*/
	            if (!$item) {
	                $this->_setFlashMessage('Item not exists.', 'error');
	                $this->_showFlashMessages();
	                return redirect()->back();
	            }
	            $item = $object->getById($id, [
	                'status' => null,
	            ]);
	            /*Create new if not exists*/
	            if (!$item) {
	                $item = new Review();
	                $item->id = $id;
	                $item->save();
	                $item = $object->getById($id, [
	                    'status' => null,
	                ]);
	            }
	            $this->_setPageTitle('Edit review', $item->title);
	            $this->dis['object'] = $item;
	        }

	        return $this->_viewAdmin('reviews.edit', $this->dis);
	}

	public function postEdit(Request $request, Review $object, $id)
	{
		$data = $request->all();
            	$data['slug'] = str_slug($data['title']);
	       \DB::beginTransaction();
	       if ($id == 0) {
	            $result = $object->createItem($data);
	       } else {
	            $result = $object->updateItemContent($id, $data);
	      	}
	       if ($result['error']) {
	            \DB::rollBack();
	            $this->_setFlashMessage($result['message'], 'error');
	            $this->_showFlashMessages();
	            if ($id == 0) {
	                return redirect()->back()->withInput();
	            }
	            return redirect()->back();
	       }

	        \DB::commit();

	        $this->_setFlashMessage($result['message'], 'success');
	        $this->_showFlashMessages();

	        /*Save completed*/
	        if ($id == 0) {
	            return redirect()->to(asset($this->adminCpAccess . '/' . $this->routeLink . '/edit/' . $result['object']->id));
	        }
	        return redirect()->back();
	}

	public function deleteDelete(Request $request, Review $object, $id)
	{
		$result = $object->deleteItem($id);
		return response()->json($result, $result['response_code']);
	}
}
