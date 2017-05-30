<style media="screen">
    .tooltip,.tooltip:before{display:block;left:0;width:100%}.tooltip:after,.tooltip:before{content:" ";position:absolute}label.label-checkbox{padding:0;margin-bottom:5px}form label{font-weight:400}.form-horizontal .form-group{margin-left:0;margin-right:0}.tooltip{background:#1496bb;bottom:100%;color:#fff;margin-bottom:15px;opacity:0;padding:20px;pointer-events:none;position:absolute;-webkit-transform:translateY(10px);-moz-transform:translateY(10px);-ms-transform:translateY(10px);-o-transform:translateY(10px);transform:translateY(10px);-webkit-transition:all .25s ease-out;-moz-transition:all .25s ease-out;-ms-transition:all .25s ease-out;-o-transition:all .25s ease-out;transition:all .25s ease-out}.tooltip:before{bottom:-20px;height:20px}.tooltip:after{border-left:solid transparent 10px;border-right:solid transparent 10px;border-top:solid #1496bb 10px;bottom:-10px;height:0;right:0;margin-left:-13px;width:0}.tooltip .heading-tooltip{font-size:14px}.tooltip .tooltip-list{padding:0 0 0 32px;list-style-type:circle}
    /* IE can just show/hide with no transition */
    .lte8 .tooltip {
      display: none;
    }
    .lte8 [tooltip=format]:hover .tooltip {
      display: block;
    }
    #booking_phone:focus ~ [tooltip=format] .tooltip, [tooltip=format]:hover .tooltip {
      opacity: 1;
      pointer-events: auto;
      -webkit-transform: translateY(0px);
         -moz-transform: translateY(0px);
          -ms-transform: translateY(0px);
           -o-transform: translateY(0px);
              transform: translateY(0px);
    }
