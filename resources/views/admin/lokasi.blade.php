@extends('template.admin-dashboard')

@section('title', 'Master Lokasi')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Lokasi</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List Lokasi</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLokasiModal">
            <i class="icofont-plus"></i> Tambah Lokasi
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

    <!-- Lokasi Table -->
    <div class="dashboard__table table-responsive">
        <table class="">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="lokasi-table-body">
                @forelse ($lokasi as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-bs-toggle="modal" data-bs-target="#editLokasiModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#deleteLokasiModal">
                                <i class="icofont-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Lokasi Modal -->
    <div class="modal fade" id="addLokasiModal" tabindex="-1" aria-labelledby="addLokasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLokasiModalLabel">Tambah Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addLokasiForm" action="{{ route('lokasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            @error('nama')
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

    <!-- Edit Lokasi Modal -->
    <div class="modal fade" id="editLokasiModal" tabindex="-1" aria-labelledby="editLokasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLokasiModalLabel">Edit Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editLokasiForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            @error('nama')
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

    <!-- Delete Lokasi Modal -->
    <div class="modal fade" id="deleteLokasiModal" tabindex="-1" aria-labelledby="deleteLokasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLokasiModalLabel">Delete Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteLokasiForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this location?
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
        console.error('CSRF token not found. Please add <meta name="csrf-token" content="{{ csrf_token() }}"> to the layout.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
    }

    // Function to refresh table data
    function refreshTable() {
        fetch('{{ route('lokasi.data') }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('lokasi-table-body');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                return;
            }
            data.forEach(item => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.nama}</td>
                    <td>${item.created_at}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${item.id}" data-nama="${item.nama}" data-bs-toggle="modal" data-bs-target="#editLokasiModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}" data-bs-toggle="modal" data-bs-target="#deleteLokasiModal">
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
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('editLokasiForm').action = `{{ url('dashboard/master-lokasi') }}/${id}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteLokasiForm').action = `{{ url('dashboard/master-lokasi') }}/${id}`;
            });
        });
    }

    // Initial attachment of button listeners
    attachButtonListeners();

    // AJAX for Add Form
    document.getElementById('addLokasiForm').addEventListener('submit', function(e) {
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
                document.getElementById('addLokasiModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to add location');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editLokasiForm').addEventListener('submit', function(e) {
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
                document.getElementById('editLokasiModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to update location');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteLokasiForm').addEventListener('submit', function(e) {
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
                document.getElementById('deleteLokasiModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to delete location');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });
});
</script>
@endsection