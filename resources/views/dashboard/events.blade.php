<table class="table table-striped w-100">
    <tr>
        <th>Status</th>
        <th>Events</th>
        <th></th>
    </tr>
    @foreach($events as $event)
        <tr>
            <td>
                <span class="badge rounded-pill bg-{{ $event->stoppedEvent ? 'danger' : 'success' }}">
                    {{ $event->stoppedEvent ? 'stopped' : 'running' }}
                </span>
            </td>
            <td>
                <p class="mb-0 fw-bolder">{{ $event->key }}</p>
                <p class="mb-0 fw-normal">{{ $event->event->description }}</p>
                <p class="mb-0 fw-lighter">{{ $event->event->expression }}</p>
                @if ($event->stoppedEvent)
                    <small class="text-danger d-block pt-2">
                        Stopped at:
                        <p class="mb-0">
                            {{ $event->stoppedEvent->created_at->format('Y-m-d H:i:sO') }}
                        </p>
                        @if ($event->stoppedEvent->by)
                            <p class="mb-0">{{ $event->stoppedEvent->by }}</p>
                        @endif
                    </small>
                @endif
            </td>
            <td class="text-end">
                @if($enableExecute && $event->isEventConsoleCommand())
                    <form class="d-inline-block" method="post" action="{{ route('schedule-police.exec') }}">
                        @csrf
                        <input type="hidden" name="command" value="{{ $event->key }}" />
                        <button class="btn btn-outline-primary btn-sm" type="submit">Execute</button>
                    </form>
                @endif
                <form class="d-inline-block" method="post" action="{{ route('schedule-police.' . ($event->stoppedEvent ? 'start' : 'stop')) }}">
                    @csrf
                    <input type="hidden" name="key" value="{{ $event->key }}" />
                    <input type="hidden" name="expression" value="{{ $event->event->expression }}" />
                    <button class="btn btn-{{ $event->stoppedEvent ? 'primary' : 'danger' }} btn-sm" type="submit">{{ $event->stoppedEvent ? 'Start' : 'Stop' }}</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
