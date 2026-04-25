<x-layout.default>
    @section('title', $pageTitle)

    <form
        id="service-form"
        method="POST"
        action="{{ route('service.store') }}"
        class="mt-4"
    >
        @csrf
        @if(isset($item))
            <input type="hidden" name="edit_id" value="{{ $item->id }}">
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ $pageTitle }}
            </h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('service.list') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Save Service') }}</button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                    <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                        <h3 class="font-semibold text-base">{{ __('Service Information') }}</h3>
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
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Category') }}</label>
                                <select name="category_id" class="form-select mt-1">
                                    <option value="">{{ __('No Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(($item->category_id ?? old('category_id')) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                        <div x-data="fileManager('{{ $item->thumbnail ?? old('thumbnail', '') }}', 'service_thumbnail')" class="space-y-2">
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

                        <div x-data="fileManager('{{ $item->image ?? old('image', '') }}', 'service_image')" class="space-y-2">
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Image') }}</label>
                            <button type="button"
                                    x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                                    class="btn btn-outline-primary btn-sm w-full">
                                {{ __('Choose Image') }}
                            </button>
                            <input type="hidden" name="image" x-model="fileUrl">
                            <template x-if="filePreview">
                                <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[160px]">
                            </template>
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
                        <div x-data="fileManager('{{ $item->meta_image ?? old('meta_image', '') }}', 'service_meta_image')" class="space-y-2">
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

        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn btn-primary">{{ __('Save Service') }}</button>
        </div>
    </form>
</x-layout.default>
