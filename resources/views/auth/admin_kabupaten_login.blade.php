@extends('layouts.public')
@section('title', 'Login Admin Kabupaten')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border-t-4 border-green-600">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Admin Kabupaten</h2>
            <p class="text-gray-500 mt-2">Masuk ke Dasbor Kabupaten Anda</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('adminkab.login.post') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" id="email" class="focus:ring-green-600 focus:border-green-600 block w-full pl-10 py-3 sm:text-sm border-gray-300 rounded-md border" required value="{{ old('email') }}">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password" class="focus:ring-green-600 focus:border-green-600 block w-full pl-10 py-3 sm:text-sm border-gray-300 rounded-md border" required>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Login Admin Kabupaten
                </button>
            </div>
            
            <div class="mt-4 text-center border-t pt-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">Bukan Admin Kabupaten? Login BUMDes</a>
            </div>
        </form>
    </div>
</div>
@endsection
