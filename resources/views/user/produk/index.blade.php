@extends('layouts.admin')
@section('title', 'Katalog Produk & Layanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Katalog Produk & Layanan</h2>
        <p class="text-gray-500 text-sm mt-1">Daftar produk unggulan dan layanan BUMDesa Anda yang ditampilkan ke publik.</p>
    </div>
    @premium('produk', 'create')
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Produk / Jasa
    </button>
    @endpremium
</div>

<!-- Tampilan Katalog Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($produks as $kt)
    <div class="bg-white border rounded-xl overflow-hidden hover:shadow-lg transition flex flex-col group">
        <div class="h-48 bg-gray-200 relative overflow-hidden">
            @if($kt->image)
                <img src="{{ asset('storage/'.$kt->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex justify-center items-center"><i class="fa-solid fa-box text-gray-400 text-5xl"></i></div>
            @endif
            @if($kt->category)
            <div class="absolute top-2 left-2 bg-blue-500 text-white text-[10px] uppercase font-bold px-2 py-1 rounded shadow">{{ $kt->category }}</div>
            @endif
            
            <!-- Quick Actions Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                @premium('produk', 'update')
                <button onclick="editProduk({{ $kt->toJson() }})" class="bg-yellow-400 hover:bg-yellow-500 text-white p-2.5 rounded-full transition shadow tooltip" title="Edit Data"><i class="fa-solid fa-pen"></i></button>
                @endpremium

                @premium('produk', 'delete')
                <form action="{{ route('user.produk.destroy', $kt->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari katalog?');" class="inline">
                    @csrf @method('DELETE')
                    <button class="bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-full transition shadow tooltip" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
                @endpremium
            </div>
        </div>
        <div class="p-4 flex-grow flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-900 text-lg line-clamp-2 leading-tight" title="{{ $kt->name }}">{{ $kt->name }}</h3>
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $kt->description }}</p>
            </div>
            <div class="mt-4 pt-4 border-t flex justify-between items-end">
                <div>
                    <span class="text-xs text-gray-500 block mb-0.5">Harga</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($kt->price ?? 0, 0, ',', '.') }}</span>
                </div>
                @if($kt->link_pembelian)
                <a href="{{ $kt->link_pembelian }}" target="_blank" class="text-xs bg-gray-100 border text-gray-700 font-bold px-2 py-1.5 rounded hover:bg-gray-200 transition tooltip" title="Buka Link Toko/WA"><i class="fa-solid fa-external-link-alt"></i></a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full border-2 border-dashed border-gray-300 rounded-xl p-12 text-center text-gray-400">
        <i class="fa-solid fa-boxes-packing text-5xl mb-4 text-gray-300"></i>
        <p class="text-lg">Katalog Produk / Layanan Anda masih kosong.</p>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="mt-4 text-primary font-bold hover:underline">Tambah Produk Pertama</button>
    </div>
    @endforelse
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Tambah Produk / Layanan</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('user.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk / Layanan</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriOptions as $opt)
                            <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Foto</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white border-gray-300 rounded-md p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Link Order (Tokopedia, Shopee, atau WA)</label>
                <input type="url" name="link_pembelian" placeholder="https://..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                <p class="text-[10px] text-gray-500 mt-1">Gunakan format wa.me/628... untuk tombol kontak WhatsApp.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Simpan Ke Katalog</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Edit Produk / Layanan</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form id="form-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk / Layanan</label>
                <input type="text" name="name" id="edit-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" id="edit-category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriOptions as $opt)
                            <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" id="edit-price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ganti Foto (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Link Order / WhatsApp</label>
                <input type="url" name="link_pembelian" id="edit-link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="edit-desc" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editProduk(produk) {
        document.getElementById('form-edit').action = `/user/produk/${produk.id}`;
        document.getElementById('edit-name').value = produk.name;
        
        const catSelect = document.getElementById('edit-category');
        let optExists = false;
        for(let i=0; i<catSelect.options.length; i++){
            if(catSelect.options[i].value === produk.category){
                optExists = true;
                break;
            }
        }
        if(!optExists && produk.category){   
            catSelect.add(new Option(produk.category, produk.category));
        }
        catSelect.value = produk.category || '';
        
        document.getElementById('edit-price').value = produk.price || '';
        document.getElementById('edit-link').value = produk.link_pembelian || '';
        document.getElementById('edit-desc').value = produk.description || '';
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endsection
