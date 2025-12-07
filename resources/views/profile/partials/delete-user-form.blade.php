<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
        @csrf
        @method('delete')

        <div>
            <x-form.input-label for="password" :value="__('Password')" />
            <x-form.text-input id="password" name="password" type="password" class="mt-1 block w-full"
                placeholder="{{ __('Password') }}" required />
            <x-form.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-ui.danger-button>
            {{ __('Delete Account') }}
        </x-ui.danger-button>
    </form>
</section>
