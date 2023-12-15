<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Laravel Schedule Control</title>

        <link href="{{ asset('/vendor/schedule-control/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/vendor/schedule-control/css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container w-75 px-5">
            <div class="py-5">
                @if($configured)
                    <div class="d-flex align-items-start">
                    <ul class="nav flex-column nav-pills me-3" role="tablist" aria-orientation="vertical">
                        <li>
                            <img src="{{ asset('/vendor/schedule-control/logo.png') }}" id="logo" alt="logo" />
                        </li>
                        <li class="nav-item">
                            <a href="#v-events" id="v-events-tab" class="nav-link active" data-bs-toggle="tab" data-bs-target="#v-events" role="tab" aria-controls="v-events" aria-selected="true">Events</a>
                        </li>
                        @if($enableExecute)
                            <li class="nav-item">
                                <a href="#v-execute" id="v-execute-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#v-execute" role="tab" aria-controls="v-execute" aria-selected="false">Execute</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content w-100 border p-4 shadow bg-body rounded" id="v-tabContent">
                        <div class="tab-pane fade show active" id="v-events" role="tabpanel" aria-labelledby="v-events-tab" tabindex="0">
                            <table class="table table-striped w-100">
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
                                                    <button class="btn btn-outline-primary btn-sm" type="submit">Execute</button>
                                                </form>
                                            @endif
                                            <form class="d-inline-block" method="post" action="{{ route('schedule-control.' . ($event->stopped ? 'start' : 'stop')) }}">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $event->key }}" />
                                                <button class="btn btn-{{ $event->stopped ? 'primary' : 'danger' }} btn-sm" type="submit">{{ $event->stopped ? 'Start' : 'Stop' }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        @if($enableExecute)
                        <div class="tab-pane fade" id="v-execute" role="tabpanel" aria-labelledby="v-execute-tab" tabindex="0">
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
                        </div>
                        @endif
                    </div>
                </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Not properly configured!</h4>
                        <p>Make sure to:</p>
                        <hr>
                        <ul>
                            <li>extend <i>ControlledConsoleKernel</i> in your <i>\App\Console\Kernel</i>.</li>
                            <li>run migration: <i>php artisan migrate</i>.</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <script src="{{ asset('/vendor/schedule-control/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/vendor/schedule-control/js/app.js') }}"></script>
    </body>
</html>
