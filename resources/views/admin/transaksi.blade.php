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
                    <th>Pembayaran</th>
                    <th>Status</th>
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
                        <td>
                            @if ($trx->pembayaran->isEmpty())
                                <span>Belum ada pembayaran</span>
                            @else
                                <ul>
                                    @foreach ($trx->pembayaran as $pembayaran)
                                        <li>
                                            {{ date('d-m-Y', strtotime($pembayaran->tanggal)) }}: 
                                            {{ number_format($pembayaran->nominal, 0, ',', '.') }} 
                                            ({{ ucfirst($pembayaran->tipe_bayar) }} - 
                                            {{ ucfirst(str_replace('_', ' ', $pembayaran->jenis_bayar)) }})
                                            @if ($pembayaran->keterangan)
                                                - {{ $pembayaran->keterangan }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                <strong>Total: {{ number_format($trx->pembayaran->sum('nominal'), 0, ',', '.') }}</strong>
                            @endif
                        </td>
                        <td>{{ ucfirst($trx->status) }}</td>
                        <td>
                            <a href="{{ route('pembayaran.index', $trx->id) }}" class="btn btn-sm btn-success">
                                <i class="icofont-money-bag"></i> Bayar
                            </a>
                            
                            <a href="{{ route('transaksi.invoice', $trx->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="icofont-print"></i> Invoice
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn"
                                data-id="{{ $trx->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteTransaksiModal">
                                <i class="icofont-trash"></i>
                            </button>

                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center">Tidak ada data</td></tr>
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
                        <select name="user_id" id="edit_user_id" class="form-control">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>No Order</label>
                        <input type="text" name="no_order" id="edit_no_order" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Kos</label>
                        <select name="kos_id" id="edit_kos_id" class="form-control">
                            <option value="">Pilih Kos</option>
                            @foreach ($kos as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Kamar</label>
                        <select name="kamar_id" id="edit_kamar_id" class="form-control">
                            <option value="">Pilih Kamar</option>
                            @foreach ($kamars as $kamar)
                                <option value="{{ $kamar->id }}">{{ $kamar->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Paket</label>
                        <select name="paket_id" id="edit_paket_id" class="form-control">
                            <option value="">Pilih Paket</option>
                            @foreach ($pakets as $paket)
                                <option value="{{ $paket->id }}">{{ $paket->nama }}</option>
                            @endforeach
                        </select>
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
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="cancel">Cancel</option>
                        </select>
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
    // Edit transaksi
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const fields = [
                'id', 'no_order', 'user_id', 'kos_id', 'kamar_id', 'paket_id',
                'tanggal', 'start_order_date', 'end_order_date', 'harga', 'status'
            ];
            fields.forEach(field => {
                const el = document.getElementById('edit_' + field);
                if (el) el.value = this.dataset[field];
            });
            document.getElementById('editTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });

    // Delete transaksi
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('delete_id').value = this.dataset.id;
            document.getElementById('deleteTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });
});
</script>
@endsection