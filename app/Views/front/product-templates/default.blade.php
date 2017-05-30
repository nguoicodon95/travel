@extends('front._master')

@section('css')
    <link href="/css/ubislider.min.css" rel="stylesheet" type="text/css">
    <style>
        .detail-post h1 {
            font-size: 18px;
        }

        .post.excerpt, .pr.excerpt {
          clear: both;
          margin-bottom: 30px;
          background-color: #fff;
          padding: 20px;
          border: 1px solid #cdcdcd;
        }
        .corner:before {
          content: "";
          position: absolute;
          left: -10px;
          width: 0;
          height: 0;
          border-style: solid;
          border-width: 0 0 10px 10px;
          border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0);
      }
      .post-date-ribbon {
          text-align: center;
          line-height: 25px;
          color: #fff;
          font-size: 12px;
          margin-top: -30px;
          position: relative;
          padding: 0 7px;
          float: left;
          background-color: #0182c6;
      }
      article header {
          margin-bottom: 0;
          float: left;
      }
      .title {
          margin-bottom: 5px;
          margin-top: 10px;
          font-size: 15px;
          line-height: 18px;
          display: inline;
      }
      .featured-thumbnail {
          float: left;
          max-width: 150px;
          width: 27.2%;
          margin-right: 10px;
          line-height: 2;
      }

      .popular_pr {
          display: block;
          background: #fff;
          padding: 10px;
      }
      .popular_pr .thumb {
          width: 30%;
          float: left;
          padding-right: 5px;
      }
      .popular_pr h2 {
          font-size: 15px;
          line-height: 18px;
      }
    </style>
@endsection

@section('js')
<script type="text/javascript" src="/third_party/jqueryElevateZoom.js"></script>
<script src="/third_party/ubislider.min.js"></script>
@endsection

@section('js-init')
    <script>
        $('#slider').ubislider({
            arrowsToggle: true,
            type: 'ecommerce',
            hideArrows: true,
            autoSlideOnLastClick: true,
            modalOnClick: true,
            position: 'vertical'
        });

        (function(){

            $('#itemslider').carousel({ interval: 3000 });

            $('.carousel-showmanymoveone .item').each(function(){
                var itemToClone = $(this);

                for (var i=1;i<6;i++) {
                itemToClone = itemToClone.next();


                if (!itemToClone.length) {
                    itemToClone = $(this).siblings(':first');
                }


                itemToClone.children(':first-child').clone()
                    .addClass("cloneditem-"+(i))
                    .appendTo($(this));
                }
            });
        }());

    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="ubislider-image-container left" data-ubislider="#slider" style="cursor: pointer"></div>
            <div id="slider" class="ubislider left">
                <a class="arrow prev"></a>
                <a class="arrow next"></a>
                <ul id="gal1" class="ubislider-inner">
                    <li>
                        <a>
                            <img src="{{ $object->thumbnail }}" alt="">
                        </a>
                    </li>
                    @if(!empty($thumbs) && isset($thumbs))
                        @foreach($thumbs as $thumb)
                        <li>
                            <a>
                                <img class="product-v-img" src="{{ $thumb['src'] }}">
                            </a>
                        </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="col-md-5 col-md-offset-0">
            <div class="product_info">
                <h1 class="name">{{ $object->title }}</h1>
                <div>Mã sản phẩm: {{ $object->sku }}</div>
                <hr>
                <div class="price-box">
                    <span class="regular-price">
                        @if($object->old_price != 0)
                        <span class="old-price">{{ _formatPrice($object->old_price) }}</span>
                        @endif
                        <span class="price">{{ _formatPrice($object->price) }}</span>
                    </span>
                </div>
                <div>
                    <table class="description">
                    @if(isset($attributes) && !empty($attributes))
                        @foreach($attributes as $key => $value)
                            <tr>
                                <th>{{ $key }}</th>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </table>
                </div>
                <div class="addtocart">
                    <div class="select-qty">
                        <label for="qty">Số lượng</label>
                        <select name="qty" id="qty">
                            @for($i = 1; $i < 7; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <a href="{{ _getAddToCartLink($object->content_id) }}" class="btn btn-cart pull-right">Đặt hàng</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h2 class="group_title">
                <span>Thông tin chi chiết</span>
            </h2>
            <div class="dt-content">
                {!! $object->content !!}
            </div>
        </div>
        <div class="col-md-4 filterbx hidden-xs">
            <h2 class="group_title">
                <span>Sản phẩm {{ $object->brand->name }}</span>
            </h2>
            @if(isset($product_s_brand) && !empty($product_s_brand))
              @foreach($product_s_brand as $r)
              <article class="pr excerpt">
                  <div class="post-date-ribbon">
                      <div class="corner"></div><u>Giá:</u> {{ _formatPrice($r['price']) }}
                  </div>
                  <header>
                  <a href="{{ _getProductLink($r['slug']) }}" title="{{ $r['title'] or '' }}">
                      <div class="featured-thumbnail">
                          <img src="{{ $r['thumbnail'] or '' }}" class="attachment-ribbon-lite-featured size-ribbon-lite-featured wp-post-image" alt="{{ $r['title'] or '' }}" title="{{ $r['title'] or '' }}">
                      </div>
                  </a>
                  <h2 class="title text-center">
                      <a href="{{ _getProductLink($r['slug']) }}" title="{{ $r['title'] or '' }}" rel="bookmark">{{ $r['title'] or '' }}</a>
                  </h2>
                  </header><!--.header-->
                  <div class="clearfix"></div>
              </article>
              @endforeach
            @endif
        </div>
    </div>
    <h2 class="group_title">
        <span>Sản phẩm cùng loại</span>
    </h2>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="carousel carousel-showmanymoveone slide" id="itemslider">
                <div class="carousel-inner">
                @if(isset($same_product) && !empty($same_product))
                    @foreach($same_product as $k => $p)
                    <?php
                        $active = '';
                        if($k == 0) {
                            $active = 'active';
                        }
                    ?>
                    <div class="item {{ $active }}">
                        <div class="col-xs-12 col-sm-6 col-md-2">
                        <a href="{{ _getProductLink($p->slug) }}">
                            <img src="{{ $p->thumbnail }}" class="img-responsive center-block">
                            <h4 class="text-center">{{ $p->title }}</h4>
                        </a>

                        <div class="price-box" align="center">
                            <span class="regular-price">
                                @if($p['old_price'] != 0)
                                <span class="old-price"><s>{{ _formatPrice($p->old_price) }}</s></span>
                                @endif
                                <span class="price">{{ _formatPrice($p->price) }}</span>
                            </span>
                        </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>

                <div id="slider-control">
                <a class="left carousel-control" href="#itemslider" data-slide="prev">
                    <img src="/images/libraries/arrow_left.png" alt="Left" class="img-responsive">
                </a>
                <a class="right carousel-control" href="#itemslider" data-slide="next">
                    <img src="/images/libraries/arrow_right.png" alt="Right" class="img-responsive">
                </a>
            </div>
        </div>
    </div>

@endsection
