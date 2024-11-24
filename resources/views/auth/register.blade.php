<x-guest-layout>
    <div class="bg-white h-full flex flex-col">
        <!-- Back button -->
        <div class="p-4">
            <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-700 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali</span>
            </a>
        </div>
        <div class="flex-1 flex items-center">
            <div class="p-6 rounded-lg w-full">
                <h1 class="text-center text-2xl font-bold mb-12 text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-green-600 to-blue-600">
                    DAFTAR
                </h1>

                <form method="POST" action="{{ route('register') }}" class="space-y-2">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block mb-1 font-semibold text-gray-700">{{ __('Nama') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                        @error('name')
                            <p class="mt-2 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block mb-1 font-semibold text-gray-700">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                        @error('email')
                            <p class="mt-2 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('login') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium transition duration-300">
                            {{ __('Sudah Memiliki Akun?') }}
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-gradient-to-l hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                            {{ __('Daftar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
