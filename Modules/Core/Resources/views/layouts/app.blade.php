@php
    use Illuminate\Support\Facades\Route;
    $currentRouteName = Route::currentRouteName();

    $light_theme = false; $dark_theme = false;
    $theme = Cookie::get('theme');
    if ($theme === 'light')
        $light_theme = true;
    elseif ($theme === 'dark')
        $dark_theme = true;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- TODO: REPLACE INLINE FONTS + CSS --}}
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('fav/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('fav/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('fav/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('fav/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('fav/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('fav/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('fav/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('fav/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('fav/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('fav/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('fav/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('fav/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('fav/ms-icon-144x144.png') }}">
{{--    <meta name="theme-color" content="#ffffff">--}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
    <title>{{ ucfirst($currentRouteName) }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <style>
        /*INFO: THIS STYLES SHOULD BE HERE MANDATORY!!!*/
        #main{
            opacity: 0;
            transition: opacity 0.5s ease;
            background-image: url('logo_bg.png');
            background-position: center 200px;
            background-repeat: no-repeat;
        }
        .animate {
            opacity: 1 !important;
        }
        #content {
            padding-top: 75px;
        }

    </style>

    {{--Theme SCRIPTS--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .Toastify__toast {
            background-color: #333 !important;
            color: white !important;
        }
    </style>

</head>
<body id="main" @class([
    'dark:bg-gray-800' => $dark_theme,
    'font-sans', 'antialiased'])>
<div id="dynamic-content" class="min-h-screen bg-gray-100 dark:bg-gray-900 bg-blue-500 text-white">
    @include('core::layouts.navigation')

    <!-- Page Heading -->
    @yield('header')

    @php $status = 'active'; @endphp
        <!-- Page Content -->
    <div id="content" class="py-2 h-full px-24">
        <div class="container-fluid">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="pt-2 pb-6 text-gray-900 dark:text-gray-100">
                    <div class="px-24 content_wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm fixed">
        <div class="text-gray-900 dark:text-gray-100">
            <div class="max-w-9xl mx-auto pt-0 pb-2 px-4 sm:px-6 lg:px-8 relative">
                <div class="float-left">
                    <p class="ml-20 mt-3 text-xs">TransimeX - CRM <span class="text-red-200">Beta - 0.001</span></p>
                </div>
                <div class="float-right" >
                    <v-gotop></v-gotop>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    const page = document.getElementById("main");

    setTimeout(function (){
        page.classList.toggle("animate");
    }, 700)
</script>

{{--<script>--}}
{{--    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({--}}
{{--        key: "AIzaSyAb726TP38q1UpX8-O-hRdeeNobXO4pAcc",--}}
{{--        v: "weekly",--}}
{{--        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).--}}
{{--        // Add other bootstrap parameters as needed, using camel case.--}}
{{--    });--}}
{{--</script>--}}
</body>
</html>
