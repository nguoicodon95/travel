@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')

@endsection

@section('js')
@endsection

@section('js-init')
  <script type="text/javascript">
      // store the currently selected tab in the hash value
      $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
          var id = $(e.target).attr("href").substr(1);
          window.location.hash = id;
      });

      // on load of the page: switch to the currently selected tab
      var hash = window.location.hash;
          $('#o_tab a[href="' + hash + '"]').tab('show');
  </script>
@endsection

@section('content')
    <?php
        $status = '<span class="label label-primary label-sm">Chưa giải quyết</span>';
        if ($transaction->status != 0 && $transaction->status != 2 && $transaction->status != 3) {
            $status = '<span class="label label-success label-sm">Đã giải quyết</span>';
        } else if($transaction->status != 0 && $transaction->status != 1 && $transaction->status != 3) {
            $status = '<span class="label label-warning label-sm">Giữ lại</span>';
        } else if($transaction->status != 0 && $transaction->status != 1 && $transaction->status != 2) {
            $status = '<span class="label label-danger label-sm">Hủy</span>';
        }
    ?>
    <div class="row">
        <div class="col-lg-12">
            <!-- Begin: life time stats -->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-basket font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold uppercase">
                        Order #{{ $transaction->id }} </span>
                        <span class="caption-helper">{{ $transaction->created_at }}</span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-default btn-circle">
                        <i class="fa fa-angle-left"></i>
                        <span class="hidden-480">
                        Back </span>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-lg" id="o_tab">
                            <li class="active">
                                <a href="#detail-order" data-toggle="tab">
                                Thông tin đặt hàng </a>
                            </li>
                            <li>
                                <a href="#update-order" data-toggle="tab">
                                Cập nhật đơn đặt hàng </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="detail-order">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet yellow-crusta box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Order Details
                                                </div>
                                                <div class="actions">
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                         Order #:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ $transaction->id }}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        Order Date & Time:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ $transaction->created_at }}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                         Order Status:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {!! $status !!}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        Grand Total:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ _formatPrice($transaction->amount) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Customer Information
                                                </div>
                                                <!-- <div class="actions">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                    <i class="fa fa-pencil"></i> Edit </a>
                                                </div> -->
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        Customer Name:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ $transaction->name }}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        Email:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ $transaction->email }}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        Phone Number:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ $transaction->phone }}
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        Note:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        {{ !is_null(trim($transaction->messages)) ? $transaction->messages : '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="update-order">
                                <div class="col-md-12">
                                    <form action="" method="post">
                                        {{ csrf_field() }}
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <label><b>Trạng thái đơn hàng</b></label>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select name="status" class="table-group-action-input form-control input-inline input-small input-sm">
                                                            <option value="">Select...</option>
                                                            <option value="0" {{ $transaction->status == 0 ? 'selected' : ''  }}>Chưa giải quyết</option>
                                                            <option value="1" {{ $transaction->status == 1 ? 'selected' : ''  }}>Đã giải quyết</option>
                                                            <option value="2" {{ $transaction->status == 2 ? 'selected' : ''  }}>Giữ lại</option>
                                                            <option value="3" {{ $transaction->status == 3 ? 'selected' : ''  }}>Hủy</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">
                                                    <label><b>Ghi chú đơn hàng</b></label>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <textarea name="data" class="form-control" rows="5">{{ $transaction->data or '' }}</textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan=2>
                                                    <label class="pull-left">
                                                        <input type="checkbox" name="sent_mail">Gửi email đến khách hàng
                                                    </label>
                                                    <button class="btn btn-sm green pull-right">
                                                        <i class="fa fa-check"></i> Cập nhật
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
@endsection
