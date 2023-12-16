<form method="post" action="{{ route('schedule-police.exec') }}">
    @csrf
    <div class="input-group mb-3">
        <label class="input-group-text">php artisan</label>
        <input type="text" name="command" class="form-control" placeholder="command" aria-label="command" value="{{ session('command') ?? 'inspire' }}" />
        <button class="btn btn-outline-primary" type="submit">Execute</button>
    </div>
</form>
<div id="console-output" class="p-3 {{ session('commandOutputIsError') ? 'text-danger': '' }}">
    @if (session('commandOutputMessage'))
        {!! nl2br(session('commandOutputMessage')) !!}
    @endif
</div>
