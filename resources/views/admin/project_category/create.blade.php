<x-layout.default>
@section('title', $pageTitle)

<div class="panel mt-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
        {{ $pageTitle }}
    </h1>

    <div>
        <form
            method="POST"
            action="{{ $function_type == 'create' ? route('projectCategory.store') : route('projectCategory.update', $item->id) }}"
            enctype="multipart/form-data"
        >
            @csrf
            @if($function_type == 'update')
                @method('PUT')
                <input type="hidden" name="edit_id" value="{{ $item->id }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('Name') }}</label>
                    <div class="flex">
                        {!! defaultInputIcon() !!}
                        <input
                            name="name"
                            data-slug-source
                            type="text"
                            value="{{ $item->name ?? old('name') }}"
                            class="form-input ltr:rounded-l-none rtl:rounded-r-none"
                            required
                        />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('Slug') }}</label>
                    <div class="flex">
                        {!! defaultInputIcon() !!}
                        <input
                            name="slug"
                            data-slug-target
                            type="text"
                            value="{{ $item->slug ?? old('slug') }}"
                            class="form-input ltr:rounded-l-none rtl:rounded-r-none"
                        />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('Icon') }}</label>
                    <input
                        name="icon"
                        type="text"
                        value="{{ $item->icon ?? old('icon') }}"
                        class="form-input w-full"
                        placeholder="e.g., fas fa-folder"
                    />
                    <p class="text-xs text-gray-500 mt-1">{{ __('Font Awesome icon class') }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('Sort Order') }}</label>
                    <div class="flex">
                        {!! defaultInputIcon() !!}
                        <input
                            name="sort_order"
                            type="number"
                            value="{{ $item->sort_order ?? old('sort_order', 0) }}"
                            class="form-input ltr:rounded-l-none rtl:rounded-r-none"
                        />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('Status') }}</label>
                    <select name="status" class="form-select w-full">
                        <option value="1" @selected(($item->status ?? 1) == 1)>Active</option>
                        <option value="0" @selected(($item->status ?? 1) == 0)>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">{{ __('Description') }}</label>
                <textarea
                    name="description"
                    rows="3"
                    class="form-textarea w-full"
                >{{ $item->description ?? old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">{{ __('Image') }}</label>
                <div
                    x-data="projectCategoryImage('{{ $item->image ?? '' }}')"
                    class="space-y-2"
                >
                    <button
                        type="button"
                        x-on:click="$dispatch('open-file-manager', { callback: 'projectCategoryImageSelected' })"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700"
                    >
                        {{ __('Choose Image') }}
                    </button>

                    <input type="hidden" name="image" x-model="image">

                    <template x-if="preview">
                        <div class="mt-3">
                            <img
                                :src="preview"
                                class="rounded-xl border object-cover shadow-sm"
                                width="200"
                            >
                        </div>
                    </template>
                </div>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(($item->is_featured ?? 0) == 1)
                        class="form-checkbox"
                    />
                    <span class="ml-2 text-gray-700 font-medium">{{ __('Is Featured') }}</span>
                </label>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">
                    {{ $function_type == 'create' ? __('Submit') : __('Update') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function projectCategoryImage(existingImage = '') {
        return {
            image: existingImage,
            preview: existingImage ? existingImage : '',
            init() {
                window.addEventListener('projectCategoryImageSelected', (e) => {
                    this.image = e.detail.url;
                    this.preview = e.detail.url;
                });
            }
        }
    }
</script>

<script src="{{ asset('assets/js/slugify.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeSlugify();
    });
</script>
</x-layout.default>
