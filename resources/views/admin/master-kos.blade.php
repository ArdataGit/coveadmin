@extends('template.admin-dashboard')

@section('title', 'Master Kos')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Kos</h2>

    <!-- Search input -->
    <div class="mb-3 row" >
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Kos...">
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addKosModal">
                <i class="icofont-plus"></i> Tambah Kos
            </button>
        </div>
    </div>

    <div id="alert-container"></div>

    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat Kota</th>
                    <th>Keterangan</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="kos-table-body">
                <tr><td colspan="6" class="text-center">Loading data...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Add Kos Modal -->
    <div class="modal fade" id="addKosModal" tabindex="-1" aria-labelledby="addKosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addKosForm" action="{{ route('kos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addKosModalLabel">Tambah Kos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kos</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat_kota" class="form-label">Alamat Kota</label>
                        <input type="text" class="form-control" id="alamat_kota" name="alamat_kota" required>
                    </div>
                    <div class="mb-3">
                        <label for="daerah_id" class="form-label">Daerah</label>
                        <select class="form-control" id="daerah_id" name="daerah_id" required>
                            <option value="">Pilih Daerah</option>
                            @foreach ($lokasi as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="link_maps" class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control" id="link_maps" name="link_maps">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Kos Modal -->
    <div class="modal fade" id="editKosModal" tabindex="-1" aria-labelledby="editKosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editKosForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKosModalLabel">Edit Kos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Kos</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat_kota" class="form-label">Alamat Kota</label>
                        <input type="text" class="form-control" id="edit_alamat_kota" name="alamat_kota" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_daerah_id" class="form-label">Daerah</label>
                        <select class="form-control" id="edit_daerah_id" name="daerah_id" required>
                            <option value="">Pilih Daerah</option>
                            @foreach ($lokasi as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_link_maps" class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control" id="edit_link_maps" name="link_maps">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Kos Modal -->
    <div class="modal fade" id="deleteKosModal" tabindex="-1" aria-labelledby="deleteKosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteKosForm" method="POST" class="modal-content">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKosModalLabel">Hapus Kos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kos ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Pastikan meta tag csrf-token ada di layout.');
        return;
    }

    // Fungsi menampilkan alert bootstrap
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

    // Fungsi refresh tabel dengan search
    function refreshTable(search = '') {
        let url = '{{ route('kos.data') }}';
        if (search) url += '?search=' + encodeURIComponent(search);

        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('kos-table-body');
            tbody.innerHTML = '';
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Data tidak tersedia</td></tr>';
                return;
            }
            data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.setAttribute('data-id', item.id);
            row.innerHTML = `
                <td>${index + 1}</td> 
                <td>${item.nama}</td>
                <td>${item.alamat_kota}</td>
                <td>${item.keterangan || '-'}</td>
                <td>${new Date(item.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                <td>
                    <a href="{{ url('dashboard/kos-detail') }}/${item.id}" class="btn btn-sm btn-info">
                        <i class="icofont-home"></i> Kamar
                    </a>
                    <button class="btn btn-sm btn-warning edit-btn"
                        data-id="${item.id}"
                        data-nama="${item.nama}"
                        data-alamat_kota="${item.alamat_kota}"
                        data-daerah_id="${item.daerah_id}"
                        data-keterangan="${item.keterangan || ''}"
                        data-link_maps="${item.link_maps || ''}"
                        data-bs-toggle="modal"
                        data-bs-target="#editKosModal">
                        <i class="icofont-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn"
                        data-id="${item.id}"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteKosModal">
                        <i class="icofont-trash"></i> Delete
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
            attachButtonListeners();
        })
        .catch(e => {
            console.error(e);
            showAlert('danger', 'Gagal memuat data.');
        });
    }

    // Pasang event listener untuk tombol Edit dan Delete
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.onclick = function() {
                const id = this.dataset.id;
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = this.dataset.nama;
                document.getElementById('edit_alamat_kota').value = this.dataset.alamat_kota;
                document.getElementById('edit_daerah_id').value = this.dataset.daerah_id;
                document.getElementById('edit_keterangan').value = this.dataset.keterangan;
                document.getElementById('edit_link_maps').value = this.dataset.link_maps;
                document.getElementById('editKosForm').action = `{{ url('dashboard/master-kos') }}/${id}`;
            };
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.onclick = function() {
                const id = this.dataset.id;
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteKosForm').action = `{{ url('dashboard/master-kos') }}/${id}`;
            };
        });
    }

    // AJAX Submit Add Form
    document.getElementById('addKosForm').onsubmit = function(e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                form.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addKosModal'));
                modal.hide();
                showAlert('success', data.message);
                refreshTable(document.getElementById('searchInput').value.trim());
            } else {
                showAlert('danger', data.message || 'Gagal menambahkan kos.');
            }
        })
        .catch(() => showAlert('danger', 'Terjadi kesalahan, coba lagi.'));
    };

    // AJAX Submit Edit Form
    document.getElementById('editKosForm').onsubmit = function(e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel handle PUT lewat _method
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editKosModal'));
                modal.hide();
                showAlert('success', data.message);
                refreshTable(document.getElementById('searchInput').value.trim());
            } else {
                showAlert('danger', data.message || 'Gagal memperbarui kos.');
            }
        })
        .catch(() => showAlert('danger', 'Terjadi kesalahan, coba lagi.'));
    };

    // AJAX Submit Delete Form
    document.getElementById('deleteKosForm').onsubmit = function(e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel handle DELETE lewat _method
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteKosModal'));
                modal.hide();
                showAlert('success', data.message);
                refreshTable(document.getElementById('searchInput').value.trim());
            } else {
                showAlert('danger', data.message || 'Gagal menghapus kos.');
            }
        })
        .catch(() => showAlert('danger', 'Terjadi kesalahan, coba lagi.'));
    };

    // Search input event
    document.getElementById('searchInput').addEventListener('input', function() {
        refreshTable(this.value.trim());
    });

    // Initial load
    refreshTable();
});
</script>
@endsection
