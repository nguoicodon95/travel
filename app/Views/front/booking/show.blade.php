@extends('front._master')

@section('css')
<style>
  .section {
    padding-top: 0 !important;
  }
</style>
@endsection

@section('js')
@endsection

@section('js-init')
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <h4 class="group_title"><span>Register successfully</span></h4>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    Merci {{ $booking->fullname }} était possible chez nous. Nous avons reçu vos informations d’enregistrement. Nous avons bientôt vous contactera pour confirmer vos informations de réservation.
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ asset('/') }}" style="color: #4a90e2; font-weight: bold;"><u>Go Home</u></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
