<?php
namespace App\Models;

use App\Models;
use App\Models\AbstractModel;

class Menu extends AbstractModel
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
    protected $table = 'menus';

    protected $primaryKey = 'id';

    /**
     * Validation
     */
    public $rules = array(
        'slug' => 'required|unique:menus',
        'status' => 'integer|between:0,1',
    );

    protected $editableFields = [
        'title',
        'slug',
        'status',
    ];

    public function menuNode()
    {
        return $this->hasMany('App\Models\MenuNode', 'menu_id');
    }

    public static function deleteMenu($id)
    {
        $result = [
            'error' => true,
            'response_code' => 500,
            'message' => [],
        ];
        $item = static::find($id);
        if (!$item) {
            $result['response_code'] = 404;
            $result['message'] = 'Menu not found';
            return $result;
        }

        $related = $item;
        if (!count($related)) {
            $related = null;
        }

        /*Remove all related content*/
        if ($related != null) {
            $menuContents = [];
            $tempMenuNode = MenuNode::where('menu_id', $id);
            if ($tempMenuNode->delete()) {
                $result['message'][] = 'Xóa nút trình đơn hoàn thành!';
            } else {
                $result['message'][] = 'Một số lỗi xảy ra khi xóa các nút trình đơn liên quan!';
            }

            // if ($temp->delete()) {
            //     $result['message'][] = 'Xóa nội dung liên quan hoàn thành!';
            // } else {
            //     $result['message'][] = 'Một số lỗi xảy ra khi xóa nội dung đơn liên quan!';
            // }
            if ($item->delete()) {
                $result['error'] = false;
                $result['response_code'] = 200;
                $result['message'][] = 'Xóa menu hoàn thành!';
            }
        } else {
            if ($item->delete()) {
                $result['error'] = false;
                $result['response_code'] = 200;
                $result['message'][] = 'Xóa menu hoàn thành!';
            }
        }

        return $result;
    }
}
