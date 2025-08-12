@extends('template.admin-dashboard')

@section('title', 'Master Paket Harga')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Paket Harga</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List Paket Harga</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaketHargaModal">
            <i class="icofont-plus"></i> Tambah Paket Harga
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

    <!-- Paket Harga Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kos</th>
                    <th>Kamar</th>
                    <th>Harga Harian</th>
                    <th>Harga Bulanan</th>
                    <th>Harga 3 Bulan</th>
                    <th>Harga 6 Bulan</th>
                    <th>Harga Tahunan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paket-harga-table-body">
                @forelse ($paketHargas as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->kos->nama ?? '-' }}</td>
                        <td>{{ $item->kamar->nama ?? '-' }}</td>
                        <td>{{ $item->perharian_harga ? number_format($item->perharian_harga, 0, ',', '.') : '-' }}</td>
                        <td>{{ $item->perbulan_harga ? number_format($item->perbulan_harga, 0, ',', '.') : '-' }}</td>
                        <td>{{ $item->pertigabulan_harga ? number_format($item->pertigabulan_harga, 0, ',', '.') : '-' }}</td>
                        <td>{{ $item->perenambulan_harga ? number_format($item->perenambulan_harga, 0, ',', '.') : '-' }}</td>
                        <td>{{ $item->pertahun_harga ? number_format($item->pertahun_harga, 0, ',', '.') : '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="{{ $item->id }}" 
                                data-kos_id="{{ $item->kos_id }}" 
                                data-kamar_id="{{ $item->kamar_id }}" 
                                data-perharian_harga="{{ $item->perharian_harga }}" 
                                data-perbulan_harga="{{ $item->perbulan_harga }}" 
                                data-pertigabulan_harga="{{ $item->pertigabulan_harga }}" 
                                data-perenambulan_harga="{{ $item->perenambulan_harga }}" 
                                data-pertahun_harga="{{ $item->pertahun_harga }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editPaketHargaModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" 
                                data-id="{{ $item->id }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deletePaketHargaModal">
                                <i class="icofont-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Paket Harga Modal -->
    <div class="modal fade" id="addPaketHargaModal" tabindex="-1" aria-labelledby="addPaketHargaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaketHargaModalLabel">Tambah Paket Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addPaketHargaForm" action="{{ route('paket-harga.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kos_id" class="form-label">Kos</label>
                            <select class="form-control" id="kos_id" name="kos_id" required>
                                <option value="">Pilih Kos</option>
                                @foreach ($koses as $kos)
                                    <option value="{{ $kos->id }}">{{ $kos->nama }}</option>
                                @endforeach
                            </select>
                            @error('kos_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kamar_id" class="form-label">Kamar</label>
                            <select class="form-control" id="kamar_id" name="kamar_id" required>
                                <option value="">Pilih Kamar</option>
                                @foreach ($kamars as $kamar)
                                    <option value="{{ $kamar->id }}">{{ $kamar->nama }}</option>
                                @endforeach
                            </select>
                            @error('kamar_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="perharian_harga" class="form-label">Harga Per Hari</label>
                            <input type="number" class="form-control" id="perharian_harga" name="perharian_harga">
                            @error('perharian_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="perbulan_harga" class="form-label">Harga Per Bulan</label>
                            <input type="number" class="form-control" id="perbulan_harga" name="perbulan_harga">
                            @error('perbulan_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pertigabulan_harga" class="form-label">Harga Per 3 Bulan</label>
                            <input type="number" class="form-control" id="pertigabulan_harga" name="pertigabulan_harga">
                            @error('pertigabulan_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="perenambulan_harga" class="form-label">Harga Per 6 Bulan</label>
                            <input type="number" class="form-control" id="perenambulan_harga" name="perenambulan_harga">
                            @error('perenambulan_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pertahun_harga" class="form-label">Harga Per Tahun</label>
                            <input type="number" class="form-control" id="pertahun_harga" name="pertahun_harga">
                            @error('pertahun_harga')
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

    <!-- Edit Paket Harga Modal -->
    <div class="modal fade" id="editPaketHargaModal" tabindex="-1" aria-labelledby="editPaketHargaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaketHargaModalLabel">Edit Paket Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPaketHargaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_kos_id" class="form-label">Kos</label>
                            <select class="form-control" id="edit_kos_id" name="kos_id" required>
                                <option value="">Pilih Kos</option>
                                @foreach ($koses as $kos)
                                    <option value="{{ $kos->id }}">{{ $kos->nama }}</option>
                                @endforeach
                            </select>
                            @error('kos_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_kamar_id" class="form-label">Kamar</label>
                            <select class="form-control" id="edit_kamar_id" name="kamar_id" required>
                                <option value="">Pilih Kamar</option>
                                @foreach ($kamars as $kamar)
                                    <option value="{{ $kamar->id }}">{{ $kamar->nama }}</option>
                                @endforeach
                            </select>
                            @error('kamar_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_perharian_harga" class="form-label">Harga Per Hari</label>
                            <input type="number" class="form-control" id="edit_perharian_harga" name="perharian_harga">
                            @error('perharian_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_perbulan_harga" class="form-label">Harga Per Bulan</label>
                            <input type="number" class="form-control" id="edit_perbulan_harga" name="perbulan_harga">
                            @error('perbulan_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_pertigabulan_harga" class="form-label">Harga Per 3 Bulan</label>
                            <input type="number" class="form-control" id="edit_pertigabulan_harga" name="pertigabulan_harga">
                            @error('pertigabulan_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_perenambulan_harga" class="form-label">Harga Per 6 Bulan</label>
                            <input type="number" class="form-control" id="edit_perenambulan_harga" name="perenambulan_harga">
                            @error('perenambulan_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_pertahun_harga" class="form-label">Harga Per Tahun</label>
                            <input type="number" class="form-control" id="edit_pertahun_harga" name="pertahun_harga">
                            @error('pertahun_harga')
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

    <!-- Delete Paket Harga Modal -->
    <div class="modal fade" id="deletePaketHargaModal" tabindex="-1" aria-labelledby="deletePaketHargaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePaketHargaModalLabel">Delete Paket Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deletePaketHargaForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this paket harga?
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
        fetch('{{ route('paket-harga.data') }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('paket-harga-table-body');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center">No data available</td></tr>';
                return;
            }
            data.forEach(item => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.kos?.nama || '-'}</td>
                    <td>${item.kamar?.nama || '-'}</td>
                    <td>${item.perharian_harga ? new Intl.NumberFormat('id-ID').format(item.perharian_harga) : '-'}</td>
                    <td>${item.perbulan_harga ? new Intl.NumberFormat('id-ID').format(item.perbulan_harga) : '-'}</td>
                    <td>${item.pertigabulan_harga ? new Intl.NumberFormat('id-ID').format(item.pertigabulan_harga) : '-'}</td>
                    <td>${item.perenambulan_harga ? new Intl.NumberFormat('id-ID').format(item.perenambulan_harga) : '-'}</td>
                    <td>${item.pertahun_harga ? new Intl.NumberFormat('id-ID').format(item.pertahun_harga) : '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" 
                            data-id="${item.id}" 
                            data-kos_id="${item.kos_id}" 
                            data-kamar_id="${item.kamar_id}" 
                            data-perharian_harga="${item.perharian_harga || ''}" 
                            data-perbulan_harga="${item.perbulan_harga || ''}" 
                            data-pertigabulan_harga="${item.pertigabulan_harga || ''}" 
                            data-perenambulan_harga="${item.perenambulan_harga || ''}" 
                            data-pertahun_harga="${item.pertahun_harga || ''}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editPaketHargaModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" 
                            data-id="${item.id}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deletePaketHargaModal">
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
                const kos_id = this.getAttribute('data-kos_id');
                const kamar_id = this.getAttribute('data-kamar_id');
                const perharian_harga = this.getAttribute('data-perharian_harga');
                const perbulan_harga = this.getAttribute('data-perbulan_harga');
                const pertigabulan_harga = this.getAttribute('data-pertigabulan_harga');
                const perenambulan_harga = this.getAttribute('data-perenambulan_harga');
                const pertahun_harga = this.getAttribute('data-pertahun_harga');
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_kos_id').value = kos_id;
                document.getElementById('edit_kamar_id').value = kamar_id;
                document.getElementById('edit_perharian_harga').value = perharian_harga;
                document.getElementById('edit_perbulan_harga').value = perbulan_harga;
                document.getElementById('edit_pertigabulan_harga').value = pertigabulan_harga;
                document.getElementById('edit_perenambulan_harga').value = perenambulan_harga;
                document.getElementById('edit_pertahun_harga').value = pertahun_harga;
                document.getElementById('editPaketHargaForm').action = `{{ url('dashboard/master-paket-harga') }}/${id}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deletePaketHargaForm').action = `{{ url('dashboard/master-paket-harga') }}/${id}`;
            });
        });
    }

    // Initial attachment of button listeners
    attachButtonListeners();

    // AJAX for Add Form
    document.getElementById('addPaketHargaForm').addEventListener('submit', function(e) {
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
                document.getElementById('addPaketHargaModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to add paket harga');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editPaketHargaForm').addEventListener('submit', function(e) {
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
                document.getElementById('editPaketHargaModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to update paket harga');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deletePaketHargaForm').addEventListener('submit', function(e) {
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
                document.getElementById('deletePaketHargaModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to delete paket harga');
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