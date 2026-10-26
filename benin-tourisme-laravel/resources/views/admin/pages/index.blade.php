@extends('admin.layout')

@section('title', 'Pages')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Gestion des pages</h1>
    <p class="mt-2 text-gray-600">{{ $pages->total() }} pages au total</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Chemin
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Titre
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Vue
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Override
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($pages as $page)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                    {{ $page->path }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    {{ $page->title }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                    {{ $page->view }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($page->use_override)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Actif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Désactivé
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.pages.edit', $page) }}"
                       class="text-blue-600 hover:text-blue-900 mr-4">
                        Éditer
                    </a>
                    <a href="{{ url($page->path) }}"
                       target="_blank"
                       class="text-gray-600 hover:text-gray-900">
                        Voir
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $pages->links() }}
</div>
@endsection
