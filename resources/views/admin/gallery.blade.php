@extends('template.admin-dashboard')

@section('title', 'Gallery Kos - {{ $kos->nama }}')

@push('style')
<style>
    .gallery-img {
        max-width: 150px; /* Adjust as needed */
        max-height: 150px; /* Adjust as needed */
        object-fit: cover;
    }
    .alert-dismissible {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
    }
</style>
@endpush

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Gallery Kos: {{ $kos->nama }}</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List Gambar</h5>
        <a href="{{ route('kos.index') }}" class="btn btn-secondary">Kembali ke Kos</a>
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

    <!-- Image Upload Form -->
    <div class="mb-4">
        <form id="uploadImageForm" action="{{ route('kos.gallerykos.store', $kos->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">Upload Gambar</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Gallery Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Nama File</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="gallery-table-body">
                <!-- Populated via AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Delete Image Modal -->
    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteImageModalLabel">Delete Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteImageForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_image_id">
                    <div class="modal-body">
                        Are you sure you want to delete this image?
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
        console.error('CSRF token not found.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
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

    // Function to refresh gallery table
    function refreshTable() {
        fetch('{{ route('gallerykos.data', $kos->id) }}', {
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
            const tbody = document.getElementById('gallery-table-body');
            tbody.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No images available</td></tr>';
                return;
            }
            data.forEach(item => {
                if (!item || !item.id) return;
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td><img src="${item.url}" alt="${item.nama_file || 'Image'}" class="gallery-img" style="max-width:150px;"></td>
                    <td>${item.nama_file || '-'}</td>
                    <td>${item.created_at ? new Date(item.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-btn" 
                                data-id="${item.id}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteImageModal">
                            <i class="icofont-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            attachButtonListeners();
        })
        .catch(error => {
            console.error('Error fetching gallery data:', error);
            showAlert('danger', 'Failed to refresh gallery data.');
        });
    }

    // Function to attach event listeners to delete buttons
    function attachButtonListeners() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_image_id').value = id;
                // Construct the delete URL dynamically
                const deleteUrl = `{{ route('kos.gallerykos.destroy', [$kos->id, ':id']) }}`.replace(':id', id);
                document.getElementById('deleteImageForm').action = deleteUrl;
            });
        });
    }

    // AJAX for Upload Form
    document.getElementById('uploadImageForm').addEventListener('submit', function(e) {
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
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to upload image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while uploading the image.');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteImageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        // Add _method for DELETE since HTML forms don't support DELETE natively
        formData.append('_method', 'DELETE');

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
                document.getElementById('deleteImageModal').querySelector('.btn-close').click();
                showAlert('success', data.message);
                refreshTable();
            } else {
                showAlert('danger', data.message || 'Failed to delete image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while deleting the image.');
        });
    });

    // Initial table refresh
    refreshTable();
});
</script>
@endsection