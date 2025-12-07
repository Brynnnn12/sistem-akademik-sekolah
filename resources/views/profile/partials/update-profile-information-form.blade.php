<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-form.input-label for="nip" :value="__('NIP')" />
            <x-form.text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $profile->nip)"
                autocomplete="nip" />
            <x-form.input-error class="mt-2" :messages="$errors->get('nip')" />
        </div>

        <div>
            <x-form.input-label for="nama" :value="__('Nama')" />
            <x-form.text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $profile->nama)"
                required autofocus autocomplete="nama" />
            <x-form.input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-form.input-label for="no_hp" :value="__('No HP')" />
            <x-form.text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $profile->no_hp)"
                autocomplete="no_hp" />
            <x-form.input-error class="mt-2" :messages="$errors->get('no_hp')" />
        </div>

        <div>
            <x-form.input-label for="alamat" :value="__('Alamat')" />
            <textarea id="alamat" name="alamat"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                rows="3">{{ old('alamat', $profile->alamat) }}</textarea>
            <x-form.input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <div>
            <x-form.input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
            <select id="jenis_kelamin" name="jenis_kelamin"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih</option>
                <option value="laki-laki"
                    {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki
                </option>
                <option value="perempuan"
                    {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan
                </option>
            </select>
            <x-form.input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
        </div>

        <div>
            <x-form.input-label for="photo" :value="__('Foto')" />
            <input id="photo" name="photo" type="file" class="mt-1 block w-full" accept="image/*" />
            <x-form.input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <x-form.input-label for="email" :value="__('Email')" />
            <x-form.text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-form.input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-ui.primary-button>{{ __('Save') }}</x-ui.primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
