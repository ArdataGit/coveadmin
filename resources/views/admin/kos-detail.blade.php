@extends('template.admin-dashboard')

@section('title', 'Detail Kos - {{ $kos->nama }}')

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
    margin-left: 1rem;
    font-size: 0.9rem;
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
</style>
@endpush

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Detail Kos: {{ $kos->nama }}</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List Kamar</h5>
        <div>
            <a href="{{ route('kos.index') }}" class="btn btn-secondary">Kembali ke Master Kos</a>
            <button id="addKamarButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKamarModal">
                <i class="icofont-plus"></i> Tambah Kamar
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

    <!-- Kamar Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Quantity</th>
                    <th>Lantai</th>
                    <th>Fasilitas</th>
                    <th>Dekat Dengan</th>
                    <th>Deskripsi</th>
                    <th>Jenis Kos</th>
                    <th>Tipe Kos</th>
                    <th>Tipe Sewa</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="kamar-table-body">
                @forelse ($kosDetails as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->lantai ? $item->lantai->nama : '-' }}</td>
                        <td>
                            @foreach (json_decode($item->fasilitas_ids ?? '[]', true) as $fasId)
                                @php
                                    $fasNama = $fasilitas->firstWhere('id', $fasId)->nama ?? null;
                                @endphp
                                @if ($fasNama)
                                    <span class="badge bg-primary">{{ $fasNama }}</span>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach (json_decode($item->dekat_dengan ?? '[]', true) as $lokasi)
                                <span class="badge bg-success">{{ $lokasi }}</span>
                            @endforeach
                        </td>
                        <td>{!! $item->deskripsi ? html_entity_decode($item->deskripsi) : '-' !!}</td>
                        <td>{{ $item->jenis_kos }}</td>
                        <td>{{ $item->tipe_kos?->nama ?? '-' }}</td>
                        <td>{{ $item->tipe_sewa }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="{{ $item->id }}"
                                data-nama="{{ $item->nama }}"
                                data-tipe_kos_id="{{ $item->tipe_kos_id }}"
                                data-lantai_id="{{ $item->lantai_id }}"
                                data-quantity="{{ $item->quantity }}"
                                data-jenis_kos="{{ $item->jenis_kos }}"
                                data-deskripsi="{{ htmlentities($item->deskripsi ?? '') }}"
                                data-dekat_dengan='{{ json_encode(json_decode($item->dekat_dengan ?? '[]', true)) }}'
                                data-fasilitas_ids='{{ json_encode(json_decode($item->fasilitas_ids ?? '[]', true)) }}'
                                data-tipe_sewa="{{ $item->tipe_sewa }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#editKamarModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <a href="/dashboard/kos/{{ $item->kos_id }}/gallery/{{ $item->id }}" 
                               class="btn btn-sm btn-info">
                                <i class="icofont-image"></i> Gallery
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn" 
                                data-id="{{ $item->id }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteKamarModal">
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

    <!-- Add Kamar Modal -->
    <div class="modal fade" id="addKamarModal" tabindex="-1" aria-labelledby="addKamarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKamarModalLabel">Tambah Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addKamarForm" action="{{ route('kos.detail.store', $kos->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="kos_id" value="{{ $kos->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kamar</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tipe_kos_id" class="form-label">Tipe Kos</label>
                            <select class="form-control" id="tipe_kos_id" name="tipe_kos_id" required>
                                <option value="">Pilih Tipe Kos</option>
                                @foreach ($tipeKos as $tipe)
                                    <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                                @endforeach
                            </select>
                            @error('tipe_kos_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="lantai_id" class="form-label">Lantai</label>
                            <select class="form-control" id="lantai_id" name="lantai_id" required>
                                <option value="">Pilih Lantai</option>
                                @foreach ($lantai as $lan)
                                    <option value="{{ $lan->id }}">{{ $lan->nama }}</option>
                                @endforeach
                            </select>
                            @error('lantai_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tipe_sewa" class="form-label">Tipe Sewa</label>
                            <select class="form-control" id="tipe_sewa" name="tipe_sewa" required>
                                <option value="Bulanan">Bulanan</option>
                                <option value="Harian">Harian</option>
                            </select>
                            @error('tipe_sewa')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kos" class="form-label">Jenis Kos</label>
                            <select class="form-control" id="jenis_kos" name="jenis_kos" required>
                                <option value="">Pilih Jenis Kos</option>
                                <option value="Putra">Putra</option>
                                <option value="Putri">Putri</option>
                                <option value="Campur">Campur</option>
                            </select>
                            @error('jenis_kos')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="fasilitas_ids" class="form-label">Fasilitas</label>
                            <select class="form-control" id="fasilitas_ids" aria-label="Pilih fasilitas untuk kamar">
                                <option value="">Pilih Fasilitas</option>
                                @foreach ($fasilitas as $fas)
                                    <option value="{{ $fas->id }}">{{ $fas->nama }}</option>
                                @endforeach
                            </select>
                            <div class="chip-container" id="add_fasilitas_chips"></div>
                            @error('fasilitas_ids')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                            @error('deskripsi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="dekat_dengan" class="form-label">Dekat Dengan</label>
                            <input type="text" id="dekat_dengan_input" class="form-control" placeholder="Ketik lalu Enter">
                            <div class="chip-container" id="add_dekat_chips"></div>
                            @error('dekat_dengan')
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

    <!-- Edit Kamar Modal -->
    <div class="modal fade" id="editKamarModal" tabindex="-1" aria-labelledby="editKamarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKamarModalLabel">Edit Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKamarForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="kos_id" value="{{ $kos->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Kamar</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_tipe_kos_id" class="form-label">Tipe Kos</label>
                            <select class="form-control" id="edit_tipe_kos_id" name="tipe_kos_id" required>
                                <option value="">Pilih Tipe Kos</option>
                                @foreach ($tipeKos as $tipe)
                                    <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                                @endforeach
                            </select>
                            @error('tipe_kos_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_lantai_id" class="form-label">Lantai</label>
                            <select class="form-control" id="edit_lantai_id" name="lantai_id" required>
                                <option value="">Pilih Lantai</option>
                                @foreach ($lantai as $lan)
                                    <option value="{{ $lan->id }}">{{ $lan->nama }}</option>
                                @endforeach
                            </select>
                            @error('lantai_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_tipe_sewa" class="form-label">Tipe Sewa</label>
                            <select class="form-control" id="edit_tipe_sewa" name="tipe_sewa" required>
                                <option value="Bulanan">Bulanan</option>
                                <option value="Harian">Harian</option>
                            </select>
                            @error('tipe_sewa')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_jenis_kos" class="form-label">Jenis Kos</label>
                            <select class="form-control" id="edit_jenis_kos" name="jenis_kos" required>
                                <option value="">Pilih Jenis Kos</option>
                                <option value="Putra">Putra</option>
                                <option value="Putri">Putri</option>
                                <option value="Campur">Campur</option>
                            </select>
                            @error('jenis_kos')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_fasilitas_ids" class="form-label">Fasilitas</label>
                            <select class="form-control" id="edit_fasilitas_ids" aria-label="Pilih fasilitas untuk kamar">
                                <option value="">Pilih Fasilitas</option>
                                @foreach ($fasilitas as $fas)
                                    <option value="{{ $fas->id }}">{{ $fas->nama }}</option>
                                @endforeach
                            </select>
                            <div class="chip-container" id="edit_fasilitas_chips"></div>
                            @error('fasilitas_ids')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi"></textarea>
                            @error('deskripsi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_dekat_dengan" class="form-label">Dekat Dengan</label>
                            <input type="text" id="edit_dekat_input" class="form-control" placeholder="Ketik lalu Enter">
                            <div class="chip-container" id="edit_dekat_chips"></div>
                            @error('dekat_dengan')
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

    <!-- Delete Kamar Modal -->
    <div class="modal fade" id="deleteKamarModal" tabindex="-1" aria-labelledby="deleteKamarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKamarModalLabel">Delete Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteKamarForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this kamar?
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
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.4.0/purify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
    }

    // Initialize CKEditor
    let addEditor = null;
    let editEditor = null;

    function initCKEditor() {
        // CKEditor for Add Modal
        ClassicEditor
            .create(document.querySelector('#deskripsi'), {
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
            .create(document.querySelector('#edit_deskripsi'), {
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
        
        // Move focus to the "Tambah Kamar" button before hiding the modal
        const focusTarget = document.getElementById('addKamarButton');
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
    ['addKamarModal', 'editKamarModal', 'deleteKamarModal'].forEach(modalId => {
        const modalElement = document.getElementById(modalId);
        modalElement.addEventListener('hidden.bs.modal', () => {
            // Ensure focus is moved to a safe element after modal is hidden
            const focusTarget = document.getElementById('addKamarButton');
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

    // Fetch fasilitas data for mapping IDs to names
    const fasilitasMap = {};
    @foreach ($fasilitas as $fas)
        fasilitasMap[{{ $fas->id }}] = '{{ $fas->nama }}';
    @endforeach

    // Function to create a chip
    function createChip(id, name, container, formId) {
        if (!id || id === '') return;
        // Check if chip already exists to prevent duplicates
        if (container.querySelector(`[data-id="${id}"]`)) return;
        const chip = document.createElement('span');
        chip.className = 'chip';
        chip.dataset.id = id;
        chip.innerHTML = `${name} <span class="remove-chip" role="button" aria-label="Remove ${name}">&times;</span>`;
        container.appendChild(chip);
        // Add hidden input for form submission
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'fasilitas_ids[]';
        input.value = id;
        document.getElementById(formId).appendChild(input);
        // Remove chip on click
        chip.querySelector('.remove-chip').addEventListener('click', () => {
            chip.remove();
            input.remove();
        });
    }

    // Function to create dekat_dengan chip
    function createDekatChip(value, container, formId) {
        if (!value || value.trim() === '') return;
        // Check duplicate
        if ([...container.querySelectorAll('.chip')].some(chip => chip.textContent.trim() === value.trim())) return;

        const chip = document.createElement('span');
        chip.className = 'chip';
        chip.innerHTML = `${value} <span class="remove-chip" role="button">&times;</span>`;
        container.appendChild(chip);

        // Hidden input for form
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'dekat_dengan[]';
        input.value = value;
        document.getElementById(formId).appendChild(input);

        chip.querySelector('.remove-chip').addEventListener('click', () => {
            chip.remove();
            input.remove();
        });
    }

    // Initialize Add Kamar Modal dropdown
    const addFasilitasSelect = document.getElementById('fasilitas_ids');
    const addFasilitasChips = document.getElementById('add_fasilitas_chips');
    addFasilitasSelect.addEventListener('change', () => {
        const id = addFasilitasSelect.value;
        const name = addFasilitasSelect.options[addFasilitasSelect.selectedIndex]?.text;
        createChip(id, name, addFasilitasChips, 'addKamarForm');
        addFasilitasSelect.value = ''; // Reset dropdown
    });

    // Initialize Edit Kamar Modal dropdown
    const editFasilitasSelect = document.getElementById('edit_fasilitas_ids');
    const editFasilitasChips = document.getElementById('edit_fasilitas_chips');
    editFasilitasSelect.addEventListener('change', () => {
        const id = editFasilitasSelect.value;
        const name = editFasilitasSelect.options[editFasilitasSelect.selectedIndex]?.text;
        createChip(id, name, editFasilitasChips, 'editKamarForm');
        editFasilitasSelect.value = ''; // Reset dropdown
    });

    // Handle Add Kamar dekat_dengan input
    const addDekatInput = document.getElementById('dekat_dengan_input');
    const addDekatChips = document.getElementById('add_dekat_chips');
    addDekatInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            createDekatChip(addDekatInput.value, addDekatChips, 'addKamarForm');
            addDekatInput.value = '';
        }
    });

    // Handle Edit Kamar dekat_dengan input
    const editDekatInput = document.getElementById('edit_dekat_input');
    const editDekatChips = document.getElementById('edit_dekat_chips');
    editDekatInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            createDekatChip(editDekatInput.value, editDekatChips, 'editKamarForm');
            editDekatInput.value = '';
        }
    });

    // Function to refresh table data
    function refreshTable() {
        fetch('{{ route('kos.detail.data', $kos->id) }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('kamar-table-body');
            tbody.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center">No data available</td></tr>';
                return;
            }

            data.forEach((item, index) => {
                if (!item) return;

                // Fasilitas
                const fasilitasIds = Array.isArray(item.fasilitas_ids)
                    ? item.fasilitas_ids
                    : JSON.parse(item.fasilitas_ids || '[]');
                const fasilitasChips = fasilitasIds.map(id => {
                    const nama = fasilitasMap[id] || 'Unknown';
                    return `<span class="badge bg-primary me-1 text-white">${nama}</span>`;
                }).join('');

                // Dekat dengan
                const dekatDenganArr = Array.isArray(item.dekat_dengan)
                    ? item.dekat_dengan
                    : JSON.parse(item.dekat_dengan || '[]');
                const dekatChips = dekatDenganArr.map(val =>
                    `<span class="badge bg-success me-1 text-white">${val}</span>`
                ).join('');

                // Sanitize deskripsi for table display
                const deskripsiHtml = renderHtml(item.deskripsi);

                // Row
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id || '');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nama || '-'}</td>
                    <td>${item.quantity || '-'}</td>
                    <td>${item.lantai?.nama || '-'}</td>
                    <td>${fasilitasChips || '-'}</td>
                    <td>${dekatChips || '-'}</td>
                    <td title="${item.deskripsi ? DOMPurify.sanitize(item.deskripsi) : '-'}">${deskripsiHtml}</td>
                    <td>${item.jenis_kos || '-'}</td>
                    <td>${item.tipe_kos?.nama || '-'}</td>
                    <td>${item.tipe_sewa || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" 
                            data-id="${item.id || ''}"
                            data-nama="${item.nama || ''}"
                            data-tipe_kos_id="${item.tipe_kos_id || ''}"
                            data-lantai_id="${item.lantai_id || ''}"
                            data-quantity="${item.quantity || ''}"
                            data-jenis_kos="${item.jenis_kos || ''}"
                            data-deskripsi="${encodeURIComponent(item.deskripsi || '')}"
                            data-dekat_dengan='${JSON.stringify(dekatDenganArr)}'
                            data-fasilitas_ids='${JSON.stringify(fasilitasIds)}'
                            data-tipe_sewa="${item.tipe_sewa || 'Bulanan'}"
                            data-bs-toggle="modal" 
                            data-bs-target="#editKamarModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <a href="/dashboard/kos/${item.kos_id}/gallery/${item.id}" 
                           class="btn btn-sm btn-info">
                            <i class="icofont-image"></i> Gallery
                        </a>
                        <button class="btn btn-sm btn-danger delete-btn" 
                            data-id="${item.id || ''}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteKamarModal">
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

    // Function to attach event listeners to buttons
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama') || '';
                const tipe_kos_id = this.getAttribute('data-tipe_kos_id') || '';
                const lantai_id = this.getAttribute('data-lantai_id') || '';
                const quantity = this.getAttribute('data-quantity') || '';
                const jenis_kos = this.getAttribute('data-jenis_kos') || '';
                const deskripsi = decodeURIComponent(this.getAttribute('data-deskripsi') || '');
                const dekatDenganData = JSON.parse(this.getAttribute('data-dekat_dengan') || '[]');
                const fasilitasIds = JSON.parse(this.getAttribute('data-fasilitas_ids') || '[]');
                const tipe_sewa = this.getAttribute('data-tipe_sewa') || 'Bulanan';

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_tipe_kos_id').value = tipe_kos_id;
                document.getElementById('edit_lantai_id').value = lantai_id;
                document.getElementById('edit_quantity').value = quantity;
                document.getElementById('edit_jenis_kos').value = jenis_kos;
                document.getElementById('edit_tipe_sewa').value = tipe_sewa;
                document.getElementById('editKamarForm').action = `{{ url('dashboard/kos-detail') }}/${id}`;

                // Set deskripsi to editor
                if (editEditor) {
                    editEditor.setData(deskripsi);
                } else {
                    document.getElementById('edit_deskripsi').value = deskripsi;
                }

                // Clear existing chips and inputs
                editFasilitasChips.innerHTML = '';
                document.querySelectorAll('#editKamarForm input[name="fasilitas_ids[]"]').forEach(input => input.remove());
                // Populate chips for existing fasilitas_ids
                fasilitasIds.forEach(id => {
                    const name = fasilitasMap[id] || 'Unknown';
                    createChip(id, name, editFasilitasChips, 'editKamarForm');
                });

                // Clear existing dekat_dengan chips and inputs
                editDekatChips.innerHTML = '';
                document.querySelectorAll('#editKamarForm input[name="dekat_dengan[]"]').forEach(input => input.remove());
                // Populate dekat_dengan chips
                dekatDenganData.forEach(val => createDekatChip(val, editDekatChips, 'editKamarForm'));
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteKamarForm').action = `{{ url('dashboard/kos-detail') }}/${id}`;
            });
        });
    }

    // AJAX for Add Form
    document.getElementById('addKamarForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        // Update deskripsi from editor to form data
        if (addEditor) {
            formData.set('deskripsi', addEditor.getData());
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
                addFasilitasChips.innerHTML = '';
                addDekatChips.innerHTML = '';
                document.querySelectorAll('#addKamarForm input[name="fasilitas_ids[]"]').forEach(input => input.remove());
                document.querySelectorAll('#addKamarForm input[name="dekat_dengan[]"]').forEach(input => input.remove());
                if (addEditor) {
                    addEditor.setData('');
                }
                closeModal('addKamarModal');
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to add kamar');
            }
        })
        .catch(error => {
            console.error('Error submitting add form:', error);
            showAlert('danger', 'An error occurred. Please try again.');
            closeModal('addKamarModal');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editKamarForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        // Update deskripsi from editor to form data
        if (editEditor) {
            formData.set('deskripsi', editEditor.getData());
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
                closeModal('editKamarModal');
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to update kamar');
            }
        })
        .catch(error => {
            console.error('Error submitting edit form:', error);
            showAlert('danger', 'An error occurred. Please try again.');
            closeModal('editKamarModal');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteKamarForm').addEventListener('submit', function(e) {
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
                closeModal('deleteKamarModal');
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to delete kamar');
            }
        })
        .catch(error => {
            console.error('Error submitting delete form:', error);
            showAlert('danger', 'An error occurred. Please try again.');
            closeModal('deleteKamarModal');
        });
    });

    // Initial attachment of button listeners
    attachButtonListeners();

    // Initial table refresh
    refreshTable();
});
</script>
@endsection