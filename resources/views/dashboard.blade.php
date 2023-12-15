@extends('schedule-control::layouts.default')

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
                @include('schedule-control::dashboard.events')
            </div>
            @if($enableExecute)
                <div class="tab-pane fade" id="v-execute" role="tabpanel" aria-labelledby="v-execute-tab" tabindex="0">
                    @include('schedule-control::dashboard.execute')
                </div>
            @endif
        </div>
    </div>
@endsection
