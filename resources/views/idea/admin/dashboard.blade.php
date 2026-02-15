@extends('layouts.ideastaff')

@section('content')

@php
    $totalIdeas = $ideas->count();
    $draft = $ideas->where('status','draft')->count();
    $voting = $ideas->where('status','voting')->count();
    $reviewed = $ideas->where('status','reviewed')->count();
@endphp

<div class="container py-4">

    <h2 class="fw-bold mb-4">
        🏢 Admin Innovation Dashboard
    </h2>

    {{-- EXECUTIVE SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body">
                    <h6>Total Ideas</h6>
                    <h2>{{ $totalIdeas }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-secondary text-white">
                <div class="card-body">
                    <h6>Draft</h6>
                    <h2>{{ $draft }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning">
                <div class="card-body">
                    <h6>Voting</h6>
                    <h2>{{ $voting }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Reviewed</h6>
                    <h2>{{ $reviewed }}</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- PIPELINE --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">
            Innovation Pipeline
        </div>
        <div class="card-body">

            <div class="progress" style="height:25px">

                <div class="progress-bar bg-secondary"
                    style="width: {{ $totalIdeas ? ($draft/$totalIdeas*100) : 0 }}%">
                    Draft
                </div>

                <div class="progress-bar bg-warning"
                    style="width: {{ $totalIdeas ? ($voting/$totalIdeas*100) : 0 }}%">
                    Voting
                </div>

                <div class="progress-bar bg-success"
                    style="width: {{ $totalIdeas ? ($reviewed/$totalIdeas*100) : 0 }}%">
                    Reviewed
                </div>

            </div>

        </div>
    </div>

    {{-- IDEAS TABLE --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            All Submitted Ideas
        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Votes</th>
                        <th>Submitted</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($ideas as $idea)
                        <tr>

                            <td>
                                <strong>{{ $idea->title }}</strong>
                            </td>

                            <td>
                                <span class="badge
                                    @if($idea->status=='draft') bg-secondary
                                    @elseif($idea->status=='voting') bg-warning
                                    @elseif($idea->status=='reviewed') bg-success
                                    @else bg-dark @endif">
                                    {{ ucfirst($idea->status) }}
                                </span>
                            </td>

                            <td>
                                {{ $idea->votes_count }}
                            </td>

                            <td>
                                {{ $idea->created_at->format('d M Y') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection