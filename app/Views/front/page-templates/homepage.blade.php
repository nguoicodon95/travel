@extends('front._master')

@section('css')
<style media="screen">
  .controls {
	display: block !important;
  }
  .post-in-catalog .description {
	font-size: 13px;
	height: 55px;
	overflow-y: auto;
  }
  .panel {
	max-height: 310px;
	overflow-y: hidden;
  }
  .panel h6 {
	font-size: 26px;
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
	max-height: 220px;
	overflow-y: auto;
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
</style>
@endsection

@section('content')
<div class="section">
	<div class="container">
	@if(!empty($catalog_child))
	  @foreach($catalog_child as $key => $catalog)
		<div class="row">
			<div class="col-md-9">
				<h2>{{ $key }}</h2>
			</div>
			<div class="col-md-3">
				<!-- Controls -->
				<div class="controls pull-right crsl-nav" id="carosel{{str_slug($key)}}">
					<a class="left fa fa-chevron-left previous" href="#" data-slide="prev"></a>
					<a class="right fa fa-chevron-right next" href="#" data-slide="next"></a>
				</div>
			</div>
		</div>
		<div class="gallery{{str_slug($key)}} crsl-items" data-navigation="carosel{{str_slug($key)}}">
			<div class="crsl-wrap">
			  @forelse ($catalog as $child)
				<div class="crsl-item">
					<div class="thumbnail">
						<a href="{{ _getCategoryLinkWithParentSlugs($child->id) }}">
							<img class="image-background image-full" src="/images/libraries/trans.png" style="background-image: url('{{ $child->thumbnail }}');" alt="{{ $child->title }}" title="{{ $child->title }}" />
						</a>
					</div>
					<h3><a href="{{ _getCategoryLinkWithParentSlugs($child->id) }}">{{ $child->title }}</a></h3>
				</div>
			  @empty
				<p>No catalog for section</p>
			  @endforelse
			</div>
		</div>
		<script>
			jQuery(document).ready(function($){
				$('.gallery{{str_slug($key)}}').carousel({ visible: 3, itemMargin: 10, itemMinWidth: 300 });
			});
		</script>
	  @endforeach
	@endif
	</div>
</div>
<div class="section bg-grey">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<h2>VOYAGES PRÉFÉRÉS</h2>
			</div>
			<div class="col-md-3">
				<!-- Controls -->
				<div class="controls pull-right crsl-nav" id="carosel-fav">
					<a class="left fa fa-chevron-left previous" href="#carousel-prev" data-slide="prev"></a>
					<a class="right fa fa-chevron-right next" href="#carousel-next" data-slide="next"></a>
				</div>
			</div>
		</div>
		<div class="gallery-fav crsl-items" data-navigation="carosel-fav">
			<div class="crsl-wrap">
				@if(!empty($post_favourite))
				  @foreach ($post_favourite as $post)
				  <div class="crsl-item">
					  <div class="thumbnail">
						  <a href="{{ _getPostLink($post->slug) }}">
							<img class="image-background image-full" src="/images/libraries/trans.png" style="background-image: url('{{ $post->thumbnail }}');" alt="{{ $post->title }}" title="{{ $post->title }}" />
						  </a>
					  </div>
					  <h3 class="voyage-title">
						  <a href="">{{ $post->title }}
							  <span class="fa fa-angle-right"></span>
						  </a>
					  </h3>
				  </div>
				  @endforeach
				@endif
			</div>
		</div>
	</div>
</div>
<div class="section reset-padding-bottom">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<h4 class="about-title">{{ $customfield['title'] or '' }}</h4>
				<div class="about-content">{!! $customfield['body'] or '' !!}</div>
				<div class="about-bottom">
					<div class="row">
						<div class="col-md-9 reset-padding-right">
							<h5>Si Vous ne trouvez pas ce que vous cherchez?</h5>
						</div>
						<div class="col-md-3">
							<a class="btn btn-success" href="/contact">Ecrivez nous</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="voy-in-view">
					<div class="row">
						<div class="col-md-12">
							<div class="panel">
								<div class="panel-header">
									<h6><span>avis de nos</span> clients</h6>
								</div>
								<div class="panel-body">
									<ul>
									@foreach($reviews as $review)
									<li>
										<a href="{{ route('testimonial.detail', $review->slug) }}">{{ $review->title }}</a>
									</li>
									@endforeach
									</ul>
								</div>
								<div class="panel-footer text-right">
									<a href="{{ route('testimonial.all') }}" class="more">Autres »</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
	</div>
</div>
<div class="section post-in-catalog">
	<div class="container">
	@if(!empty($post_in_cate))
	  @foreach($post_in_cate as $row)
		<div class="row">
			<div class="col-md-9">
				<h2><a href="{{ _getCategoryLinkWithParentSlugs($row['id']) }}">{{ $row['title'] }}</a></h2>
			</div>
			<div class="col-md-3">
				<!-- Controls -->
				<div class="controls pull-right crsl-nav" id="carosel{{str_slug($row['title'])}}">
					<a class="left fa fa-chevron-left previous" href="#" data-slide="prev"></a>
					<a class="right fa fa-chevron-right next" href="#" data-slide="next"></a>
				</div>
			</div>
		</div>
		<div class="gallery{{ str_slug($row['title']) }} crsl-items" data-navigation="carosel{{str_slug($row['title'])}}">
			<div class="crsl-wrap">
			  @forelse ($row['posts'] as $post)
				<div class="crsl-item">
					<div class="thumbnail">
						<a href="{{ _getPostLink($post->slug) }}">
							<img class="image-background image-full" src="/images/libraries/trans.png" style="background-image: url('{{ $post->thumbnail }}');" alt="{{ $post->title }}" title="{{ $post->title }}" />
						</a>
					</div>
					<h3><a href="{{ _getPostLink($post->slug) }}">{{ $post->title }}</a></h3>
					<div class="description">
						{{ $post->description }}
					</div>
				</div>
			  @empty
				<p>No catalog for section</p>
			  @endforelse
			</div>
		</div>
		<script>
			jQuery(document).ready(function($){
				$('.gallery{{str_slug($row['title'])}}').carousel({ visible: 3, itemMargin: 10, itemMinWidth: 300 });
			});
		</script>
	  @endforeach
	@endif
	</div>
</div>
@endsection

@section('js')
<script src="/third_party/carousel/responsiveCarousel.min.js"></script>
@stop

@section('js-init')
<script>
	jQuery(document).ready(function($){
		$('.gallery-fav').carousel({ visible: 3, itemMargin: 10, itemMinWidth: 300 });
	});
</script>
@stop
