@extends('master')

@section('title', 'Kanban Board')

@section('content')
    <div class="dashboard-container">
        @include('partials.sidebar')

        <div class="main-content">
            @include('kanban.partials.header')
            @include('kanban.partials.filters-container')
            @include('kanban.partials.kanban-board')

        </div>
    </div>

    @include('kanban.partials.add-task-modal')
    @include('kanban.partials.overlay-modal')
@endsection
