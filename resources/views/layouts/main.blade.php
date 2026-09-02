<!DOCTYPE html>
<html lang="{{ $locale = str_replace('_', '-', app()->getLocale()) }}">
@include('head')
<body data-bs-theme="light" data-user="{{ json_encode(['id' => auth()->id()]) }}">
<div class="page">
    @include('header')
    <main class="page-wrapper">
        {{ $slot }}
        @include('footer')
    </main>
</div>
@stack('body-script')
</body>
</html>
