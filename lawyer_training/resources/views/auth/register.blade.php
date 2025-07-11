<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- User Type and Entity Type -->
        <div class="flex mt-4">
            <div class="w-1/2 mr-2">
                <x-input-label for="user_type" :value="__('User Type')" />
                <select name="user_type" id="user_type" class="block mt-1 w-full" required>
                    <option value="developer">Developer</option>
                    <option value="investor">Investor</option>
                </select>
                <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
            </div>

            <div class="w-1/2 ml-2">
                <x-input-label for="entity_type" :value="__('Entity Type')" />
                <select name="entity_type" id="entity_type" class="block mt-1 w-full" required>
                    <option value="company">Company</option>
                    <option value="individual">Individual</option>
                </select>
                <x-input-error :messages="$errors->get('entity_type')" class="mt-2" />
            </div>
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email and Phone -->
        <div class="flex mt-4">
            <div class="w-1/2 mr-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="w-1/2 ml-2">
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required autocomplete="address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- National ID -->
        <div class="mt-4">
            <x-input-label for="national_id" :value="__('National ID')" />
            <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id" :value="old('national_id')" required autocomplete="national_id" />
            <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
        </div>

        <!-- National ID Front and Back -->
        <div class="flex mt-4">
            <div class="w-1/2 mr-2">
                <x-input-label for="national_id_front" :value="__('National ID (Front)')" />
                <x-text-input id="national_id_front" class="block mt-1 w-full" type="file" name="national_id_front" required />
                <x-input-error :messages="$errors->get('national_id_front')" class="mt-2" />
            </div>

            <div class="w-1/2 ml-2">
                <x-input-label for="national_id_back" :value="__('National ID (Back)')" />
                <x-text-input id="national_id_back" class="block mt-1 w-full" type="file" name="national_id_back" required />
                <x-input-error :messages="$errors->get('national_id_back')" class="mt-2" />
            </div>
        </div>

        <!-- Password and Confirm Password -->
        <div class="flex mt-4">
            <div class="w-1/2 mr-2">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="w-1/2 ml-2">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
