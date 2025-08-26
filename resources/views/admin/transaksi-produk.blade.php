@extends('template.admin-dashboard')

@section('title', 'Transaksi Produk')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Transaksi Produk</h2>

    <div class="mb-3 row">
        <div class="col-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari transaksi produk...">
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransaksiModal">
                <i class="icofont-plus"></i> Tambah Transaksi
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

    <!-- Transaksi Table -->
    <div class="dashboard__table table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="transaksi-table-body">
                @forelse ($transaksi as $item)
                    <tr>
                        <td>{{ $item->id_transaksi }}</td>
                        <td>{{ $item->no_order }}</td>
                        <td>{{ $item->user->nama ?? '-' }}</td>
                        <td>{{ $item->produk->judul_produk ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'lunas' ? 'success' : ($item->status == 'belum_lunas' ? 'warning' : 'danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td>{{ $item->tanggal_transaksi->format('d M Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="{{ $item->id_transaksi }}"
                                    data-no-order="{{ $item->no_order }}"
                                    data-id-user="{{ $item->id_user }}"
                                    data-id-produk="{{ $item->id_produk }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-subtotal="{{ $item->subtotal }}"
                                    data-status="{{ $item->status }}"
                                    data-bs-toggle="modal" data-bs-target="#editTransaksiModal">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" 
                                    data-id="{{ $item->id_transaksi }}" 
                                    data-bs-toggle="modal" data-bs-target="#deleteTransaksiModal">
                                <i class="icofont-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Transaksi Modal -->
    <div class="modal fade" id="addTransaksiModal" tabindex="-1" aria-labelledby="addTransaksiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('transaksi-produk.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTransaksiModalLabel">Tambah Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <select name="id_user" class="form-control" id="add_id_user" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" data-harga="">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Produk</label>
                            <select name="id_produk" class="form-control" id="add_id_produk" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produk as $p)
                                    <option value="{{ $p->id_produk }}" data-harga="{{ $p->harga }}">{{ $p->judul_produk }} (Rp {{ number_format($p->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="add_jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control" id="add_harga_satuan" name="harga_satuan" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subtotal</label>
                            <input type="number" class="form-control" id="add_subtotal" name="subtotal" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="datetime-local" class="form-control" name="tanggal_transaksi" id="add_tanggal_transaksi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" id="add_status">
                                <option value="belum_lunas">Belum Lunas</option>
                                <option value="lunas">Lunas</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Transaksi Modal -->
    <div class="modal fade" id="editTransaksiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editTransaksiForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">No Order</label>
                            <input type="text" class="form-control" id="edit_no_order" name="no_order" required readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <select name="id_user" class="form-control" id="edit_id_user" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Produk</label>
                            <select name="id_produk" class="form-control" id="edit_id_produk" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produk as $p)
                                    <option value="{{ $p->id_produk }}" data-harga="{{ $p->harga }}">{{ $p->judul_produk }} (Rp {{ number_format($p->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="edit_jumlah" name="jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control" id="edit_harga_satuan" name="harga_satuan" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subtotal</label>
                            <input type="number" class="form-control" id="edit_subtotal" name="subtotal" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status">
                                <option value="belum_lunas">Belum Lunas</option>
                                <option value="lunas">Lunas</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Transaksi Modal -->
    <div class="modal fade" id="deleteTransaksiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteTransaksiForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus transaksi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
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
    // Fungsi untuk menghitung subtotal
    function calculateSubtotal(jumlahInput, hargaInput, subtotalInput) {
        const jumlah = parseInt(jumlahInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const subtotal = jumlah * harga;
        subtotalInput.value = subtotal >= 0 ? subtotal : 0;
    }

    // Tambah Transaksi
    document.getElementById('add_id_produk').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        document.getElementById('add_harga_satuan').value = harga;
        calculateSubtotal(document.getElementById('add_jumlah'), document.getElementById('add_harga_satuan'), document.getElementById('add_subtotal'));
    });

    document.getElementById('add_jumlah').addEventListener('input', function() {
        calculateSubtotal(this, document.getElementById('add_harga_satuan'), document.getElementById('add_subtotal'));
    });


    // Edit Transaksi
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_no_order').value = this.dataset.noOrder;
            document.getElementById('edit_id_user').value = this.dataset.idUser;
            document.getElementById('edit_id_produk').value = this.dataset.idProduk;
            document.getElementById('edit_jumlah').value = this.dataset.jumlah;
            document.getElementById('edit_harga_satuan').value = (this.dataset.subtotal / this.dataset.jumlah).toFixed(2);
            document.getElementById('edit_subtotal').value = this.dataset.subtotal;
            document.getElementById('edit_status').value = this.dataset.status;
            document.getElementById('editTransaksiForm').action = `/dashboard/transaksi-produk/${this.dataset.id}`;

            // Sinkronkan harga saat produk diubah
            document.getElementById('edit_id_produk').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                document.getElementById('edit_harga_satuan').value = harga;
                calculateSubtotal(document.getElementById('edit_jumlah'), document.getElementById('edit_harga_satuan'), document.getElementById('edit_subtotal'));
            });

            // Hitung subtotal saat jumlah diubah
            document.getElementById('edit_jumlah').addEventListener('input', function() {
                calculateSubtotal(this, document.getElementById('edit_harga_satuan'), document.getElementById('edit_subtotal'));
            });
        });
    });

    // Delete Transaksi
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('deleteTransaksiForm').action = `/dashboard/transaksi-produk/${this.dataset.id}`;
        });
    });

    // AJAX Search
    document.getElementById('searchInput').addEventListener('input', function() {
        const search = this.value;
        fetch(`/dashboard/transaksi-produk/data?search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('transaksi-table-body');
                tbody.innerHTML = '';
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data</td></tr>';
                    return;
                }
                data.data.forEach((item, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${item.id_transaksi}</td>
                            <td>${item.no_order}</td>
                            <td>${item.user?.nama ?? '-'}</td>
                            <td>${item.produk?.judul_produk ?? '-'}</td>
                            <td>${item.jumlah}</td>
                            <td>Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
                            <td><span class="badge bg-${item.status === 'lunas' ? 'success' : (item.status === 'belum_lunas' ? 'warning' : 'danger')}">${item.status.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase())}</span></td>
                            <td>${new Date(item.tanggal_transaksi).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" 
                                        data-id="${item.id_transaksi}"
                                        data-no-order="${item.no_order}"
                                        data-id-user="${item.user?.id ?? ''}"
                                        data-id-produk="${item.produk?.id_produk ?? ''}"
                                        data-jumlah="${item.jumlah}"
                                        data-subtotal="${item.subtotal}"
                                        data-status="${item.status}"
                                        data-bs-toggle="modal" data-bs-target="#editTransaksiModal">
                                    <i class="icofont-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" 
                                        data-id="${item.id_transaksi}" 
                                        data-bs-toggle="modal" data-bs-target="#deleteTransaksiModal">
                                    <i class="icofont-trash"></i> Hapus
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