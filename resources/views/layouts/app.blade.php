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

        <!-- Theme Switcher: harus di <head> untuk mencegah flash -->
        <script>
            (function () {
                var saved = localStorage.getItem('theme') || 'light';
                if (saved !== 'dark' && saved !== 'light') {
                    saved = 'light';
                }
                function applyTheme(t) {
                    var resolved = t === 'dark' ? 'dark' : 'light';
                    // Tailwind dark mode (class)
                    if (resolved === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    // daisyUI theme (data-theme attribute)
                    document.documentElement.setAttribute('data-theme', resolved);
                }
                applyTheme(saved);
                // Re-apply theme on Livewire page transitions (wire:navigate)
                document.addEventListener('livewire:navigated', function() {
                    var current = localStorage.getItem('theme') || 'light';
                    applyTheme(current);
                });
                // Global theme manager API
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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
