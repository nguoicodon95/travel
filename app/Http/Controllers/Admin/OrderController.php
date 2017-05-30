<?php

namespace App\Http\Controllers\Admin;

use Acme;
use App\Http\Controllers\Admin\AdminFoundation\CustomFields;
use App\Models;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class OrderController extends BaseAdminController
{
    public $bodyClass = 'order-controller', $routeLink = 'bookings';

    public function __construct()
    {
        parent::__construct();

        $this->_setPageTitle('Đặt hàng', 'manage orders');
        $this->_setBodyClass($this->bodyClass);

        $this->_loadAdminMenu($this->routeLink);
    }

    public function getIndex(Request $request, Booking $object)
    {
        $this->_setBodyClass($this->bodyClass . ' orders-list-page');
        return $this->_viewAdmin('orders.index');
    }


    public function postIndex(Request $request, Booking $object)
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
            $records["customActionMessage"] = "Hành động nhóm đã không hoàn thành. xảy ra một số lỗi.";
            $ids = (array) $request->get('id', []);
            $customActionValue = $request->get('customActionValue', 0);
            $result = $object->updateMultiple($ids, [
                            'status' => $customActionValue,
                        ], true);
            if (!$result['error']) {
                $records["customActionStatus"] = "success";
                $records["customActionMessage"] = "Hành động Group đã được hoàn thành.";
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
            case 1:{
                    $orderBy = 'id';
                }
                break;
            case 2:{
                    $orderBy = 'name';
                }
                break;
            case 3:{
                $orderBy = 'email';
            }
                break;
            case 4:{
                    $orderBy = 'status';
                }
                break;
            case 5:{
                    $orderBy = 'amount';
                }
                break;
            case 6:{
                    $orderBy = 'phone';
                }
                break;
            case 7:{
                    $orderBy = 'order';
                }
                break;
            default:{
                    $orderBy = 'created_at';
                }
                break;
        }
        $orderType = $request->get('order')[0]['dir'];

        $getByFields = [];
        if ($request->get('order_id', null) != null) {
            $getByFields['id'] = ['compare' => '=', 'value' => $request->get('id')];
        }
        if ($request->get('name', null) != null) {
            $getByFields['fullname'] = ['compare' => 'LIKE', 'value' => $request->get('name')];
        }
        if ($request->get('email', null) != null) {
            $getByFields['email'] = ['compare' => 'LIKE', 'value' => $request->get('email')];
        }
        if ($request->get('address', null) != null) {
            $getByFields['address'] = ['compare' => 'LIKE', 'value' => $request->get('address')];
        }
        if ($request->get('amount', null) != null) {
            $getByFields['amount'] = ['compare' => '=', 'value' => $request->get('amount')];
        }
        if ($request->get('order_status', null) != null) {
            $getByFields['status'] = ['compare' => '=', 'value' => $request->get('order_status')];
        }

        $items = $object->searchBy($getByFields, [$orderBy => $orderType], true, $limit);

        $iTotalRecords = $items->count();
        $sEcho = intval($request->get('sEcho'));

        foreach ($items as $key => $row) {
            $status = '<span class="label label-primary label-sm">Chưa giải quyết</span>';
            if ($row->status != 0 && $row->status != 2 && $row->status != 3) {
                $status = '<span class="label label-success label-sm">Đã giải quyết</span>';
            } else if($row->status != 0 && $row->status != 1 && $row->status != 3) {
                $status = '<span class="label label-warning label-sm">Giữ lại</span>';
            } else if($row->status != 0 && $row->status != 1 && $row->status != 2) {
                $status = '<span class="label label-danger label-sm">Hủy</span>';
            }

            /*Edit link*/
            $link = asset($this->adminCpAccess . '/' . $this->routeLink . '/detail/' . $row->id);
           	/*$link = asset($this->adminCpAccess . '/' . $this->routeLink . '/edit/' . $row->id . '/' . $this->defaultLanguageId);*/
            /*$removeLink = asset($this->adminCpAccess . '/' . $this->routeLink . '/delete/' . $row->id);*/
            $records["data"][] = array(
                '<input type="checkbox" name="id[]" value="' . $row->id . '">',
                $row->id,
                $row->fullname,
                $row->email,
                $status,
                _formatPrice($row->amount),
                $row->address,
                $row->created_at->format('d/m/Y H:i'),
                '<a class="fast-edit" title="Fast edit">Fast edit</a>',
                '<a href="' . $link . '" class="btn btn-outline green btn-sm"><i class="icon-eye"></i></a>'
            );
        }

        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;

        return response()->json($records);
    }

    public function getDetail($id, Booking $object) {
        $data = [
            'viewed' => 1
        ];
        $result = $object->updateItemContent($id, $data);
        $trans = $object->getById($id);
        $this->dis['transaction'] = $trans;
        return $this->_viewAdmin('orders.show', $this->dis);
    }

    public function postDetail( Request $request, $trans_id, Booking $object) {
        $data = $request->except(['tab', 'update-order']);
        $sent_mail = isset($data['sent_mail']) ? $data['sent_mail'] : '';
        $result = $object->updateItemContent($trans_id, $data);

        $base_wt = [
            'phone' => $this->CMSSettings['phone'],
            'email' => $this->CMSSettings['email'],
            'address' => $this->CMSSettings['address'],
            'fb_link' => $this->CMSSettings['fb_link'],
        ];
        $data = [
            'transaction' => $result['object'],
            'base_st' => $base_wt
        ];
        $view = 'front.mails.notify';
        $subject = 'Thông báo từ website';
        if (!$result['error']) {
            $records["customActionStatus"] = "success";
            $records["customActionMessage"] = "Hành động đã được hoàn thành.";
            \DB::commit();
        } else {
            \DB::rollBack();
        }
        $to = [
            [
                'name' => $result['object']->fullname,
                'email' => $result['object']->email,
            ]
        ];
        $from =[
            'name' => $this->CMSSettings['site_title'],
            'address' => $this->CMSSettings['email'],
        ];
        if(isset($sent_mail) && !empty($sent_mail)) {
            $mail = _sendEmail($view, $subject, $data, $to, $from);
            $this->_setFlashMessage("Một email đã được gửi đến ".$result['object']->fullname , 'success');
            $this->_showFlashMessages();
        }

        return redirect()->back();
    }
}
