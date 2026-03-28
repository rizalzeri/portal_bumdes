@extends('layouts.admin')
@section('title', 'Konfigurasi Harga Langganan')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Konfigurasi Harga Langganan</h2>
        <p class="text-gray-500 text-sm mt-1">Atur paket durasi & harga premium yang ditawarkan kepada BUMDesa dan Kabupaten.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
        class="bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Tambah Paket
    </button>
</div>

{{-- Preview Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach($configs as $cfg)
    @php $total = $cfg->base_price_per_month * $cfg->months; @endphp
    <div class="bg-white rounded-xl border {{ $cfg->is_active ? 'border-primary/30' : 'border-gray-100 opacity-60' }} p-5 relative">
        @if(!$cfg->is_active)
            <div class="absolute top-2 right-2 bg-gray-200 text-gray-500 text-[9px] font-bold uppercase px-2 py-0.5 rounded-full">Nonaktif</div>
        @else
            <div class="absolute top-2 right-2 {{ $cfg->type === 'kabupaten' ? 'bg-indigo-100 text-indigo-700' : 'bg-green-100 text-green-700' }} text-[9px] font-bold uppercase px-2 py-0.5 rounded-full">{{ ucfirst($cfg->type) }}</div>
        @endif
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $cfg->months }} Bulan</p>
        <h4 class="text-base font-black text-gray-900 mb-3">{{ $cfg->name }}</h4>
        <div class="mb-1">
            <span class="text-2xl font-black text-primary">Rp {{ number_format($cfg->base_price_per_month, 0, ',', '.') }}</span>
            <span class="text-gray-400 text-xs">/bln</span>
        </div>
        <div class="text-xs text-gray-500 mb-4">
            Total: <strong class="text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </div>
        @if($cfg->description)
            <p class="text-xs text-gray-500 italic mb-3">{{ $cfg->description }}</p>
        @endif
        <div class="flex gap-2">
            <button onclick="editConfig({{ $cfg->toJson() }})"
                class="flex-1 text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-1.5 rounded-lg transition-colors">
                <i class="fa-solid fa-pen mr-1"></i> Edit
            </button>
            <form action="{{ route('superadmin.pricing-config.destroy', $cfg->id) }}" method="POST"
                onsubmit="return confirm('Hapus paket ini?')" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-1.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @endforeach

    @if($configs->isEmpty())
    <div class="col-span-4 bg-gray-50 border border-dashed border-gray-300 rounded-xl p-10 text-center text-gray-400">
        <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
        <p>Belum ada paket harga. Tambahkan paket pertama Anda.</p>
    </div>
    @endif
</div>

{{-- Detail Table --}}
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table id="pricing-table" class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Tipe</th>
                    <th class="px-6 py-3">Nama Paket</th>
                    <th class="px-6 py-3 text-center">Durasi</th>
                    <th class="px-6 py-3 text-right">Harga/Bulan</th>
                    <th class="px-6 py-3 text-right">Total Harga</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($configs as $cfg)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ ucfirst($cfg->type) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $cfg->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $cfg->months }} bulan</td>
                    <td class="px-6 py-4 text-right font-medium">Rp {{ number_format($cfg->base_price_per_month, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right font-bold text-primary">Rp {{ number_format($cfg->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $cfg->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($cfg->is_active)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Aktif</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="editConfig({{ $cfg->toJson() }})"
                                class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('superadmin.pricing-config.destroy', $cfg->id) }}" method="POST"
                                class="inline" onsubmit="return confirm('Hapus paket ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada konfigurasi harga.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center p-5 border-b">
            <h3 class="text-xl font-bold text-gray-900">Tambah Paket Harga</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg w-8 h-8 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('superadmin.pricing-config.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Target Pengguna <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="bumdes">BUMDesa</option>
                        <option value="kabupaten">Kabupaten</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Paket 1 Bulan"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Durasi (Bulan) <span class="text-red-500">*</span></label>
                    <input type="number" name="months" required min="1" max="120" placeholder="Cth: 1"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Per Bulan (IDR) <span class="text-red-500">*</span></label>
                    <input type="number" name="base_price_per_month" required min="0" placeholder="Cth: 10000"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    <p class="text-xs text-gray-400 mt-1">Total = Harga/Bulan × Durasi</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" placeholder="Cth: Hemat 10% - Akses premium selama X bulan"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-primary rounded">
                        <span class="text-sm font-semibold text-gray-700">Aktifkan paket ini</span>
                    </label>
                </div>
            </div>
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 shadow-sm">Simpan Paket</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center p-5 border-b">
            <h3 class="text-xl font-bold text-gray-900">Edit Paket Harga</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg w-8 h-8 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Target Pengguna <span class="text-red-500">*</span></label>
                    <select name="type" id="edit_type" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="bumdes">BUMDesa</option>
                        <option value="kabupaten">Kabupaten</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Durasi (Bulan) <span class="text-red-500">*</span></label>
                    <input type="number" name="months" id="edit_months" required min="1" max="120"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Per Bulan (IDR) <span class="text-red-500">*</span></label>
                    <input type="number" name="base_price_per_month" id="edit_price" required min="0"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    <p class="text-xs text-gray-400 mt-1">Total = Harga/Bulan × Durasi</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" id="edit_desc"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="edit_active" value="1" class="w-4 h-4 text-primary rounded">
                        <span class="text-sm font-semibold text-gray-700">Paket ini aktif</span>
                    </label>
                </div>
            </div>
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editConfig(cfg) {
        document.getElementById('form-edit').action = '/superadmin/pricing-config/' + cfg.id;
        document.getElementById('edit_type').value   = cfg.type;
        document.getElementById('edit_name').value   = cfg.name;
        document.getElementById('edit_months').value = cfg.months;
        document.getElementById('edit_price').value  = cfg.base_price_per_month;
        document.getElementById('edit_desc').value   = cfg.description || '';
        document.getElementById('edit_active').checked = !!cfg.is_active;
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    $(document).ready(function() {
        $('#pricing-table').DataTable({
            order: [[1, 'asc']],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: { first: "Pertama", last: "Terakhir", next: "Lanjut", previous: "Kembali" }
            }
        });
    });
</script>
@endpush
@endsection
