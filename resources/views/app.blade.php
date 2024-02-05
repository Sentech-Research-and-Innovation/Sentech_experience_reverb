<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <style>

    </style>
    <!-- Fonts -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @routes
    {{--        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"]) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

    <style>
        body {
            font-family: 'Roboto', sans-serif;

            font-weight: 300 !important
        }
    </style>
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
<script type="text/javascript">
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}",
        token: 1,
        jsPermissions: {!! auth()->user()
            ?->jsPermissions() !!}
    }
</script>
