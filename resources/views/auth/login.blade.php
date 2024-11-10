<x-guest-layout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-200 p-4 rounded-lg shadow-lg w-full max-w-4xl mx-auto mt-5 mb-5">
        <h1 class="text-center text-2xl font-bold mb-12 text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-green-600 to-blue-600">
            Log in
        </h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-1" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-2">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block mb-1 font-semibold text-gray-700">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                @error('email')
                    <p class="mt-1 text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1 font-semibold text-gray-700">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                @error('password')
                    <p class="mt-1 text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <label for="remember_me" class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</label>
                
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium transition duration-300">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                

                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-gradient-to-l hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                    {{ __('Log in') }}
                </button>

            </div>
            <a href="{{ route('register') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium transition duration-300"> 
                {{ __('Belum punya akun?') }}
        </form>
    </div>
</x-guest-layout>
