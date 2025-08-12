@extends('template.admin-dashboard')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="dashboard-content">
    <h2 class="mb-4">Daftar Transaksi</h2>

    <!-- Alert -->
    <div id="alert-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
        @endif
    </div>

    <!-- Tabel Transaksi -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Kos</th>
                    <th>Kamar</th>
                    <th>Tanggal</th>
                    <th>Periode</th>
                    <th>Harga</th>
                    <th>Nominal</th>
                    <th>Tipe Bayar</th>
                    <th>Jenis Bayar</th>
                    <th>Status</th>
                    <th>Metode Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $trx)
                    <tr>
                        <td>{{ $trx->id }}</td>
                        <td>{{ $trx->no_order }}</td>
                        <td>{{ $trx->user->nama ?? '-' }}</td>
                        <td>{{ $trx->kos->nama ?? '-' }}</td>
                        <td>{{ $trx->kamar->nama ?? '-' }}</td>
                        <td>{{ $trx->tanggal }}</td>
                        <td>
                            {{ $trx->start_order_date ? date('d-m-Y', strtotime($trx->start_order_date)) : '-' }}
                            s/d
                            {{ $trx->end_order_date ? date('d-m-Y', strtotime($trx->end_order_date)) : '-' }}
                        </td>
                        <td>{{ number_format($trx->harga, 0, ',', '.') }}</td>
                        <td>{{ number_format($trx->nominal, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($trx->tipe_bayar) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $trx->jenis_bayar)) }}</td>
                        <td>{{ ucfirst($trx->status) }}</td>
                        <td>{{ ucfirst($trx->methode_pembayaran) }}</td>
                        <td>
                            <button class="btn btn-sm btn-success pembayaran-btn"
                                data-id="{{ $trx->id }}"
                                data-no_order="{{ $trx->no_order }}"
                                data-user_id="{{ $trx->user_id }}"
                                data-kos_id="{{ $trx->kos_id }}"
                                data-kamar_id="{{ $trx->kamar_id }}"
                                data-paket_id="{{ $trx->paket_id }}"
                                data-tanggal="{{ now()->format('Y-m-d') }}"
                                data-start_order_date="{{ $trx->start_order_date }}"
                                data-end_order_date="{{ $trx->end_order_date }}"
                                data-harga="{{ $trx->harga }}"
                                data-quantity="{{ $trx->quantity }}"
                                data-tipe_bayar="{{ $trx->tipe_bayar }}"
                                data-jenis_bayar="{{ $trx->jenis_bayar }}"
                                data-methode_pembayaran="{{ $trx->methode_pembayaran }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#pembayaranBaruModal">
                                <i class="icofont-money-bag"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn"
                                data-id="{{ $trx->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteTransaksiModal">
                                <i class="icofont-trash"></i>
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr><td colspan="14" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editTransaksiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editTransaksiForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header"><h5>Edit Transaksi</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>User</label>
                        <select name="user_id" id="edit_user_id" class="form-control"></select>
                    </div>
                    <div class="mb-3">
                        <label>No Order</label>
                        <input type="text" name="no_order" id="edit_no_order" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Kos</label>
                        <select name="kos_id" id="edit_kos_id" class="form-control"></select>
                    </div>
                    <div class="mb-3">
                        <label>Kamar</label>
                        <select name="kamar_id" id="edit_kamar_id" class="form-control"></select>
                    </div>
                    <div class="mb-3">
                        <label>Paket</label>
                        <select name="paket_id" id="edit_paket_id" class="form-control"></select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Start Order</label>
                        <input type="date" name="start_order_date" id="edit_start_order_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>End Order</label>
                        <input type="date" name="end_order_date" id="edit_end_order_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" id="edit_harga" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="number" name="nominal" id="edit_nominal" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Tipe Bayar</label>
                        <select name="tipe_bayar" id="edit_tipe_bayar" class="form-control">
                            <option value="dp">DP</option>
                            <option value="full">Full</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Bayar</label>
                        <select name="jenis_bayar" id="edit_jenis_bayar" class="form-control">
                            <option value="biaya_kos">Biaya Kos</option>
                            <option value="tagihan">Tagihan</option>
                            <option value="denda">Denda</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="cancel">Cancel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Metode Pembayaran</label>
                        <input type="text" name="methode_pembayaran" id="edit_methode_pembayaran" class="form-control">
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

<!-- Modal Pembayaran Baru -->
<div class="modal fade" id="pembayaranBaruModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="pembayaranBaruForm" method="POST">
                @csrf
                <div class="modal-header"><h5>Tambah Pembayaran Baru</h5></div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="pay_id">

                    <div class="mb-3">
                        <label>No Order</label>
                        <input type="text" name="no_order" id="pay_no_order" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="number" name="nominal" id="pay_nominal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="pay_keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteTransaksiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteTransaksiForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-header"><h5>Hapus Transaksi</h5></div>
                <div class="modal-body">Yakin hapus transaksi ini?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const fields = [
                'id','no_order','user_id','kos_id','kamar_id','paket_id',
                'tanggal','start_order_date','end_order_date','harga','nominal',
                'keterangan','tipe_bayar','jenis_bayar','status','methode_pembayaran'
            ];
            fields.forEach(field => {
                const el = document.getElementById('edit_' + field);
                if (el) el.value = this.dataset[field];
            });
            document.getElementById('editTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('delete_id').value = this.dataset.id;
            document.getElementById('deleteTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });

    // Edit transaksi lama
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const fields = [
                'id','no_order','user_id','kos_id','kamar_id','paket_id',
                'tanggal','start_order_date','end_order_date','harga','nominal',
                'keterangan','tipe_bayar','jenis_bayar','status','methode_pembayaran'
            ];
            fields.forEach(field => {
                const el = document.getElementById('edit_' + field);
                if (el) el.value = this.dataset[field];
            });
            document.getElementById('editTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });

    // Pembayaran baru
    document.querySelectorAll('.pembayaran-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('pay_id').value = this.dataset.id;
            document.getElementById('pay_no_order').value = this.dataset.no_order;
            document.getElementById('pembayaranBaruForm').action = `/dashboard/transaksi/${this.dataset.id}/pembayaran`;
        });
    });

    // Hapus transaksi
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('delete_id').value = this.dataset.id;
            document.getElementById('deleteTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });
});
</script>
@endsection
