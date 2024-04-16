@extends('schedule-police::layouts.default')

@section('content')
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Not properly configured!</h4>
        <hr/>
        <p>Make sure to:</p>
        <ul>
            <li>Run the migration, <strong>php artisan migrate</strong></li>
            <li>Publish the assets, <strong>php artisan vendor:publish --tag=schedule-police-assets --force</strong></li>
        </ul>
    </div>
@endsection
