@extends('template.admin-dashboard')

@section('title', 'Master Tickets')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Master Tickets</h2>
    <div class="d-flex justify-content-between mb-3">
        <h5>List Tickets</h5>
    </div>
    <div class="row">
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Search tickets..." style="max-width: 300px;">
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addTicketModal">
                <i class="icofont-plus"></i> Tambah Ticket
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

    <!-- Tickets Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Admin Response</th> <!-- New column for Admin Response -->
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ticket-table-body">
                @forelse ($tickets as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ Str::limit($item->description, 50) }}</td>
                        <td>{{ $item->category ?? '-' }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            @if ($item->image)
                                <a href="#" class="image-preview" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-image="{{ asset('img/tickets/' . $item->user_id . '/' . $item->image) }}">
                                    <img src="{{ asset('img/tickets/' . $item->user_id . '/' . $item->image) }}" alt="Ticket Image" style="max-width: 100px;">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->admin_response ? Str::limit($item->admin_response, 50) : '-' }}</td> <!-- Display Admin Response -->
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $item->id }}" data-user_id="{{ $item->user_id }}" data-title="{{ $item->title }}" data-description="{{ $item->description }}" data-category="{{ $item->category }}" data-bs-toggle="modal" data-bs-target="#editTicketModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#deleteTicketModal">
                                <i class="icofont-trash"></i> Delete
                            </button>
                                <button class="btn btn-sm btn-info response-btn" data-id="{{ $item->id }}" data-admin_response="{{ $item->admin_response ?? '' }}" data-status="{{ $item->status }}" data-bs-toggle="modal" data-bs-target="#responseTicketModal">
                                    <i class="icofont-comment"></i> Respond
                                </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No data available</td> <!-- Updated colspan to 9 -->
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Ticket Modal -->
    <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
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
                    <h5 class="modal-title" id="addTicketModalLabel">Tambah Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addTicketForm" action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User ID</label>
                            <input type="number" class="form-control" id="user_id" name="user_id" value="{{ Auth::user()->id }}" required>
                            @error('user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category">
                            @error('category')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
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

    <!-- Edit Ticket Modal -->
    <div class="modal fade" id="editTicketModal" tabindex="-1" aria-labelledby="editTicketModalLabel" aria-hidden="true">
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
                    <h5 class="modal-title" id="editTicketModalLabel">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTicketForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_user_id" class="form-label">User ID</label>
                            <input type="number" class="form-control" id="edit_user_id" name="user_id" required>
                            @error('user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="edit_category" name="category">
                            @error('category')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
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

    <!-- Delete Ticket Modal -->
    <div class="modal fade" id="deleteTicketModal" tabindex="-1" aria-labelledby="deleteTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTicketModalLabel">Delete Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteTicketForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        Are you sure you want to delete this ticket?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Admin Response Modal -->
    <div class="modal fade" id="responseTicketModal" tabindex="-1" aria-labelledby="responseTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseTicketModalLabel">Admin Response</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="responseTicketForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="response_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="admin_response" class="form-label">Admin Response</label>
                            <textarea class="form-control" id="admin_response" name="admin_response" required></textarea>
                            @error('admin_response')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="Full-size Image" style="max-width: 100%; max-height: 500px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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
    function refreshTable(search = '') {
        const url = search 
            ? `{{ route('tickets.data') }}?search=${encodeURIComponent(search)}`
            : '{{ route('tickets.data') }}';
        
        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('ticket-table-body');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">No data available</td></tr>';
                return;
            }
            data.forEach((item, index) => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', item.id);
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.title}</td>
                    <td>${item.description.length > 50 ? item.description.substring(0, 50) + '...' : item.description}</td>
                    <td>${item.category || '-'}</td>
                    <td>${item.status}</td>
                    <td>${item.image ? `<a href="#" class="image-preview" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-image="/img/tickets/${item.user_id}/${item.image}"><img src="/img/tickets/${item.user_id}/${item.image}" alt="Ticket Image" style="max-width: 100px;"></a>` : '-'}</td>
                    <td>${item.admin_response ? (item.admin_response.length > 50 ? item.admin_response.substring(0, 50) + '...' : item.admin_response) : '-'}</td>
                    <td>${new Date(item.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="${item.id}" data-user_id="${item.user_id}" data-title="${item.title}" data-description="${item.description}" data-category="${item.category || ''}" data-bs-toggle="modal" data-bs-target="#editTicketModal">
                            <i class="icofont-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}" data-bs-toggle="modal" data-bs-target="#deleteTicketModal">
                            <i class="icofont-trash"></i> Delete
                        </button>
                        <button class="btn btn-sm btn-info response-btn" data-id="${item.id}" data-admin_response="${item.admin_response || ''}" data-status="${item.status}" data-bs-toggle="modal" data-bs-target="#responseTicketModal">
                            <i class="icofont-comment"></i> Respond
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            // Reattach event listeners for buttons and image preview
            attachButtonListeners();
            attachImagePreviewListeners();
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
                const user_id = this.getAttribute('data-user_id');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const category = this.getAttribute('data-category');
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_user_id').value = user_id;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_category').value = category;
                document.getElementById('editTicketForm').action = `{{ url('dashboard/tickets') }}/${id}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteTicketForm').action = `{{ url('dashboard/tickets') }}/${id}`;
            });
        });

        document.querySelectorAll('.response-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const admin_response = this.getAttribute('data-admin_response');
                const status = this.getAttribute('data-status');
                document.getElementById('response_id').value = id;
                document.getElementById('admin_response').value = admin_response;
                document.getElementById('status').value = status;
                document.getElementById('responseTicketForm').action = `{{ url('dashboard/tickets') }}/${id}/admin-response`;
            });
        });
    }

    // Function to attach event listeners for image preview
    function attachImagePreviewListeners() {
        document.querySelectorAll('.image-preview').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const imageUrl = this.getAttribute('data-image');
                document.getElementById('previewImage').src = imageUrl;
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

    // Initial attachment of button listeners
    attachButtonListeners();
    attachImagePreviewListeners();

    // AJAX for Add Form
    document.getElementById('addTicketForm').addEventListener('submit', function(e) {
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
                document.getElementById('addTicketModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Ticket created successfully');
                refreshTable(document.getElementById('searchInput').value);
            } else {
                showAlert('danger', data.message || 'Failed to create ticket');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Edit Form
    document.getElementById('editTicketForm').addEventListener('submit', function(e) {
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
                document.getElementById('editTicketModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Ticket updated successfully');
                refreshTable(document.getElementById('searchInput').value);
            } else {
                showAlert('danger', data.message || 'Failed to update ticket');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Delete Form
    document.getElementById('deleteTicketForm').addEventListener('submit', function(e) {
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
                document.getElementById('deleteTicketModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Ticket deleted successfully');
                refreshTable(document.getElementById('searchInput').value);
            } else {
                showAlert('danger', data.message || 'Failed to delete ticket');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred. Please try again.');
        });
    });

    // AJAX for Admin Response Form
    document.getElementById('responseTicketForm').addEventListener('submit', function(e) {
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
                document.getElementById('responseTicketModal').querySelector('.btn-close').click();
                showAlert('success', data.message || 'Response saved successfully');
                refreshTable(document.getElementById('searchInput').value);
            } else {
                showAlert('danger', data.message || 'Failed to save response');
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