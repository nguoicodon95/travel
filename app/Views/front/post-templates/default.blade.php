@extends('front._master')

@section('css')

@endsection

@section('js')
@endsection

@section('js-init')

@endsection

@push('style')
  <style>
      .heading1{font-size:20px;line-height:20px;text-transform:uppercase;color:#1b2834;font-weight:900;margin:0 0 10px}.public-in{color:#878787;font-size:13px;margin:0 0 15px}.panel.current{background:#337ab7;border-radius:0;color:#fff}.panel .list-group-item a{color:#333}.panel .list-group-item.active a{color:#fff}
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
                <div class="clearfix"></div>
                <div class="content">
                    {!! $object->content !!}
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
