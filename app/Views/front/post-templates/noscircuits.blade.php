@extends('front._master')

@section('css')
<link rel="stylesheet" href="/third_party/datepicker/css/datepicker.css">
@endsection

@section('js')
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script src="/third_party/datepicker/js/bootstrap-datepicker.js"></script>
@endsection

@section('js-init')
  <script type="text/javascript">
      // Validate
    	$.validator.addMethod("regx", function(value, element, regexpr) {
    	    return regexpr.test(value);
    	}, "Téléphone not invalid.");
      $(function() {
          $('#debug').hide();
          $("#_form_confirm").validate({
              rules: {
                  address: "required",
                  start_date: {
                      required: true,
                      date: true
                  },
                  number_person: {
                      required: true,
                      number: true,
                  },
                  number_children: {
                      number: true,
                  },
                  travel_time: "required",
                  fullname: {
                      required: true,
                      minlength: 2,
                  },
                  phone: {
                    required: true,
                    regx: /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/,
                  },
                  email: {
                      required: true,
                      email: true
                  },
              },
              errorPlacement: function(error, element) {},
              showErrors: function(errorMap, errorList) {
                  $('#debug').show();
                  $("#debug").html("Les informations ci-dessous (marquées d'une astérisque) sont absentes ou incorrectes.");
                  this.defaultShowErrors();
              },
              submitHandler: function(form) {
                  form.submit();
              }
          });
          // carousel
          var items = $(".nav li").length;
          var leftRight=0;
          if(items>5){
              leftRight=(items-5)*50*-1;
          }
          $('#custom_carousel').on('slide.bs.carousel', function (evt) {
            $('#custom_carousel .controls li.active').removeClass('active');
            $('#custom_carousel .controls li:eq('+$(evt.relatedTarget).index()+')').addClass('active');
          })
          $('.controls .nav').draggable({
              axis: "x",
              stop: function() {
                  var ml = parseInt($(this).css('left'));
                  if(ml>0)
                  $(this).animate({left:"0px"});
                      if(ml<leftRight)
                          $(this).animate({left:leftRight+"px"});
                  if(ml<-550)
                      $(this).animate({left:"0px"});
              }
          });
          $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
          });
          // on load of the page: switch to the currently selected tab
          var hash = window.location.hash;
          $('#tab-form a[href="' + hash + '"]').tab('show');
          // datepicker
          var nowTemp = new Date();
          var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
          $('#booking_startdate').datepicker({
            onRender: function(date) {
              return date.valueOf() < now.valueOf() ? 'disabled' : '';
            }
          });
      });
  </script>
@endsection

