<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'THE GAME - RRHH') : config('app.name', 'THE GAME - RRHH') }}
</title>

<!-- Favicon personalizado con el logo de la empresa -->
<link rel="icon" type="image/png" href="{{ asset('img/logo_thegame.png') }}">
<link rel="apple-touch-icon" href="{{ asset('img/logo_thegame.png') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
