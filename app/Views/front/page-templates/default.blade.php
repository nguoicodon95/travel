@extends('front._master')

@section('css')
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
              <h2>{{ $catalog['root'] or '' }}</h2>
              <div class="panel-group panel panel-default" id="accordion">
                {!! $left_menu_on_page !!}
               </div>
            </div>
            <div class="col-md-8">
                {!! $object->content !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@stop

@section('js-init')
@stop
