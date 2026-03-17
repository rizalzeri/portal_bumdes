@extends('layouts.admin')
@section('title', 'Manajemen Fitur Premium')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Fitur Premium</h2>
        <p class="text-gray-500 text-sm mt-1">Atur ketersediaan dan batasan fitur untuk BUMDesa (Premium & Gratis).</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 w-full md:w-auto justify-center shadow-sm">
        <i class="fa-solid fa-plus"></i> Tambah Fitur
    </button>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left datatable">
            <thead class="bg-gray-50 text-gray-700 uppercase text-[11px] font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Nama Fitur</th>
                    <th class="px-6 py-4">Key / Kode</th>
                    <th class="px-6 py-4 text-center">Status Premium</th>
                    <th class="px-6 py-4 text-center">Limit Gratis</th>
                    <th class="px-6 py-4 text-center">Aksi Jika Gratis</th>
                    <th class="px-6 py-4 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($features as $feature)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-3 font-semibold text-gray-800">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">{{ $feature->category }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="font-bold text-gray-800 block">{{ $feature->name }}</span>
                        <span class="text-xs text-gray-500 block mt-0.5" title="{{ $feature->description }}">{{ Str::limit($feature->description, 50) }}</span>
                    </td>
                    <td class="px-6 py-3 font-mono text-xs text-blue-600">
                        {{ $feature->key }}
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
                            <form action="{{ route('superadmin.premium-features.destroy', $feature->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengaturan fitur premium ini? Semua BUMDesa akan kembali ke akses default gratis.')">
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
            <h3 class="text-xl font-bold text-gray-900">Tambah Aturan Fitur Baru</h3>
            <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('superadmin.premium-features.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Key Fitur <span class="text-red-500">*</span></label>
                    <input type="text" name="key" required placeholder="Cth: profil.edit_maps" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary font-mono text-gray-600 bg-gray-50 focus:bg-white transition-colors">
                    <span class="text-[10px] text-gray-500 mt-1 block">Key unik yang digunakan developer. Harus persis.</span>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Fitur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Custom Google Maps" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" required placeholder="Cth: Profil BUMDesa" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary" placeholder="Penjelasan tentang apa fitur ini dan apa bedanya dengan gratis"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2 border-t mt-4">
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                        <input type="hidden" name="is_premium" value="0">
                        <input type="checkbox" name="is_premium" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Jadikan Fitur Premium</span>
                            <span class="block text-[10px] text-gray-500">Centang agar hanya pengguna Premium yang memiliki akses penuh.</span>
                        </div>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Limit Gratis (Opsional)</label>
                    <input type="number" name="free_limit" placeholder="Kosongkan jika tak terbatas" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    <span class="text-[10px] text-gray-500 mt-1 block">Batas maksimal untuk user gratis.</span>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Aksi Jika Gratis <span class="text-red-500">*</span></label>
                    <select name="fallback_action" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="hide">Sembunyikan (Hide)</option>
                        <option value="readonly">Hanya Lihat (Read-Only)</option>
                        <option value="paywall">Tampilkan Paywall Label</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-5 py-2 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-900 transition-colors">Simpan Fitur</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center p-5 border-b">
            <h3 class="text-xl font-bold text-gray-900">Ubah Aturan Fitur</h3>
            <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg w-8 h-8 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Key Fitur <span class="text-red-500">*</span></label>
                    <input type="text" name="key" id="edit_key" required class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-600 font-mono" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Fitur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" id="edit_category" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" id="edit_description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2 border-t mt-4">
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                        <input type="hidden" name="is_premium" value="0">
                        <input type="checkbox" name="is_premium" id="edit_is_premium" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Jadikan Fitur Premium</span>
                            <span class="block text-[10px] text-gray-500">Centang agar hanya pengguna Premium yang memiliki akses penuh.</span>
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
                        <option value="readonly">Hanya Lihat (Read-Only)</option>
                        <option value="paywall">Tampilkan Paywall Label</option>
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
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Status berhasl diubah'
                    });
                }
            }
        });
    }

    function editFeature(feature) {
        document.getElementById('form-edit').action = "{{ route('superadmin.premium-features.index') }}/" + feature.id;
        document.getElementById('edit_key').value = feature.key;
        document.getElementById('edit_name').value = feature.name;
        document.getElementById('edit_category').value = feature.category;
        document.getElementById('edit_description').value = feature.description || '';
        document.getElementById('edit_is_premium').checked = feature.is_premium;
        document.getElementById('edit_free_limit').value = feature.free_limit || '';
        document.getElementById('edit_fallback').value = feature.fallback_action;
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endpush
@endsection
