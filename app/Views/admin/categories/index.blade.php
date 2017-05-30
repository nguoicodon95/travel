@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')

@endsection

@section('js')
    <script src="/admin/theme/assets/global/scripts/datatable.js"></script>
    <script src="/admin/theme/assets/global/plugins/datatables/datatables.min.js"></script>
    <script src="/admin/theme/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
@endsection

@section('js-init')
    <script src="/admin/dist/pages/table-datatables-ajax.js"></script>
    <script>
        $(document).ready(function(){
            TableDatatablesAjax.init({
                ajaxGet: '{{ asset($adminCpAccess.'/categories') }}',
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
                ajaxUrlSaveRow: '{{ asset($adminCpAccess.'/categories/fast-edit') }}'
            });
        });
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
                        <span class="caption-subject font-dark sbold uppercase">All categories</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided">
                            <a class="btn btn-transparent btn-success btn-circle btn-sm active" href="{{ asset($adminCpAccess.'/categories/edit/0/') }}"><i class="fa fa-plus"></i> Create</a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <div class="table-actions-wrapper">
                            <span></span>
                            <select class="table-group-action-input form-control input-inline input-small input-sm">
                                <option value="">Select...</option>
                                <option value="1">Activated</option>
                                <option value="0">Disabled</option>
                                <option value="set_show_child_index">Hiển thị danh mục con trên trang chủ</option>
                                <option value="unset_show_child_index">Không hiển thị danh mục con trên trang chủ</option>
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
                                <th width="40%">Title</th>
                                <th width="10%">Page template</th>
                                <th width="5%">Status</th>
                                <th width="5%">Display homepage</th>
                                <th width="5%">Order</th>
                                <th width="10%">Created at</th>
                                <th width="10%">Fast edit</th>
                                <th width="10%">Actions</th>
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
