@extends('layouts.app')
@section('title', 'Tambah Admin Kabupaten')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-6 md:p-8 max-w-3xl">
        <div class="mb-6 flex justify-between items-center pb-4 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Form Buat Admin Kabupaten</h2>
            <a href="{{ route('superadmin.adminkab.index') }}"
                class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('superadmin.adminkab.store') }}" method="POST">
            @csrf
            <div class="space-y-6">

                <!-- Provinsi -->
                <div>
                    <label for="province_id" class="block text-sm font-medium text-gray-700">Provinsi <span
                            class="text-red-500">*</span></label>
                    <select id="province_id" name="province_id"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary p-2.5 border"
                        required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ old('province_id') == $prov->id ? 'selected' : '' }}>
                                {{ $prov->name }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kabupaten -->
                <div>
                    <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten <span
                            class="text-red-500">*</span></label>
                    <select id="kabupaten_id" name="kabupaten_id"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary p-2.5 border"
                        required disabled>
                        <option value="">-- Pilih Kabupaten --</option>
                    </select>
                    @error('kabupaten_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kredensial -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 mt-2">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i
                            class="fa-solid fa-key text-primary"></i> Kredensial Login</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Utama <span
                                    class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary p-2.5 border"
                                placeholder="admin@kabupaten.go.id" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary p-2.5 border"
                                placeholder="Contoh: admin_bandung" required>
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password <span
                                    class="text-red-500">*</span></label>
                            <div class="relative mt-2">
                                <input type="password" id="password" name="password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary p-2.5 border"
                                    required minlength="6">
                                <button type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 hover:text-primary transition-colors"
                                    onclick="togglePassword()">
                                    <i class="fa-solid fa-eye-slash text-gray-400" id="toggle-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="inline-flex justify-center flex-row items-center whitespace-nowrap px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Admin
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('toggle-icon');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const provinceSelect = document.getElementById('province_id');
                const kabupatenSelect = document.getElementById('kabupaten_id');
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
                            console.error('Error:', error);
                            kabupatenSelect.innerHTML = '<option value="">Gagal memuat API</option>';
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
