@extends('admin._master')

@section('page-toolbar')

@endsection

@section('css')
@endsection

@section('js')
@endsection

@section('js-init')
    
@endsection

@section('content')
    <form action="" method="post">
        {{ csrf_field() }}
        <textarea name="content" style="width: 100%; height: 500px; padding: 10px;">{{ $content }}</textarea>
        <button>Save</button>
    </form>
@endsection
