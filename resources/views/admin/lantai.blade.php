@extends('template.admin-dashboard')

@section('title', 'Master Lantai')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Lantai</h2>
    
    <div class="mb-3 row" >
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Lantai...">
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addLantaiModal">
                <i class="icofont-plus"></i> Tambah Lantai
            </button>
        </div>
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

    <!-- Lantai Table -->
    <div class="dashboard__table table-responsive">
        <table class="">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="lantai-table-body">
                @forelse ($lantai as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-bs-toggle="modal" data-bs-target="#editLantaiModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#deleteLantaiModal">
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

    <!-- Add Lantai Modal -->
    <div class="modal fade" id="addLantaiModal" tabindex="-1" aria-labelledby="addLantaiModalLabel" aria-hidden="true">
        
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLantaiModalLabel">Tambah Lantai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addLantaiForm" action="{{ route('lantai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lantai</label>
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

    <!-- Edit Lantai Modal -->
    <div class="modal fade" id="editLantaiModal" tabindex="-1" aria-labelledby="editLantaiModalLabel" aria-hidden="true">
        
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLantaiModalLabel">Edit Lantai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editLantaiForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Lantai</label>
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

    <!-- Delete Lantai Modal -->
    <div class="modal fade" id="deleteLantaiModal" tabindex="-1" aria-labelledby="deleteLantaiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLantaiModalLabel">Delete Lantai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteLantaiForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this floor?
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan.');
        return;
    }

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) alert.classList.remove('show');
            setTimeout(() => alert?.remove(), 150);
        }, 3000);
    }

    function refreshTable(search = '') {
        let url = '{{ route('lantai.data') }}';
        if (search) url += '?search=' + encodeURIComponent(search);

        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('lantai-table-body');
            tbody.innerHTML = '';
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                return;
            }
            data.forEach((item, index) => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nama}</td>
                    <td>${item.created_at}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${item.id}" data-nama="${item.nama}" data-bs-toggle="modal" data-bs-target="#editLantaiModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}" data-bs-toggle="modal" data-bs-target="#deleteLantaiModal">
                            <i class="icofont-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            attachButtonListeners();
        })
        .catch(() => showAlert('danger', 'Gagal memuat data.'));
    }

    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = function() {
                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_nama').value = this.dataset.nama;
                document.getElementById('editLantaiForm').action = `{{ url('dashboard/master-lantai') }}/${this.dataset.id}`;
            };
        });
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = function() {
                document.getElementById('delete_id').value = this.dataset.id;
                document.getElementById('deleteLantaiForm').action = `{{ url('dashboard/master-lantai') }}/${this.dataset.id}`;
            };
        });
    }

    // Event search input
    document.getElementById('searchInput').addEventListener('input', function() {
        refreshTable(this.value.trim());
    });

    // Initial load
    refreshTable();
});

</script>
@endsection