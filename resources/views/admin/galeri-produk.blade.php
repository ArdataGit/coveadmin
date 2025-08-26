@extends('template.admin-dashboard')

@section('title', 'Galeri Gambar Produk - ' . $produk->judul_produk)

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Galeri Gambar: {{ $produk->judul_produk }}</h2>

    <div class="mb-3 row">
        <div class="col-6">
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                <i class="icofont-arrow-left"></i> Kembali ke Daftar Produk
            </a>
        </div>
        <div class="col-6">
            <button class="btn btn-primary" style="float: inline-end;" data-bs-toggle="modal" data-bs-target="#addGambarModal">
                <i class="icofont-plus"></i> Tambah Gambar
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

    <!-- Gambar Table -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="gambar-table-body">
                @forelse ($gambar as $index => $img)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <img src="{{ asset($img->url_gambar) }}" alt="Gambar Produk" class="img-fluid" style="max-width: 100px; height: auto;">
                        </td>
                        <td>{{ $img->url_gambar }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-gambar-btn" 
                                    data-id="{{ $img->id_gambar }}" 
                                    data-bs-toggle="modal" data-bs-target="#deleteGambarModal">
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

    <!-- Add Gambar Modal -->
    <div class="modal fade" id="addGambarModal" tabindex="-1" aria-labelledby="addGambarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('produk.gambar.store', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addGambarModalLabel">Tambah Gambar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Gambar</label>
                            <input type="file" class="form-control" name="gambar[]" multiple required>
                            @error('gambar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Gambar Modal -->
    <div class="modal fade" id="deleteGambarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteGambarForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Gambar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus gambar ini?
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
    // Attach data ke modal delete gambar
    document.querySelectorAll('.delete-gambar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('deleteGambarForm').action = `/dashboard/produk/gambar/${this.dataset.id}`;
        });
    });
});
</script>
@endsection