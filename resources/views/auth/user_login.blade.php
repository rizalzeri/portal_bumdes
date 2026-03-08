@extends('layouts.public')
@section('title', 'Login Pengelola BUMDesa')

@section('content')
    <div class="flex items-center justify-center min-h-[70vh]">
        <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border-t-4 border-blue-600">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Portal BUMDesa</h2>
                <p class="text-gray-500 mt-2">Login Pengelola BUMDesa</p>
            </div>

            @if ($errors->any())
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

            <form method="POST" action="{{ route('user.login.post') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Domain / Username</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="email" id="email"
                            class="focus:ring-blue-600 focus:border-blue-600 block w-full pl-10 py-3 sm:text-sm border-gray-300 rounded-md border"
                            placeholder="Contoh: mybumdes" required value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="focus:ring-blue-600 focus:border-blue-600 block w-full pl-10 py-3 sm:text-sm border-gray-300 rounded-md border"
                            required>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Login Aplikasi BUMDes
                    </button>
                </div>

                <div class="mt-4 text-center border-t pt-4 flex flex-col gap-2">
                    <a href="{{ route('adminkab.login') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900">Login sebagai Admin Kabupaten</a>
                    <a href="{{ route('superadmin.login') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900">Login sebagai Super Admin</a>
                </div>
            </form>
        </div>
    </div>
@endsection