</style>
<form enctype="multipart/form-data" class="form-horizontal" action="{{ route('post.Booking') }}" method="post" novalidate="novalidate" id="_form_confirm">
    {{ csrf_field() }}
    <input type="hidden" name="post_id" value="{{ $object->id or 0 }}">
    <div>
        <div class="has-error">
            <div id="debug" class="alert alert-danger"></div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">Les informations ci-dessous (marquées d'une astérisque) sont absentes ou incorrectes.</div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="booking_name">Nom et Prénom <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input class="form-control" name="fullname" id="booking_name" type="text">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="booking_gender">Civilité</label>
                            <select class="form-control" name="gender" id="booking_gender">
                                <option value="mr">Mr</option>
                                <option value="ms">Mlle</option>
                                <option value="mrs">Mme</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="booking_phone">Téléphone <span class="text-danger">*</span></label>
                    <div class="">
                        <div class="input-group">
                            <input class="form-control" name="phone" id="booking_phone" type="text">
                            <span class="input-group-addon" tooltip="format"><i class="fa fa-phone"></i>
                              <div class="tooltip">
                                  <p class="heading-tooltip">Phone number valid formats:</p>
                                  <ul class="tooltip-list">
                                    <div class="row">
                                      <li class="col-md-6 reset-padding-left">(123) 456-7890</li>
                                      <li class="col-md-6 reset-padding-left">123-456-7890</li>
                                      <li class="col-md-6 reset-padding-left">123.456.7890</li>
                                      <li class="col-md-6 reset-padding-left">1234567890</li>
                                      <li class="col-md-6 reset-padding-left">+31636363634</li>
                                      <li class="col-md-6 reset-padding-left">075-63546725</li>
                                    </div>
                                  </ul>
                              </div>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <label for="booking_startdate">Date d’arrivée <span class="text-danger">*</span></label>
                    <div class="">
                        <div class="input-group">
                            <input class="form-control" name="start_date" id="booking_startdate" type="text">
                            <span class="input-group-addon"><i class="fa fa-plane"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="booking_numpeople">Combien de personne <span class="text-danger">*</span></label>
                  <div class="">
                      <div class="input-group">
                          <input class="form-control" name="number_person" id="booking_numpeople" type="number">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                      </div>
                  </div>
                </div>
            </div><!-- col -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="booking_address">Adresse <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input class="form-control" name="address" id="booking_address" type="text">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="booking_email">Adresse E-mail <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" name="email" id="booking_email" type="text">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="booking_duration">Durée du voyage <span class="text-danger">*</span></label>
                    <div class="">
                        <div class="input-group">
                            <input class="form-control" name="travel_time" id="booking_duration" type="text">
                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="booking_numchildren">Combien d'enfant en desous de 12 ans <span class="text-danger"></span></label>
                    <div class="">
                        <div class="input-group">
                            <input class="form-control" name="number_children" id="booking_numchildren" type="number">
                            <span class="input-group-addon"><i class="fa fa-users"></i></span>
                        </div>
                    </div>
                </div>
            </div><!-- col -->
        </div><!-- row -->
        <hr>
        <div class="form-group">
            <div class="col-md-12 reset-padding-all">
                <label>Quelles expériences ou type d'activité aimeriez vous intégrer dans votre voyage ?</label>
                <div class="row">
                    <span id="booking_experiences">
                      @if(isset($activity_type) && !empty($activity_type))
                        @foreach($activity_type as $key => $type)
                          <div class="col-md-6">
                              <div class="checkbox">
                                  <label>
                                      <input class="" id="booking_experiences_{{$key}}" value="{{$key}}" type="checkbox" name="activity_type[]">
                                      <label class="label-checkbox" for="booking_experiences_{{$key}}">{{ $type }}</label>
                                  </label>
                              </div>
                          </div><!-- col -->
                        @endforeach
                      @endif
                    </span>
                </div><!-- row -->
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-3">
                    <p class="form-control-static">Autre(s) idée(s)</p>
                </div><!-- col -->
                <div class="col-sm-4">
                    <input class="form-control" name="activity_type_other" id="booking_experienIdeaOther" type="text">
                </div><!-- col -->
            </div><!-- row -->
        </div>
        <hr>
        <div class="form-group">
            <label>Quel type de voyage voulez vous?</label>
            <div class="row">
                <span id="booking_expectedVoyages">
                  @if(isset($travel_type) && !empty($travel_type))
                    @foreach($travel_type as $key => $type)
                    <div class="col-sm-6">
                        <div class="checkbox">
                            <label>
                                <input id="booking_expectedVoyages_{{$key}}" value="{{$key}}" type="checkbox" name="travel_type[]">
                                <label class="label-checkbox" for="booking_expectedVoyages_{{$key}}">{{ $type }}</label>
                            </label>
                        </div>
                    </div>
                    @endforeach
                  @endif
                </span>
            </div><!-- row -->
        </div>
        <hr>
        <div class="form-group">
            <label>En ce qui concerne les repas, vous souhaitez voyager en</label>
            <div class="row">
                <span id="booking_expectedEats">
                  @if(isset($type_eat) && !empty($type_eat))
                    @foreach($type_eat as $key => $type)
                    <div class="col-sm-6">
                        <div class="checkbox">
                            <label>
                                <input class="" id="booking_expectedEats_{{$key}}" value="{{$key}}" type="checkbox" name="eat_type[]">
                                <label class="label-checkbox" for="booking_expectedEats_{{$key}}">{{ $type }}</label>
                            </label>
                        </div>
                    </div>
                    @endforeach
                  @endif
                </span>
            </div><!-- row -->
        </div>
        <hr>
        <div class="form-group">
            <label>Merci de partager avec nous votre vision du voyage et nous expliquer de quelle façon vous souhaitez découvrir notre pays.</label>
            <small><i>Nous vous conseillons vivement de nous faire part ci-dessous d'un minimum de vos envies, c'est ainsi que nous apprécierons au mieux vos</i></small>
            <div class="input-group">
                <textarea class="form-control" name="content" id="booking_content"></textarea>
                <span class="input-group-addon"><i class="fa fa-comment"></i></span>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">ENVOYER</button>
        </div>
    </div>
</form>
