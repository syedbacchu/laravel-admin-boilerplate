<x-layout.default>
@section('title', $pageTitle)

<form
    id="project-form"
    method="POST"
    action="{{ $function_type == 'create' ? route('project.store') : route('project.update', $item->id) }}"
    class="mt-4"
>
    @csrf
    @if($function_type == 'update')
        @method('PUT')
        <input type="hidden" name="edit_id" value="{{ $item->id }}">
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ $pageTitle }}
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('project.list') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ $function_type == 'create' ? __('Save Project') : __('Update Project') }}</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Project Information') }}</h3>
                </div>
                <div class="p-4 space-y-4">
                    <input
                        name="title"
                        data-slug-source
                        type="text"
                        value="{{ $item->title ?? old('title') }}"
                        class="form-input text-lg font-semibold"
                        placeholder="{{ __('Add title') }}"
                        required
                    />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Permalink / Slug') }}</label>
                            <input
                                name="slug"
                                data-slug-target
                                type="text"
                                value="{{ $item->slug ?? old('slug') }}"
                                class="form-input mt-1"
                            />
                        </div>
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Project URL') }}</label>
                            <input type="url" name="project_url" value="{{ $item->project_url ?? old('project_url') }}" class="form-input mt-1" placeholder="https://example.com">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Short Description') }}</label>
                        <input type="text" name="short_description" value="{{ $item->short_description ?? old('short_description') }}" class="form-input mt-1 w-full">
                    </div>

                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Description') }}</label>
                        <textarea name="description" rows="6" class="form-textarea mt-1 w-full">{{ $item->description ?? old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Start Date') }}</label>
                            <input type="date" name="start_date" value="{{ $item->start_date ?? old('start_date') }}" class="form-input mt-1">
                        </div>
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('End Date') }}</label>
                            <input type="date" name="end_date" value="{{ $item->end_date ?? old('end_date') }}" class="form-input mt-1">
                        </div>
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Project Status') }}</label>
                            <select name="project_status" class="form-select mt-1">
                                <option value="ongoing" @selected(($item->project_status ?? 'ongoing') == 'ongoing')>{{ __('Ongoing') }}</option>
                                <option value="hold" @selected(($item->project_status ?? 'ongoing') == 'hold')>{{ __('On Hold') }}</option>
                                <option value="completed" @selected(($item->project_status ?? 'ongoing') == 'completed')">{{ __('Completed') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6 xl:sticky xl:top-20 self-start">
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Publish') }}</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Status') }}</label>
                        <select name="status" class="form-select mt-1 w-full">
                            <option value="1" @selected(($item->status ?? 1) == 1)>Active</option>
                            <option value="0" @selected(($item->status ?? 1) == 0)>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Category') }}</label>
                        <select name="category_id" class="form-select mt-1 w-full">
                            <option value="">{{ __('No Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(($item->category_id ?? $selectedCategoryId ?? old('category_id')) == $category->id)">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Featured') }}</label>
                            <select name="is_featured" class="form-select mt-1 w-full">
                                <option value="1" @selected(($item->is_featured ?? 0) == 1)>Yes</option>
                                <option value="0" @selected(($item->is_featured ?? 0) == 0)>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Sort Order') }}</label>
                            <input type="number" name="sort_order" value="{{ $item->sort_order ?? old('sort_order', 0) }}" class="form-input mt-1 w-full" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Images') }}</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div x-data="fileManager('{{ $item->thumbnail ?? old('thumbnail', '') }}', 'project_thumbnail')" class="space-y-2">
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Thumbnail') }}</label>
                        <button type="button"
                                x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary btn-sm w-full">
                            {{ __('Choose Thumbnail') }}
                        </button>
                        <input type="hidden" name="thumbnail" x-model="fileUrl">
                        <template x-if="filePreview">
                            <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[160px]">
                        </template>
                    </div>

                    <div x-data="projectGallery({{ json_encode($item->gallery ?? old('gallery', [])) }})" class="space-y-2">
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Gallery') }}</label>
                        <button type="button"
                                x-on:click="$dispatch('open-file-manager', { callback: 'projectGalleryImageSelected', multiple: true })"
                                class="btn btn-outline-primary btn-sm w-full">
                            {{ __('Choose Gallery Images') }}
                        </button>
                        <input type="hidden" name="gallery" x-model="galleryJson">
                        
                        <div class="grid grid-cols-3 gap-2 mt-2">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="relative group">
                                    <img :src="image" class="rounded-lg border object-cover w-full h-20">
                                    <button type="button" 
                                            x-on:click="removeImage(index)"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('SEO') }}</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Meta Title') }}</label>
                        <input type="text" name="meta_title" value="{{ $item->meta_title ?? old('meta_title') }}" class="form-input mt-1 w-full">
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Meta Keywords') }}</label>
                        <input type="text" name="meta_keywords" value="{{ $item->meta_keywords ?? old('meta_keywords') }}" class="form-input mt-1 w-full">
                    </div>
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Meta Description') }}</label>
                        <textarea name="meta_description" rows="3" class="form-textarea mt-1 w-full">{{ $item->meta_description ?? old('meta_description') }}</textarea>
                    </div>
                    <div x-data="fileManager('{{ $item->meta_image ?? old('meta_image', '') }}', 'project_meta_image')" class="space-y-2">
                        <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Meta Image') }}</label>
                        <button type="button"
                                x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary btn-sm w-full">
                            {{ __('Choose Meta Image') }}
                        </button>
                        <input type="hidden" name="meta_image" x-model="fileUrl">
                        <template x-if="filePreview">
                            <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[160px]">
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function projectGallery(existingGallery) {
    const gallery = Array.isArray(existingGallery) ? existingGallery : [];
    return {
        images: gallery,
        get galleryJson() {
            return JSON.stringify(this.images);
        },
        set galleryJson(value) {
            try {
                this.images = JSON.parse(value) ?? [];
            } catch (e) {
                this.images = [];
            }
        },
        init() {
            window.addEventListener('projectGalleryImageSelected', (e) => {
                const newImages = e.detail.images || [e.detail.url];
                this.images = [...this.images, ...newImages];
            });
        },
        removeImage(index) {
            this.images.splice(index, 1);
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
