<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Bonne Arrivée</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>
<body class="bg-gray-50">
    @auth
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.pages.index') }}" class="text-xl font-bold text-gray-900">
                        Admin — Bonne Arrivée
                    </a>
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('admin.pages.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.pages.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            Pages
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-4">{{ auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
