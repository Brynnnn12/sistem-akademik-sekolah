@if (session()->has('success'))
    <x-sweet.sweet-alert type="success" title="Sukses" text="{{ session('success') }}" :show-on-load="true" timer="3000"
        position="top-end" toast="true" width="300px" />
@endif

@if (session()->has('error'))
    <x-sweet.sweet-alert type="error" title="Error!" text="{{ session('error') }}" :show-on-load="true" timer="3000"
        position="top-end" toast="true" width="300px" />
@endif

@if (session()->has('warning'))
    <x-sweet.sweet-alert type="warning" title="Warning!" text="{{ session('warning') }}" :show-on-load="true"
        timer="3000" position="top-end" toast="true" width="300px" />
@endif

@if (session()->has('info'))
    <x-sweet.sweet-alert type="info" title="Information" text="{{ session('info') }}" :show-on-load="true"
        timer="3000" position="top-end" toast="true" width="300px" />
@endif

@if ($errors->any())
    @php
        $errorList = implode('\n', $errors->all());
    @endphp
    <x-sweet.sweet-alert type="error" title="Validation Errors" text="{{ $errorList }}" :show-on-load="true"
        timer="5000" position="top-end" toast="true" width="350px" />
@endif
