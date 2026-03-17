@extends('layouts.admin')
@section('title', 'Kinerja BUMDesa')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kinerja dan Capaian BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data pemeringkatan, serta omset dan aset BUMDesa per tahun.</p>
    </div>

    <!-- Hasil Pemeringkatan -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-bold text-gray-900 border-b pb-2 mb-4"><i class="fa-solid fa-ranking-star mr-2 text-primary"></i> Hasil Pemeringkatan</h3>
        <form action="{{ route('user.kinerja.update', ['slug' => auth()->user()->username, 'kinerja' => 'pemeringkatan']) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
            @csrf
            @method('PUT')
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status Pemeringkatan BUMDesa</label>
                <select name="pemeringkatan" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih Hasil Pemeringkatan --</option>
                    <option value="Maju" {{ $bumdes->pemeringkatan == 'Maju' ? 'selected' : '' }}>Maju</option>
                    <option value="Berkembang" {{ $bumdes->pemeringkatan == 'Berkembang' ? 'selected' : '' }}>Berkembang</option>
                    <option value="Perintis" {{ $bumdes->pemeringkatan == 'Perintis' ? 'selected' : '' }}>Perintis</option>
                    <option value="Pemula" {{ $bumdes->pemeringkatan == 'Pemula' ? 'selected' : '' }}>Pemula</option>
                </select>
            </div>
            <button type="submit" class="bg-primary hover:bg-primary-900 text-white px-6 py-2 rounded-lg font-bold shadow-sm transition-colors w-full md:w-auto text-sm">
                Simpan Pemeringkatan
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 border-b pb-2 mb-4"><i class="fa-solid fa-plus mr-2 text-primary"></i> Tambah Data Kinerja</h3>
                <form action="{{ route('user.kinerja.store', ['slug' => auth()->user()->username]) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kategori</label>
                        <select name="kategori" id="kategori-select" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2" onchange="updateItems()">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Reguler">Reguler</option>
                            <option value="Ketahanan Pangan">Ketahanan Pangan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Item Data</label>
                        <select name="item" id="item-select" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                            <option value="">-- Pilih Kategori Dahulu --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tahun Periode</label>
                        <input type="number" name="year" required min="2000" max="{{ date('Y') + 1 }}" value="{{ date('Y') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nominal (Rp)</label>
                        <input type="number" name="nominal" required min="0" placeholder="Contoh: 15000000" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <p class="text-[10px] text-gray-500 mt-1">Masukkan angka saja tanpa titik/koma (Contoh: 15000000 untuk 15 Juta)</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition-colors text-sm">
                            <i class="fa-solid fa-save mr-1"></i> Tambah Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-bold text-gray-900 border-b pb-2 mb-4"><i class="fa-solid fa-list mr-2 text-primary"></i> Riwayat Data Kinerja</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left datatable">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Tahun</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Item Data</th>
                                <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($kinerja as $k)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold">{{ $k->year }}</td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs px-2 py-1 rounded-full border bg-gray-100 font-semibold">{{ $k->description }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $k->title }}</td>
                                    <td class="px-4 py-3 text-right font-mono">{{ number_format($k->value, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('user.kinerja.destroy', ['slug' => auth()->user()->username, 'kinerja' => $k->id]) }}" method="POST" onsubmit="return confirm('Hapus data kinerja ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md tooltip" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateItems() {
            const kategori = document.getElementById('kategori-select').value;
            const itemSelect = document.getElementById('item-select');
            
            itemSelect.innerHTML = '';
            
            if (!kategori) {
                itemSelect.innerHTML = '<option value="">-- Pilih Kategori Dahulu --</option>';
                return;
            }
            
            let options = [];
            if (kategori === 'Reguler') {
                options = ['Omset', 'Laba', 'PADes', 'Aset'];
            } else if (kategori === 'Ketahanan Pangan') {
                options = ['Omset', 'Laba', 'PADes', 'Modal Saat Ini'];
            }
            
            options.forEach(opt => {
                const el = document.createElement('option');
                el.value = opt;
                el.textContent = opt;
                itemSelect.appendChild(el);
            });
        }
    </script>
@endsection
