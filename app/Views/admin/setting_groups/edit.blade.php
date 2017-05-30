@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')
    <link rel="stylesheet" href="/admin/core/third_party/bootstrap-tagsinput/bootstrap-tagsinput.css">
@endsection

@section('js')
    <script type="text/javascript" src="/admin/core/third_party/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/config.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/adapters/jquery.js"></script>

    {{--Custom field templates--}}
    @include('admin._shared._custom-field-templates')
@endsection

@section('js-init')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.js-ckeditor').ckeditor({

            });

            $('.js-tags-editor').tagsinput({
                'tagClass': 'label label-default'
            });

            Utility.convertTitleToSlug('.the-object-title', '.the-object-slug');

            $('.js-validate-form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {

                },
                rules: {
                    title: {
                        minlength: 3,
                        maxlength: 255,
                        required: true
                    },
                    slug: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    description: {
                        maxlength: 1000
                    }
                },

                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                }
            });

            /*Handle custom fields*/
            Utility.handleCustomFields();
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <form class="js-validate-form" method="POST" accept-charset="utf-8" action="" novalidate>
                    {{ csrf_field() }}
                    <div class="col-md-9">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-note font-dark"></i>
                                    <span class="caption-subject font-dark sbold uppercase">Basic information</span>
                                </div>
                                <div class="actions">
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="form-group">
                                    <label><b>Name <span class="text-danger">(*)</span></b></label>
                                    <input required type="text" name="name" class="form-control the-object-title" value="{{ $object->name or '' }}" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label><b>Slug <span class="text-danger">(*)</span></b></label>
                                    <input type="text" name="slug" class="form-control the-object-slug" value="{{ $object->slug or '' }}" autocomplete="off">
                                </div>

                                <div class="btn-group btn-group-devided">
                                    <button class="btn btn-transparent btn-success active btn-circle" type="submit">
                                        <i class="fa fa-check"></i> Save
                                    </button>
                                </div>
                                <a href="/admincp/setting-groups" class="btn btn-transparent btn-primary pull-right btn-circle">
                                    <i class="icon-action-undo"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
