<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>@php $e = \App\Models\Empresa::find(session('empresa_id')) ?? \App\Models\Empresa::first(); echo $e->nombre ?? config('app.name', 'Sistema de Grúas'); @endphp</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @php $favicon = $e?->favicon ? asset('storage/'.$e->favicon) : null; @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ $favicon }}" />
    @endif
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|roboto:400,500,700|poppins:400,500,600,700|montserrat:400,500,600,700|open+sans:400,500,600,700|lato:400,500,700|oswald:400,500,600,700|raleway:400,500,600,700|pt+sans:400,700|source+sans+3:400,500,600,700|nunito:400,500,600,700|work+Sans:400,500,600,700|quicksand:400,500,600,700|rubik:400,500,600,700|nunito+Sans:400,500,600,700|dm+Sans:400,500,700|figtree:400,500,600,700|plus+jakarta+Sans:400,500,600,700|manrope:400,500,600,700|outfit:400,500,600,700|space+grotesk:400,500,600,700|urbanist:400,500,600,700|redHat+Display:400,500,600,700|sarabun:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @routes
    @inertiaHead
</head>
<body class="font-sans antialiased"
    style="@php
        $emp = \App\Models\Empresa::find(session('empresa_id')) ?? \App\Models\Empresa::first();
        if ($emp) {
            $c = $emp->color ?? '#FFD500';
            $cs = $emp->color_secundario ?? '#E6A000';
            $font = $emp->tipografia ?? 'Inter';
            echo '--geg-primary: '.$c.'; --geg-primary-dark: '.$cs.'; --geg-yellow: '.$c.'; --geg-yellow-dark: '.$cs.';';
            echo 'font-family: \''.$font.'\', sans-serif;';
        }
    @endphp">
    @inertia
</body>
</html>
