@extends('layouts.main')

@section('content')
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
                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <span class="badge rounded-pill bg-{{ $task->stoppedEvent ? 'danger' : 'success' }}">
                                    {{ $task->stoppedEvent ? 'stopped' : 'running' }}
                                </span>
                            </td>
                            <td>
                                <p class="mb-0 fw-bolder">{{ $task->key }}</p>
                                <p class="mb-0 fw-normal">{{ $task->event->description }}</p>
                                <p class="mb-0 fw-lighter">{{ $task->event->expression }}</p>
                                @if ($task->stoppedEvent)
                                    <small class="text-danger d-block pt-2">
                                        Stopped at:
                                        <p class="mb-0">
                                            {{ $task->stoppedEvent->created_at->format('Y-m-d H:i:sO') }}
                                        </p>
                                        @if ($task->stoppedEvent->by)
                                            <p class="mb-0">{{ $task->stoppedEvent->by }}</p>
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($enableExecute && $task->isEventConsoleCommand())
                                    <form class="d-inline-block" method="post" action="{{ route('schedule-control.exec') }}">
                                        @csrf
                                        <input type="hidden" name="command" value="{{ $task->key }}" />
                                        <button class="btn btn-outline-primary btn-sm" type="submit">Execute</button>
                                    </form>
                                @endif
                                <form class="d-inline-block" method="post" action="{{ route('schedule-control.' . ($task->stoppedEvent ? 'start' : 'stop')) }}">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $task->key }}" />
                                    <input type="hidden" name="expression" value="{{ $task->event->expression }}" />
                                    <button class="btn btn-{{ $task->stoppedEvent ? 'primary' : 'danger' }} btn-sm" type="submit">{{ $task->stoppedEvent ? 'Start' : 'Stop' }}</button>
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
@endsection
