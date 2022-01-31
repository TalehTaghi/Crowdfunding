@extends('layouts.master')
@section('content')
    @if($projects === "no_own_projects")
        <div class="content">
            <div class="lgray-bg py-5">
                <div class="container">
                    <h1>Oops!.. Nothing to see here &#128533;</h1>
                    <h3>Looks like you don't have projects</h3>
                    <h3><a href="{{ route('index') }}">Click here to go home &#127968;</a></h3>
                </div>
            </div>
        </div>
    @else
        @foreach($projects as $project)
            @include('layouts.project')
        @endforeach
    @endif
@endsection
@section('css')
    <link href="{{ asset('assets/css/footer.css') }}" rel="stylesheet" type="text/css" />
@endsection
@if($projects !== "no_own_projects" && \App\Http\Controllers\DonationController\DonationController::isOwnProject($projects->first()->idProject))
    @section('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}"/>
    @endsection
    @section('js')
        <script type="text/javascript" src="{{ asset('Assets/DataTables/datatables.min.js') }}"></script>

        <script>
            @foreach($projects as $project)
                new DataTable('#investorsTable{{$project->idProject}}', {});
            @endforeach
        </script>
    @endsection
@endif
