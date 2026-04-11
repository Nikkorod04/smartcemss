<x-guest-layout>
    <div class="animate-fade-in">
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <p class="text-gray-600 text-sm">Community Extension Services Monitoring & Evaluation System</p>
            <p class="text-gray-500 text-xs mt-2">Leyte Normal University</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Tab Navigation -->
        <div x-data="{ tab: 'staff' }" class="mb-6">
            <div class="flex gap-2 border-b border-gray-200">
                <button @click="tab = 'staff'" :class="tab === 'staff' ? 'border-lnu-blue text-lnu-blue bg-gray-50' : 'text-gray-600 border-transparent'" class="flex-1 py-3 text-center font-semibold border-b-2 transition duration-300">
                    Staff Login
                </button>
                <button @click="tab = 'faculty'" :class="tab === 'faculty' ? 'border-lnu-blue text-lnu-blue bg-gray-50' : 'text-gray-600 border-transparent'" class="flex-1 py-3 text-center font-semibold border-b-2 transition duration-300 hover-gold">
                    I'm a Faculty
                </button>
            </div>

            <!-- Staff Login Form -->
            <div x-show="tab === 'staff'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="mt-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-5">
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="block mt-2 input-field" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-2 input-field"
                                        type="password"
                                        name="password"
                                        required 
                                        autocomplete="current-password" 
                                        placeholder="Enter your password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-lnu-blue shadow-sm focus:ring-lnu-blue" name="remember">
                            <span class="ms-2 text-sm text-gray-700">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-primary mb-4">
                        {{ __('Log In') }}
                    </button>
                </form>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a class="text-sm text-lnu-blue hover:text-lnu-blue underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Faculty Login Form -->
            <div x-show="tab === 'faculty'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="mt-6">
                <form method="POST" action="{{ route('faculty.login') }}">
                    @csrf

                    <!-- Access Token -->
                    <div class="mb-5">
                        <x-input-label for="token" :value="__('Access Token')" />
                        <x-text-input id="token" class="block mt-2 input-field" type="password" name="token" required autofocus placeholder="Paste your access token" />
                        <x-input-error :messages="$errors->get('token')" class="mt-2" />
                    </div>

                    <p class="text-sm text-gray-600 mb-6">
                        Your access token was provided by the Director. If you don't have one, please contact the Extension Program Office.
                    </p>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-secondary">
                        {{ __('Access as Faculty') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-center text-xs text-gray-600">
                Don't have an account? <a href="#" class="font-semibold text-lnu-blue hover:text-lnu-gold">Contact the administrator</a>
            </p>
        </div>
    </div>
</x-guest-layout>
