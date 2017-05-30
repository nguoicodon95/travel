<?php

namespace App\Models;

use \Carbon\Carbon;
use App\Models\AbstractModel;

class Booking extends AbstractModel
{

    protected $table = 'bookings';
    protected $editableFields = [
      'post_id',
      'fullname',
      'gender',
      'address',
      'email',
      'phone',
      'start_date',
      'travel_time',
      'number_person',
      'number_children',
      'activity_type',
      'travel_type',
      'eat_type',
      'content',
      'data',
      'status',
    ];


    public function post() {
    	return $this->belongsTo('App\Models\Post', 'post_id');
    }

    public function updateItem($id, $data, $justUpdateSomeFields = true)
    {
        $data['id'] = $id;
        $result = $this->fastEdit($data, true, $justUpdateSomeFields);
        return $result;
    }

    public function updateItemContent($id, $data)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => 'Xảy ra một số lỗi!',
        ];

        $trans = static::find($id);
        if (!$trans) {
            $result['message'] = 'Không tìm thấy booking nào.';
            $result['response_code'] = 404;
            return $result;
        }

        $data['id'] = $id;

        return $trans->fastEdit($data, false, true);
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
            $result['message'] = 'Không tìm thấy booking nào.';
            return $result;
        }

        $related = $object;
        if (!count($related)) {
            $related = null;
        }

        if ($object->delete()) {
            $result['error'] = false;
            $result['response_code'] = 200;
            $result['message'] = ['Đã xóa booking!'];
        }

        return $result;
    }

    public function createItem($data)
    {
        $resultCreateItem = $this->updateItem(0, $data);

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

    public static function getById($id, $options = [], $select = [])
    {
        return static::where('id', '=', $id)->first();
    }

}
