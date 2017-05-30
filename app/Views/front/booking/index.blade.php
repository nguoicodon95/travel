@extends('front._master')

@section('css')
<link rel="stylesheet" href="/third_party/datepicker/css/datepicker.css">
@endsection
@push('style')
<style>
  .heading1{margin:0 0 10px;font-size:20px;line-height:20px;color:#1b2834;font-weight:900}input.error,input.error~span.input-group-addon{background-color:#f2dede;border-color:#ebccd1;color:#a94442}
  .di-desc-title {
    font-size: 16px;
    color: #333;
    text-transform: uppercase;
    font-weight: 600;
    font-style: normal;
    padding: 15px 0;
}
</style>
@endpush

@section('js')
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script src="/third_party/datepicker/js/bootstrap-datepicker.js"></script>
@endsection

@section('js-init')
<script type="text/javascript">
      // Validate
      $.validator.addMethod("regx", function(value, element, regexpr) {
          return regexpr.test(value);
      }, "Téléphone not invalid.");
      $(function() {
          $('#debug').hide();
          $("#_form_confirm").validate({
              rules: {
                  address: "required",
                  start_date: {
                      required: true,
                      date: true
                  },
                  number_person: {
                      required: true,
                      number: true,
                  },
                  number_children: {
                      number: true,
                  },
                  travel_time: "required",
                  fullname: {
                      required: true,
                      minlength: 2,
                  },
                  phone: {
                    required: true,
                    regx: /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/,
                  },
                  email: {
                      required: true,
                      email: true
                  },
              },
              errorPlacement: function(error, element) {},
              showErrors: function(errorMap, errorList) {
                  $('#debug').show();
                  $("#debug").html("Les informations ci-dessous (marquées d'une astérisque) sont absentes ou incorrectes.");
                  this.defaultShowErrors();
              },
              submitHandler: function(form) {
                  form.submit();
              }
          });
          // datepicker
          var nowTemp = new Date();
          var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
          $('#booking_startdate').datepicker({
            onRender: function(date) {
              return date.valueOf() < now.valueOf() ? 'disabled' : '';
            }
          });
      });
  </script>
@endsection
@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
              <div class="panel-group panel panel-default" id="accordion">
                {!! $menu_left_on_search_page !!}
               </div>
            </div>
            <div class="col-md-8">
                <h1 class="heading1">DEVIS GRATUIT - DEVIS SUR MESURE</h1>
                <div class="di-desc-title">CRÉEZ VOTRE VOYAGE PRIVATIF "SUR MESURE" AVEC ACACIA VOYAGE!</div>
                <div class="s">
                    <p>
                        Soit à partir de nos propositions de voyages que vous pouvez modifier et adapter selon vos envies (durée, parcours, hébergements,...). Soit à partir de votre projet, confiez-nous votre rêve et nous mettrons tout en œuvre pour l'exaucer ! L’un de nos conseillers spécialistes vous contactera sous 48 heures afin d’élaborer avec vous votre voyage sur mesure.
                    </p>
                </div>
                 @include('front/_modules._booking')
            </div>
        </div>
    </div>
</div>
@endsection
