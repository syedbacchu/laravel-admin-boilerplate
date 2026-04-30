<x-layout.default>
@section('title', $pageTitle)

<form
    method="POST"
    action="{{ route('stat.store') }}"
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
            <a href="{{ route('stat.list') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Save Stat') }}</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="xl:col-span-2 space-y-6">
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">

                <div class="px-4 py-3 border-b bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Stat Information') }}</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- TITLE -->
                    <input
                        name="title"
                        type="text"
                        value="{{ $item->title ?? old('title') }}"
                        class="form-input text-lg font-semibold"
                        placeholder="Title"
                    />

                    <!-- SUBTITLE -->
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">Subtitle</label>
                        <input type="text"
                               name="subtitle"
                               value="{{ $item->subtitle ?? old('subtitle') }}"
                               class="form-input mt-1 w-full">
                    </div>
                    <!-- DESCRIPTION -->
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">Description</label>
                        <textarea type="text"
                               name="description"
                               value="{{ $item->description ?? old('description') }}"
                               class="form-input mt-1 w-full"
                               rows="3"
                        ></textarea>
                    </div>

                    <!-- LINK -->
                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">Link</label>
                        <input type="text"
                               name="link"
                               value="{{ $item->link ?? old('link') }}"
                               class="form-input mt-1 w-full">
                    </div>

                </div>

                {{-- Custom Fields --}}
                @if(isset($item))
                    @customFields($item)
                @else
                    @customFields(\App\Models\Stat::class)
                @endif

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6 xl:sticky xl:top-20 self-start">

            <!-- STATUS -->
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Publish') }}</h3>
                </div>

                <div class="p-4 space-y-4">

                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">Status</label>
                        <select name="status" class="form-select mt-1 w-full">
                            <option value="1" @selected(($item->status ?? 1) == 1)>Active</option>
                            <option value="0" @selected(($item->status ?? 1) == 0)>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">Sort Order</label>
                        <input type="number"
                               name="sort_order"
                               value="{{ $item->sort_order ?? old('sort_order', 0) }}"
                               class="form-input mt-1 w-full" />
                    </div>
                    <div class="mb-2">
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
            </div>

            <!-- IMAGE -->
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">Image</h3>
                </div>

                <div class="p-4 space-y-4">

                    <div x-data="fileManager('{{ $item->image ?? old('image', '') }}', 'image')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary btn-sm w-full">
                            Choose Image
                        </button>

                        <input type="hidden" name="image" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="rounded-lg border object-cover w-full max-h-[160px]">
                        </template>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="btn btn-primary">
            Save Stat
        </button>
    </div>

</form>
</x-layout.default>