@extends('layouts.public')
@section('title', 'Buat Website BUMDesa')

@section('content')
    <div class="bg-gray-50 min-h-screen py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Buat Akun Website BUMDesa</h1>
                <p class="mt-4 text-lg text-gray-500">Mulai majukan desa Anda dengan bergabung di Portal BUMDesa.</p>
            </div>

            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('public.register.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">

                            <!-- Pilihan Paket -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Paket</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="package" value="gratis" class="peer sr-only" required
                                            {{ old('package') == 'gratis' ? 'checked' : '' }}>
                                        <div
                                            class="rounded-lg border-2 border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-primary peer-checked:bg-blue-50 transition-all">
                                            <div class="font-bold text-gray-900 mb-1">Gratis</div>
                                            <div class="text-xs text-gray-500">Fitur terbatas & menunggu konfirmasi Admin
                                                Kab.</div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="package" value="premium" class="peer sr-only"
                                            {{ old('package') == 'premium' ? 'checked' : '' }}>
                                        <div
                                            class="rounded-lg border-2 border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-accent peer-checked:bg-yellow-50 transition-all">
                                            <div class="font-bold text-gray-900 mb-1">Premium</div>
                                            <div class="text-xs text-gray-500">Akses semua fitur, langsung aktif setelah
                                                bayar.</div>
                                        </div>
                                    </label>
                                </div>
                                @error('package')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Data Wilayah -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="province_id" class="block text-sm font-medium text-gray-700">Provinsi <span
                                            class="text-red-500">*</span></label>
                                    <select id="province_id" name="province_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 border"
                                        required>
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach ($provinces as $prov)
                                            <option value="{{ $prov->id }}"
                                                {{ old('province_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten
                                        <span class="text-red-500">*</span></label>
                                    <select id="kabupaten_id" name="kabupaten_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 border"
                                        required disabled>
                                        <option value="">-- Pilih Kabupaten --</option>
                                    </select>
                                    @error('kabupaten_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Desa & Email -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="desa" class="block text-sm font-medium text-gray-700">Nama Desa <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="desa" name="desa" value="{{ old('desa') }}"
                                        placeholder="Cth. Makmur Jaya"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 border"
                                        required>
                                    @error('desa')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        placeholder="admin@desa.id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 border"
                                        required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kredensial -->
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <h4 class="font-bold text-gray-800 mb-4 text-sm"><i
                                        class="fa-solid fa-lock text-primary mr-2"></i> Kredensial Login & Domain</h4>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="username" class="block text-sm font-medium text-gray-700">Domain Website
                                            / Username <span class="text-red-500">*</span></label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <span
                                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                                domain
                                            </span>
                                            <input type="text" id="username" name="username"
                                                value="{{ old('username') }}"
                                                class="flex-1 min-w-0 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 p-2 border focus:border-primary focus:ring-primary"
                                                placeholder="contoh-desa" required>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Ini akan menjadi alamat website anda dan
                                            username login. Hanya huruf, angka, dan strip (-).</p>
                                        @error('username')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700">Password
                                                <span class="text-red-500">*</span></label>
                                            <input type="password" id="password" name="password"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 border"
                                                required minlength="6">
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="password_confirmation"
                                                class="block text-sm font-medium text-gray-700">Konfirmasi Password <span
                                                    class="text-red-500">*</span></label>
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 border"
                                                required minlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="submit"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="fa-solid fa-paper-plane mr-2 mt-0.5"></i> Daftarkan BUMDesa Saya
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const provinceSelect = document.getElementById('province_id');
                const kabupatenSelect = document.getElementById('kabupaten_id');

                // Cek jika ada old data
                const oldKabupatenId = "{{ old('kabupaten_id') }}";

                function loadKabupaten(provinceId, selectedKabId = null) {
                    kabupatenSelect.innerHTML = '<option value="">Memuat...</option>';
                    kabupatenSelect.disabled = true;

                    if (!provinceId) {
                        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                        return;
                    }

                    fetch(`/api/kabupatens/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                            data.forEach(kab => {
                                const option = document.createElement('option');
                                option.value = kab.id;
                                option.textContent = kab.name;
                                if (selectedKabId && selectedKabId == kab.id) {
                                    option.selected = true;
                                }
                                kabupatenSelect.appendChild(option);
                            });
                            kabupatenSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching kabupatens:', error);
                            kabupatenSelect.innerHTML = '<option value="">Gagal memuat</option>';
                        });
                }

                provinceSelect.addEventListener('change', function() {
                    loadKabupaten(this.value);
                });

                if (provinceSelect.value) {
                    loadKabupaten(provinceSelect.value, oldKabupatenId);
                }
            });
        </script>
    @endpush
@endsection
