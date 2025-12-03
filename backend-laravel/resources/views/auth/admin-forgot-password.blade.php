<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Admin Password Reset: Enter your email address and we will email you a password reset link.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Generate Reset Link') }}
            </x-primary-button>
        </div>
    </form>

    @if (session('reset_link'))
        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <p class="font-bold">Reset Link Generated:</p>
            <p class="break-all">{{ session('reset_link') }}</p>
        </div>
        <script>
            window.onload = function() {
                prompt("Here is your password reset link (Copy it):", "{{ session('reset_link') }}");
            }
        </script>
    @endif
</x-guest-layout>
