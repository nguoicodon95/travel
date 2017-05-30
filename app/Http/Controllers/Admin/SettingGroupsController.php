<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingGroup;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class SettingGroupsController extends BaseAdminController
{
    public $bodyClass = 'setting-groups-controller', $routeLink = 'setting-groups', $routeEditPostLink = 'posts';
    public function __construct()
    {
        parent::__construct();

        $this->_setPageTitle('Setting Groups', 'manage group for setting');
        $this->_setBodyClass($this->bodyClass);

        $this->_loadAdminMenu($this->routeLink);
    }

    public function getIndex(Request $request, SettingGroup $object)
    {
        $this->_setBodyClass($this->bodyClass . ' group-setting');

        return $this->_viewAdmin('setting_groups.index');
    }

    public function postIndex(Request $request, SettingGroup $object)
    {
        $offset = $request->get('start', 0);
        $limit = $request->get('length', 10);
        $paged = ($offset + $limit) / $limit;
        Paginator::currentPageResolver(function () use ($paged) {
            return $paged;
        });

        $records = [];
        $records["data"] = [];

        /*
         * Sortable data
         */
        $orderBy = $request->get('order')[0]['column'];
        switch ($orderBy) {
            case 1:
                {
                    $orderBy = 'id';
                }
                break;
            case 2:
                {
                    $orderBy = 'name';
                }
                break;
            case 3:
                {
                    $orderBy = 'slug';
                }
                break;
        }
        $orderType = $request->get('order')[0]['dir'];
        $getByFields = [];
        if ($request->get('name', null) != null) {
            $getByFields['name'] = ['compare' => 'LIKE', 'value' => $request->get('name')];
        }
        if ($request->get('slug', null) != null) {
            $getByFields['slug'] = ['compare' => '=', 'value' => $request->get('slug')];
        }

        $items = $object->searchBy($getByFields, [$orderBy => $orderType], true, $limit);
        $iTotalRecords = $items->count();
        $sEcho = intval($request->get('sEcho'));
        foreach ($items as $key => $row) {
            $link = asset($this->adminCpAccess . '/' . $this->routeLink . '/edit/' . $row->id);
            $removeLink = asset($this->adminCpAccess . '/' . $this->routeLink . '/delete/' . $row->id);
            $itemLink = route('web.settings', $row->id);

            $records["data"][] = array(
                '<input type="checkbox" name="id[]" value="' . $row->id . '">',
                $row->id,
                $row->name,
                $row->slug,
                $row->setting->count(),
                '<a class="fast-edit" title="Fast edit">Fast edit</a>',
                '<a href="' . $link . '" class="btn btn-outline green btn-sm"><i class="icon-pencil"></i></a>' .
                '<a href="' . $itemLink . '" class="btn btn-outline green btn-sm"><i class="icon-eye"></i></a>' .
                '<button type="button" data-ajax="' . $removeLink . '" data-method="DELETE" data-toggle="confirmation" class="btn btn-outline red-sunglo btn-sm ajax-link"><i class="fa fa-trash"></i></button>',
            );
        }

        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;

        return response()->json($records);
    }

    public function postFastEdit(Request $request, SettingGroup $object)
    {
        $data = [
            'id' => $request->get('args_0', null),
            'name' => $request->get('args_1', null),
            'slug' => $request->get('args_2', null),
        ];

        $result = $object->fastEdit($data, false, true);
        return response()->json($result, $result['response_code']);
    }


    public function getEdit(Request $request, SettingGroup $object, $id)
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

            $item = $object->getById($id, []);

            /*Create new if not exists*/
            if (!$item) {
                $item = new SettingGroup();
                $item->id = $id;
                $item->save();
                $item = $object->getById($id, []);
            }
            $this->dis['object'] = $item;
            $this->_setPageTitle('Edit Group', $item->title);
        }

        return $this->_viewAdmin('setting_groups.edit', $this->dis);
    }

    public function postEdit(Request $request, SettingGroup $object, $id)
    {
        $data = $request->all();
        if (!$data['slug']) {
            $data['slug'] = str_slug($data['title']);
        }

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

        if ($id == 0) {
            return redirect()->to(asset($this->adminCpAccess . '/' . $this->routeLink . '/edit/' . $result['object']->id));
        }
        return redirect()->back();
    }


    public function deleteDelete(Request $request, SettingGroup $object, $id)
    {
        $result = $object->deleteItem($id);
        return response()->json($result, $result['response_code']);
    }
}
