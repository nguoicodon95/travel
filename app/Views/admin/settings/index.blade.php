@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')
    <link rel="stylesheet" href="/admin/core/third_party/bootstrap-tagsinput/bootstrap-tagsinput.css">
    <style>
        .panel-actions .icon-trash {
            cursor: pointer;
        }

        .panel-actions .icon-trash:hover {
            color: #e94542;
        }

        .panel hr {
            margin-bottom: 10px;
        }

        .panel {
            padding-bottom: 15px;
        }

        .sort-icons {
            font-size: 21px;
            color: #ccc;
            position: relative;
            cursor: pointer;
        }

        .sort-icons:hover {
            color: #37474F;
        }

        .icon-arrow-up, .icon-arrow-down {
            margin-right: 10px;
        }

        .icon-arrow-down {
            top: 10px;
        }

        .page-title {
            margin-bottom: 0;
        }

        .new-setting {
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .new-setting .panel-title {
            margin: 0 auto;
            display: inline-block;
            color: #999fac;
            font-weight: lighter;
            font-size: 13px;
            background: #fff;
            width: auto;
            height: auto;
            position: relative;
            padding-right: 15px;
        }

        #toggle_options {
            clear: both;
            float: right;
            font-size: 12px;
            position: relative;
            margin-top: 15px;
            margin-right: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            z-index: 9;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        textarea {
            min-height: 120px;
        }
        select.form-control {
            display: block;
        }
    </style>
    <link rel="stylesheet" href="/admin/dist/custom-fields.css">
@endsection

@section('js')
    <script src="/admin/js/jsonarea.min.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript" src="/admin/core/third_party/ckeditor/config.js"></script>
@endsection

@section('js-init')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.js-tags-editor').tagsinput({
                'tagClass': 'label label-default'
            });
            $('.js-validate-form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                },
                rules: {
                    'email_receives_feedback': {
                        required: true,
                        email: true
                    },
                    'site_title': {
                        required: true,
                        minlength: 3
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
        });
    </script>
    <script>
        var myJSONArea = JSONArea(document.getElementById('options_textarea'), {
            sourceObjects: []
        });
        valid_json = false;
        myJSONArea.getElement().addEventListener('update', function (e) {
            if (e.target.value != "") {
                valid_json = e.detail.isJSON;
                console.log(valid_json)
            }
        });
        myJSONArea.getElement().addEventListener('focusout', function (e) {
            if (valid_json) {
                $('#valid_options').show();
                $('#invalid_options').hide();
                var ugly = e.target.value;
                var obj = JSON.parse(ugly);
                var pretty = JSON.stringify(obj, undefined, 4);
                document.getElementById('options_textarea').value = pretty;
            } else {
                $('#valid_options').hide();
                $('#invalid_options').show();
            }
        });
    </script>
    <script>
        $('document').ready(function () {
            $('#toggle_options').click(function () {
                $('.new-settings-options').toggle();
                if ($('#toggle_options .voyager-double-down').length) {
                    $('#toggle_options .voyager-double-down').removeClass('voyager-double-down').addClass('voyager-double-up');
                } else {
                    $('#toggle_options .voyager-double-up').removeClass('voyager-double-up').addClass('voyager-double-down');
                }
            });
        });
    </script>
    <script>
        $('document').ready(function () {
            $('.icon-trash').click(function () {
                var action = '{{ route('web.settings.delete') }}/' + $(this).data('id'),
                    display = $(this).data('display-name') + '/' + $(this).data('display-key');

                $('#delete_setting_title').text(display);
                $('#delete_form')[0].action = action;
                $('#delete_modal').modal('show');
            });

        });
    </script>
@endsection

@section('content')
@if($group_id != 4)
    <form action="{{ route('web.settings',$group_id) }}" novalidate method="POST" enctype="multipart/form-data" class="js-validate-form">
        {{ csrf_field() }}
        <div class="portlet light form-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-pin"></i>
                    <span class="caption-subject sbold uppercase">{{ $pageTitle}}</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <div class="form-horizontal form-bordered">
                    <div class="form-body">
                        <div class="panel-body">

                        @foreach($settings as $setting)
                            @if ($setting->type == "homepage")
                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        {{ $setting->display_name }}
                                        <span class="help-block">Key: {{ $setting->option_key }}</span>
                                    </label>
                                    <div class="col-md-7">
                                        <select name="default_homepage" class="form-control">
                                            @foreach($pages as $key => $row)
                                                <option value="{{ $row->id }}" {{ $setting->option_value == $row->id ? 'selected' : '' }}>{{ $row->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif ($setting->type == "text")
                            <div class="form-group">
                                <label class="control-label col-md-3">
                                    {{ $setting->display_name }}
                                    <span class="help-block">Key: {{ $setting->option_key }}</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $setting->option_value }}" name="{{ $setting->option_key }}"/>
                                </div>
                                <div class="col-md-1">
                                    <i class="icon-trash"
                                        data-id="{{ $setting->id }}"
                                        data-display-key="{{ $setting->option_key }}"
                                        data-display-name="{{ $setting->display_name }}"></i>
                                </div>
                            </div>
                            @elseif ($setting->type == "password")
                            <div class="form-group">
                                <label class="control-label col-md-3">
                                    {{ $setting->display_name }}
                                    <span class="help-block">Key: {{ $setting->option_key }}</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="{{ $setting->option_key }}"/>
                                </div>
                                <div class="col-md-1">
                                    <i class="icon-trash"
                                        data-id="{{ $setting->id }}"
                                        data-display-key="{{ $setting->option_key }}"
                                        data-display-name="{{ $setting->display_name }}"></i>
                                </div>
                            </div>
                            @elseif($setting->type == "text_area")
                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        {{ $setting->display_name }}
                                        <span class="help-block">Key: {{ $setting->option_key }}</span>
                                    </label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="{{ $setting->option_key }}">@if(isset($setting->option_value)){{ $setting->option_value }}@endif</textarea>
                                    </div>
                                </div>
                            @elseif($setting->type == "editor")
                                <script>
                                    $(document).ready(function () {
                                        CKEDITOR.replace("wyswyg_editor_field_{{ $setting->option_key }}", {
                                            toolbar: [['mode', 'Source', 'Image', 'TextColor', 'BGColor', 'Styles', 'Format', 'Font', 'FontSize', 'CreateDiv', 'PageBreak', 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'RemoveFormat']]
                                        });
                                    });
                                </script>
                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        {{ $setting->display_name }}
                                        <span class="help-block">Key: {{ $setting->option_key }}</span>
                                    </label>
                                    <div class="col-md-7">
                                        <textarea rows="3" name="{{ $setting->option_key }}" id="wyswyg_editor_field_{{ $setting->option_key }}"
                                                data-fieldtype="wyswyg"
                                                class="form-control wyswyg-editor">{!! $setting->option_value !!}</textarea>
                                    </div>
                                  </div>
                            @elseif($setting->type == "image" || $setting->type == "file")
                                <div class="form-group">
                                    <label class="control-label col-md-3">{{ $setting->display_name }}
                                    <span class="help-block">Key: {{ $setting->option_key }}</span></label>
                                    <div class="col-md-7">
                                        <div class="select-media-box">
                                            <button type="button" class="btn blue show-add-media-popup">Choose image</button>
                                            <div class="clearfix"></div>
                                            <a title="" class="show-add-media-popup">
                                                <img src="{{ (isset($setting->option_value) && trim($setting->option_value != '')) ? $setting->option_value : '/admin/images/no-image.png' }}" alt="Thumbnail" class="img-responsive">
                                            </a>
                                            <input type="hidden" name="{{ $setting->option_key }}" value="{{ $setting->option_value or '' }}" class="input-file">
                                            <a title="" class="remove-image"><span>&nbsp;</span></a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($setting->type == "select_dropdown")
                                <?php $options = json_decode($setting->details); ?>
                                <?php $selected_value = (isset($setting->option_value) && !empty($setting->option_value)) ? $setting->option_value : NULL; ?>
                                <select class="form-control" name="{{ $setting->option_key }}">
                                    <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
                                    @if(isset($options->options))
                                        @foreach($options->options as $index => $option)
                                            <option value="{{ $index }}" @if($default == $index && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $index){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>

                            @elseif($setting->type == "radio_btn")
                                <?php $options = json_decode($setting->details); ?>
                                <?php $selected_value = (isset($setting->option_value) && !empty($setting->option_value)) ? $setting->option_value : NULL; ?>
                                <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
                                <ul class="radio">
                                    @foreach($options as $index => $option)
                                        <li>
                                            <input type="radio" id="option-{{ $index }}" name="{{ $setting->option_key }}"
                                                    value="{{ $index }}" @if($default == $index && $selected_value === NULL){{ 'checked' }}@endif @if($selected_value == $index){{ 'checked' }}@endif>
                                            <label for="option-{{ $index }}">{{ $option }}</label>
                                            <div class="check"></div>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($setting->type == "checkbox")
                                <?php $options = json_decode($setting->details); ?>
                                <?php $checked = (isset($setting->option_value) && $setting->option_value == 1) ? true : false; ?>
                                @if (isset($options->on) && isset($options->off))
                                    <input type="checkbox" name="{{ $setting->option_key }}" class="toggleswitch" @if($checked) checked @endif data-on="{{ $options->on }}" data-off="{{ $options->off }}">
                                @else
                                    <input type="checkbox" name="{{ $setting->option_key }}" @if($checked) checked @endif class="toggleswitch">
                                @endif
                            @elseif($setting->type == "keywords")
                                <div class="form-group">
                                    <label class="control-label col-md-3">{{ $setting->display_name }}</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control js-tags-editor" value="{{ $setting->option_value }}" name="{{ $setting->option_key }}"/>
                                    </div>
                                </div>
                            @elseif($setting->type == "root")
                                <div class="form-group">
                                    <label class="control-label col-md-3">{{ $setting->display_name }}</label>
                                    <div class="col-md-7">
                                        <div class="md-checkbox">
                                            <input type="checkbox"
                                                    value="1"
                                                    id="{{ $setting->option_key }}"
                                                    name="{{ $setting->option_key }}"
                                                    {{ (int)$setting->option_value == 1 ? 'checked' : '' }}
                                                    class="md-radiobtn">
                                            <label for="{{ $setting->option_key }}" style="margin-bottom: 0;">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> In construction mode
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @elseif($setting->type == 'slideshow')
                                <div id="repeat-box-slideshow">
                                     <div class="form-group">
                                        <label><b>Title</b></label>
                                        <input required type="text" name="title" class="form-control the-object-title" value="" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label><b>Link</b></label>
                                        <input required type="text" name="title" class="form-control the-object-title" value="" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label><b>Thumbnail image</b></label>
                                        <br>
                                        <div class="select-media-box">
                                            <button type="button" class="btn blue show-add-media-popup">Choose image</button>
                                            <div class="clearfix"></div>
                                            <a title="" class="show-add-media-popup">
                                                <img src="{{ '/admin/images/no-image.png' }}" alt="Thumbnail" class="img-responsive">
                                            </a>
                                            <input type="hidden" name="thumbnail" value="" class="input-file">
                                            
                                            <input type="hidden" name="thumbnail_path"
                                                    class="thumbnail-path hidden">
                                            <a title="" class="remove-image"><span>&nbsp;</span></a>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a class="btn red btn-add-box-slide" title="" id="">Add new</a>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="text-right" style="padding: 15px;">
                            <button type="submit" class="btn btn-circle green font-white btn-default">
                                <i class="fa fa-check"></i> Update
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END FORM-->
        </div>
        <div class="clearfix"></div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light form-fit">


                <div style="clear:both"></div>

                <div class="panel" style="margin-top:10px;">
                    <div class="panel-heading new-setting">
                        <hr>
                        <label>
                            <i class="icon-plus"></i> New Setting
                        </label>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('web.settings.create', $group_id) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="col-md-4">
                                <label for="display_name">Name</label>
                                <input type="text" class="form-control" name="display_name">
                            </div>
                            <div class="col-md-4">
                                <label for="key">Key</label>
                                <input type="text" class="form-control" name="option_key">
                            </div>
                            <div class="col-md-4">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="text">Text Box</option>
                                    <option value="password">Password</option>
                                    <option value="text_area">Text Area</option>
                                    <option value="editor">Editor</option>
                                    <option value="checkbox">Check Box</option>
                                    <option value="radio_btn">Radio Button</option>
                                    <option value="select_dropdown">Select Dropdown</option>
                                    <option value="file">File</option>
                                    <option value="image">Image</option>
                                    <option value="slideshow">Slideshow</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <a id="toggle_options"><i class="voyager-double-down"></i> OPTIONS</a>
                                <div class="new-settings-options">
                                    <label for="options">Options
                                        <small>(optional, only applies to certain types like dropdown box or radio button)
                                        </small>
                                    </label>
                                    <textarea name="details" id="options_textarea" class="form-control" placeholder='[{"a":"b"}]'></textarea>
                                    <div id="valid_options" class="alert-success alert" style="display:none">Valid Json</div>
                                    <div id="invalid_options" class="alert-danger alert" style="display:none">Invalid Json</div>
                                </div>
                            </div>

                            <div style="clear:both"></div>
                            <button type="submit" class="btn btn-primary pull-right new-setting-btn">
                                <i class="voyager-plus"></i> Add New Setting
                            </button>
                            <div style="clear:both"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i>Bạn đồng ý xóa item này?</h4>
            </div>
            <div class="modal-footer">
                <form action="{{ route('web.settings.delete') }}" id="delete_form"
                        method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                            value="Đồng ý xóa item này">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Hủy</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
@else
  Đây là bộ phận không thể tiếp cận, có thể ảnh hưởng đến việc chạy site
@endif
@endsection