@push('style')
  <style>
    .heading-container p,.heading1,.heading5{text-transform:uppercase}.heading1{margin:0 0 10px;font-size:20px;line-height:20px;color:#1b2834;font-weight:900}.content-quality{float:left;width:193px}.content-quality p{margin-left:10px;font-family:'Open Sans',sans-serif;font-size:14px;font-weight:600;line-height:17px}.heading-container p,.tabtop li a,section p{font-family:Lato,sans-serif}.content-quality p span{display:block}.tabtop li a{font-weight:700;color:#1b2834;border-radius:0;margin-right:22.01px;border:1px solid #ebebeb!important}.tabtop .active a:before{content:"♦";position:absolute;top:15px;left:82px;color:#e31837;font-size:30px}.tabtop li a:hover{color:#e31837!important;text-decoration:none}.tabtop .active a:hover{color:#fff!important}.tabtop .active a{background-color:#e31837!important;color:#FFF!important}.margin-tops{margin-top:30px}.tabtop li a:last-child{padding:10px 22px}.thbada{padding:10px 28px!important}.margin-tops4{margin-top:20px}.tabsetting{border-top:5px solid #ebebeb;padding-top:6px}.services{background-color:#d4d4d4;min-height:710px;padding:65px 0 27px}.services a:hover{color:#000}.services h1{margin-top:0!important}.heading-container p{text-align:center;font-size:16px!important}.main img:hover{-webkit-filter:grayscale(1, 1);-webkit-transform:scale(1,1);transform:scale(1,1)}#custom_carousel .item .top{overflow:hidden;max-height:300px;margin-bottom:15px;background:#f3f3f3}#custom_carousel .item{color:#000;background-color:#fff;padding:20px 0;overflow:hidden}#custom_carousel .item img{max-width:100%;height:auto;margin:0 auto}#custom_carousel .der,#custom_carousel .izq{position:absolute;top:40%;background:#222;height:40px;width:40px;margin-top:30px}#custom_carousel .izq{left:-25px;border:4px solid #FFF;border-radius:23px}#custom_carousel .der{right:-25px!important;left:inherit;border:4px solid #FFF;border-radius:23px}#custom_carousel .controls,#custom_carousel .controls .nav{margin:0;white-space:nowrap;text-align:center;position:relative;background:#fff;border:0;padding:0}.alert,.panel.current{border-radius:0}#custom_carousel .controls{overflow:hidden}#custom_carousel .controls .nav{width:auto}#custom_carousel .controls li{transition:all .5s ease;display:inline-block;max-width:100px;height:90px;opacity:.5}#custom_carousel .controls li a{padding:0}#custom_carousel .controls li img{max-width:100%;height:65px}#custom_carousel .controls li.active{background-color:#fff;opacity:1}#custom_carousel .controls a small{overflow:hidden;display:block;font-size:10px;margin-top:5px;font-weight:700}.panel.current{background:#337ab7;color:#fff}.panel .list-group-item a{color:#333}.panel .list-group-item.active a{color:#fff}.public-in{color:#878787;font-size:13px;margin:0 0 15px}.heading5{font-size:16px;font-weight:700;margin:15px 0}input.error,input.error~span.input-group-addon{background-color:#f2dede;border-color:#ebccd1;color:#a94442}
    @include('front._modules.style_post')
  </style>
@endpush

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
              <h2>{{ $catalog['root'] or '' }}</h2>
              <div class="panel-group panel panel-default" id="accordion">
                @if(!empty($catalog['child_catalog']))
                  @foreach($catalog['child_catalog'] as $key => $catalog)
                    <div class="panel {{ in_array($catalog->slug, $catalog_current) ? 'current' : '' }}">
                        <div class="panel-heading">
                          <h4 class="panel-title">
                            <a href="{{ _getCategoryLinkWithParentSlugs($catalog->id) }}">
                              {{ $catalog->title }}
                            </a>
                            @if($catalog->child()->count() > 0)
                            <span style="cursor: pointer" class="accordion-toggle pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> -- </span>
                            @endif
                          </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            @if($catalog->child()->count() > 0)
                            <ul class="list-group">
                              @foreach( $catalog->child as $child_of_child )
                                <li class="list-group-item {{ in_array($child_of_child->slug, $catalog_current) ? 'active' : '' }}"><a href="{{ _getCategoryLinkWithParentSlugs($child_of_child->id) }}">- {{ $child_of_child->title }}</a></li>
                              @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                  @endforeach
                @endif
               </div>
            </div>
            <div class="col-md-8">
                <h1 class="heading1">{{ $object->title }}</h1>
                <div class="public-in">
                    <span>Publié dans:</span>
                    <span>{!! $public_in or '' !!}</span>
                </div>
                @if(isset($gallery_post) && !empty($gallery_post))
                <div id="custom_carousel" class="carousel slide" data-ride="carousel" data-interval="4000">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                      @foreach($gallery_post as $key => $row)
                        <div class="item {{ $key == 0 ? 'active' : '' }}">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="top col-md-12 reset-padding-all">
                                        <img src="{{ $row['image'] }}" class="img-responsive">
                                    </div>
                                </div>
                            </div>
                        </div>
                      @endforeach
                    </div>
                    <a data-slide="prev" href="#custom_carousel" class="izq carousel-control">‹</a>
                    <a data-slide="next" href="#custom_carousel" class="der carousel-control">›</a>
                    <!-- End Carousel Inner -->
                    <div class="controls draggable ui-widget-content col-md-12">
                        <ul class="nav ui-widget-header">
                          @foreach($gallery_post as $key => $row)
                            <li data-target="#custom_carousel" data-slide-to="{{ $key }}" {{ $key == 0 ? 'class="active"' : '' }}>
                              <a href="#">
                                <img src="{{ $row['image'] }}">
                              </a>
                            </li>
                          @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <div class="clearfix"></div>
                <div class="tabbable-panel margin-tops4">
                    <div class="tabbable-line tabsetting">
                        <ul class="nav nav-tabs tabtop" id="tab-form">
                          <li class="active"> <a href="#tab_detail" data-toggle="tab"> Programme detaillé </a> </li>
                          <li> <a href="#tab_book" data-toggle="tab"> Personnalisez ce voyage</a> </li>
                        </ul>
                        <div class="tab-content margin-tops">
                            <div class="tab-pane active fade in" id="tab_detail">
                                {!! $object->content !!}
                            </div>
                            <div class="tab-pane fade" id="tab_book">
                                <h5 class="heading5">CRÉEZ VOTRE VOYAGE PRIVATIF "SUR MESURE" AVEC ACACIA VOYAGE!</h5>
                                <p>
                                    Soit à partir de nos propositions de voyages que vous pouvez modifier et adapter selon vos envies (durée, parcours, hébergements,...). Soit à partir de votre projet, confiez-nous votre rêve et nous mettrons tout en œuvre pour l'exaucer ! L’un de nos conseillers spécialistes vous contactera sous 48 heures afin d’élaborer avec vous votre voyage sur mesure.
                                </p>
                                <p class="text-danger">
                                    (*) : Champs obligatoires
                                </p>
                                @include('front/_modules._booking')
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Other post -->
                <h2>AUTRES ARTICLES</h2>
                <div class="list-grid">
                  @if(!empty($post_same_category))
                    @foreach(collect($post_same_category)->take(5) as $post)
                    <div class="grid row">
                        <div class="col-md-5 reset-padding-left">
                            <div class="thumbnail">
                                <a href="{{ _getPostLink($post->slug) }}">
                                    <img class="image-background image-full" src="/images/libraries/trans.png" style="background-image: url('{{ $post->thumbnail }}');" alt="{{ $post->title or '' }}" title="{{ $post->title or '' }}" />
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="post-title">
                                <a href="{{ _getPostLink($post->slug) }}" title="{{ $post->title or '' }}">{{ $post->title or '' }}</a>
                            </div>
                            <div class="post-desc">{{ $post->description or '' }}</div>
                            <div class="post-catalog">
                                <span class="pull-left fb13">Publié dans:</span><a href="{{ _getCategoryLinkWithParentSlugs($post->cate_id) }}"> {{ $post->cate_title or '' }}</a>
                                <a href="#" class="pull-right readmore">En savoir &raquo;</a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
