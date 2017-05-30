<!DOCTYPE html>
<!--[if IE 8]>
<html lang="{{ $currentLanguageCode or 'en' }}" class="ie8 no-js {{ ($showHeaderAdminBar) ? 'show-admin-bar' : '' }}"> <![endif]-->
<!--[if IE 9]>
<html lang="{{ $currentLanguageCode or 'en' }}" class="ie9 no-js {{ ($showHeaderAdminBar) ? 'show-admin-bar' : '' }}"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ $currentLanguageCode or 'en' }}" class="{{ ($showHeaderAdminBar) ? 'show-admin-bar' : '' }}">
<!--<![endif]-->
    <head>
        @include('front/_shared/_metas')
        <!-- GLOBAL PLUGINS -->
        {{--<link rel="stylesheet" href="/fonts/Open-Sans/font.css">--}}
        <!-- GLOBAL PLUGINS -->
        <!-- OTHER PLUGINS -->
        @yield('css')
        <!-- END OTHER PLUGINS -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/third_party/slides/responsiveslides.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="/css/style.css" rel="stylesheet" type="text/css" media="all"/>
        <!-- END THEME LAYOUT STYLES -->
        <!-- NOSCRIPT -->
        <noscript>
            <link href="/css/noscript.css" rel="stylesheet" type="text/css" media="all"/>
        </noscript>
        <!-- END NOSCRIPT -->
        <link rel="stylesheet" href="/css/responsive.css">
        @if($showHeaderAdminBar)
        <link rel="stylesheet" href="/admin/css/admin-bar.css">
        @endif
        <link rel="shortcut icon" href="{{ $CMSSettings['favicon'] or '' }}"/>
        {!! $CMSSettings['google_analytics'] or '' !!}
        @stack('style')
        <style>
            #box-message,#message{right:0;position:fixed;top:0;bottom:0;margin:auto}#box-message h3,#message{background:#555;color:#FFF;font-weight:500;padding:10px}#box-message form,#message{padding:10px}#message{width:100px;height:52px;border-top-left-radius:3px;border-bottom-left-radius:3px;z-index:999;cursor:pointer}#box-message{width:330px;height:420px;border:1px solid #555;background:#FFF;border-radius:5px;color:#333;transform:scale(0);-webkit-transform:scale(0);-moz-transform:scale(0);-o-transform:scale(0);transition:all .4s ease;-webkit-transition:all .4s ease;-moz-transition:all .4s ease;-o-transition:all .4s ease;transform-origin:top right;-webkit-transform-origin:top right;-moz-transform-origin:top right;-o-transform-origin:top right}#box-message h3{margin:0;font-size:16px}
        </style>
        <!-- BEGIN CORE PLUGINS -->
        <script src="/dist/core.min.js"></script>
        <!-- END CORE PLUGINS -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- OTHER PLUGINS -->
        @yield('js')
        <!-- END OTHER PLUGINS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/dist/app.min.js"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script src="/third_party/slides/responsiveslides.min.js"></script>
        <!-- JS INIT -->
        @yield('js-init')
        <!-- END JS INIT -->
        <script>
            $(function () {
                $(".rslides").responsiveSlides({
                    auto: true,
                    pager: true,
                    nav: true,
                    speed: 700,
                    timeout: 7000,
                    namespace: "centered-btns"
                });
            });
            $(function () {
                var width = $(window).width();
                // if(width >= 1200) {
                    $(".dropdown").hover(
                        function() {
                            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideDown("400");
                            $(this).toggleClass('open');
                        },
                        function() {
                            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideUp("400");
                            $(this).toggleClass('open');
                        }
                    );
                // }
            });
        </script>
    </head>
    <body>@if($showHeaderAdminBar) @include('admin/_shared/_admin-bar') @endif
        <div class="wrap">
            <noscript>
            <div class="global-site-notice noscript">
                <div class="notice-inner">
                    <p>
                        <strong>Dường như JavaScript bị tắt trong trình duyệt của bạn.</strong><br />
                        Bạn phải có bật Javascript trong trình duyệt của bạn để sử dụng các chức năng của trang web này.
                    </p>
                </div>
            </div>
            </noscript>
            <div class="page">
                <header class="header">
                    @include('front/_shared/_header')
                </header>
                @if(isset($show_slide) && $show_slide == true)
                <div class="rslides_container">
                    <ul class="rslides" id="slider-gallery">
                        @if(!empty($slides_default))
                            @foreach($slides_default as $row)
                            <li>
                                <a href="#">
                                    <img src="{{ $row['image'] }}" alt="{{ $row['title'] }}" />
                                </a>
                                <div class="scaption">
                                    <div class="scaption-inner">
                                        <div class="container">
                                            <div class="scaption-item">
                                                <h3><a href="{{ $row['link'] }}">{{ $row['caption'] }}</a></h3>
                                            </div><!-- scaption-item -->
                                        </div><!-- container -->
                                    </div><!-- scaption-inner -->
                                </div>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                @endif
                <div class="clearfix"></div>
                <div class="main">
                    <div class="col-main">
                        @yield('content')
                    </div>
                </div>
                <div id="message">
                    <span class="title">
                        <i class="fa fa-envelope"></i>
                        Laissez un message
                    </span>
                    <div id="box-message" style="">
                        <h3>Laisez un message <span class="close pull-right"><i class="fa fa-times"></i></span></h3>
                        <form action="/global-actions/message" method="POST">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>Nom et Prénom:</label>
                                <input type="text" class="form-control" name="name" required="">
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" name="email" required="">
                            </div>
                            <div class="form-group">
                                <label>Message:</label>
                                <textarea type="text" class="form-control" name="content" rows="5" required=""></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="send-message">Envoyer un message</button>
                            </div>
                        </form>
                    </div>
                </div>
                <footer class="footer">
                    @include('front/_shared/_footer')
                </footer>
            </div>
        </div>
        <!--Modals-->
        @include('front/_shared/_modals')
        <!--Google captcha-->
        @include('front/_shared/_google-captcha')
        <!--Google captcha-->
        @include('front/_shared/_flash-messages')
        <script>
            $("#message .title").click(function(){$("#box-message").css({transform:"scale(1)","-webkit-transform":"scale(1)","-moz-transform":"scale(1)","-o-transform":"scale(1)"})}),$("#message .close").click(function(){$("#box-message").attr({style:""})});
        </script>
        <!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55a21f6523c938bd"></script>
    </body>
</html>
