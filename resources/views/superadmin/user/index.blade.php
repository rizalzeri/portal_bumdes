@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola akun Super Admin, Admin Kabupaten, dan Pengguna BUMDesa.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Pengguna
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email & Telp</th>
                    <th class="px-6 py-3">Peran / Role</th>
                    <th class="px-6 py-3">Kabupaten</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-primary font-medium">{{ $user->email }}</div>
                        <div class="text-xs text-gray-400">{{ $user->phone ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'superadmin')
                            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-purple-200">Super Admin</span>
                        @elseif($user->role === 'admin_kabupaten')
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">Admin Kab.</span>
                        @else
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-200">User BUMDes</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $user->kabupaten->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editUser({{ $user->toJson() }})" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('superadmin.user.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
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
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Tambah Pengguna Baru</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('superadmin.user.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required minlength="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Peran (Role)</label>
                <select name="role" id="add-role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2" onchange="toggleKabupaten('add-role', 'add-kabupaten-div')">
                    <option value="user">User BUMDes / Pengunjung</option>
                    <option value="admin_kabupaten">Admin Kabupaten</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>
            <div id="add-kabupaten-div" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Pilih Kabupaten (Wajib untuk Admin Kabupaten)</label>
                <select name="kabupaten_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($kabupatens as $kab)
                        <option value="{{ $kab->id }}">{{ $kab->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Ubah Data Pengguna</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form id="form-edit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="edit-email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone" id="edit-phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="password" minlength="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Peran (Role)</label>
                <select name="role" id="edit-role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2" onchange="toggleKabupaten('edit-role', 'edit-kabupaten-div')">
                    <option value="user">User BUMDes / Pengunjung</option>
                    <option value="admin_kabupaten">Admin Kabupaten</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>
            <div id="edit-kabupaten-div" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Pilih Kabupaten</label>
                <select name="kabupaten_id" id="edit-kabupaten" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($kabupatens as $kab)
                        <option value="{{ $kab->id }}">{{ $kab->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleKabupaten(selectId, divId) {
        const val = document.getElementById(selectId).value;
        const div = document.getElementById(divId);
        if(val === 'admin_kabupaten' || val === 'user') {
            div.classList.remove('hidden');
        } else {
            div.classList.add('hidden');
        }
    }

    function editUser(user) {
        document.getElementById('form-edit').action = `/superadmin/user/${user.id}`;
        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-phone').value = user.phone || '';
        document.getElementById('edit-role').value = user.role;
        document.getElementById('edit-kabupaten').value = user.kabupaten_id || '';
        
        toggleKabupaten('edit-role', 'edit-kabupaten-div');
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endsection
