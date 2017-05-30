@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')

@endsection

@section('js')
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/config.js"></script>
@endsection

@section('js-init')
     <script type="text/javascript">
        $(document).ready(function () {
            $('.js-ckeditor').ckeditor({
            });
            // Utility.convertTitleToSlug('.the-object-title', '.the-object-slug');

            $('.js-validate-form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {},
                rules: {
                    title: {
                        minlength: 3,
                        maxlength: 255,
                        required: true
                    },
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
    <div class="portlet light form-fit bordered">
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form class="form-horizontal form-bordered" action="" method="POST" accept-charset="UTF-8">
                {!! csrf_field() !!}
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-md-3 text-right">Title</div>
                        <div class="col-md-7">
                            <input class="form-control" type="text" name="title" value="{{ $object->title or '' }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 text-right">Customer name</div>
                        <div class="col-md-7">
                            <input class="form-control" type="text" name="customer" value="{{ $object->customer or '' }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 text-right">Content</div>
                        <div class="col-md-7">
                            <textarea name="content" rows="5" class="form-control js-ckeditor">{!! $object->content or '' !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 text-right">Thumbnail image</div>
                        <div class="col-md-7">
                            <div class="select-media-box">
                                <button type="button" class="btn blue show-add-media-popup">Choose image
                                </button>
                                <div class="clearfix"></div>
                                <a title="" class="show-add-media-popup"><img
                                            src="{{ (isset($object) && trim($object->thumbnail != '')) ? $object->thumbnail : '/admin/images/no-image.png' }}"
                                            alt="Thumbnail" class="img-responsive"></a>
                                <input type="hidden" name="thumbnail" value="{{ $object->thumbnail or '' }}"
                                       class="input-file">
                                <input type="hidden" name="thumbnail_path"
                                        class="thumbnail-path hidden">
                                <a title="" class="remove-image"><span>&nbsp;</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-push-3 col-md-7 text-right">
                            <button type="submit" class="btn btn-circle green font-white btn-default">
                                <i class="fa fa-check"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
@endsection