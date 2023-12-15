<form method="post" action="{{ route('schedule-control.exec') }}">
    @csrf
    <div class="input-group mb-3">
        <label class="input-group-text">php artisan</label>
        <input type="text" name="command" class="form-control" placeholder="Command" aria-label="Command" value="{{ session('command') ?? '' }}" />
        <button class="btn btn-outline-primary" type="submit">Execute</button>
    </div>
</form>
<div id="console-output" class="p-3 {{ session('commandOutputIsError') ? 'text-danger': '' }}">
    @if (session('commandOutputMessage'))
        {!! nl2br(session('commandOutputMessage')) !!}
    @endif
</div>
