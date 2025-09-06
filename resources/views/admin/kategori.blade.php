@extends('template.admin-dashboard')

@section('title', 'Master Kategori')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Kategori</h2>

    <div class="mb-3 row">
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Kategori...">
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
                <i class="icofont-plus"></i> Tambah Kategori
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

    <!-- Kategori Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="kategori-table-body">
                @forelse ($kategori as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="{{ $item->id_kategori }}" 
                                    data-nama="{{ $item->nama_kategori }}"
                                    data-bs-toggle="modal" data-bs-target="#editKategoriModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" 
                                    data-id="{{ $item->id_kategori }}" 
                                    data-bs-toggle="modal" data-bs-target="#deleteKategoriModal">
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

    <!-- Add Kategori Modal -->
    <div class="modal fade" id="addKategoriModal" tabindex="-1" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addKategoriForm" action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" required>
                            @error('nama_kategori')
                                <span class="text-danger">{{ $message }}</span>
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

    <!-- Edit Kategori Modal -->
    <div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editKategoriForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama_kategori" required>
                            @error('nama_kategori')
                                <span class="text-danger">{{ $message }}</span>
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

    <!-- Delete Kategori Modal -->
    <div class="modal fade" id="deleteKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteKategoriForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus kategori ini?
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
    // Ambil CSRF token dari meta
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found. Please add <meta name="csrf-token" content="{{ csrf_token() }}"> to the layout.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
    }

    const searchInput = document.getElementById('searchInput');

    // Fungsi tampilkan alert
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

    // Fungsi attach event listener ke tombol Edit dan Delete
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('editKategoriForm').action = `{{ url('dashboard/kategori') }}/${id}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('deleteKategoriForm').action = `{{ url('dashboard/kategori') }}/${id}`;
            });
        });
    }

    // Fungsi refresh tabel data
    function refreshTable(search = '') {
        const url = new URL('{{ route('kategori.data') }}', window.location.origin);
        if (search) {
            url.searchParams.append('search', search);
        }

        fetch(url.toString(), {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('kategori-table-body');
            tbody.innerHTML = '';
            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                return;
            }
            data.data.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nama_kategori}</td>
                    <td>${new Date(item.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="${item.id_kategori}" 
                                data-nama="${item.nama_kategori}"
                                data-bs-toggle="modal" data-bs-target="#editKategoriModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" 
                                data-id="${item.id_kategori}" 
                                data-bs-toggle="modal" data-bs-target="#deleteKategoriModal">
                            <i class="icofont-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            attachButtonListeners();
        })
        .catch(error => {
            console.error('Error fetching table data:', error);
            showAlert('danger', 'Failed to refresh table data.');
        });
    }

    // Event listener untuk input search
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        refreshTable(query);
    });

    // Initial load data tanpa search
    refreshTable();

    // Attach listeners awal
    attachButtonListeners();

    // AJAX Submit Add Form
    document.getElementById('addKategoriForm').addEventListener('submit', function(e) {
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
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                form.reset();
                const modalEl = document.getElementById('addKategoriModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                modal.dispose();

                // Hapus backdrop
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';

                showAlert('success', data.message);
                refreshTable(searchInput.value.trim());
            } else {
                showAlert('danger', data.message || 'Failed to add category');
            }
        })
        .catch(() => showAlert('danger', 'An error occurred. Please try again.'));
    });

    // AJAX Submit Edit Form
    document.getElementById('editKategoriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel PUT via _method
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                form.reset();
                const modalEl = document.getElementById('editKategoriModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                modal.dispose();

                // Hapus backdrop dan reset body
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';

                showAlert('success', data.message);
                refreshTable(searchInput.value.trim());
            } else {
                showAlert('danger', data.message || 'Failed to update category');
            }
        })
        .catch(() => showAlert('danger', 'An error occurred. Please try again.'));
    });

    // AJAX Submit Delete Form
    document.getElementById('deleteKategoriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel DELETE via _method
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const modalEl = document.getElementById('deleteKategoriModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                modal.dispose();

                // Hapus backdrop dan reset body
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';

                showAlert('success', data.message);
                refreshTable(searchInput.value.trim());
            } else {
                showAlert('danger', data.message || 'Failed to delete category');
            }
        })
        .catch(() => showAlert('danger', 'An error occurred. Please try again.'));
    });
});
</script>
@endsection