<?php

namespace App\Models;

use App\Models;
use App\Models\AbstractModel;

class SettingGroup extends AbstractModel
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $editableFields = [
        'name',
        'slug'
    ];
    protected $rules = [
        'name' => 'required|max:255',
        'slug' => 'required|max:255|unique_multiple:setting_groups,slug',
    ];

    protected $table = 'setting_groups';

    public $timestamps = false;

    public function setting()
    {
        return $this->hasMany('App\Models\Setting', 'group_id');
    }

    public function updateItem($id, $data, $justUpdateSomeFields = true)
    {
        $data['id'] = $id;
        return $this->fastEdit($data, true, $justUpdateSomeFields);
    }

    public function updateItemContent($id, $data)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => 'Some error occurred!',
        ];

        $page = static::find($id);
        if (!$page) {
            $result['message'] = 'The page you have tried to edit not found.';
            $result['response_code'] = 404;
            return $result;
        }

        if (isset($data['slug'])) {
            $data['slug'] = str_slug($data['slug']);
        }
        $data['id'] = $id;

        return $page->fastEdit($data, false, true);
    }

    public static function deleteItem($id)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => 'Some error occurred!',
        ];
        $object = static::find($id);

        if (!$object) {
            $result['message'] = 'The page you have tried to edit not found';
            return $result;
        }

        $related = $object;
        if (!count($related)) {
            $related = null;
        }

        if ($object->delete()) {
            $result['error'] = false;
            $result['response_code'] = 200;
            $result['message'] = 'Delete page completed!';
        }

        return $result;
    }

    public function createItem($data)
    {
        $dataPage = [];
        if (isset($data['title'])) {
            $dataPage['title'] = $data['title'];
        }

        $resultCreateItem = $this->updateItem(0, $dataPage);

        /*No error*/
        if (!$resultCreateItem['error']) {
            $group_id = $resultCreateItem['object']->id;
            $resultUpdateItemContent = $this->updateItemContent($group_id, $data);
            if ($resultUpdateItemContent['error']) {
                $this->deleteItem($resultCreateItem['object']->id);
            }
            return $resultUpdateItemContent;
        }
        return $resultCreateItem;
    }

    public static function getById($id, $options = [], $select = [])
    {
        $options = (array) $options;
        $defaultArgs = [];
        $args = array_merge($defaultArgs, $options);

        $select = (array) $select;
        if (!$select) {
            $select = [
                'setting_groups.slug',
                'setting_groups.name',
                'setting_groups.id',
            ];
        }

        return static::where('setting_groups.id', '=', $id)
                ->select($select)
                ->first();
    }

    public static function getBySlug($slug, $options = [], $select = [])
    {
        $options = (array) $options;
        $defaultArgs = [];
        $args = array_merge($defaultArgs, $options);

        $select = (array) $select;
        if (!$select) {
            $select = [
                'setting_groups.slug',
                'setting_groups.name',
                'setting_groups.id',
            ];
        }

        return static::where('slug', '=', $slug)
            ->select($select)
            ->first();
    }


}
