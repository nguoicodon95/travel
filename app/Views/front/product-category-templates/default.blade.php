@extends('front._master')

@section('css')

@endsection

@section('js')
    <script type="text/javascript" src="/js/pagecategorypr.js"></script>
@endsection

@section('js-init')
    <script type="text/javascript">
        FilterJs.Elemchange('input[name=price]', 'price')
        FilterJs.Elemchange('input[name=sortby]', 'sortby')
        FilterJs.Elemchange('input[name=brands]', 'brands')
        FilterJs.hs_filter()
    </script>
@endsection

@section('content')
    <div class="btn btn-default hidden" id="ft-button" style="margin-bottom: 5px">Tìm kiếm</div>
    <div class="products_grid p_block_category">
        <div class="col-md-3 filterbx hidden-xs">
            <form action="" method="get" id="form_filter">
                <div class="sort-by">
                    <h2 class="group_title"><span>Lựa chọn</span></h2>
                    <div class="asc-sort">
                        <input type="radio" name="sortby" id="sortasc" class="radio" value="asc" {{ isset($sort_by) && ($sort_by == 'asc') ? 'checked' : null }}/>
                        <label for="sortasc">Giá từ thấp đến cao</label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="desc-sort">
                        <input type="radio" name="sortby" id="sortdesc" class="radio" value="desc" {{ isset($sort_by) && ($sort_by == 'desc') ? 'checked' : null }}/>
                        <label for="sortdesc">Giá từ cao đến thấp</label>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="brands">
                    <h2 class="group_title"><span>Nhà sản xuất</span></h2>
                    @if(isset($brands) && !empty($brands))
                        @foreach($brands as $brand)
                          <div class="funkyradio-danger">
                              <input type="checkbox" name="brands" id="brand{{ $brand->id }}" value="{{ $brand->id }}" {{ isset($f_brand) && in_array($brand->id, $f_brand) ? 'checked' : '' }}/>
                              <label for="brand{{ $brand->id }}">{{ $brand->name }} <span class="badge">{{ $brand->product()->count() }}</span></label>
                          </div>
                        @endforeach
                    @endif
                </div>

                @if(isset($rangePrice))
                <div class="find sort-by">
                    <h2 class="group_title"><span>Tìm kiếm theo giá</span></h2>
                    <div class="asc-sort">
                        <input type="radio" name="price" id="default_pr" class="radio" value="default" {{ !isset($price_filter) || ($price_filter == 'default')  ? 'checked' : null }}/>
                        <label for="default_pr">Mặc định</label>
                    </div>
                    @forelse($rangePrice as $key => $price)
                    <?php
                        $value = (string) $price['min'].'-'.$price['max'];
                    ?>
                    <div class="asc-sort">
                        <input type="radio" name="price" id="{{ $key }}" class="radio" value="{{ $value }}" {{ isset($price_filter) && ($price_filter == $value) ? 'checked' : null }}/>
                        <label for="{{ $key }}" title="{{ _formatPrice($price['min']). ' - ' ._formatPrice($price['max']) }}">{{ _formatPrice($price['min']). ' - ' ._formatPrice($price['max']) }}</label>
                    </div>
                    <div class="clearfix"></div>
                    @empty
                        <p>Giá cố định</p>
                    @endforelse
                </div>
                @endif
            </form>
        </div>
        <div class="col-md-9 mb-prbx">
            <div class="prbx">
            @if(isset($all_product))
                @forelse($all_product as $p)
                <div class="col-md-4 grid">
                    <div class="item">
                        <div class="thumb">
                            <a class="product-image" href="{{ _getProductLink($p->slug) }}" title="{{ $p->title }}">
                                <img class="product-img" src="{{ $p->thumbnail }}" alt="{{ $p->title }}" />
                            </a>
                        </div>
                        <h3>
                            <a href="{{ _getProductLink($p->slug) }}" title="{{ $p->title }}">{!! ($p->title) !!}</a>
                        </h3>
                        <div class="price-box">
                            <span class="regular-price">
                                @if($p->old_price != 0)
                                <span class="old-price"><s>{{ _formatPrice($p->old_price) }}</s></span>
                                @endif
                                <span class="price">{{ _formatPrice($p->price) }}</span>
                            </span>
                        </div>
                        <div align="left" class="bgr">
                            <a class="addcart btn btn-danger btn-sm" href="{{ _getAddToCartLink($p->content_id) }}">Đặt hàng</a>
                            <a class="detail btn btn-info btn-sm" href="{{ _getProductLink($p->slug) }}">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                @empty
                    <p>Hiện tại chưa có sản phẩm nào</p>
                @endforelse
            @endif
                <div class="clearfix"></div>
            </div>
            <div align="center">
                {!! $all_product->setPath(asset(Request::path()))->appends(Request::query())->render() !!}
            </div>
        </div>
    </div>
@endsection
