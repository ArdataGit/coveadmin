@extends('template.admin-dashboard')

@section('title', 'Master Fasilitas')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Fasilitas</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List Fasilitas</h5>
    </div>
    <div class="row">
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Search facilities..." style="max-width: 300px;">
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addFasilitasModal">
                <i class="icofont-plus"></i> Tambah Fasilitas
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

    <!-- Fasilitas Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="fasilitas-table-body">
                @forelse ($fasilitas as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>
                            @if ($item->image)
                                <img src="{{ asset($item->image) }}" alt="{{ $item->nama }}" style="max-width: 50px; max-height: 50px;">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-nama="{{ $item->nama }}" 
                                    data-image="{{ $item->image ? Storage::url($item->image) : '' }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editFasilitasModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteFasilitasModal">
                                <i class="icofont-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Fasilitas Modal -->
    <div class="modal fade" id="addFasilitasModal" tabindex="-1" aria-labelledby="addFasilitasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFasilitasModalLabel">Tambah Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addFasilitasForm" action="{{ route('fasilitas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Fasilitas</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <span class="image-preview d-inline-block mt-2"></span>
                            @error('image')
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

    <!-- Edit Fasilitas Modal -->
    <div class="modal fade" id="editFasilitasModal" tabindex="-1" aria-labelledby="editFasilitasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFasilitasModalLabel">Edit Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFasilitasForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Fasilitas</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                            <span class="image-preview d-inline-block mt-2"></span>
                            @error('image')
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

    <!-- Delete Fasilitas Modal -->
    <div class="modal fade" id="deleteFasilitasModal" tabindex="-1" aria-labelledby="deleteFasilitasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFasilitasModalLabel">Delete Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteFasilitasForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this facility?
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

    // Function to update image preview
    function updateImagePreview(inputId, previewClass) {
        const input = document.getElementById(inputId);
        const preview = input.parentElement.querySelector(previewClass);
        input.addEventListener('change', function() {
            preview.innerHTML = '';
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Initialize image previews
    updateImagePreview('image', '.image-preview');
    updateImagePreview('edit_image', '.image-preview');

    // Function to refresh table data
    function refreshTable(search = '') {
        const url = search 
            ? `{{ route('fasilitas.data') }}?search=${encodeURIComponent(search)}`
            : '{{ route('fasilitas.data') }}';
        
        fetch(url, {
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
        .then(data => {
            const tbody = document.getElementById('fasilitas-table-body');
            tbody.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No data available</td></tr>';
                return;
            }
            data.forEach((item, index) => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nama || '-'}</td>
                    <td>
                        ${item.image ? `<img src="{{ asset($item->image) }}" alt="${item.nama || ''}" style="max-width: 50px; max-height: 50px;">` : '-'}
                    </td>
                    <td>${item.created_at || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="${item.id}" 
                                data-nama="${item.nama || ''}" 
                                data-image="${item.image || ''}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editFasilitasModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" 
                                data-id="${item.id}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteFasilitasModal">
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
                const image = this.getAttribute('data-image');
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                // Update image preview
                const preview = document.getElementById('edit_image').parentElement.querySelector('.image-preview');
                preview.innerHTML = '';
                if (image) {
                    const img = document.createElement('img');
                    img.src = image;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    preview.appendChild(img);
                }
                document.getElementById('editFasilitasForm').action = `{{ url('dashboard/master-fasilitas') }}/${id}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteFasilitasForm').action = `{{ url('dashboard/master-fasilitas') }}/${id}`;
            });
        });
    }

    // Search functionality
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.trim();
            refreshTable(searchTerm);
        }, 300); // Debounce to prevent excessive requests
    });

    // AJAX for Add Form
    document.getElementById('addFasilitasForm').addEventListener('submit', function(e) {
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
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                form.reset();
                document.getElementById('addFasilitasModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Facility added successfully');
                refreshTable(document.getElementById('searchInput').value);
                // Reset image preview
                document.getElementById('image').parentElement.querySelector('.image-preview').innerHTML = '';
            } else {
                showAlert('danger', data.message || 'Failed to add facility');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editFasilitasForm').addEventListener('submit', function(e) {
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
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('editFasilitasModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Facility updated successfully');
                refreshTable(document.getElementById('searchInput').value);
            } else {
                showAlert('danger', data.message || 'Failed to update facility');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteFasilitasForm').addEventListener('submit', function(e) {
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
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('deleteFasilitasModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Facility deleted successfully');
                refreshTable(document.getElementById('searchInput').value);
            } else {
                showAlert('danger', data.message || 'Failed to delete facility');
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