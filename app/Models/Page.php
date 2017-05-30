<?php
namespace App\Models;

use App\Models;
use App\Models\AbstractModel;

class Page extends AbstractModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

    protected $primaryKey = 'id';

    protected $rules = [
        'title' => 'required|max:255',
        'slug' => 'required|max:255|unique_multiple:pages,slug',
        'description' => 'max:1000',
        'content' => 'string',
        'status' => 'integer|required|between:0,1',
        'thumbnail' => 'string|max:255',
        'tags' => 'string|max:255',
        'created_by' => 'integer',
    ];

    protected $editableFields = [
        'title',
        'slug',
        'status',
        'order',
        'page_template',
        'description',
        'content',
        'thumbnail',
        'tags',
        'created_by',
    ];

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

        /*Update page template*/
        if (isset($data['page_template'])) {
            $page->page_template = $data['page_template'];
            $page->save();
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

        /*Remove all related content*/
        if ($related != null) {
            PageMeta::join('pages', 'pages.id', '=', 'page_metas.content_id')
                ->where('pages.id', '=', $id)
                ->delete();
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
        $dataPage = ['status' => 1];
        if (isset($data['title'])) {
            $dataPage['title'] = $data['title'];
        }

        if (isset($data['page_template'])) {
            $dataPage['page_template'] = $data['page_template'];
        }

        if (!isset($data['status'])) {
            $data['status'] = 1;
        }

        $resultCreateItem = $this->updateItem(0, $dataPage);

        /*No error*/
        if (!$resultCreateItem['error']) {
            $page_id = $resultCreateItem['object']->id;
            $resultUpdateItemContent = $this->updateItemContent($page_id, $data);
            if ($resultUpdateItemContent['error']) {
                $this->deleteItem($resultCreateItem['object']->id);
            }
            return $resultUpdateItemContent;
        }
        return $resultCreateItem;
    }

    public static function getWithContent($fields = [], $select = [], $order = null, $multiple = false, $perPage = 0)
    {
        $fields = (array) $fields;

        $select = (array) $select;
        if (!$select) {
            $select = [
                'pages.status as global_status',
                'pages.page_template',
                'pages.title',
                'pages.slug',
                'pages.description',
                'pages.content',
                'pages.thumbnail',
                'pages.tags',
                'pages.id',
            ];
        }

        $obj = static::select($select);
        if ($fields && is_array($fields)) {
            foreach ($fields as $key => $row) {
                $obj = $obj->where(function ($q) use ($key, $row) {
                    switch ($row['compare']) {
                        case 'LIKE':{
                                $q->where($key, $row['compare'], '%' . $row['value'] . '%');
                            }break;
                        case 'IN':{
                                $q->whereIn($key, (array) $row['value']);
                            }break;
                        case 'NOT_IN':{
                                $q->whereNotIn($key, (array) $row['value']);
                            }break;
                        default:{
                                $q->where($key, $row['compare'], $row['value']);
                            }break;
                    }
                });
            }
        }
        if ($order && is_array($order)) {
            foreach ($order as $key => $value) {
                $obj = $obj->orderBy($key, $value);
            }
        }
        if ($order == 'random') {
            $obj = $obj->orderBy(\DB::raw('RAND()'));
        }

        $obj = $obj->groupBy('pages.id');

        if ($multiple) {
            if ($perPage > 0) {
                return $obj->paginate($perPage);
            }

            return $obj->get();
        }
        return $obj->first();
    }

    public static function getById($id, $options = [], $select = [])
    {
        $options = (array) $options;
        $defaultArgs = [
            'global_status' => 1,
        ];
        $args = array_merge($defaultArgs, $options);

        $select = (array) $select;
        if (!$select) {
            $select = [
                'pages.status as global_status',
                'pages.page_template',
                'pages.title',
                'pages.slug',
                'pages.description',
                'pages.content',
                'pages.thumbnail',
                'pages.tags',
                'pages.id',
            ];
        }

        return static::where('pages.id', '=', $id)
                ->where(function ($q) use ($args) {
                if ($args['global_status'] != null) {
                    $q->where('pages.status', '=', $args['global_status']);
                }
            })
            ->select($select)
            ->first();
    }

    public static function getBySlug($slug, $options = [], $select = [])
    {
        $options = (array) $options;
        $defaultArgs = [
            'global_status' => 1,
        ];
        $args = array_merge($defaultArgs, $options);

        $select = (array) $select;
        if (!$select) {
            $select = [
                'pages.status as global_status',
                'pages.page_template',
                'pages.title',
                'pages.slug',
                'pages.description',
                'pages.content',
                'pages.thumbnail',
                'pages.tags',
                'pages.id',
            ];
        }

        return static::where('slug', '=', $slug)
            ->where(function ($q) use ($args) {
                if ($args['global_status'] != null) {
                    $q->where('status', '=', $args['global_status']);
                }

            })
            ->select($select)
            ->first();
    }

    /*
    * Title: Search fulltextsearch on pages table
    * Author: Kin
    */

    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword!='') {
            $query->where(function ($query) use ($keyword) {
                $query->whereRaw('MATCH (title, description, tags) AGAINST (?)' , array($keyword));
                $query->where('status', 1);
            });
        }
        return $query;
    }
}
