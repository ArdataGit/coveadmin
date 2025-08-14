@extends('template.admin-dashboard')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Transaksi</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahTransaksiModal">Tambah Transaksi</button>
    </div>

    <!-- Alert -->
    <div id="alert-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
        @endif
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('transaksi.index') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Cari (Nama/No Order/Kos)</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Masukkan kata kunci">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Filter Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="dashboard__table table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Nomor</th>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Kos</th>
                    <th>Kamar</th>
                    <th>Tanggal</th>
                    <th>Periode</th>
                    <th>Jatuh Tempo</th>
                    <th>Harga</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $trx)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $trx->no_order }}</td>
                        <td>{{ $trx->user->nama ?? '-' }}</td>
                        <td>{{ $trx->kos->nama ?? '-' }}</td>
                        <td>{{ $trx->kamar->nama ?? '-' }}</td>
                        <td>{{ date('d-m-Y', strtotime($trx->tanggal)) }}</td>
                        <td>
                            {{ $trx->start_order_date ? date('d-m-Y', strtotime($trx->start_order_date)) : '-' }}
                            s/d
                            {{ $trx->end_order_date ? date('d-m-Y', strtotime($trx->end_order_date)) : '-' }}
                        </td>
                        <td>{{ $trx->end_order_date ? date('d-m-Y', strtotime($trx->end_order_date)) : '-' }}</td>
                        <td>{{ number_format($trx->harga, 0, ',', '.') }}</td>
                        <td>{{ $trx->pembayaran->count() }} Pembayaran</td>
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
                    <tr><td colspan="12" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div class="modal fade" id="tambahTransaksiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('api/transaksi') }}">
                    @csrf
                    <div class="modal-header"><h5>Tambah Transaksi</h5></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>User</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Kos</label>
                            <select name="kos_id" id="kos_id" class="form-control" required>
                                <option value="">Pilih Kos</option>
                                @foreach ($kos as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            @error('kos_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Kamar</label>
                            <select name="kamar_id" id="kamar_id" class="form-control" required>
                                <option value="">Pilih Kos terlebih dahulu</option>
                            </select>
                            @error('kamar_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Paket</label>
                            <select name="paket_id" id="paket_id" class="form-control" required>
                                <option value="">Pilih Kamar terlebih dahulu</option>
                            </select>
                            @error('paket_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Jenis Harga</label>
                            <select name="jenis_harga" id="jenis_harga" class="form-control" required>
                                <option value="">Pilih Paket terlebih dahulu</option>
                                <option value="perharian_harga">Per Hari</option>
                                <option value="perbulan_harga">Per Bulan</option>
                                <option value="pertigabulan_harga">Per Tiga Bulan</option>
                                <option value="perenambulan_harga">Per Enam Bulan</option>
                                <option value="pertahun_harga">Per Tahun</option>
                            </select>
                            @error('jenis_harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                            @error('tanggal')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Start Order</label>
                            <input type="date" name="start_order_date" class="form-control">
                            @error('start_order_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>End Order</label>
                            <input type="date" name="end_order_date" class="form-control">
                            @error('end_order_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control" required readonly>
                            @error('harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1">
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found.');
        alert('CSRF token is missing. Please contact the administrator.');
        return;
    }

    // Delete transaksi
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('delete_id').value = this.dataset.id;
            document.getElementById('deleteTransaksiForm').action = `/dashboard/transaksi/${this.dataset.id}`;
        });
    });

    // AJAX untuk mengambil KosDetail berdasarkan kos_id
    const kosSelect = document.getElementById('kos_id');
    const kamarSelect = document.getElementById('kamar_id');
    const paketSelect = document.getElementById('paket_id');
    const jenisHargaSelect = document.getElementById('jenis_harga');
    const hargaInput = document.getElementById('harga');
    const quantityInput = document.getElementById('quantity');
    let paketHargaData = []; // Menyimpan data paket harga untuk digunakan saat memilih jenis harga

    kosSelect.addEventListener('change', function() {
        const kosId = this.value;
        kamarSelect.innerHTML = '<option value="">Pilih Kamar</option>';
        paketSelect.innerHTML = '<option value="">Pilih Kamar terlebih dahulu</option>';
        jenisHargaSelect.innerHTML = '<option value="">Pilih Paket terlebih dahulu</option>';
        hargaInput.value = '';

        if (kosId) {
            fetch(`/dashboard/kos/${kosId}/details`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(kamar => {
                            const option = document.createElement('option');
                            option.value = kamar.id;
                            option.textContent = kamar.nama;
                            kamarSelect.appendChild(option);
                        });
                    } else {
                        kamarSelect.innerHTML = '<option value="">Tidak ada kamar tersedia</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching kos details:', error);
                    kamarSelect.innerHTML = '<option value="">Gagal memuat kamar</option>';
                });
        }
    });

    // AJAX untuk mengambil PaketHarga berdasarkan kamar_id
    kamarSelect.addEventListener('change', function() {
        const kamarId = this.value;
        paketSelect.innerHTML = '<option value="">Pilih Paket</option>';
        jenisHargaSelect.innerHTML = '<option value="">Pilih Paket terlebih dahulu</option>';
        hargaInput.value = '';

        if (kamarId) {
            fetch(`/dashboard/kos/details/${kamarId}/paket-harga`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        paketHargaData = data.data; // Simpan data untuk digunakan saat memilih jenis harga
                        data.data.forEach(paket => {
                            const option = document.createElement('option');
                            option.value = paket.id;
                            option.textContent = paket.nama;
                            paketSelect.appendChild(option);
                        });
                    } else {
                        paketSelect.innerHTML = '<option value="">Tidak ada paket tersedia</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching paket harga:', error);
                    paketSelect.innerHTML = '<option value="">Gagal memuat paket</option>';
                });
        }
    });

    // Mengisi jenis harga saat paket dipilih
    paketSelect.addEventListener('change', function() {
        const paketId = this.value;
        jenisHargaSelect.innerHTML = '<option value="">Pilih Jenis Harga</option>';
        hargaInput.value = '';

        if (paketId) {
            // Mengisi opsi jenis harga
            const options = [
                { value: 'perharian_harga', text: 'Per Hari' },
                { value: 'perbulan_harga', text: 'Per Bulan' },
                { value: 'pertigabulan_harga', text: 'Per Tiga Bulan' },
                { value: 'perenambulan_harga', text: 'Per Enam Bulan' },
                { value: 'pertahun_harga', text: 'Per Tahun' }
            ];
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.text;
                jenisHargaSelect.appendChild(option);
            });
        }
    });

    // Mengisi harga berdasarkan jenis harga yang dipilih dan memperbarui harga berdasarkan quantity
    jenisHargaSelect.addEventListener('change', function() {
        const jenisHarga = this.value;
        const paketId = paketSelect.value;
        hargaInput.value = '';

        if (jenisHarga && paketId) {
            const selectedPaket = paketHargaData.find(paket => paket.id == paketId);
            if (selectedPaket && selectedPaket[jenisHarga]) {
                const baseHarga = selectedPaket[jenisHarga];
                const quantity = parseInt(quantityInput.value) || 1;
                hargaInput.value = baseHarga * quantity;
            } else {
                hargaInput.value = '';
                alert('Harga untuk jenis ini tidak tersedia');
            }
        }
    });

    // Update harga saat quantity berubah
    quantityInput.addEventListener('input', function() {
        const jenisHarga = jenisHargaSelect.value;
        const paketId = paketSelect.value;
        if (jenisHarga && paketId) {
            const selectedPaket = paketHargaData.find(paket => paket.id == paketId);
            if (selectedPaket && selectedPaket[jenisHarga]) {
                const baseHarga = selectedPaket[jenisHarga];
                const quantity = parseInt(this.value) || 1;
                hargaInput.value = baseHarga * quantity;
            }
        }
    });

    // AJAX untuk submit form tambah transaksi
    document.querySelector('#tambahTransaksiModal form').addEventListener('submit', function(e) {
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
            const alertContainer = document.getElementById('alert-container');
            if (data.success) {
                alertContainer.innerHTML = `<div class="alert alert-success alert-dismissible fade show">${data.message}</div>`;
                form.reset();
                kamarSelect.innerHTML = '<option value="">Pilih Kos terlebih dahulu</option>';
                paketSelect.innerHTML = '<option value="">Pilih Kamar terlebih dahulu</option>';
                jenisHargaSelect.innerHTML = '<option value="">Pilih Paket terlebih dahulu</option>';
                hargaInput.value = '';
                quantityInput.value = '1';
                bootstrap.Modal.getInstance(document.getElementById('tambahTransaksiModal')).hide();
                window.location.reload(); // Refresh halaman untuk memperbarui tabel
            } else {
                alertContainer.innerHTML = `<div class="alert alert-danger alert-dismissible fade show">${data.message || 'Gagal menambahkan transaksi'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('alert-container').innerHTML = '<div class="alert alert-danger alert-dismissible fade show">Gagal menambahkan transaksi</div>';
        });
    });

    // AJAX untuk submit form hapus transaksi
    document.getElementById('deleteTransaksiForm').addEventListener('submit', function(e) {
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
            const alertContainer = document.getElementById('alert-container');
            if (data.success) {
                alertContainer.innerHTML = `<div class="alert alert-success alert-dismissible fade show">${data.message}</div>`;
                bootstrap.Modal.getInstance(document.getElementById('deleteTransaksiModal')).hide();
                window.location.reload(); // Refresh halaman untuk memperbarui tabel
            } else {
                alertContainer.innerHTML = `<div class="alert alert-danger alert-dismissible fade show">${data.message || 'Gagal menghapus transaksi'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('alert-container').innerHTML = '<div class="alert alert-danger alert-dismissible fade show">Gagal menghapus transaksi</div>';
        });
    });
});
</script>
@endsection