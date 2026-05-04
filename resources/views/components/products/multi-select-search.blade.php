@props([
    'name' => 'category_ids[]',
    'label' => 'Categories',
    'options' => [],
    'selected' => [],
    'placeholder' => 'Search and select categories...',
    'searchable' => true,
])

<div class="multi-select-search" x-data="multiSelectSearch()">
    <label class="form-label">{{ $label }}</label>

    <!-- Search Input -->
    @if($searchable)
    <div class="relative mb-2">
        <input type="text"
               x-model="search"
               @input="filterOptions"
               placeholder="{{ $placeholder }}"
               class="form-input w-full pr-10">
        <svg class="absolute right-3 top-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    @endif

    <!-- Select Box -->
    <div class="border rounded-lg overflow-hidden max-h-60 overflow-y-auto bg-white">
        <select name="{{ $name }}"
                multiple
                class="form-select w-full border-0"
                size="{{ count($options) > 10 ? 10 : count($options) }}"
                x-ref="selectBox">
            @foreach($options as $option)
                <option value="{{ $option['id'] }}"
                        @if(in_array($option['id'], $selected)) selected @endif
                        data-search="{{ strtolower($option['name'] ?? '') }}">
                    {{ $option['name'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Selected Items Display -->
    <div class="mt-2 flex flex-wrap gap-2">
        <template x-for="option in selectedOptions" :key="option.value">
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                <span x-text="option.label"></span>
                <button type="button"
                        @click="deselectOption(option.value)"
                        class="hover:text-blue-600">×</button>
            </span>
        </template>
    </div>

    <!-- Helper Text -->
    <p class="text-xs text-gray-500 mt-1">
        💡 Hold <kbd class="px-1 py-0.5 bg-gray-100 rounded">Ctrl</kbd> (Windows) or <kbd class="px-1 py-0.5 bg-gray-100 rounded">Cmd</kbd> (Mac) to select multiple
    </p>
</div>

<script>
function multiSelectSearch() {
    return {
        search: '',
        selectBox: null,
        selectedOptions: [],

        init() {
            this.$nextTick(() => {
                this.selectBox = this.$refs.selectBox;
                this.updateSelectedOptions();
            });
        },

        filterOptions() {
            if (!this.selectBox) return;

            const searchLower = this.search.toLowerCase();
            const options = this.selectBox.options;

            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const searchText = option.getAttribute('data-search') || option.text.toLowerCase();

                if (searchText.includes(searchLower)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        },

        updateSelectedOptions() {
            if (!this.selectBox) return;

            this.selectedOptions = [];
            const options = this.selectBox.selectedOptions;

            for (let i = 0; i < options.length; i++) {
                this.selectedOptions.push({
                    value: options[i].value,
                    label: options[i].text
                });
            }
        },

        deselectOption(value) {
            if (!this.selectBox) return;

            const options = this.selectBox.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === value) {
                    options[i].selected = false;
                    break;
                }
            }

            this.updateSelectedOptions();
        }
    }
}
</script>

<style>
.multi-select-search select option {
    padding: 8px 12px;
}

.multi-select-search select option:hover {
    background-color: #f3f4f6;
}

.multi-select-search select option:checked {
    background-color: #3b82f6;
    color: white;
}
</style>
