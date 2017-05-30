@extends('front._master')

@push('style')
<style>
    .list-grid .grid {
        border-bottom: 1px solid #e3e3e3;
        margin: 0 0 30px;
    }
</style>
@endpush

@section('js')
@endsection

@section('js-init')
   
@endsection

@section('content')
<div class="section catalog-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="panel-group panel panel-default" id="accordion">
                    {!! $menu_left_on_search_page !!}
                </div>
            </div>
            @if(isset($posts) && !empty($posts))
            <div class="col-md-8">
                <h4 style="margin-top: 0"><span>Il y a <strong>{{ $count_posts }}</strong> résultats pour mot clé <strong>"{{ Request::get('keyword') }}"</strong></span></h4>
                <form action="{{ route('search', []) }}" method="get">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" placeholder="Recherche...">
                        <span class="input-group-btn">
                            <button class="btn">Recherche</button>
                        </span>
                    </div>
                </form>
                <hr>
                <div class="list-grid">
                    @foreach($posts as $post)
                    <div class="grid row">
                        <div class="col-md-5 reset-padding-all">
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
                </div>
                <div align="center">
                    {!! $posts->setPath(asset(Request::path()))->appends(Request::query())->render() !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
