@extends('front._master')

@section('css')

@endsection

@section('js')
@endsection

@section('js-init')
@endsection

@push('style')
	<style>
		.heading1{font-size:20px;line-height:25px;text-transform:uppercase;color:#1b2834;font-weight:900;margin:0 0 10px}
		.post-title a {
			color: #333;
			font-weight: bold;
			line-height: 23px;
		}
		.grid {
			border-bottom: 1px solid #f5f5f5;
			padding: 10px 0 0;
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
	</style>
@endpush

@section('content')
<div class="section">
		<div class="container">
				<div class="row">
						<div class="col-md-3">
							<div class="aside-left">
					                {!! $left_menu_default !!}
							</div>
						</div>
						<div class="col-md-9">
								<div class="row">
									<div class="col-md-3" style="padding-right: 0;">
										<div class="thumbnail">
											<img src="{{ $object->thumbnail }}">
										</div>
									</div>
									<div class="col-md-9">
										<h1 class="heading1">{{ $object->title }}</h1>
										<p></p>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="content">
									{!! $object->content !!}
								</div>

								<hr>
								<!-- Other post -->
								<div class="list-grid" id="reviews">
									@if(!empty($reviews))
										@foreach($reviews as $post)
										<div class="grid row">
												<div class="col-md-3">
													<div class="thumbnail">
															<a href="{{ route('testimonial.detail', $post->slug) }}">
																<img class="image-background image-full" src="/images/libraries/trans.png" style="background-image: url('{{ $post->thumbnail }}');" alt="{{ $post->title or '' }}" title="{{ $post->title or '' }}" />
															</a>
													</div>
												</div>
												<div class="col-md-7">
														<div class="post-title">
																<a href="{{ route('testimonial.detail', $post->slug) }}" title="{{ $post->title or '' }}">{{ $post->title or '' }}</a>
														</div>
														<div class="post-desc">{{ str_limit(strip_tags($post->content), 100)  }}</div>
												</div>
										</div>
										@endforeach
									@endif

						                    {!! $reviews->setPath(asset('/testimonial'))->appends(Request::query())->render() !!}
								</div>
						</div>
				</div>
		</div>
</div>
@endsection
