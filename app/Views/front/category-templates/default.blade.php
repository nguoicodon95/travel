@extends('front._master')

@section('css')
<style>
	.active a {
		color: #fff;
	}
	.section.catalog-section h2 {
		float: none;
	}
	.section.catalog-section h2.group_title {
		position: relative;
	}
	.aside-left {
		border:  1px solid #888;
	}
	.aside-left li {
		line-height:  22px;
	    	padding-bottom: 5px;
	}
	.aside-left .menu>li>a {
		padding: 0;
		color: #333;
		text-transform: uppercase;
		font-weight: bold;
		border: 1px solid #888;
		display: block;
		padding: 5px 30px;
		border-left: 0;
		border-right: 0;
		margin-top: -1px;
	}
	.aside-left .menu>li>ul {
		padding-top: 10px;
		padding-left: 15px;
	    	margin-left: 30px;
	}
	.aside-left .menu>li>ul li {
		list-style-type: square;
	}
	.aside-left .menu>li>ul li a {
		color:  #555;
	}
	.aside-left li.active>a {
	    background: #337ab7;
	    color: #fff !important;
	}
	  .panel {
	  	margin-top: 20px;
		overflow-y: hidden;
		border-color: #888 !important;
		padding: 10px;
		border-radius: 0 !important;
	  }
	  .panel h6 {
		font-size: 18px;
		text-transform: uppercase;
		font-weight: bold;
		font-family: fantasy;
		letter-spacing: 2px;
	  }
	  .panel h6 span	 {
	  	color: #cb4426;
	  }
	  .panel-body {
	  	border-top: 1px solid #f5f5f5;
	  	padding: 15px 0 !important;
	  }
	  .panel-body ul li {
	  	line-height: 22px;
	  	padding: 5px 0;
	  	border-bottom: 1px dotted #f5f5f5;
	  }
	  .panel-body ul li a {
	  	color: #333;
		font-weight: bold;
	  }
	  .more {
	  	color: #333;
	  }
	@include('front._modules.style_post')

	@media screen and (min-width: 991px) {
		.panel-body .thumbnail {
			width: 37%;
			margin-right: 10px;
			float: left;
		}
		.panel-body p.title {
			font-size: 13px;
			line-height: 15px;
		}
	}
</style>
@endsection

@section('js')

@endsection

@section('js-init')

@endsection

@section('content')
<div class="section catalog-section">
		<div class="container">
				<div class="row">
						<div class="col-md-3">
							<div class="aside-left">
								{!! $left_menu_default !!}
							</div>
							<div class="panel">
								<div class="panel-header">
									<h6><span>avis de nos</span> clients</h6>
								</div>
								<div class="panel-body">
									<ul>
									@foreach($reviews as $review)
									<li>
										<a href="{{ route('testimonial.detail', $review->slug) }}">
											<div class="thumbnail">
												<img class="image-background image-full" src="/images/libraries/trans.png" style="background-image: url('{{ $review->thumbnail }}');" alt="{{ $review->title or '' }}" title="{{ $review->title or '' }}" />
											</div>
											<p class="title">{{ $review->title }}</p>
										</a>
									</li>
									@endforeach
									</ul>
								</div>
								<div class="panel-footer text-right">
									<a href="{{ route('testimonial.all') }}" class="more">Autres »</a>
								</div>
							</div>
						</div>
						<div class="col-md-9">
								<h2 class="group_title">{{ $object->title }}</h2>
								<div class="list-grid">
									@if(!empty($posts))
										@foreach($posts as $post)
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

								<div align="center">
										{!! $posts->setPath(asset(Request::path()))->appends(Request::query())->render() !!}
								</div>
						</div>
				</div>
		</div>
</div>
@endsection
