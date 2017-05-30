<div class="container">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <!-- menu-top -->
                    <!-- <div class="menu-top">
                        <ul>
                            <li><a href="">Blog</a></li>
                            <li><a href="">Video</a></li>
                            <li><a href="">Témoignages</a></li>
                            <li><a href="">Newsletter</a></li>
                            <li><a href="">Nous contacter</a></li>
                        </ul>
                    </div> -->
                    <div class="navbar-header">
                        <!--logo-->
                        <div class="reset-padding-left col-md-3">
                            <a href="/" title="" class="navbar-brand">
                                <img src="{{ $CMSSettings['site_logo'] or '/images/logo/logo.png' }}" alt="{{ $CMSSettings['site_title'] or '' }}" />
                            </a>
                        </div>
                        <!--end logo-->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-rs">
                            <span class="sr-only">Toggle navigation</span>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                        </button>
                        <!--top Search-->
                        <div class="col-md-5">
                            <form action="{{ route('search', []) }}" method="get">
                                <div class="input-group search-group">
                                    <input class="form-control" name="keyword" placeholder="Recherche..." type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary">Recherche</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!--End top search-->
                        <!-- Hotline -->
                        <div class="hotline">
                            <div class="hotline-title">Assitance 24/24</div>
                            <div class="hotline-number">{{ $CMSSettings['phone'] or '' }}</div>
                        </div>
                        <!-- End hotline -->
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <div class="col-md-10">
                <div class="collapse navbar-collapse" id="navbar-collapse-rs">
                    {!! $mega_menu !!}
                </div>
            </div>
            <div class="col-md-2 medias reset-padding-right">
                <ul>
                    <li> <a href="{{ $CMSSettings['fb_link'] or '' }}"> <i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li> <a href="{{ $CMSSettings['twiter_link'] or '' }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    <li> <a href="{{ $CMSSettings['google_plus_link'] or '' }}"> <i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                    <li> <a href="{{ $CMSSettings['pinterest_link'] or '' }}"> <i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>
