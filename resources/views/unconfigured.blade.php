@extends('schedule-police::layouts.default')

@section('content')
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Not properly configured!</h4>
        <p>Make sure to:</p>
        <hr>
        <ul>
            <li>extend <i>ControlledConsoleKernel</i> in your <i>\App\Console\Kernel</i>.</li>
            <li>run migration: <i>php artisan migrate</i>.</li>
        </ul>
    </div>
@endsection
