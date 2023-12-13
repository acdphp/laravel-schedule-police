
<html lang="">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <title></title>
    </head>
    <body>
        <div class="container">
            <div class="py-5">
                <h1>Scheduler Control</h1>
            </div>
            @if($configured)
                <div class="row">
                <div class="col">
                    <table class="table table-striped">
                        <tr>
                            <th>Status</th>
                            <th>Events</th>
                            <th></th>
                        </tr>
                        @foreach($events as $event)
                            <tr>
                                <td>
                            <span class="badge rounded-pill bg-{{ $event->stopped ? 'danger' : 'success' }}">
                                {{ $event->stopped ? 'stopped' : 'running' }}
                            </span>
                                    <small class="d-block pt-2">
                                        {!! str_replace(' ', '<br/>', $event->stopped) !!}
                                    </small>
                                </td>
                                <td>
                                    <span class="d-block fw-bolder">{{ $event->name }}</span>
                                    <span class="d-block fw-normal">{{ $event->description }}</span>
                                    <span class="d-block fw-lighter">{{ $event->expression }}</span>
                                </td>
                                <td class="text-end">
                                    @if($enableExecute && $event->is_console)
                                        <form class="d-inline-block" method="post" action="{{ route('schedule-control.exec') }}">
                                            @csrf
                                            <input type="hidden" name="command" value="{{ json_decode($event->key)->event }}" />
                                            <button class="btn btn-primary btn-sm" type="submit">Execute</button>
                                        </form>
                                    @endif
                                    <form class="d-inline-block" method="post" action="{{ route('schedule-control.' . ($event->stopped ? 'start' : 'stop')) }}">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $event->key }}" />
                                        <button class="btn btn-{{ $event->stopped ? 'success' : 'danger' }} btn-sm" type="submit">{{ $event->stopped ? 'Start' : 'Stop' }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                @if($enableExecute && $event->is_console)
                    <div class="col-4">
                        <form method="post" action="{{ route('schedule-control.exec') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <label class="input-group-text">php artisan</label>
                                <input type="text" name="command" class="form-control" placeholder="Command" aria-label="Command" value="{{ session('command') ?? '' }}" />
                                <button class="btn btn-primary" type="submit">Execute</button>
                            </div>
                        </form>
                        <hr/>
                        @if (session('commandOutput'))
                            {!! nl2br(session('commandOutput')) !!}
                        @endif
                    </div>
                @endif
            </div>
            @else
                <p>
                    Not properly configured. Make sure to:
                    - extend <i>ControlledConsoleKernel</i> in your <i>\App\Console\Kernel</i>.
                    - migrate: <i>php artisan migrate</i>.
                </p>
            @endif
        </div>
    </body>
</html>
