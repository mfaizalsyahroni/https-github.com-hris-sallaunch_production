@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container d-flex justify-content-center align-items-start pt-2 min-vh-100">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 1000px; width: 100%;">
            <div class="card-body p-4">

                <h4 class="text-center fw-bold mb-3">
                    Employee Details at
                    <div>
                        PT SALLAUNCH PRODUCTION
                    </div>
                </h4>

                @if (session('success'))
                    <div id="successAlert" class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>

                    <script>
                        setTimeout(() => document.getElementById('successAlert')?.remove(), 8000);
                    </script>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 align-middle">

                        <thead class="table-secondary text-center">
                            <tr>
                                <th>Employee ID</th>
                                <th>Fullname</th>
                                <th>Role</th>
                                <th>Working Period Start</th>
                                <th>Employment Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($worker as $row)
                                <tr>
                                    <td><strong>{{ $row->employee_id }}</strong></td>
                                    <td><small class="text-muted">{{ $row->fullname }}</small></td>
                                    <td>{{ $row->role }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->working_period_start)->format('d M Y') }}</td>
                                    <td>{{ $row->employment_type }}</td>

                                    <td class="text-center">

                                        {{-- UPDATE --}}
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editWorker{{ $row->employee_id }}">
                                            <i class="fa fa-pen"></i>
                                            <span>Update</span>
                                        </button>

                                        {{-- DELETE --}}
                                        <form action="{{ route('workers.destroy', $row->employee_id) }}" method="POST"
                                            style="display:inline-block" onsubmit="return confirm('Delete this employee?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                                <span>Delete</span>
                                            </button>

                                        </form>

                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div class="modal fade" id="editWorker{{ $row->employee_id }}">
                                    <div class="modal-dialog modal-lg">

                                        <form action="{{ route('workers.update', $row->employee_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5>Edit Employee</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="mb-2">
                                                        <label>Employee ID</label>
                                                        <input type="text" name="employee_id" class="form-control"
                                                            value="{{ $row->employee_id }}">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label>Fullname</label>
                                                        <input type="text" name="fullname" class="form-control"
                                                            value="{{ $row->fullname }}">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label>Role</label>
                                                        <input type="text" name="role" class="form-control"
                                                            value="{{ $row->role }}">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label>Employment Type</label>
                                                        <select name="employment_type" class="form-control">

                                                            <option value="permanent"
                                                                {{ $row->employment_type == 'permanent' ? 'selected' : '' }}>
                                                                Permanent</option>
                                                            <option value="contract"
                                                                {{ $row->employment_type == 'contract' ? 'selected' : '' }}>
                                                                Contract</option>
                                                            <option value="intern"
                                                                {{ $row->employment_type == 'intern' ? 'selected' : '' }}>
                                                                Intern
                                                            </option>
                                                            <option value="probation"
                                                                {{ $row->employment_type == 'probation' ? 'selected' : '' }}>
                                                                Probation</option>
                                                            <option value="freelance"
                                                                {{ $row->employment_type == 'freelance' ? 'selected' : '' }}>
                                                                Freelance</option>

                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label>Working Start</label>
                                                        <input type="date" name="working_period_start"
                                                            class="form-control" value="{{ $row->working_period_start }}">
                                                    </div>

                                                    <hr>

                                                    <div class="mb-2">
                                                        <label>Password (optional)</label>
                                                        <input type="password" name="password" class="form-control">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label>Confirm Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control">
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-success">
                                                        Update
                                                    </button>
                                                </div>

                                            </div>

                                        </form>

                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No data available
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center my-3">
        <form action="{{ route('new_employee.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger px-4">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection
