@extends('layouts.admin')
@section('title', 'Pelaporan Finansial')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pelaporan Keuangan</h2>
            <p class="text-gray-500 text-sm mt-1">Unggah laporan keuangan bulanan BUMDesa untuk mendapatkan penilaian kredit
                dan kepercayaan mitra.</p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Buat Laporan Baru
        </button>
    </div>

    <!-- Laporan List -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Periode</th>
                        <th class="px-6 py-3 text-right">Pendapatan</th>
                        <th class="px-6 py-3 text-right">Pengeluaran</th>
                        <th class="px-6 py-3 text-right">Laba Bersih</th>
                        <th class="px-6 py-3 text-right">Total Aset</th>
                        <th class="px-6 py-3 text-center">Berkas Lampiran</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $lap)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            @php
                                $laba = $lap->laba_rugi ?? ($lap->laba_bersih ?? 0);
                                $aset = $lap->total_aset ?? ($lap->aset ?? 0);
                                $file = $lap->file_url ?? ($lap->file_laporan ?? null);
                                $periode = $lap->tahun ?? $lap->year;
                            @endphp
                            <td
                                class="px-6 py-4 font-bold text-gray-900 border-l-4 {{ $laba >= 0 ? 'border-primary' : 'border-red-500' }}">
                                {{ match ((int) $lap->bulan) {
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                    default => $lap->bulan,
                                } }}
                                {{ $periode }}
                            </td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($lap->pendapatan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($lap->pengeluaran, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold {{ $laba >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($laba, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($aset, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($file)
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                        class="text-xs bg-gray-100 border text-gray-700 px-3 py-1.5 rounded hover:bg-gray-200 transition font-bold tooltip"
                                        title="Unduh / Lihat Dokumen">
                                        <i class="fa-solid fa-file-pdf text-red-500 mr-1"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="editLaporan({{ $lap->toJson() }})"
                                    class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1 tooltip"
                                    title="Edit Laporan">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('user.finansial.destroy', $lap->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Hapus laporan bulan ini secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"
                                        title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Buat Laporan Bulan Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('user.finansial.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bulan</label>
                        <select name="bulan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                            @php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                            @endphp
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ date('n') == $num ? 'selected' : '' }}>
                                    {{ $num }} - {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tahun</label>
                        <input type="number" name="tahun" value="{{ date('Y') }}" required min="2000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    </div>
                </div>

                <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg space-y-4">
                    <h4 class="font-bold text-sm text-gray-800 border-b pb-2">Nilai Nominal Keuangan (Rupiah)</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Pendapatan (Kotor) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="pendapatan" required min="0" value="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-right">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Pengeluaran <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="pengeluaran" required min="0" value="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-right">
                        <p class="text-[10px] text-gray-500 mt-1">Laba Bersih akan dihitung otomatis oleh sistem.</p>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700">Total Aset (Akumulasi Berjalan) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="aset" required min="0" value="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-right">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Lampirkan Bukti Dokumen (Opsional)</label>
                    <input type="file" name="file_laporan" accept=".pdf,.xls,.xlsx,.doc,.docx"
                        class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 border-gray-300 rounded-md p-2">
                    <p class="text-xs text-gray-500 mt-1">Unggah PDF/Excel laporan L/R atau neraca. Maksimal 10MB.</p>
                </div>

                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Simpan
                        Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Koreksi Laporan Keuangan</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form id="form-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="bg-blue-50 text-blue-800 px-4 py-2 rounded mb-2 font-bold text-sm" id="edit-periode-text">
                    <!-- Diisi via JS -->
                </div>

                <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Pendapatan (Kotor) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="pendapatan" id="edit-pendapatan" required min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-right">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Pengeluaran <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="pengeluaran" id="edit-pengeluaran" required min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-right">
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700">Total Aset (Akumulasi Berjalan) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="aset" id="edit-aset" required min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-right">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Ganti Bukti Dokumen (Opsional)</label>
                    <input type="file" name="file_laporan" accept=".pdf,.xls,.xlsx,.doc,.docx"
                        class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 border-gray-300 rounded-md p-2">
                </div>

                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui
                        Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const months = {
            1: 'Januari',
            2: 'Februari',
            3: 'Maret',
            4: 'April',
            5: 'Mei',
            6: 'Juni',
            7: 'Juli',
            8: 'Agustus',
            9: 'September',
            10: 'Oktober',
            11: 'November',
            12: 'Desember'
        };

        function editLaporan(lap) {
            document.getElementById('form-edit').action = `/user/finansial/${lap.id}`;

            let monthName = months[lap.bulan] || lap.bulan;
            let periode = lap.tahun || lap.year;
            document.getElementById('edit-periode-text').innerHTML = `Periode: ${monthName} ${periode}`;

            document.getElementById('edit-pendapatan').value = lap.pendapatan;
            document.getElementById('edit-pengeluaran').value = lap.pengeluaran;
            document.getElementById('edit-aset').value = lap.total_aset || lap.aset || 0;

            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
@endsection
