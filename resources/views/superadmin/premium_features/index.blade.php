@extends('layouts.admin')
@section('title', 'Manajemen Fitur Premium')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Fitur Premium</h2>
        <p class="text-gray-500 text-sm mt-1">Atur ketersediaan dan batasan fitur untuk BUMDesa (Berdasarkan Menu/Modul).</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 w-full md:w-auto justify-center shadow-sm">
        <i class="fa-solid fa-plus"></i> Atur Fitur Baru
    </button>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left datatable">
            <thead class="bg-gray-50 text-gray-700 uppercase text-[11px] font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Nama Menu / Modul</th>
                    <th class="px-6 py-4">Aksi CRUD</th>
                    <th class="px-6 py-4 text-center">Status Premium</th>
                    <th class="px-6 py-4 text-center">Limit Gratis</th>
                    <th class="px-6 py-4 text-center">Tampilan Jika Gratis</th>
                    <th class="px-6 py-4 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($features as $feature)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-3 font-semibold text-gray-800">
                        {{ $modules[$feature->module] ?? $feature->module }}
                    </td>
                    <td class="px-6 py-3">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold ring-1 ring-blue-100 italic">{{ strtoupper($feature->action) }}</span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <div class="flex justify-center items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" {{ $feature->is_premium ? 'checked' : '' }} onchange="togglePremium({{ $feature->id }}, this.checked)">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center font-bold text-gray-700">
                        {{ $feature->free_limit ?? '∞' }}
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="text-xs font-semibold px-2 py-1 rounded {{ $feature->fallback_action == 'hide' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-yellow-50 text-yellow-600 border border-yellow-100' }}">
                            {{ strtoupper($feature->fallback_action) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="editFeature({{ $feature }})" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors tooltip" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('superadmin.premium-features.destroy', $feature->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengaturan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors tooltip" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center p-5 border-b">
            <h3 class="text-xl font-bold text-gray-900">Atur Hak Akses Menu</h3>
            <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('superadmin.premium-features.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Menu / Modul <span class="text-red-500">*</span></label>
                    <select name="module" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="">-- Pilih Menu --</option>
                        @foreach($modules as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Aksi Khusus <span class="text-red-500">*</span></label>
                    <select name="action" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        @foreach($actions as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t mt-4">
                <div class="col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer p-4 border rounded-xl hover:bg-gray-50 transition-all group">
                        <input type="hidden" name="is_premium" value="0">
                        <input type="checkbox" name="is_premium" value="1" class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Kunci dengan Status Premium</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5">Berikan batasan akses bagi pengguna paket Gratis.</span>
                        </div>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Limit Gratis (Max Item)</label>
                    <input type="number" name="free_limit" placeholder="Cth: 3 (Kosongkan = Unlimited)" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Aksi Jika Gratis <span class="text-red-500">*</span></label>
                    <select name="fallback_action" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="hide">Sembunyikan (Hide)</option>
                        <option value="readonly">Hanya Lihat (View Only)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-5 py-2 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-900 transition-colors shadow-sm">Simpan Aturan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center p-5 border-b">
            <h3 class="text-xl font-bold text-gray-900">Ubah Hak Akses Menu</h3>
            <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Menu / Modul <span class="text-red-500">*</span></label>
                    <select name="module" id="edit_module" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        @foreach($modules as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Aksi CRUD <span class="text-red-500">*</span></label>
                    <select name="action" id="edit_action" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        @foreach($actions as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2 border-t mt-4">
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                        <input type="hidden" name="is_premium" value="0">
                        <input type="checkbox" name="is_premium" id="edit_is_premium" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Jadikan Fitur Premium</span>
                            <span class="block text-[10px] text-gray-500 mt-0.5">Kunci fitur ini untuk pengguna premium.</span>
                        </div>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Limit Gratis (Opsional)</label>
                    <input type="number" name="free_limit" id="edit_free_limit" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Aksi Jika Gratis <span class="text-red-500">*</span></label>
                    <select name="fallback_action" id="edit_fallback" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="hide">Sembunyikan (Hide)</option>
                        <option value="readonly">Hanya Lihat (View Only)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-5 py-2 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-900 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function togglePremium(id, checked) {
        $.ajax({
            url: "{{ url('superadmin/premium-features/inline-update') }}/" + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                field: 'is_premium',
                value: checked ? 1 : 0
            },
            success: function(response) {
                if(response.success) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Pengaturan fitur berhasil diperbarui'
                    });
                }
            }
        });
    }

    function editFeature(feature) {
        document.getElementById('form-edit').action = "{{ route('superadmin.premium-features.index') }}/" + feature.id;
        document.getElementById('edit_module').value = feature.module;
        document.getElementById('edit_action').value = feature.action;
        document.getElementById('edit_is_premium').checked = feature.is_premium;
        document.getElementById('edit_free_limit').value = feature.free_limit || '';
        document.getElementById('edit_fallback').value = feature.fallback_action;
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endpush
@endsection
