<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ app('settings')['theme']}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="min-vh-100 d-flex align-items-center py-4 bg-body-tertiary">
        <main class="w-100">
            @if (isset($status))
                <div class="container mb-4">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                {{ $status }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>

</html>