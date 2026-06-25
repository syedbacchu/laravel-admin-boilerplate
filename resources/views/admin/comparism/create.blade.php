<x-layout.default>
@section('title', $pageTitle)

<form method="POST" action="{{ route('comparism.store') }}" class="mt-4">
    @csrf

    @if(isset($item))
        <input type="hidden" name="edit_id" value="{{ $item->id }}">
    @endif

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ $pageTitle }}
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('comparism.list') }}" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-primary">Save Comparism</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="xl:col-span-2 space-y-6">
            <div class="panel !p-0 overflow-hidden border">

                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Comparism Information</h3>
                </div>

                <div class="p-4 space-y-4">

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

                    <div>
                        <label class="text-xs uppercase text-gray-500">Area</label>
                        <input name="area"
                               data-slug-target
                               value="{{ $item->area ?? old('area') }}"
                               class="form-input mt-1"
                               placeholder="Enter Area">
                    </div>

                </div>

                {{-- Custom Fields --}}
                @if(isset($item))
                    @customFields($item)
                @else
                    @customFields(\App\Models\Comparison::class)
                @endif

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6 xl:sticky xl:top-20 self-start">

            <!-- STATUS -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Publish</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- STATUS -->
                    <label class="text-xs uppercase text-gray-500">Status</label>
                    <select name="status" class="form-select w-full">
                        <option value="1" @selected(($item->status ?? 1)==1)>Active</option>
                        <option value="0" @selected(($item->status ?? 1)==0)>Inactive</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="btn btn-primary">
            Save comparism
        </button>
    </div>

</form>
</x-layout.default>