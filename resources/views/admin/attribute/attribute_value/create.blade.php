<x-layout.default>
@section('title', $pageTitle)

<form
    method="POST"
    action="{{ route('attribute.value.store') }}"
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
            <a href="{{ route('attribute.value.list') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Save Attribute Value') }}</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="xl:col-span-2 space-y-6">
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">

                <div class="px-4 py-3 border-b bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Attribute Information') }}</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- Name -->
                    <label class="text-xs uppercase font-semibold text-gray-500">
                        Name
                    </label>
                    <input
                        name="name"
                        type="text"
                        value="{{ $item->name ?? old('name') }}"
                        class="form-input text-lg font-semibold"
                        placeholder="Name"
                    />

                    <div>
                        <label class="text-xs uppercase font-semibold text-gray-500">
                            Attribute Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type_id" class="form-select mt-1 w-full">
                            <option value="">Select Attribute</option>
                            @foreach($attributes ?? [] as $attr)
                                <option value="{{ $attr->id }}"
                                    {{ old('type_id', $item->type_id ?? '') == $attr->id ? 'selected' : '' }}>
                                    {{ $attr->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Value -->
                    <label class="text-xs uppercase font-semibold text-gray-500">
                        Value
                    </label>
                    <input
                        name="value"
                        type="text"
                        value="{{ $item->value ?? old('value') }}"
                        class="form-input text-lg font-semibold"
                        placeholder="Value"
                    />

                </div>
                {{-- Custom Fields --}}
                @if(isset($item))
                    @customFields($item)
                @else
                    @customFields(\App\Models\AttributeValue::class)
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
                </div>
            </div>

            <!-- IMAGE -->
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">Icon</h3>
                </div>

                <div class="p-4 space-y-4">

                    <div x-data="fileManager('{{ $item->icon ?? old('icon', '') }}', 'icon')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary btn-sm w-full">
                            Choose icon
                        </button>

                        <input type="hidden" name="icon" x-model="fileUrl">

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
            Save Attribute
        </button>
    </div>

</form>
</x-layout.default>