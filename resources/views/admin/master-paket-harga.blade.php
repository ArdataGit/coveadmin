@extends('template.admin-dashboard')

@section('title', 'Master Paket Harga')

@push('style')
<style>
/* Style for chips */
.chip {
    display: inline-flex;
    align-items: center;
    background-color: #007bff;
    color: white;
    border-radius: 20px;
    padding: 5px 10px;
    margin: 2px;
    margin-left: 0.5rem;
    font-size: 0.85rem;
}
.chip .remove-chip {
    color: white;
    margin-left: 5px;
    cursor: pointer;
    font-weight: bold;
}
.chip-container {
    margin-top: 5px;
    min-height: 30px;
}
.form-control {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.empty-state {
    text-align: center;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 20px;
}
.ketersediaan-list {
    padding-left: 20px; /* jarak bullet */
    margin: 0;
    list-style-type: disc; /* bullet list */
}
.ketersediaan-list li {
    margin-bottom: 4px;
}
.ketersediaan-column {
    min-width: 250px; /* lebar kolom */
}
/* Table column width adjustments */
.table th, .table td {
    vertical-align: middle;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.table th:nth-child(1), .table td:nth-child(1) { /* Nomor */
    width: 5%;
}
.table th:nth-child(2), .table td:nth-child(2) { /* Nama Paket */
    width: 12%;
}
.table th:nth-child(3), .table td:nth-child(3) { /* Kos */
    width: 8%;
}
.table th:nth-child(4), .table td:nth-child(4) { /* Kamar */
    width: 8%;
}
.table th:nth-child(5), .table td:nth-child(5) { /* Harga Harian */
    width: 10%;
}
.table th:nth-child(6), .table td:nth-child(6) { /* Harga Bulanan */
    width: 10%;
}
.table th:nth-child(7), .table td:nth-child(7) { /* Harga 3 Bulan */
    width: 10%;
}
.table th:nth-child(8), .table td:nth-child(8) { /* Harga 6 Bulan */
    width: 10%;
}
.table th:nth-child(9), .table td:nth-child(9) { /* Harga Tahunan */
    width: 10%;
}
.table th:nth-child(11), .table td:nth-child(11) { /* Actions */
    width: 7%;
}
</style>
@endpush

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Paket Harga</h2>

    <!-- Search input and Add button -->
    <div class="mb-3 row">
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Paket Harga..." 
                   style="max-width: 300px;" @if($paketHargas->isEmpty()) disabled @endif>
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addPaketHargaModal">
                <i class="icofont-plus"></i> Tambah Paket Harga
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

    <!-- Paket Harga Table -->
    @if ($paketHargas->isNotEmpty())
        <div class="dashboard__table table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Nomor</th>
                        <th>Nama Paket</th>
                        <th>Kos</th>
                        <th>Kamar</th>
                        <th>Harga Harian</th>
                        <th>Harga Bulanan</th>
                        <th>Harga 3 Bulan</th>
                        <th>Harga 6 Bulan</th>
                        <th>Harga Tahunan</th>
                        <th style="width: 30%" class="ketersediaan-column">Ketersediaan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="paket-harga-table-body">
                    @php
                        \Carbon\Carbon::setLocale('id');
                    @endphp
                    @foreach ($paketHargas as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kos->nama ?? '-' }}</td>
                            <td>{{ $item->kamar->nama ?? '-' }}</td>
                            <td>{{ $item->perharian_harga ? number_format($item->perharian_harga, 0, ',', '.') : '-' }}</td>
                            <td>{{ $item->perbulan_harga ? number_format($item->perbulan_harga, 0, ',', '.') : '-' }}</td>
                            <td>{{ $item->pertigabulan_harga ? number_format($item->pertigabulan_harga, 0, ',', '.') : '-' }}</td>
                            <td>{{ $item->perenambulan_harga ? number_format($item->perenambulan_harga, 0, ',', '.') : '-' }}</td>
                            <td>{{ $item->pertahun_harga ? number_format($item->pertahun_harga, 0, ',', '.') : '-' }}</td>
                            <td>
                                @php
                                    $ketersediaan = is_string($item->ketersediaan) ? json_decode($item->ketersediaan, true) : $item->ketersediaan;
                                @endphp
                                @if (is_array($ketersediaan) && !empty($ketersediaan))
                                    <ul class="ketersediaan-list">
                                        @foreach ($ketersediaan as $range)
                                            <li class="mb-2">
                                                <strong>{{ \Carbon\Carbon::parse($range['start_date'])->translatedFormat('j F Y') }}</strong> s/d 
                                                <strong>{{ \Carbon\Carbon::parse($range['end_date'])->translatedFormat('j F Y') }}</strong>
                                            </li>
                                            <br>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-nama="{{ $item->nama }}" 
                                    data-kos_id="{{ $item->kos_id }}" 
                                    data-kamar_id="{{ $item->kamar_id }}" 
                                    data-perharian_harga="{{ $item->perharian_harga }}" 
                                    data-perbulan_harga="{{ $item->perbulan_harga }}" 
                                    data-pertigabulan_harga="{{ $item->pertigabulan_harga }}" 
                                    data-perenambulan_harga="{{ $item->perenambulan_harga }}" 
                                    data-pertahun_harga="{{ $item->pertahun_harga }}" 
                                    data-ketersediaan="{{ htmlspecialchars(json_encode($ketersediaan), ENT_QUOTES, 'UTF-8') }}"
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <p>No paket harga available. Click "Tambah Paket Harga" to add a new package.</p>
        </div>
    @endif

    <!-- Add Paket Harga Modal -->
    <div class="modal fade" id="addPaketHargaModal" tabindex="-1" aria-labelledby="addPaketHargaModalLabel" aria-hidden="true">
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
                    <h5 class="modal-title" id="addPaketHargaModalLabel">Tambah Paket Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addPaketHargaForm" action="{{ route('paket-harga.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ketersediaan" id="add_ketersediaan_input">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
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
                                <!-- Options will be populated dynamically via AJAX -->
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
                        <div class="mb-3">
                            <label for="ketersediaan" class="form-label">Ketersediaan (Rentang Tanggal)</label>
                            <div class="row">
                                <div class="col-5">
                                    <input type="date" class="form-control" id="ketersediaan_start" placeholder="Tanggal Mulai">
                                </div>
                                <div class="col-5">
                                    <input type="date" class="form-control" id="ketersediaan_end" placeholder="Tanggal Selesai">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-sm btn-primary mt-3 w-100" id="add_ketersediaan">Tambah</button>
                                </div>
                            </div>
                            <div class="chip-container" id="add_ketersediaan_chips"></div>
                            @error('ketersediaan')
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
                    <h5 class="modal-title" id="editPaketHargaModalLabel">Edit Paket Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPaketHargaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="ketersediaan" id="edit_ketersediaan_input">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
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
                                <!-- Options will be populated dynamically via AJAX -->
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
                        <div class="mb-3">
                            <label for="edit_ketersediaan" class="form-label">Ketersediaan (Rentang Tanggal)</label>
                            <div class="row">
                                <div class="col-5">
                                    <input type="date" class="form-control" id="edit_ketersediaan_start" placeholder="Tanggal Mulai">
                                </div>
                                <div class="col-5">
                                    <input type="date" class="form-control" id="edit_ketersediaan_end" placeholder="Tanggal Selesai">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-sm btn-primary mt-2 w-100" id="edit_add_ketersediaan">Tambah</button>
                                </div>
                            </div>
                            <div class="chip-container" id="edit_ketersediaan_chips"></div>
                            @error('ketersediaan')
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
    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
    }

    // Store table data for search filtering
    let tableData = [];

    // Array of Indonesian month names
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Function to format date to "tanggal nama_bulan tahun"
    function formatIndonesianDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        if (isNaN(date)) return '-';
        const day = date.getDate();
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }

    // Function to format date range for display as a list
    function formatKetersediaan(ketersediaan) {
        if (!ketersediaan || !Array.isArray(ketersediaan) || ketersediaan.length === 0) {
            return '-';
        }
        return `
            <ul class="ketersediaan-list">
                ${ketersediaan.map(range => `
                    <li class="mb-1">
                        <strong>${formatIndonesianDate(range.start_date)}</strong> s/d 
                        <strong>${formatIndonesianDate(range.end_date)}</strong>
                    </li><br>
                `).join('')}
            </ul>
        `;
    }

    // Function to update ketersediaan hidden input
    function updateKetersediaanInput(containerId, inputId) {
        const container = document.getElementById(containerId);
        const ketersediaan = Array.from(container.querySelectorAll('.chip')).map(chip => ({
            start_date: chip.dataset.start,
            end_date: chip.dataset.end
        }));
        document.getElementById(inputId).value = JSON.stringify(ketersediaan);
    }

    // Function to create a chip for ketersediaan
    function createKetersediaanChip(startDate, endDate, containerId, inputId) {
        if (!startDate || !endDate) return;
        const container = document.getElementById(containerId);
        const chipText = `${formatIndonesianDate(startDate)} s/d ${formatIndonesianDate(endDate)}`;
        if (container.querySelector(`[data-start="${startDate}"][data-end="${endDate}"]`)) return; // Prevent duplicates
        const chip = document.createElement('span');
        chip.className = 'chip';
        chip.dataset.start = startDate;
        chip.dataset.end = endDate;
        chip.innerHTML = `${chipText} <span class="remove-chip" role="button" aria-label="Remove ${chipText}">&times;</span>`;
        container.appendChild(chip);
        updateKetersediaanInput(containerId, inputId);
        chip.querySelector('.remove-chip').addEventListener('click', () => {
            chip.remove();
            updateKetersediaanInput(containerId, inputId);
        });
    }

    // Initialize Add Ketersediaan
    const addKetersediaanStart = document.getElementById('ketersediaan_start');
    const addKetersediaanEnd = document.getElementById('ketersediaan_end');
    const addKetersediaanChips = document.getElementById('add_ketersediaan_chips');
    document.getElementById('add_ketersediaan').addEventListener('click', () => {
        const startDate = addKetersediaanStart.value;
        const endDate = addKetersediaanEnd.value;
        if (startDate && endDate && startDate <= endDate) {
            createKetersediaanChip(startDate, endDate, 'add_ketersediaan_chips', 'add_ketersediaan_input');
            addKetersediaanStart.value = '';
            addKetersediaanEnd.value = '';
        } else {
            showAlert('danger', 'Please enter valid start and end dates (start date must be before or equal to end date).');
        }
    });

    // Initialize Edit Ketersediaan
    const editKetersediaanStart = document.getElementById('edit_ketersediaan_start');
    const editKetersediaanEnd = document.getElementById('edit_ketersediaan_end');
    const editKetersediaanChips = document.getElementById('edit_ketersediaan_chips');
    document.getElementById('edit_add_ketersediaan').addEventListener('click', () => {
        const startDate = editKetersediaanStart.value;
        const endDate = editKetersediaanEnd.value;
        if (startDate && endDate && startDate <= endDate) {
            createKetersediaanChip(startDate, endDate, 'edit_ketersediaan_chips', 'edit_ketersediaan_input');
            editKetersediaanStart.value = '';
            editKetersediaanEnd.value = '';
        } else {
            showAlert('danger', 'Please enter valid start and end dates (start date must be before or equal to end date).');
        }
    });
// Function to fetch rooms for a given kos_id
    function fetchKamar(kosId, kamarSelectId, selectedKamarId = null) {
        const kamarSelect = document.getElementById(kamarSelectId);
        kamarSelect.innerHTML = '<option value="">Pilih Kamar</option>';
        if (!kosId) return;

        fetch(`{{ url('dashboard/kos') }}/${kosId}/details`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(response => {
            if (!response.success) {
                throw new Error('Request was not successful');
            }
            const kamars = response.data || [];
            if (kamars.length === 0) {
                showAlert('warning', 'No rooms available for this kos.');
            }
            kamars.forEach(kamar => {
                const option = document.createElement('option');
                option.value = kamar.id;
                option.textContent = kamar.nama;
                if (selectedKamarId && kamar.id == selectedKamarId) {
                    option.selected = true;
                }
                kamarSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching kamars:', error);
            showAlert('danger', 'Failed to load rooms. Please try again.');
        });
    }

    // Event listener for Add Modal kos_id change
    document.getElementById('kos_id').addEventListener('change', function() {
        const kosId = this.value;
        fetchKamar(kosId, 'kamar_id');
    });

    // Event listener for Edit Modal kos_id change
    document.getElementById('edit_kos_id').addEventListener('change', function() {
        const kosId = this.value;
        const selectedKamarId = document.getElementById('edit_kamar_id').value;
        fetchKamar(kosId, 'edit_kamar_id', selectedKamarId);
    });

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
            tableData = Array.isArray(data) ? data : [];
            renderTable(tableData);
            document.getElementById('searchInput').disabled = tableData.length === 0;
        })
        .catch(error => {
            console.error('Error fetching table data:', error);
            showAlert('danger', 'Failed to refresh table data.');
        });
    }

    // Function to render table with filtered data
    function renderTable(data) {
        const tbody = document.getElementById('paket-harga-table-body');
        const tableContainer = document.querySelector('.dashboard__table');
        const emptyState = document.querySelector('.empty-state');

        if (!data || data.length === 0) {
            if (tableContainer) tableContainer.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            return;
        }

        if (tableContainer) tableContainer.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';

        tbody.innerHTML = '';
        data.forEach((item, index) => {
            if (!item) return;
            const ketersediaan = Array.isArray(item.ketersediaan) ? item.ketersediaan : JSON.parse(item.ketersediaan || '[]');
            const row = document.createElement('tr');
            row.setAttribute('data-id', item.id || '');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.nama || '-'}</td>
                <td>${item.kos?.nama || '-'}</td>
                <td>${item.kamar?.nama || '-'}</td>
                <td>${item.perharian_harga ? new Intl.NumberFormat('id-ID').format(item.perharian_harga) : '-'}</td>
                <td>${item.perbulan_harga ? new Intl.NumberFormat('id-ID').format(item.perbulan_harga) : '-'}</td>
                <td>${item.pertigabulan_harga ? new Intl.NumberFormat('id-ID').format(item.pertigabulan_harga) : '-'}</td>
                <td>${item.perenambulan_harga ? new Intl.NumberFormat('id-ID').format(item.perenambulan_harga) : '-'}</td>
                <td>${item.pertahun_harga ? new Intl.NumberFormat('id-ID').format(item.pertahun_harga) : '-'}</td>
                <td>${formatKetersediaan(ketersediaan)}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" 
                            data-id="${item.id || ''}" 
                            data-nama="${item.nama || ''}" 
                            data-kos_id="${item.kos_id || ''}" 
                            data-kamar_id="${item.kamar_id || ''}" 
                            data-perharian_harga="${item.perharian_harga || ''}" 
                            data-perbulan_harga="${item.perbulan_harga || ''}" 
                            data-pertigabulan_harga="${item.pertigabulan_harga || ''}" 
                            data-perenambulan_harga="${item.perenambulan_harga || ''}" 
                            data-pertahun_harga="${item.pertahun_harga || ''}" 
                            data-ketersediaan='${JSON.stringify(ketersediaan)}'
                            data-bs-toggle="modal" 
                            data-bs-target="#editPaketHargaModal">
                        <i class="icofont-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" 
                            data-id="${item.id || ''}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deletePaketHargaModal">
                        <i class="icofont-trash"></i> Delete
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
        attachButtonListeners();
    }

    // Function to filter table based on search input
    function filterTable(searchTerm) {
        const filteredData = tableData.filter(item => {
            if (!item) return false;
            const searchText = searchTerm.toLowerCase();
            const ketersediaan = Array.isArray(item.ketersediaan) ? item.ketersediaan : JSON.parse(item.ketersediaan || '[]');
            const ketersediaanText = ketersediaan.map(range => `${range.start_date || ''} ${range.end_date || ''}`).join(' ').toLowerCase();
            return (
                (item.nama && item.nama.toLowerCase().includes(searchText)) ||
                (item.kos && item.kos.nama && item.kos.nama.toLowerCase().includes(searchText)) ||
                (item.kamar && item.kamar.nama && item.kamar.nama.toLowerCase().includes(searchText)) ||
                (item.perharian_harga && item.perharian_harga.toString().includes(searchText)) ||
                (item.perbulan_harga && item.perbulan_harga.toString().includes(searchText)) ||
                (item.pertigabulan_harga && item.pertigabulan_harga.toString().includes(searchText)) ||
                (item.perenambulan_harga && item.perenambulan_harga.toString().includes(searchText)) ||
                (item.pertahun_harga && item.pertahun_harga.toString().includes(searchText)) ||
                ketersediaanText.includes(searchText)
            );
        });
        renderTable(filteredData);
    }

    // Search input event listener
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        if (this.disabled) return;
        const searchTerm = this.value.trim();
        filterTable(searchTerm);
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

    // Function to attach event listeners to buttons
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const kos_id = this.getAttribute('data-kos_id');
                const kamar_id = this.getAttribute('data-kamar_id');
                const perharian_harga = this.getAttribute('data-perharian_harga');
                const perbulan_harga = this.getAttribute('data-perbulan_harga');
                const pertigabulan_harga = this.getAttribute('data-pertigabulan_harga');
                const perenambulan_harga = this.getAttribute('data-perenambulan_harga');
                const pertahun_harga = this.getAttribute('data-pertahun_harga');
                const ketersediaan = JSON.parse(this.getAttribute('data-ketersediaan') || '[]');
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_kos_id').value = kos_id;
                document.getElementById('edit_perharian_harga').value = perharian_harga;
                document.getElementById('edit_perbulan_harga').value = perbulan_harga;
                document.getElementById('edit_pertigabulan_harga').value = pertigabulan_harga;
                document.getElementById('edit_perenambulan_harga').value = perenambulan_harga;
                document.getElementById('edit_pertahun_harga').value = pertahun_harga;
                // Clear existing chips
                editKetersediaanChips.innerHTML = '';
                // Populate chips for existing ketersediaan
                ketersediaan.forEach(range => {
                    createKetersediaanChip(range.start_date, range.end_date, 'edit_ketersediaan_chips', 'edit_ketersediaan_input');
                });
                // Populate kamar options based on kos_id
                fetchKamar(kos_id, 'edit_kamar_id', kamar_id);
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

    // AJAX for Add Form
    document.getElementById('addPaketHargaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        try {
            JSON.parse(formData.get('ketersediaan') || '[]');
        } catch (e) {
            showAlert('danger', 'Invalid ketersediaan JSON format');
            return;
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
                addKetersediaanChips.innerHTML = '';
                document.getElementById('add_ketersediaan_input').value = '';
                document.getElementById('kamar_id').innerHTML = '<option value="">Pilih Kamar</option>';
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
        try {
            JSON.parse(formData.get('ketersediaan') || '[]');
        } catch (e) {
            showAlert('danger', 'Invalid ketersediaan JSON format');
            return;
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

    // Initial attachment of button listeners and table refresh
    attachButtonListeners();
    refreshTable();
});
</script>
@endsection