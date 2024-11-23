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
                    {{ __('Forgot Password') }}
                </h1>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-2">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block mb-1 font-semibold text-gray-700">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                        @error('email')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-gradient-to-l hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
