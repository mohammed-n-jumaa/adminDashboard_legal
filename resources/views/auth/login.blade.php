<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-white">
        <div class="bg-gradient-to-r from-gray-800 to-gray-700 shadow-lg rounded-lg p-8 max-w-sm w-full transform transition-all duration-500 ease-in-out hover:scale-105">
            <!-- Logo and Title -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-white">Mustashark</h1>
                <p class="text-gray-300 text-lg">Log in to access your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Display Errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-300 text-sm font-semibold mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-300 text-sm font-semibold mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="form-checkbox text-indigo-600" name="remember">
                        <span class="ml-2 text-gray-300 text-sm">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="text-indigo-600 hover:text-indigo-800 text-sm" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        Log In
                    </button>
                </div>
            </form>

            <!-- Footer Links -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">Don't have an account? 
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800">Register</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
