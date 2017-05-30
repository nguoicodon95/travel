@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')
    <link rel="stylesheet" href="/admin/core/third_party/bootstrap-datepicker/css/bootstrap-datepicker.css">

@endsection

@section('js')
    <script type="text/javascript" src="/admin/core/third_party/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('admin/theme/assets/global/scripts/datatable.js') }}"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"></script>
@endsection

@section('js-init')
    <script src="{{ asset('admin/dist/pages/table-datatables-ajax.js') }}"></script>
    <script>
        $(document).ready(function(){
            TableDatatablesAjax.init({
                ajaxGet: '{{ asset($adminCpAccess.'/bookings') }}',
                src: $('#datatable_ajax'),
                onSuccess: function(grid, response){

                },
                onError: function(grid){

                },
                onDataLoad: function(grid){

                },
                defaultLengthMenu: [
                    [-1], ["All"]
                ],
                defaultPageLength: -1,
                editableFields: [2, 6],
                actionPosition: 7,
                ajaxUrlSaveRow: '{{ asset($adminCpAccess.'/') }}'
            });
        });
        $('.date-picker').datepicker();
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!-- Begin: life time stats -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-layers font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">All orders</span>
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <div class="table-actions-wrapper">
                            <span></span>
                            <select class="table-group-action-input form-control input-inline input-small input-sm">
                                <option value="">Select...</option>
                								<option value="0">Chưa giải quyết</option>
                								<option value="1">Đã giải quyết</option>
                								<option value="2">Giữ lại</option>
                								<option value="3">Hủy</option>
                            </select>
                            <button class="btn btn-sm green table-group-action-submit" data-toggle="confirmation">
                                <i class="fa fa-check"></i> Submit
                            </button>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable vertical-middle" id="datatable_ajax">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="1%">
                                    <input type="checkbox" class="group-checkable">
                                </th>
                                <th width="5%">
                                    #
                                </th>
                                <th width="17%">Name</th>
                                <th width="10%">E-mail</th>
                                <th width="5%">Status</th>
                                <th width="10%">Amount</th>
                                <th width="10%">Address</th>
                                <th width="17%">create at</th>
                                <th width="10%">Fast edit</th>
                                <th width="7%">Actions</th>
                            </tr>

								<tr role="row" class="filter">
									<td></td>
									<td><input type="text" class="form-control form-filter input-sm" name="order_id"></td>
									<td><input type="text" class="form-control form-filter input-sm" name="name"></td>
									<td><input type="text" class="form-control form-filter input-sm" name="email"></td>
									<td>
										<select name="order_status" class="form-control form-filter input-sm">
											<option value="">Select...</option>
											<option value="0">Pending</option>
											<option value="1">Closed</option>
											<option value="2">On Hold</option>
											<option value="3">Fraud</option>
										</select>
									</td>
									<td>
										<div class="margin-bottom-5">
											<input type="text" class="form-control form-filter input-sm margin-bottom-5 clearfix" name="order_purchase_price_from" placeholder="From"/>
										</div>
										<input type="text" class="form-control form-filter input-sm" name="order_purchase_price_to" placeholder="To"/>
									</td>
									<td>
										<input type="text" class="form-control form-filter input-sm" name="address">
									</td>
									<td>
										<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
											<input type="text" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
											<input type="text" class="form-control form-filter input-sm" readonly name="order_date_to" placeholder="To">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</td>
									<td></td>
									<td class="text-center">
										<div class="margin-bottom-5">
											<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i></button>
										</div>
										<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i></button>
									</td>
								</tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
@endsection
