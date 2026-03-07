@extends('layouts.admin')
@section('title', 'Manajemen Langganan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Langganan BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Pantau dan kelola status langganan tahunan akun BUMDesa maupun Kabupaten.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">ID / Order ID</th>
                    <th class="px-6 py-3">Nama (BUMDes / Kab)</th>
                    <th class="px-6 py-3">Paket</th>
                    <th class="px-6 py-3">Masa Berlaku</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($langganans as $lang)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-primary">{{ $lang->payment_token ?? 'MANUAL-'.$lang->id }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">
                        @if($lang->bumdes)
                            <i class="fa-solid fa-house-flag text-blue-500 mr-2"></i> {{ $lang->bumdes->name }}
                        @elseif($lang->kabupaten)
                            <i class="fa-solid fa-map text-green-500 mr-2"></i> {{ $lang->kabupaten->name }} (Kabupaten)
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-accent">{{ $lang->package_name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-xs">{{ \Carbon\Carbon::parse($lang->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lang->end_date)->format('d/m/Y') }}</div>
                        @if($lang->status === 'active')
                            <div class="text-[10px] {{ \Carbon\Carbon::parse($lang->end_date)->isPast() ? 'text-red-500 font-bold' : 'text-green-500' }}">Sisa {{ \Carbon\Carbon::parse($lang->end_date)->diffInDays(now()) }} hari</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lang->status === 'active')
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">Aktif</span>
                        @elseif($lang->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-200">Pending Payment</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-200">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editLangganan({{ $lang->toJson() }})" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1" title="Edit Masa Berlaku">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('superadmin.langganan.destroy', $lang->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus record langganan ini? BUMDesa terkait mungkin kehilangan akses fitur premium.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Perbarui Status Langganan</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form id="form-edit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="bg-blue-50 p-4 rounded-md border border-blue-100 text-sm mb-4">
                <strong>Subjek:</strong> <span id="display-subject" class="font-bold text-primary"></span><br>
                <strong>Paket:</strong> <span id="display-package" class="text-accent font-bold"></span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status Langganan</label>
                <select name="status" id="edit-status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif (Expired)</option>
                    <option value="pending">Pending Payment</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Berakhir Pada (End Date)</label>
                <input type="date" name="end_date" id="edit-end" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                <p class="text-xs text-gray-500 mt-1">Status akan otomatis tidak aktif jika melewati tanggal ini (tergantung sistem check).</p>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editLangganan(lang) {
        document.getElementById('form-edit').action = `/superadmin/langganan/${lang.id}`;
        document.getElementById('edit-status').value = lang.status;
        
        // Format date to YYYY-MM-DD for input type="date"
        const date = new Date(lang.end_date);
        const formatted = date.toISOString().split('T')[0];
        document.getElementById('edit-end').value = formatted;
        
        const subject = lang.bumdes ? lang.bumdes.name : (lang.kabupaten ? lang.kabupaten.name + ' (Kab)' : 'Unknown');
        document.getElementById('display-subject').innerText = subject;
        document.getElementById('display-package').innerText = lang.package_name;
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endsection
