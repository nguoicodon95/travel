<?php
namespace App\Models;

use App\Models;
use App\Models\AbstractModel;
use App\Models\Category;
use App\Models\Contracts;
use Carbon\Carbon;

class Post extends AbstractModel implements Contracts\MultiLanguageInterface
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
    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $rules = [
        'status' => 'integer|required|between:0,1',
        'created_by' => 'integer',
        'is_popular' => 'integer|between:0,1',
        'is_favourite' => 'integer|between:0,1',
        'order' => 'integer',
        'title' => 'required|max:255',
        'slug' => 'required|max:255|unique_multiple:posts,slug',
        'description' => 'max:1000',
        'content' => 'string',
        'thumbnail' => 'string|max:255',
        'tags' => 'string|max:255',
        'price' => 'numeric',
    ];

    protected $editableFields = [
        'order',
        'page_template',
        'created_by',
        'is_popular',
        'is_favourite',
        'title',
        'slug',
        'description',
        'content',
        'status',
        'thumbnail',
        'tags',
        'price',
        'time',
    ];

    public function adminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'created_by');
    }

    public function category()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_posts', 'post_id', 'category_id');
    }

    public function updateItem($id, $data, $justUpdateSomeFields = true)
    {
        $data['id'] = $id;
        $result = $this->fastEdit($data, true, $justUpdateSomeFields);

        if (!$result['error']) {
            /*Save categories*/
            if (isset($data['category_ids'])) {
                $result['object']->category()->sync($data['category_ids']);
            }
        }
        return $result;
    }

    public function updateItemContent($id, $data)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => 'Xảy ra một số lỗi!',
        ];

        $post = static::find($id);
        if (!$post) {
            $result['message'] = 'Các bài viết mà bạn đã cố gắng chỉnh sửa không tìm thấy.';
            $result['response_code'] = 404;
            return $result;
        }

        if (isset($data['slug'])) {
            $data['slug'] = str_slug($data['slug']);
        }

        /*Save categories*/
        if (isset($data['category_ids'])) {
            $post->category()->sync($data['category_ids']);
        }

        /*Update page template*/
        if (isset($data['page_template'])) {
            $post->page_template = $data['page_template'];
            $post->save();
        }

        /*Update post content*/
        $postContent = $post;
        if (!$postContent) {
            $postContent->save();
        }

        $data['id'] = $id;

        return $postContent->fastEdit($data, false, true);
    }

    public static function deleteItem($id)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => 'Xảy ra một số lỗi!',
        ];
        $object = static::find($id);

        if (!$object) {
            $result['message'] = 'Các bài viết mà bạn đã cố gắng chỉnh sửa không tìm thấy.';
            return $result;
        }

        $related = $object;
        if (!count($related)) {
            $related = null;
        }

        /*Remove all related content*/
        if ($related != null) {
            PostMeta::join('posts', 'posts.id', '=', 'post_metas.content_id')
                ->where('posts.id', '=', $id)
                ->delete();
        }

        $object->category()->sync([]);

        if ($object->delete()) {
            $result['error'] = false;
            $result['response_code'] = 200;
            $result['message'] = ['Xóa bài hoàn thành!'];
        }

        return $result;
    }

    public function createItem($data)
    {
        $dataPost = ['status' => 1];
        if (isset($data['title'])) {
            $dataPost['title'] = $data['title'];
        }

        if (isset($data['created_by'])) {
            $dataPost['created_by'] = $data['created_by'];
        }

        if (isset($data['category_ids'])) {
            $dataPost['category_ids'] = $data['category_ids'];
        }

        if (!isset($data['status'])) {
            $data['status'] = 1;
        }

        $resultCreateItem = $this->updateItem(0, $dataPost);

        /*No error*/
        if (!$resultCreateItem['error']) {
            $post_id = $resultCreateItem['object']->id;
            $resultUpdateItemContent = $this->updateItemContent($post_id, $data);
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
                'posts.status',
                'posts.page_template',
                'posts.title',
                'posts.is_popular',
                'posts.slug',
                'posts.description',
                'posts.content',
                'posts.thumbnail',
                'posts.tags',
                'posts.price',
                'posts.time',
                'posts.id'
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

        $obj = $obj->groupBy('posts.id');

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
                'posts.status as global_status',
                'posts.page_template',
                'posts.title',
                'posts.is_popular',
                'posts.slug',
                'posts.description',
                'posts.content',
                'posts.thumbnail',
                'posts.tags',
                'posts.price',
                'posts.time',
                'posts.id'
            ];
        }

        return static::where('id', '=', $id)
            ->where(function ($q) use ($args) {
                if ($args['global_status'] != null) {
                    $q->where('status', '=', $args['global_status']);
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
                'posts.status as global_status',
                'posts.page_template',
                'posts.title',
                'posts.is_popular',
                'posts.slug',
                'posts.description',
                'posts.content',
                'posts.thumbnail',
                'posts.tags',
                'posts.price',
                'posts.time',
                'posts.id'
            ];
        }

        return static::where('slug', '=', $slug)
            ->where(function ($q) use ($args) {
                if ($args['global_status'] != null) {
                    $q->where('posts.status', '=', $args['global_status']);
                }
            })
            ->select($select)
            ->first();
    }


    public static function getByCategory($id, $otherFields = [], $order = null, $select = null, $perPage = 0)
    {
        $items = Post::join('categories_posts', 'categories_posts.post_id', '=', 'posts.id')
            ->join('categories', 'categories.id', '=', 'categories_posts.category_id')
            ->groupBy('posts.id')
            ->where([
                'categories.id' => $id,
            ]);
        foreach ($otherFields as $key => $row) {
            $items = $items->where(function ($q) use ($key, $row) {
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
        if ($order && is_array($order)) {
            foreach ($order as $key => $value) {
                $items = $items->orderBy($key, $value);
            }
        }
        if ($order == 'random') {
            $items = $items->orderBy(\DB::raw('RAND()'));
        }

        if ($select && sizeof($select) > 0) {
            $items = $items->select($select);
        }

        if ($perPage > 0) {
            return $items->paginate($perPage);
        }

        return $items->get();
    }

    public static function getNoContentByCategory($id, $otherFields = [], $order = null, $select = null, $perPage = 0)
    {
        $items = Post::join('categories_posts', 'categories_posts.post_id', '=', 'posts.id')
            ->join('categories', 'categories.id', '=', 'categories_posts.category_id')
            ->groupBy('posts.id')
            ->where([
                'categories.id' => $id,
            ]);
        foreach ($otherFields as $key => $row) {
            $items = $items->where(function ($q) use ($key, $row) {

                if ($row['compare'] == 'LIKE') {
                    $q->where($key, $row['compare'], '%' . $row['value'] . '%');
                } else {
                    $q->where($key, $row['compare'], $row['value']);
                }
            });
        }
        if ($order && is_array($order)) {
            foreach ($order as $key => $value) {
                $items = $items->orderBy($key, $value);
            }
        }
        if ($order == 'random') {
            $items = $items->orderBy(\DB::raw('RAND()'));
        }

        if ($select && sizeof($select) > 0) {
            $items = $items->select($select);
        }
        if ($perPage > 0) {
            return $items->paginate($perPage);
        }

        return $items->get();
    }

    public function getCreatedAtAttribute($value) {
    	return Carbon::parse($value)->format('d/m/Y H:i');
    }

    public function getTitleAttribute($value) {
    	return mb_strtoupper(mb_substr($value, 0, 1)).mb_strtolower(mb_substr($value, 1));
    }

    /*
    * Title: Search fulltextsearch on posts table
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
