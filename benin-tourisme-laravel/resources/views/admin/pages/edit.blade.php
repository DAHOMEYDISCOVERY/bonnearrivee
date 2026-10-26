@extends('admin.layout')

@section('title', 'Éditer la page')

@push('head')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Éditer la page</h1>
            <p class="mt-2 text-gray-600 font-mono">{{ $page->path }}</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Retour à la liste
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('admin.pages.update', $page) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Métadonnées</h2>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $page->title) }}"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                    >
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                    >{{ old('description', $page->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Contenu personnalisé</h2>
                    <label class="flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="use_override"
                            id="use_override"
                            value="1"
                            {{ old('use_override', $page->use_override) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm font-medium text-gray-700">Activer le contenu personnalisé</span>
                    </label>
                </div>

                <div id="editor-container">
                    <textarea
                        name="content_override"
                        id="content_override"
                        class="w-full"
                    >{{ old('content_override', $page->content_override) }}</textarea>
                    @error('content_override')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.pages.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>

            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Vue Blade</dt>
                    <dd class="font-mono text-gray-900 mt-1">{{ $page->view }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500">Menu actif</dt>
                    <dd class="font-mono text-gray-900 mt-1">{{ $page->active_menu ?? 'null' }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500">Head profile</dt>
                    <dd class="font-mono text-gray-900 mt-1">{{ $page->head_profile ?? 'standard' }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500">Standalone</dt>
                    <dd class="font-mono text-gray-900 mt-1">{{ $page->standalone ? 'true' : 'false' }}</dd>
                </div>
            </dl>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="{{ url($page->path) }}"
                   target="_blank"
                   class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Voir la page →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
tinymce.init({
    selector: '#content_override',
    height: 600,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code fullscreen',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; }',
    language: 'fr_FR',
    branding: false,
});
</script>
@endpush
