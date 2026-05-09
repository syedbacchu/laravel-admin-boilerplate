<x-layout.default>
@section('title', $pageTitle)

@vite(['resources/css/quill.snow.css'])
<script src="{{ asset('assets/js/quill.js') }}"></script>

<form
    id="about-company-form"
    method="POST"
    action="{{ route('about-company.update') }}"
    class="mt-4"
>
    @csrf
    <input type="hidden" name="site_type" value="{{ $selectedSiteType }}">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ $pageTitle }}
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </div>

    <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b] mb-6">
        <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
            <h3 class="font-semibold text-base">{{ __('Site Type') }}</h3>
        </div>
        <div class="p-4">
            <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Select Site Type') }}</label>
            <select class="form-select mt-1 w-full" onchange="window.location=this.options[this.selectedIndex].dataset.url">
                @foreach($siteTypes as $value => $label)
                    <option value="{{ $value }}"
                            data-url="{{ route('about-company.edit', ['site_type' => $value]) }}"
                            @selected((int) $selectedSiteType === (int) $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="space-y-6">

        <!-- Hero Section -->
        <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                <h3 class="font-semibold text-base">{{ __('Hero Section') }}</h3>
            </div>
            <div class="p-4 space-y-4">
                <div x-data="fileManager('{{ $data->banner_image ?? '' }}', 'banner_image')" class="space-y-2">
                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Banner Image') }}</label>
                    <button type="button"
                            x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                            class="btn btn-outline-primary btn-sm w-full">
                        {{ __('Choose Banner Image') }}
                    </button>
                    <input type="hidden" name="banner_image" x-model="fileUrl">
                    <template x-if="filePreview">
                        <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[200px]">
                    </template>
                </div>

                <div>
                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ $data->title ?? '' }}" class="form-input mt-1 w-full">
                </div>

                <div>
                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Subtitle') }}</label>
                    <textarea name="subtitle" rows="2" class="form-textarea mt-1 w-full">{{ $data->subtitle ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Our Story Section -->
        <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                <h3 class="font-semibold text-base">{{ __('Our Story') }}</h3>
            </div>
            <div class="p-4 space-y-4">
                <div x-data="fileManager('{{ $data->story_image ?? '' }}', 'story_image')" class="space-y-2">
                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Story Image') }}</label>
                    <button type="button"
                            x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                            class="btn btn-outline-primary btn-sm w-full">
                        {{ __('Choose Story Image') }}
                    </button>
                    <input type="hidden" name="story_image" x-model="fileUrl">
                    <template x-if="filePreview">
                        <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[200px]">
                    </template>
                </div>

                <div>
                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Our Story') }}</label>
                    <div x-data="quillEditor('our_story', @js($data->our_story ?? ''))" class="quill-editor-wrapper">
                        <input type="hidden" name="our_story" x-model="content">
                        <div x-ref="editor"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Mission') }}</h3>
                </div>
                <div class="p-4">
                    <div x-data="quillEditor('mission', @js($data->mission ?? ''))" class="quill-editor-wrapper">
                        <input type="hidden" name="mission" x-model="content">
                        <div x-ref="editor"></div>
                    </div>
                </div>
            </div>

            <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
                <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                    <h3 class="font-semibold text-base">{{ __('Vision') }}</h3>
                </div>
                <div class="p-4">
                    <div x-data="quillEditor('vision', @js($data->vision ?? ''))" class="quill-editor-wrapper">
                        <input type="hidden" name="vision" x-model="content">
                        <div x-ref="editor"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Values -->
        <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                <h3 class="font-semibold text-base">{{ __('Core Values') }}</h3>
            </div>
            <div class="p-4">
                <div x-data="repeaterField('core_values', @js($data->core_values ?? []))">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-medium" x-text="'Item ' + (index + 1)"></span>
                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div x-data="fileManager(item.image, 'core_values_' + index)" class="space-y-2">
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Image') }}</label>
                                <button type="button"
                                        x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                                        class="btn btn-outline-primary btn-sm w-full">
                                    {{ __('Choose Image') }}
                                </button>
                                <input type="hidden" :name="'core_values[' + index + '][image]'" x-model="fileUrl">
                                <template x-if="filePreview">
                                    <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[150px]">
                                </template>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Title') }}</label>
                                    <input type="text" :name="'core_values[' + index + '][title]'" x-model="item.title" class="form-input mt-1">
                                </div>
                                <div>
                                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Sort Order') }}</label>
                                    <input type="number" :name="'core_values[' + index + '][sort_order]'" x-model="item.sort_order" class="form-input mt-1">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Subtitle') }}</label>
                                <input type="text" :name="'core_values[' + index + '][subtitle]'" x-model="item.subtitle" class="form-input mt-1">
                            </div>

                            <div>
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Description') }}</label>
                                <textarea :name="'core_values[' + index + '][description]'" x-model="item.description" rows="3" class="form-textarea mt-1"></textarea>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="btn btn-outline-primary btn-sm w-full">
                        {{ __('Add Core Value') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Company Stats -->
        <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                <h3 class="font-semibold text-base">{{ __('Company Stats') }}</h3>
            </div>
            <div class="p-4">
                <div x-data="repeaterField('company_stats', @js($data->company_stats ?? []))">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-medium" x-text="'Item ' + (index + 1)"></span>
                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div x-data="fileManager(item.image, 'company_stats_' + index)" class="space-y-2">
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Image') }}</label>
                                <button type="button"
                                        x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                                        class="btn btn-outline-primary btn-sm w-full">
                                    {{ __('Choose Image') }}
                                </button>
                                <input type="hidden" :name="'company_stats[' + index + '][image]'" x-model="fileUrl">
                                <template x-if="filePreview">
                                    <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[150px]">
                                </template>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Title') }}</label>
                                    <input type="text" :name="'company_stats[' + index + '][title]'" x-model="item.title" class="form-input mt-1">
                                </div>
                                <div>
                                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Sort Order') }}</label>
                                    <input type="number" :name="'company_stats[' + index + '][sort_order]'" x-model="item.sort_order" class="form-input mt-1">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Subtitle') }}</label>
                                <input type="text" :name="'company_stats[' + index + '][subtitle]'" x-model="item.subtitle" class="form-input mt-1">
                            </div>

                            <div>
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Description') }}</label>
                                <textarea :name="'company_stats[' + index + '][description]'" x-model="item.description" rows="3" class="form-textarea mt-1"></textarea>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="btn btn-outline-primary btn-sm w-full">
                        {{ __('Add Company Stat') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="panel !p-0 overflow-hidden border border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="px-4 py-3 border-b border-[#e0e6ed] dark:border-[#1b2e4b] bg-[#fafafa] dark:bg-[#0b1320]">
                <h3 class="font-semibold text-base">{{ __('Why Choose Us') }}</h3>
            </div>
            <div class="p-4">
                <div x-data="repeaterField('why_choose', @js($data->why_choose ?? []))">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-medium" x-text="'Item ' + (index + 1)"></span>
                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div x-data="fileManager(item.image, 'why_choose_' + index)" class="space-y-2">
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Image') }}</label>
                                <button type="button"
                                        x-on:click="$dispatch('open-file-manager', { callback: callbackName })"
                                        class="btn btn-outline-primary btn-sm w-full">
                                    {{ __('Choose Image') }}
                                </button>
                                <input type="hidden" :name="'why_choose[' + index + '][image]'" x-model="fileUrl">
                                <template x-if="filePreview">
                                    <img :src="filePreview" class="rounded-lg border object-cover w-full max-h-[150px]">
                                </template>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Title') }}</label>
                                    <input type="text" :name="'why_choose[' + index + '][title]'" x-model="item.title" class="form-input mt-1">
                                </div>
                                <div>
                                    <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Sort Order') }}</label>
                                    <input type="number" :name="'why_choose[' + index + '][sort_order]'" x-model="item.sort_order" class="form-input mt-1">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Subtitle') }}</label>
                                <input type="text" :name="'why_choose[' + index + '][subtitle]'" x-model="item.subtitle" class="form-input mt-1">
                            </div>

                            <div>
                                <label class="text-xs uppercase font-semibold text-gray-500">{{ __('Description') }}</label>
                                <textarea :name="'why_choose[' + index + '][description]'" x-model="item.description" rows="3" class="form-textarea mt-1"></textarea>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="btn btn-outline-primary btn-sm w-full">
                        {{ __('Add Why Choose Item') }}
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
</form>

<style>
    .quill-editor-wrapper .ql-toolbar {
        border-radius: 8px 8px 0 0;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .quill-editor-wrapper .ql-container {
        border-radius: 0 0 8px 8px;
        border: 1px solid #e2e8f0;
        min-height: 250px;
        font-size: 14px;
    }
</style>

<script>
function quillEditor(fieldName, initialContent) {
    return {
        fieldName: fieldName,
        content: initialContent || '',
        quill: null,

        init() {
            const toolbarOptions = [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ];

            this.quill = new Quill(this.$refs.editor, {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow',
                placeholder: 'Start typing...'
            });

            if (this.content) {
                this.quill.root.innerHTML = this.content;
            }

            this.quill.on('text-change', () => {
                this.content = this.quill.root.innerHTML;
            });
        }
    };
}

function repeaterField(fieldName, initialItems) {
    return {
        fieldName: fieldName,
        items: initialItems || [],

        addItem() {
            this.items.push({
                image: '',
                title: '',
                subtitle: '',
                description: '',
                sort_order: this.items.length
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        }
    };
}
</script>

</x-layout.default>
