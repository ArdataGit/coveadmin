@extends('template.admin-dashboard')

@section('title', 'Kelola Pembayaran Transaksi')

@section('content')
<div class="dashboard-content">
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mb-4">Kembali</a>

    <h2 class="mb-4">Kelola Pembayaran Transaksi #{{ $transaksi->no_order }}</h2>

    <!-- Alert -->
    <div id="alert-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
        @endif
    </div>

    <!-- Informasi Transaksi -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Informasi Transaksi</h5>
            <p><strong>ID:</strong> {{ $transaksi->id }}</p>
            <p><strong>No Order:</strong> {{ $transaksi->no_order }}</p>
            <p><strong>User:</strong> {{ $transaksi->user->nama ?? '-' }}</p>
            <p><strong>Kos:</strong> {{ $transaksi->kos->nama ?? '-' }}</p>
            <p><strong>Kamar:</strong> {{ $transaksi->kamar->nama ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ date('d-m-Y', strtotime($transaksi->tanggal)) }}</p>
            <p><strong>Periode:</strong> 
                {{ $transaksi->start_order_date ? date('d-m-Y', strtotime($transaksi->start_order_date)) : '-' }} 
                s/d 
                {{ $transaksi->end_order_date ? date('d-m-Y', strtotime($transaksi->end_order_date)) : '-' }}
            </p>
            <p><strong>Harga:</strong> {{ number_format($transaksi->harga, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($transaksi->status) }}</p>
        </div>
    </div>

    <!-- Tabel Pembayaran -->
    <div class="dashboard__table table-responsive">
        <h4>Daftar Pembayaran</h4>
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No Pembayaran</th>
                    <th>No. Order</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Jenis Bayar</th>
                    <th>Tipe Bayar</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayarans as $pembayaran)
                    <tr>
                        <td>{{ $pembayaran->kode_pembayaran }}</td>
                        <td>{{ $transaksi->no_order }}</td>
                        <td>{{ date('d-m-Y', strtotime($pembayaran->tanggal)) }}</td>
                        <td>{{ $transaksi->user->nama ?? '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $pembayaran->jenis_bayar)) }}</td>
                        <td>{{ ucfirst($pembayaran->tipe_bayar) }}</td>
                        <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                        <td>{{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-pembayaran-btn"
                                data-pembayaran_id="{{ $pembayaran->pembayaran_id }}"
                                data-tanggal="{{ $pembayaran->tanggal }}"
                                data-nominal="{{ $pembayaran->nominal }}"
                                data-tipe_bayar="{{ $pembayaran->tipe_bayar }}"
                                data-jenis_bayar="{{ $pembayaran->jenis_bayar }}"
                                data-keterangan="{{ $pembayaran->keterangan }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editPembayaranModal">
                                <i class="icofont-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-pembayaran-btn"
                                data-pembayaran_id="{{ $pembayaran->pembayaran_id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#deletePembayaranModal">
                                <i class="icofont-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">Belum ada pembayaran</td></tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Form Tambah Pembayaran -->
    <div class="card mt-4">
        <div class="card-body">
            <h4>Tambah Pembayaran Baru</h4>
            <form method="POST" action="{{ route('pembayaran.store') }}">
                @csrf
                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label>Nominal</label>
                    <input type="number" name="nominal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Tipe Bayar</label>
                    <select name="tipe_bayar" class="form-control" required>
                        <option value="dp">DP</option>
                        <option value="full">Full</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Jenis Bayar</label>
                    <select name="jenis_bayar" class="form-control" required>
                        <option value="biaya_kos">Biaya Kos</option>
                        <option value="tagihan">Tagihan</option>
                        <option value="denda">Denda</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Modal Edit Pembayaran -->
    <div class="modal fade" id="editPembayaranModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPembayaranForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="pembayaran_id" id="edit_pembayaran_id">
                    <div class="modal-header"><h5>Edit Pembayaran</h5></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nominal</label>
                            <input type="number" name="nominal" id="edit_nominal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tipe Bayar</label>
                            <select name="tipe_bayar" id="edit_tipe_bayar" class="form-control" required>
                                <option value="dp">DP</option>
                                <option value="full">Full</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Bayar</label>
                            <select name="jenis_bayar" id="edit_jenis_bayar" class="form-control" required>
                                <option value="biaya_kos">Biaya Kos</option>
                                <option value="tagihan">Tagihan</option>
                                <option value="denda">Denda</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Pembayaran -->
    <div class="modal fade" id="deletePembayaranModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deletePembayaranForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="pembayaran_id" id="delete_pembayaran_id">
                    <div class="modal-header"><h5>Hapus Pembayaran</h5></div>
                    <div class="modal-body">Yakin hapus pembayaran ini?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Edit pembayaran
    document.querySelectorAll('.edit-pembayaran-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const fields = ['pembayaran_id', 'tanggal', 'nominal', 'tipe_bayar', 'jenis_bayar', 'keterangan'];
            fields.forEach(field => {
                const el = document.getElementById('edit_' + field);
                if (el) el.value = this.dataset[field];
            });
            document.getElementById('editPembayaranForm').action = `/dashboard/pembayaran/${this.dataset.pembayaran_id}`;
        });
    });

    // Delete pembayaran
    document.querySelectorAll('.delete-pembayaran-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('delete_pembayaran_id').value = this.dataset.pembayaran_id;
            document.getElementById('deletePembayaranForm').action = `/dashboard/pembayaran/${this.dataset.pembayaran_id}`;
        });
    });
});
</script>
@endsection