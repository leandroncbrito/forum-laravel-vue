<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.1/trix.css">

    <script>
        window.App = {!! json_encode([
            'signedIn' => Auth::check(),
            'user' => Auth::user()
        ]) !!};
    </script>

    <style>
        body {
            padding-bottom: 100px;            
        }
        .level {
            display: flex;
            align-items: center;
        }
        .level-item {
            margin-left: 1em;
        }
        .flex {
            flex: 1;
        }
        .mr-1 {
            margin-right: 1em;
        }
        .ml-a { 
            margin-left: auto; 
        }
        [v-cloak] {
            display:none;
        }
    </style>

    @yield('header')
</head>
<body>
    <div id="app">        
        @include('layouts.nav')

        @yield('content')

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
