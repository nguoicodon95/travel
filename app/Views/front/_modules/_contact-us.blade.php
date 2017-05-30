@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="/global-actions/contact-us" method="POST" accept-charset="utf-8">
    {!! csrf_field() !!}
    <div class="form-group">
        <label>Subject <span class="required">*</span></label>
        <input type="text" name="subject" class="form-control" autocomplete="off" required="true">
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" autocomplete="off" required="true">
            </div>
            <div class="col-md-6">
                <label>Phone <span class="required">*</span></label>
                <input type="text" name="phone" class="form-control" autocomplete="off" required="true">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control" autocomplete="off" required="true">
    </div>
    <div class="form-group">
        <label>Content <span class="required">*</span></label>
        <textarea name="content" class="form-control" rows="5" required="true"></textarea>
    </div>
    <div class="form-group">
        <div id="contactBoxCaptcha"></div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary btn-block" type="submit">Send request</button>
    </div>
</form>