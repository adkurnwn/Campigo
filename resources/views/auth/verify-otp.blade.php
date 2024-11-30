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
                <h1 class="text-center text-2xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-green-600 to-blue-600">
                    Verifikasi Email
                </h1>

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-4 text-sm text-gray-600 text-center">
                    Kode OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.
                </div>

                <form method="POST" action="{{ route('verify.otp') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ request('email') }}">

                    <div>
                        <label for="otp" class="block mb-1 font-semibold text-gray-700">Kode OTP</label>
                        <div class="flex justify-center space-x-2">
                            @for($i = 1; $i <= 6; $i++)
                            <input type="text" 
                                   maxlength="1"
                                   autocomplete="off"
                                   class="w-12 h-12 text-center text-2xl font-bold border-2 rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                                   oninput="moveToNext(this, {{ $i }})"
                                   onkeypress="return onlyNumbers(event)"
                                   onpaste="handlePaste(event)"
                                   data-position="{{ $i }}"
                                   required>
                            @endfor
                            <input type="hidden" name="otp" id="finalOtp">
                        </div>
                        @error('otp')
                            <p class="mt-1 text-red-600 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block mb-1 font-semibold text-gray-700">Masukkan Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                        @error('password')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block mb-1 font-semibold text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full p-2 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500" />
                    </div>

                    <div class="flex items-center justify-center mt-6">
                        <button type="submit" 
                                onclick="combineOtp()"
                                class="w-full px-4 py-2 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-gradient-to-l hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                            Verifikasi & Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function moveToNext(field, position) {
            if (field.value.length >= field.maxLength) {
                if (position < 6) {
                    const nextField = document.querySelector(`input[data-position="${position + 1}"]`);
                    if (nextField) nextField.focus();
                }
            }
        }

        function handlePaste(event) {
            event.preventDefault();
            const pastedText = (event.clipboardData || window.clipboardData).getData('text');
            const numbers = pastedText.replace(/\D/g, '').split('').slice(0, 6);
            
            const inputs = document.querySelectorAll('input[data-position]');
            inputs.forEach((input, index) => {
                if (numbers[index]) {
                    input.value = numbers[index];
                    if (index === 5) {
                        input.focus();
                    }
                }
            });
        }

        function onlyNumbers(event) {
            const charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        function combineOtp() {
            const inputs = document.querySelectorAll('input[data-position]');
            const otpValue = Array.from(inputs).map(input => input.value).join('');
            document.getElementById('finalOtp').value = otpValue;
        }
    </script>
</x-guest-layout>