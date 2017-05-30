<?php
namespace App\Models;

use App\Models;
use App\Models\AbstractModel;
use App\Models\Contracts;

class Category extends AbstractModel implements Contracts\MultiLanguageInterface
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
    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $rules = [
        'title' => 'required|max:255',
        'slug' => 'required|max:255|unique_multiple:categories,slug',
        'description' => 'max:1000',
        'content' => 'string',
        'status' => 'integer|required|between:0,1',
        'show_child' => 'between:0,1',
        'thumbnail' => 'string|max:255',
        'tags' => 'string|max:255',
    ];

    protected $editableFields = [
        'title',
        'slug',
        'order',
        'page_template',
        'parent_id',
        'created_by',
        'description',
        'content',
        'status',
        'thumbnail',
        'tags',
        'show_child',
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function child()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function post()
    {
        return $this->belongsToMany('App\Models\Post', 'categories_posts', 'category_id', 'post_id');
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
            'message' => 'Xảy ra một số lỗi!',
        ];

        $category = static::find($id);
        if (!$category) {
            $result['message'] = 'Danh mục mà bạn đã cố gắng để sửa không được tìm thấy.';
            $result['response_code'] = 404;
            return $result;
        }

        if (isset($data['slug'])) {
            $data['slug'] = str_slug($data['slug']);
        }

        /*Update page template*/
        if (isset($data['page_template'])) {
            $category->page_template = $data['page_template'];
        }
        /*Update parent_id*/
        if (isset($data['parent_id'])) {
            $category->parent_id = $data['parent_id'];
        }
        $category->save();

        /*Update category content*/
        $categoryContent = $category;
        if (!$categoryContent) {
            $categoryContent->save();
        }

        $data['id'] = $id;

        return $categoryContent->fastEdit($data, false, true);
    }

    public static function deleteItem($id)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => 'Xảy ra một số lỗi!',
        ];
        $category = static::find($id);

        if (!$category) {
            $result['message'] = 'Danh mục mà bạn đã cố gắng chỉnh sửa không tìm thấy.';
            return $result;
        }

        \DB::beginTransaction();

        $deleteCategory = true;

        $related = $category;
        if (!count($related)) {
            $related = null;
        }

        /*Remove all related content*/
        if ($related != null) {
            CategoryMeta::join('categories', 'categories.id', '=', 'category_metas.content_id')
                ->where('categories.id', '=', $id)
                ->delete();

        }
        $category->post()->sync([]);

        $deleteCategory = $category->delete();

        if($deleteCategory) {
            /*Change all child item of this category to parent*/
            $relatedCategory = new static;
            $relatedCategory->updateMultipleGetByFields([
                'parent_id' => $id,
            ], [
                'parent_id' => 0,
            ], true);

            $result['error'] = false;
            $result['response_code'] = 200;
            $result['message'] = 'Xóa danh mục hoàn thành!';

            \DB::commit();
        } else {
            \DB::rollBack();
        }

        return $result;
    }

    public function createItem($data)
    {
        $dataCategory = ['status' => 1];
        if (isset($data['title'])) {
            $dataCategory['title'] = $data['title'];
        }

        if (isset($data['parent_id'])) {
            $dataCategory['parent_id'] = $data['parent_id'];
        }

        if (!isset($data['status'])) {
            $data['status'] = 1;
        }

        $resultCreateItem = $this->updateItem(0, $dataCategory);

        /*No error*/
        if (!$resultCreateItem['error']) {
            $category_id = $resultCreateItem['object']->id;
            $resultUpdateItemContent = $this->updateItemContent($category_id, $data);
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
                'categories.status as global_status',
                'categories.page_template',
                'categories.title',
                'categories.parent_id',
                'categories.slug',
                'categories.description',
                'categories.content',
                'categories.status',
                'categories.thumbnail',
                'categories.tags',
                'categories.id',
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

        $obj = $obj->groupBy('categories.id');

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
            'status' => 1,
        ];
        $args = array_merge($defaultArgs, $options);

        $select = (array) $select;
        if (!$select) {
            $select = [
                'categories.status as global_status',
                'categories.page_template',
                'categories.title',
                'categories.parent_id',
                'categories.slug',
                'categories.description',
                'categories.content',
                'categories.status',
                'categories.thumbnail',
                'categories.tags',
                'categories.id',
            ];
        }

        return static::where('id', '=', $id)
            ->where(function ($q) use ($args) {
                if ($args['status'] != null) {
                    $q->where('status', '=', $args['status']);
                }
            })
            ->select($select)
            ->first();
    }

    public static function getBySlug($slug, $languageId = 0, $options = [], $select = [])
    {
        $options = (array) $options;
        $defaultArgs = [
            'global_status' => 1,
        ];
        $args = array_merge($defaultArgs, $options);

        $select = (array) $select;
        if (!$select) {
            $select = [
                'categories.status as global_status',
                'categories.page_template',
                'categories.title',
                'categories.parent_id',
                'categories.slug',
                'categories.description',
                'categories.content',
                'categories.status',
                'categories.thumbnail',
                'categories.tags',
                'categories.id',
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

    public function getTitleAttribute($value) {
    	return mb_strtoupper(mb_substr($value, 0, 1)).mb_strtolower(mb_substr($value, 1));
    }
}
