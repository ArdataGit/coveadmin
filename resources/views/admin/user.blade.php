@extends('template.admin-dashboard')

@section('title', 'Master User')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master User</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List User</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="icofont-plus"></i> Tambah User
        </button>
    </div>

    <!-- Success/Error Messages -->
    <div id="alert-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- User Table -->
    <div class="dashboard__table table-responsive">
        <table class="">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Email</th>
                    <th>Foto KTP</th>
                    <th>Foto Selfie</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                @forelse ($users as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            @if($item->gambarktp)
                                <a href="#" 
                                class="preview-link text-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#imagePreviewModal" 
                                data-img-src="{{ asset("img/user/{$item->id}/gambarktp/{$item->gambarktp}") }}">
                                    Lihat Foto
                                </a>
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            @if($item->fotoselfie)
                                <a href="#" 
                                class="preview-link text-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#imagePreviewModal" 
                                data-img-src="{{ asset("img/user/{$item->id}/fotoselfie/{$item->fotoselfie}") }}">
                                    Lihat Foto
                                </a>
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-nama="{{ $item->nama }}" 
                                    data-nik="{{ $item->nik }}" 
                                    data-email="{{ $item->email }}" 
                                    data-alamat="{{ $item->alamat }}" 
                                    data-status="{{ $item->status }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editUserModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteUserModal">
                                <i class="icofont-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addUserForm" action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="number" class="form-control" id="nik" name="nik" required>
                            @error('nik')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="gambarktp" class="form-label">Foto KTP</label>
                            <input type="file" class="form-control" id="gambarktp" name="gambarktp" accept="image/*" required>
                            <div class="form-text">Format: JPG, JPEG, PNG. Max: 2MB</div>
                        </div>
                        <div class="mb-3">
                            <label for="fotoselfie" class="form-label">Foto Selfie</label>
                            <input type="file" class="form-control" id="fotoselfie" name="fotoselfie" accept="image/*" required>
                            <div class="form-text">Format: JPG, JPEG, PNG. Max: 2MB</div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
                            @error('alamat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_nik" class="form-label">NIK</label>
                            <input type="number" class="form-control" id="edit_nik" name="nik" required>
                            @error('nik')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_gambarktp" class="form-label">Foto KTP</label>
                            <input type="file" class="form-control" id="edit_gambarktp" name="gambarktp" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG. Max: 2MB</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_fotoselfie" class="form-label">Foto Selfie</label>
                            <input type="file" class="form-control" id="edit_fotoselfie" name="fotoselfie" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG. Max: 2MB</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="4" required></textarea>
                            @error('alamat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="Image Preview" style="max-width: 100%; max-height: 500px; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
    }

    // Function to refresh table data
    function refreshTable() {
        fetch('{{ route('user.data') }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('user-table-body');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">No data available</td></tr>';
                return;
            }
            data.forEach(item => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.nama}</td>
                    <td>${item.nik}</td>
                    <td>${item.email}</td>
                    <td>${item.gambarktp ? 
                        `<a href="#" 
                            class="preview-link text-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#imagePreviewModal" 
                            data-img-src="{{ asset('img/user') }}/${item.id}/gambarktp/${item.gambarktp}">
                            Lihat Foto
                        </a>` : 
                        '<span class="text-muted">No Image</span>'}</td>
                    <td>${item.fotoselfie ? 
                        `<a href="#" 
                            class="preview-link text-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#imagePreviewModal" 
                            data-img-src="{{ asset('img/user') }}/${item.id}/fotoselfie/${item.fotoselfie}">
                            Lihat Foto
                        </a>` : 
                        '<span class="text-muted">No Image</span>'}</td>
                    <td>${item.alamat}</td>
                    <td>${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</td>
                    <td>${item.created_at}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="${item.id}" 
                                data-nama="${item.nama}" 
                                data-nik="${item.nik}" 
                                data-email="${item.email}" 
                                data-alamat="${item.alamat}" 
                                data-status="${item.status}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" 
                                data-id="${item.id}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteUserModal">
                            <i class="icofont-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            // Reattach event listeners for edit and delete buttons
            attachButtonListeners();
        })
        .catch(error => {
            console.error('Error fetching table data:', error);
            showAlert('danger', 'Failed to refresh table data.');
        });
    }

    // Function to show alerts
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alert);
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 3000);
    }

    // Function to attach event listeners to buttons
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const nik = this.getAttribute('data-nik');
                const email = this.getAttribute('data-email');
                const alamat = this.getAttribute('data-alamat');
                const status = this.getAttribute('data-status');
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_nik').value = nik;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_alamat').value = alamat;
                document.getElementById('edit_status').value = status;
                document.getElementById('editUserForm').action = `{{ url('dashboard/master-user') }}/${id}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteUserForm').action = `{{ url('dashboard/master-user') }}/${id}`;
            });
        });

        document.querySelectorAll('.preview-link').forEach(link => {
            link.addEventListener('click', function() {
                const imgSrc = this.getAttribute('data-img-src');
                document.getElementById('previewImage').src = imgSrc;
            });
        });
    }

    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
                document.getElementById('addUserModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to add user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel handles PUT via _method
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editUserModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to update user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel handles DELETE via _method
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('deleteUserModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to delete user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });
    attachButtonListeners();
});
</script>
@endsection