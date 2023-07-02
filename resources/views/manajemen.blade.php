@extends('master')


@section('title')
    Manajemen Pegawai
@endsection

@section('container')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Pegawai</h3>

                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        Create User
                    </button>

                    <div class="modal fade" id="createUserModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Create User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="createForm" action="{{ route('pegawai.store') }}" method="POST">
                                        @csrf

                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="email" name="email" class="form-control" >
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password:</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="role">Role:</label>
                                            <select name="role" class="form-control">
                                                <option value="1" >Admin</option>
                                                <option value="2" >User</option>
                                            </select>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" onclick="createEmployee()">Save changes</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawai as $employee)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>
                                    @if ($employee->role == 1)
                                    Admin
                                    @elseif ($employee->role == 2)
                                    User
                                    @else
                                    Unknown
                                    @endif
                                </td>
                                <td>
                                    <!-- Delete button -->
                                    <form action="{{ route('pegawai.destroy', $employee->id) }}" method="POST">
                                        <!-- Edit button -->
                                        @csrf
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $employee->id }}">Edit</button>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $employee->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pegawai</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="updateForm{{ $employee->id }}" action="{{ route('pegawai.update', $employee->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="form-group">
                                                    <label for="name">Name:</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $employee->name }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $employee->email }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="password">Password:</label>
                                                    <input type="password" name="password" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="role">Role:</label>
                                                    <select name="role" class="form-control">
                                                        <option value="1" {{ $employee->role == 1 ? 'selected' : '' }}>Admin</option>
                                                        <option value="2" {{ $employee->role == 2 ? 'selected' : '' }}>User</option>
                                                    </select>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" onclick="updateEmployee({{ $employee->id }})">Save changes</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>





                    <!-- Tambahkan script berikut -->


                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>

    <script>
        function updateEmployee() {
            document.getElementById('updateForm').submit();
        }

        function createEmployee() {
            document.getElementById('createForm').submit();
        }
    </script>

@endsection
