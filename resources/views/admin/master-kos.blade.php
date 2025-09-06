@extends('template.admin-dashboard')

@section('title', 'Master Kos')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Kos</h2>

    <!-- Search input -->
    <div class="mb-3 row">
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Kos...">
        </div>
        <div class="col-6">
            <button id="addKosButton" class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addKosModal">
                <i class="icofont-plus"></i> Tambah Kos
            </button>
        </div>
    </div>

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
        <div class="modal-dialog modal-lg">
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
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="alamat_kota" class="form-label">Alamat Kota</label>
                        <input type="text" class="form-control" id="alamat_kota" name="alamat_kota" required>
                        @error('alamat_kota')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="daerah_id" class="form-label">Daerah</label>
                        <select class="form-control" id="daerah_id" name="daerah_id" required>
                            <option value="">Pilih Daerah</option>
                            @foreach ($lokasi as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->nama }}</option>
                            @endforeach
                        </select>
                        @error('daerah_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="5"></textarea>
                        @error('keterangan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="link_maps" class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control" id="link_maps" name="link_maps">
                        @error('link_maps')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
        <div class="modal-dialog modal-lg">
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
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat_kota" class="form-label">Alamat Kota</label>
                        <input type="text" class="form-control" id="edit_alamat_kota" name="alamat_kota" required>
                        @error('alamat_kota')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_daerah_id" class="form-label">Daerah</label>
                        <select class="form-control" id="edit_daerah_id" name="daerah_id" required>
                            <option value="">Pilih Daerah</option>
                            @foreach ($lokasi as $lok)
                                <option value="{{ $lok->id }}">{{ $lok->nama }}</option>
                            @endforeach
                        </select>
                        @error('daerah_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="5"></textarea>
                        @error('keterangan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_link_maps" class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control" id="edit_link_maps" name="link_maps">
                        @error('link_maps')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.4.0/purify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found.');
        alert('CSRF token tidak ditemukan. Pastikan meta tag csrf-token ada di layout.');
        return;
    }

    // Initialize CKEditor
    let addEditor = null;
    let editEditor = null;

    function initCKEditor() {
        // CKEditor for Add Modal
        ClassicEditor
            .create(document.querySelector('#keterangan'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                height: '250px'
            })
            .then(editor => {
                addEditor = editor;
            })
            .catch(error => {
                console.error('Error initializing CKEditor for add:', error);
            });

        // CKEditor for Edit Modal
        ClassicEditor
            .create(document.querySelector('#edit_keterangan'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                height: '250px'
            })
            .then(editor => {
                editEditor = editor;
            })
            .catch(error => {
                console.error('Error initializing CKEditor for edit:', error);
            });
    }

    // Initialize editor after DOM ready
    setTimeout(initCKEditor, 100);

    // Function to close modal and restore scroll
    function closeModal(modalId) {
        const modalElement = document.getElementById(modalId);
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        
        // Move focus to the "Tambah Kos" button before hiding the modal
        const focusTarget = document.getElementById('addKosButton');
        if (focusTarget) {
            focusTarget.focus();
        }

        modal.hide();
        
        // Remove backdrop and restore scroll
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        document.body.classList.remove('modal-open');
        document.body.style.overflow = ''; // Restore scroll
    }

    // Handle modal hidden event to ensure focus is managed
    ['addKosModal', 'editKosModal', 'deleteKosModal'].forEach(modalId => {
        const modalElement = document.getElementById(modalId);
        modalElement.addEventListener('hidden.bs.modal', () => {
            // Ensure focus is moved to a safe element after modal is hidden
            const focusTarget = document.getElementById('addKosButton');
            if (focusTarget) {
                focusTarget.focus();
            }
            // Additional cleanup for scroll
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        });
    });

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

    // Function to sanitize and render HTML
    function renderHtml(html) {
        if (!html) return '-';
        // Use DOMPurify to sanitize HTML
        const cleanHtml = DOMPurify.sanitize(html, {
            ALLOWED_TAGS: ['p', 'ul', 'ol', 'li', 'blockquote', 'strong', 'em', 'br'],
            ALLOWED_ATTR: []
        });
        // Truncate text for display
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = cleanHtml;
        const textContent = tempDiv.textContent || tempDiv.innerText || '';
        return textContent.length > 50 ? cleanHtml.substring(0, 50) + '...' : cleanHtml;
    }

    // Function to refresh table with search
    function refreshTable(search = '') {
        let url = '{{ route('kos.data') }}';
        if (search) url += '?search=' + encodeURIComponent(search);

        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('kos-table-body');
            tbody.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Data tidak tersedia</td></tr>';
                return;
            }

            data.forEach((item, index) => {
                if (!item) return;

                // Sanitize keterangan for table display
                const keteranganHtml = renderHtml(item.keterangan);

                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id || '');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nama || '-'}</td>
                    <td>${item.alamat_kota || '-'}</td>
                    <td title="${item.keterangan ? DOMPurify.sanitize(item.keterangan) : '-'}">${keteranganHtml}</td>
                    <td>${item.created_at ? new Date(item.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                    <td>
                        <a href="{{ url('dashboard/kos-detail') }}/${item.id}" class="btn btn-sm btn-info">
                            <i class="icofont-home"></i> Kamar
                        </a>
                        <button class="btn btn-sm btn-warning edit-btn"
                            data-id="${item.id || ''}"
                            data-nama="${item.nama || ''}"
                            data-alamat_kota="${item.alamat_kota || ''}"
                            data-daerah_id="${item.daerah_id || ''}"
                            data-keterangan="${encodeURIComponent(item.keterangan || '')}"
                            data-link_maps="${item.link_maps || ''}"
                            data-bs-toggle="modal"
                            data-bs-target="#editKosModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <a href="/dashboard/master-kos/${item.id}/gallery" 
                           class="btn btn-sm btn-info">
                            <i class="icofont-image"></i> Gallery
                        </a>
                        <button class="btn btn-sm btn-danger delete-btn"
                            data-id="${item.id || ''}"
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
        .catch(error => {
            console.error('Error fetching table data:', error);
            showAlert('danger', 'Gagal memuat data.');
        });
    }

    // Function to attach event listeners to buttons
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama') || '';
                const alamat_kota = this.getAttribute('data-alamat_kota') || '';
                const daerah_id = this.getAttribute('data-daerah_id') || '';
                const keterangan = decodeURIComponent(this.getAttribute('data-keterangan') || '');
                const link_maps = this.getAttribute('data-link_maps') || '';

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_alamat_kota').value = alamat_kota;
                document.getElementById('edit_daerah_id').value = daerah_id;
                document.getElementById('edit_link_maps').value = link_maps;
                document.getElementById('editKosForm').action = `{{ url('dashboard/master-kos') }}/${id}`;

                // Set keterangan to editor
                if (editEditor) {
                    editEditor.setData(keterangan);
                } else {
                    document.getElementById('edit_keterangan').value = keterangan;
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteKosForm').action = `{{ url('dashboard/master-kos') }}/${id}`;
            });
        });
    }

    // AJAX for Add Form
    document.getElementById('addKosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        // Update keterangan from editor to form data
        if (addEditor) {
            formData.set('keterangan', addEditor.getData());
        }

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
                if (addEditor) {
                    addEditor.setData('');
                }
                closeModal('addKosModal');
                showAlert('success', data.message);
                refreshTable(document.getElementById('searchInput').value.trim());
            } else {
                showAlert('danger', data.message || 'Gagal menambahkan kos.');
            }
        })
        .catch(error => {
            console.error('Error submitting add form:', error);
            showAlert('danger', 'Terjadi kesalahan, coba lagi.');
            closeModal('addKosModal');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editKosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        // Update keterangan from editor to form data
        if (editEditor) {
            formData.set('keterangan', editEditor.getData());
        }

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
                closeModal('editKosModal');
                showAlert('success', data.message);
                refreshTable(document.getElementById('searchInput').value.trim());
            } else {
                showAlert('danger', data.message || 'Gagal memperbarui kos.');
            }
        })
        .catch(error => {
            console.error('Error submitting edit form:', error);
            showAlert('danger', 'Terjadi kesalahan, coba lagi.');
            closeModal('editKosModal');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteKosForm').addEventListener('submit', function(e) {
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
                closeModal('deleteKosModal');
                showAlert('success', data.message);
                refreshTable(document.getElementById('searchInput').value.trim());
            } else {
                showAlert('danger', data.message || 'Gagal menghapus kos.');
            }
        })
        .catch(error => {
            console.error('Error submitting delete form:', error);
            showAlert('danger', 'Terjadi kesalahan, coba lagi.');
            closeModal('deleteKosModal');
        });
    });

    // Search input event
    document.getElementById('searchInput').addEventListener('input', function() {
        refreshTable(this.value.trim());
    });

    // Initial table refresh
    refreshTable();
});
</script>
@endsection