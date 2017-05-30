<form class="form-inline" accept-charset="UTF-8" method="POST" action="{{ '/global-actions/subscribe-email' }}">
    {!! csrf_field() !!}
    <div class="form-group">
        <input type="text" class="form-control" id="subscribe-email" placeholder="Adresse Email" name="email" required>
    </div>
    <button type="submit" class="btn btn-default">JE M’INSCRIS</button>
</form>