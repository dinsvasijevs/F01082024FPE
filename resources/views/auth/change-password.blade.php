<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Change Password') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="current_password" :value="__('Current Password')" />
                            <x-text-input id="current_password" class="block mt-1 w-full" type="password" name="current_password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="new_password" :value="__('New Password')" />
                            <x-text-input id="new_password" class="block mt-1 w-full" type="password" name="new_password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="new_password_confirmation" :value="__('Confirm New Password')" />
                            <x-text-input id="new_password_confirmation" class="block mt-1 w-full" type="password" name="new_password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('new_password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Change Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
