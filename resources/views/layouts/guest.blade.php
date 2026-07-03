<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Theme Switcher: di <head> untuk mencegah flash -->
        <script>
            (function () {
                var saved = localStorage.getItem('theme') || 'light';
                if (saved !== 'dark' && saved !== 'light') {
                    saved = 'light';
                }
                function applyTheme(t) {
                    var resolved = t === 'dark' ? 'dark' : 'light';
                    if (resolved === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    document.documentElement.setAttribute('data-theme', resolved);
                }
                applyTheme(saved);
                // Re-apply theme on Livewire page transitions (wire:navigate)
                document.addEventListener('livewire:navigated', function() {
                    var current = localStorage.getItem('theme') || 'light';
                    applyTheme(current);
                });
                window.themeManager = {
                    getTheme: function() { 
                        var t = localStorage.getItem('theme');
                        return (t === 'dark' || t === 'light') ? t : 'light';
                    },
                    setTheme: function(t) {
                        var resolved = t === 'dark' ? 'dark' : 'light';
                        localStorage.setItem('theme', resolved);
                        applyTheme(resolved);
                        window.dispatchEvent(new CustomEvent('theme-changed', { detail: resolved }));
                    }
                };
            })();
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/" wire:navigate>
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
