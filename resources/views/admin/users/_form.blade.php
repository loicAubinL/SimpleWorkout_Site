@csrf

<x-champ name="name" :label="__('interface.name')" :value="$compte?->name" required maxlength="100" />
<x-champ name="email" type="email" :label="__('interface.email')" :value="$compte?->email" required />

<x-champ name="role" type="select" :label="__('interface.role')" required>
    <option value="member" @selected(old('role', $compte?->role ?? 'member') === 'member')>{{ __('interface.role_member') }}</option>
    <option value="manager" @selected(old('role', $compte?->role) === 'manager')>{{ __('interface.role_manager') }}</option>
</x-champ>

<div class="rangee">
    <x-champ name="password" type="password" :label="$compte ? __('interface.new_password') : __('interface.password')"
        :aide="$compte ? __('interface.password_keep_hint') : __('interface.password_hint')"
        :required="! $compte" autocomplete="new-password" />
    <x-champ name="password_confirmation" type="password" :label="__('interface.password_confirmation')"
        :required="! $compte" autocomplete="new-password" />
</div>

<div class="actions">
    <button type="submit" class="bouton"><x-icon name="save" :size="18" /> {{ __('interface.save') }}</button>
    <a href="{{ $compte ? route('admin.users.show', $compte) : route('admin.users.index') }}" class="bouton bouton-fantome">{{ __('interface.cancel') }}</a>
</div>
