<div class="footer-groups">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h6 class="footer-lb">À propos de nous</h6>
                <div class="footer-content">
                    {!! $a_votre_ecoute !!}
                </div><!-- footer-content -->
            </div><!-- col -->

            <div class="col-sm-3">
                <h6 class="footer-lb">SITE A DÉCOUVRIR</h6>
                <div class="footer-content">
                    {!! $site_a_decouvrir !!}
                </div><!-- footer-content -->
            </div><!-- col -->

            <div class="col-sm-3">
                <h6 class="footer-lb">NOS CIRCUITS</h6>
                <div class="footer-content">
                    {!! $preparez_votre_voyage !!}
                </div><!-- footer-content -->
            </div><!-- col -->

            <div class="col-sm-3">
                <h6 class="footer-lb">TOP DESTINATIONS</h6>
                <div class="footer-content">
                    <div class="hotline">
                        <span class="hotline-title">Assitance 24/24</span>
                        <span class="hotline-number">(+84) 904 29 35 79</span>
                    </div>
                    <div class="medias">
                        <ul>
                            <li> <a href="{{ $CMSSettings['fb_link'] or '' }}"> <i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li> <a href="{{ $CMSSettings['twiter_link'] or '' }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li> <a href="{{ $CMSSettings['google_plus_link'] or '' }}"> <i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                            <li> <a href="{{ $CMSSettings['pinterest_link'] or '' }}"> <i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div><!-- footer-content -->
            </div><!-- col -->
        </div><!-- row -->
    </div><!-- container -->
</div><!-- footer-text -->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 address">
                <h6>ACACIA VOYAGE</h6>
                {!! $CMSSettings['footer_info'] or '' !!}
            </div>
        </div><!-- row -->
    </div><!-- container -->
</div><!-- copyright -->
