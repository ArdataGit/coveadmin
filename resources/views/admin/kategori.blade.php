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
                        <td>{{ $item->id_kategori }}</td>
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
                <form action="{{ route('kategori.store') }}" method="POST">
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
    // Attach data ke modal edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_nama').value = this.dataset.nama;
            document.getElementById('editKategoriForm').action = `/dashboard/kategori/${this.dataset.id}`;
        });
    });

    // Attach data ke modal delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('deleteKategoriForm').action = `/dashboard/kategori/${this.dataset.id}`;
        });
    });

    // AJAX search
    document.getElementById('searchInput').addEventListener('input', function() {
        const search = this.value;
        fetch(`/kategori/data?search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('kategori-table-body');
                tbody.innerHTML = '';
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                    return;
                }
                data.data.forEach((item, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${item.id_kategori}</td>
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
                        </tr>
                    `;
                });
            });
    });
});
</script>
@endsection