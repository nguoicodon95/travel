@extends('front._master')

@section('css')
  <style>
    .box-title .lb-name {
      float: left;
      font-size: 20px;
      font-weight: 600;
      font-style: normal;
      color: #333;
      text-transform: uppercase;
      margin: 10px 0 0;
    }
    .box-title .lb-desc {
      display: block;
      font-size: 14px;
      color: #666;
      clear: both;
    }
    .box-contact {
      line-height: 22px;
    }
    .contact-line {
      border-bottom: 1px dotted #d2d2d2;
      margin: 10px 0;
    }
    .box-contact .bc-yellow {
      background: #f9c200;
    }
    .box-contact .bc-green {
      background: #84bb39;
    }
    .box-contact .bc-box {
      padding: 15px 0;
      text-align: center;
      color: #fff;
      font-size: 22px;
      font-weight: 600;
      margin: 15px 0 0;
      line-height: 30px;
    }
    .bc-box a {
      color: #fff;
    }
    .box-contact label .fa {
      margin-right: 7px;
      font-size: 16px;
    }
  </style>
@endsection

@section('js')

@endsection

@section('js-init')

@endsection

@section('content')
<div class="map">
  {!! $object->content or '' !!}
</div>
<div class="section">
    <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="box">
                  <div class="box-title">
                    <h1 class="lb-name">Entrer en contact</h1>
                      <span class="lb-desc">ACACIA VOYAGE</span>
                  </div>
                  <div class="box-content">
                    <div class="box-contact">
                        <div class="contact-line"></div>
                          <div class="row">
                              <div class="col-sm-3 reset-padding-right">
                                  <label><i class="fa fa-home"></i>Adresse:</label>
                              </div><!-- col -->
                              <div class="col-sm-9">
                                  <p>{{ $CMSSettings['address'] or '' }}</p>
                              </div><!-- col -->
                          </div><!-- row -->
                          <div class="contact-line"></div>
                          <div class="row">
                              <div class="col-sm-3 reset-padding-right">
                                  <label><i class="fa fa-envelope"></i>Mail:</label>
                              </div><!-- col -->
                              <div class="col-sm-9">
                                  <p><a href="mailto:{{ $CMSSettings['email'] or '' }}">{{ $CMSSettings['email'] or '' }}</a></p>
                              </div><!-- col -->
                          </div><!-- row -->
                          <div class="contact-line"></div>
                          <div class="row">
                              <div class="col-sm-3 reset-padding-right">
                                  <label><i class="fa fa-phone"></i>Phone:</label>
                              </div><!-- col -->
                              <div class="col-sm-9">
                                  <p>Tel: {{ $CMSSettings['phone'] or '' }}</p>
                              </div><!-- col -->
                          </div><!-- row -->
                          <div class="row">
                            <div class="col-md-12 col-sm-6">
                                  <div class="bc-box bc-yellow">
                                      NUMÉRO URGENT
                                      <br>
                                      {{ $CMSSettings['phone'] or '' }}
                                  </div>
                              </div><!-- col -->
                              <div class="col-md-12 col-sm-6">
                                  <div class="bc-box bc-green">
                                      E-MAIL
                                      <br>
                                      <a href="mailto:{{ $CMSSettings['email'] or '' }}">{{ $CMSSettings['email'] or '' }}</a>
                                  </div>
                              </div><!-- col -->
                          </div><!-- row -->
                      </div><!-- box-contact -->
                  </div><!-- box-content -->
              </div>
          </div>
          <div class="col-md-6">
            @include('front._modules._contact-us')
          </div>
        </div>
    </div>
</div>
@endsection
