@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-5">
        <div class="card shadow rounded-4 mx-auto" style="max-width: 520px;">
            <div class="card-body p-4">

                <h4 class="fw-bold text-center mb-4">
                    Employee Profile
                    <div>
                        <i class="bi bi-person-circle"></i>
                    </div>
                </h4>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Employee ID</strong><br>
                        {{ $worker->employee_id }}
                    </li>

                    <li class="list-group-item">
                        <strong>Full Name</strong><br>
                        {{ $worker->fullname }}
                    </li>

                    <li class="list-group-item">
                        <strong>Position</strong><br>
                        {{ $worker->position }}
                    </li>

                    <li class="list-group-item">
                        <strong>Employment Type</strong><br>
                        {{ ucfirst($worker->employment_type) }}
                    </li>

                    <li class="list-group-item">
                        <strong>Working Period Start</strong><br>
                        {{ optional($worker->working_period_start)->format('d M Y') ?? '-' }}
                    </li>

                    <li class="list-group-item">
                        <strong>Status</strong><br>
                        <span class="badge bg-{{ $worker->isActive() ? 'success' : 'secondary' }}">
                            {{ $worker->isActive() ? 'Active' : 'Inactive' }}
                        </span>
                    </li>
                </ul>

            </div>
        </div>


        <div class="d-flex justify-content-center mt-4">
        <form action="{{ route('view.allemployee') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger px-4">
                <i class="fa-solid fa-right-from-bracket me-2"></i> View All Employees
            </button>
        </form>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <form action="{{ route('overtime.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
@endsection
