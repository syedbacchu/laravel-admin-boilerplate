<x-layout.default>
    @section('title', $pageTitle)

    <form
        id="feature-form"
        method="POST"
        action="{{ $function_type == 'create' ? route('feature.store') : route('feature.update', $item->id) }}"
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
                <a href="{{ route('feature.list') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-primary">{{ $function_type == 'create' ? __('Save Feature') : __('Update Feature') }}</button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                    <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                        <h3 class="font-semibold text-base">{{ __('Feature Information') }}</h3>
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
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Link') }}</label>
                                <input type="url" name="link" value="{{ $item->link ?? old('link') }}" class="form-input mt-1" placeholder="https://example.com">
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

                        <div>
                            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Category') }}</label>
                            <select name="category_id" class="form-select mt-1 w-full">
                                <option value="">{{ __('No Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(($item->category_id ?? $selectedCategoryId ?? old('category_id')) == $category->id)>
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

                        <div>
                            <label for="site_type" class="text-xs uppercase font-semibold text-gray-500">{{ __('Site Type') }}</label>
                            <div class="flex mt-1">
                                {!! defaultInputIcon() !!}
                                <select name="site_type" id="site_type" class="form-select">
                                    <option value="">{{__('Select')}}</option>
                                    @foreach(\App\Enums\SliderSiteType::getSliderSiteTypeArray() as $value => $label)
                                        <option
                                            value="{{ $value }}"
                                            {{ old('site_type', $item->site_type ?? '') == $value ? 'selected' : '' }}
                                        >
                                            {{ __($label) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                    <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                        <h3 class="font-semibold text-base">{{ __('Images') }}</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div x-data="fileManager('{{ $item->thumbnail ?? old('thumbnail', '') }}', 'feature_thumbnail')" class="space-y-2">
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

                        <div x-data="fileManager('{{ $item->image ?? old('image', '') }}', 'feature_image')" class="space-y-2">
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
                        <div x-data="fileManager('{{ $item->meta_image ?? old('meta_image', '') }}', 'feature_meta_image')" class="space-y-2">
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

    <script src="{{ asset('assets/js/slugify.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSlugify();
        });
    </script>
</x-layout.default>
