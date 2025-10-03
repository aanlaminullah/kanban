@extends('master')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-container">
        @include('partials.sidebar')

        <div class="main-content">
            @include('dashboard.partials.header')
            @include('dashboard.partials.dashboard-content')
        </div>
    </div>
@endsection
