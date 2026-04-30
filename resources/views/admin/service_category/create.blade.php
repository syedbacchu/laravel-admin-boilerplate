<x-layout.default>
    @section('title', $pageTitle)

    <div class="panel mt-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            {{ $pageTitle }}
        </h1>

        <div>
            <form
                method="POST"
                action="{{ route('serviceCategory.store') }}"
                enctype="multipart/form-data"
            >
                @csrf
                @if(isset($item))
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

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">{{ __('Status') }}</label>
                        <select name="status" class="form-select w-full">
                            <option value="1" @selected(($item->status ?? 1) == 1)>Active</option>
                            <option value="0" @selected(($item->status ?? 1) == 0)>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="site_type" class="">{{ __('Site Type') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <select name="site_type" id="" class="form-select">
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

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('Description') }}</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="form-textarea w-full"
                    >{{ $item->description ?? old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        x-data="serviceCategoryImage('{{ $item->image ?? '' }}')"
                        class="space-y-2 mt-2"
                    >
                        <label class="font-semibold text-gray-700">{{ __('Category Image') }}</label>

                        <button
                            type="button"
                            x-on:click="$dispatch('open-file-manager', { callback: 'serviceCategoryImageSelected' })"
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

                    <div class="mb-4 mt-2">
                        <label class="flex items-center mt-6">
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
                </div>

                <div>
                    <button type="submit" class="btn btn-secondary mt-6">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function serviceCategoryImage(existingImage = '') {
            return {
                image: existingImage,
                preview: existingImage ? existingImage : '',
                init() {
                    window.addEventListener('serviceCategoryImageSelected', (e) => {
                        this.image = e.detail.url;
                        this.preview = e.detail.url;
                    });
                }
            }
        }
    </script>
</x-layout.default>
