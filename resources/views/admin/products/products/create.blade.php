<x-layout.default>
    @section('title', $pageTitle)

    <form method="POST"
          action="{{ isset($item) ? route('product.update', $item->id) : route('product.store') }}"
          class="mt-4">
        @csrf
        @if(isset($item))
            @method('PUT')
            <input type="hidden" name="edit_id" value="{{ $item->id }}">
        @endif

        {{-- PAGE HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('product.list') }}" class="btn btn-outline-primary">← Back</a>
                <button type="submit" class="btn btn-primary">💾 Save Product</button>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ============================= --}}
            {{-- LEFT COLUMN (xl:col-span-2)  --}}
            {{-- ============================= --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- BASIC INFORMATION --}}
                <div class="panel border rounded-xl p-5 space-y-4 bg-white shadow-sm">
                    <h3 class="font-bold text-gray-700 border-b pb-2">📋 Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Product Name <span class="text-red-500">*</span></label>
                            <input name="name"
                                   value="{{ old('name', $item->name ?? '') }}"
                                   class="form-input @error('name') border-red-500 @enderror"
                                   required
                                   placeholder="Enter product name">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Slug</label>
                            <input name="slug"
                                   value="{{ old('slug', $item->slug ?? '') }}"
                                   class="form-input"
                                   placeholder="auto-generated-if-empty">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Tagline</label>
                        <input name="tagline"
                               value="{{ old('tagline', $item->tagline ?? '') }}"
                               class="form-input"
                               placeholder="Short catchy tagline">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">— Select Brand —</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $item->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- PRODUCT VARIATIONS --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm">
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">🎨 Product Variations</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50 p-3 rounded-lg border">
                        <div>
                            <label class="form-label">Attribute Type</label>
                            <select id="attr-type" class="form-select" onchange="loadValuesByType(this)">
                                <option value="">— Select Type —</option>
                                @foreach($attributes as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Attribute Value</label>
                            <select id="attr-value" class="form-select">
                                <option value="">— Select Value —</option>
                            </select>
                        </div>
                    </div>

                    <button type="button"
                            onclick="addVariation()"
                            class="btn btn-sm btn-secondary mb-4">
                        + Add Variation
                    </button>

                    <div id="variation-wrapper" class="space-y-3"></div>
                </div>
                {{-- QUANTITY DISCOUNTS --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm">
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">💰 Quantity Discounts</h3>
                    <p class="text-sm text-gray-500 mb-3">Offer bulk discounts when customers buy more quantity.</p>
                    <div id="qty-discount-wrapper" class="space-y-3"></div>
                    <button type="button"
                            onclick="addQtyDiscount()"
                            class="btn btn-sm btn-secondary mt-3">
                        + Add Quantity Discount
                    </button>
                </div>
                {{-- DESCRIPTIONS --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">📝 Description</h3>

                    <div>
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description"
                                  rows="2"
                                  class="form-input"
                                  placeholder="Brief product summary...">{{ old('short_description', $item->short_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Full Description</label>
                        <textarea name="description"
                                  rows="6"
                                  class="form-input"
                                  placeholder="Detailed product description...">{{ old('description', $item->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Usage Instructions</label>
                        <textarea name="usage_instructions"
                                  rows="3"
                                  class="form-input"
                                  placeholder="How to use this product...">{{ old('usage_instructions', $item->usage_instructions ?? '') }}</textarea>
                    </div>
                </div>

                {{-- PRODUCT FEATURES --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm"
                     x-data="productFeatureManager(@js($productFeatures), @js(old('features', $item->features ?? [])))">
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">⭐ Product Features</h3>

                    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_auto] gap-3 mb-4 bg-gray-50 p-3 rounded-lg border">
                        <div>
                            <label class="form-label">Feature Definition</label>
                            <select x-model="selectedFeatureId" class="form-select">
                                <option value="">— Select Feature —</option>
                                @forelse($productFeatures as $featureDefinition)
                                    <option value="{{ $featureDefinition->id }}">
                                        {{ $featureDefinition->title }}
                                    </option>
                                @empty
                                    <option value="" disabled>No product features found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button"
                                    @click="addSelectedFeature()"
                                    class="btn btn-secondary w-full md:w-auto">
                                + Add Feature
                            </button>
                        </div>
                    </div>

                    <template x-if="sections.length === 0">
                        <div class="border-2 border-dashed rounded-xl px-4 py-8 text-center text-sm text-gray-500">
                            No product feature content added yet.
                        </div>
                    </template>

                    <div class="space-y-5" x-show="sections.length > 0">
                        <template x-for="(section, sectionIndex) in sections" :key="section.uid">
                            <div class="border rounded-xl p-4 bg-gray-50 space-y-4">
                                <input type="hidden" :name="`features[${sectionIndex}][product_feature_id]`" :value="section.product_feature_id || ''">
                                <input type="hidden" :name="`features[${sectionIndex}][feature_title]`" :value="section.feature_title">
                                <input type="hidden" :name="`features[${sectionIndex}][feature_slug]`" :value="section.feature_slug">
                                <input type="hidden" :name="`features[${sectionIndex}][feature_sub_title]`" :value="section.feature_sub_title">
                                <input type="hidden" :name="`features[${sectionIndex}][feature_description]`" :value="section.feature_description">
                                <input type="hidden" :name="`features[${sectionIndex}][feature_image]`" :value="section.feature_image">
                                <input type="hidden" :name="`features[${sectionIndex}][feature_sort_order]`" :value="section.feature_sort_order">

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800" x-text="section.feature_title"></h4>
                                        <p class="text-sm text-gray-500" x-text="section.feature_sub_title || 'Add one or more content rows for this feature.'"></p>
                                        <template x-if="section.feature_slug">
                                            <p class="text-xs font-medium text-gray-400">Slug: <span x-text="section.feature_slug"></span></p>
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="addContent(sectionIndex)" class="btn btn-sm btn-primary">+ Add More</button>
                                        <button type="button" @click="removeSection(sectionIndex)" class="btn btn-sm btn-danger">Remove Feature</button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-show="section.feature_description || section.feature_image || section.feature_sort_order">
                                    <div class="md:col-span-2 space-y-2">
                                        <template x-if="section.feature_description">
                                            <p class="text-sm text-gray-600" x-text="section.feature_description"></p>
                                        </template>
                                        <template x-if="section.feature_sort_order !== null && section.feature_sort_order !== ''">
                                            <p class="text-xs font-medium text-gray-500">Sort Order: <span x-text="section.feature_sort_order"></span></p>
                                        </template>
                                    </div>
                                    <div>
                                        <template x-if="section.feature_image">
                                            <img :src="section.feature_image" class="h-24 w-full object-cover rounded border bg-white">
                                        </template>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <template x-for="(itemRow, itemIndex) in section.items" :key="itemRow.uid">
                                        <div class="border rounded-lg bg-white p-4 space-y-4">
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 text-xs font-medium">
                                                    <span x-text="section.feature_title"></span>
                                                    <span class="mx-1">•</span>
                                                    <span x-text="`Content ${itemIndex + 1}`"></span>
                                                </span>
                                                <button type="button" @click="removeContent(sectionIndex, itemIndex)" class="btn btn-sm btn-outline-danger">Remove</button>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="form-label">Content Title</label>
                                                    <input type="text"
                                                           class="form-input"
                                                           x-model="itemRow.title"
                                                           :name="`features[${sectionIndex}][items][${itemIndex}][title]`"
                                                           placeholder="Enter content title">
                                                </div>
                                                <div>
                                                    <label class="form-label">Sub Title</label>
                                                    <input type="text"
                                                           class="form-input"
                                                           x-model="itemRow.sub_title"
                                                           :name="`features[${sectionIndex}][items][${itemIndex}][sub_title]`"
                                                           placeholder="Enter content sub title">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="form-label">Description</label>
                                                <textarea rows="4"
                                                          class="form-input"
                                                          x-model="itemRow.description"
                                                          :name="`features[${sectionIndex}][items][${itemIndex}][description]`"
                                                          placeholder="Enter feature content description"></textarea>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="form-label">Sort Order</label>
                                                    <input type="number"
                                                           min="0"
                                                           class="form-input"
                                                           x-model="itemRow.sort_order"
                                                           :name="`features[${sectionIndex}][items][${itemIndex}][sort_order]`">
                                                </div>
                                                <div>
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select"
                                                            x-model="itemRow.status"
                                                            :name="`features[${sectionIndex}][items][${itemIndex}][status]`">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                                <div x-data="fileManager('', `feature_content_${section.uid}_${itemRow.uid}`)" x-init="
                                                    fileUrl = itemRow.image || '';
                                                    filePreview = itemRow.image || '';
                                                    window.addEventListener(callbackName, (e) => {
                                                        itemRow.image = e.detail.url || '';
                                                    });
                                                ">
                                                    <label class="form-label">Image</label>
                                                    <button type="button"
                                                            @click="$dispatch('open-file-manager', { callback: callbackName })"
                                                            class="btn btn-outline-primary w-full">
                                                        Choose Image
                                                    </button>
                                                    <input type="hidden"
                                                           x-model="itemRow.image"
                                                           :name="`features[${sectionIndex}][items][${itemIndex}][image]`">
                                                    <template x-if="itemRow.image">
                                                        <img :src="itemRow.image" class="mt-2 h-24 w-full object-cover rounded border">
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                

                

            </div>{{-- END LEFT COLUMN --}}

            {{-- ============================= --}}
            {{-- RIGHT COLUMN                 --}}
            {{-- ============================= --}}
            <div class="space-y-6">

                {{-- STATUS --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-3">
                    <h3 class="font-bold text-gray-700 border-b pb-2">🔘 Status</h3>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="1"
                                {{ old('status', $item->status ?? 1) == 1 ? 'checked' : '' }}>
                            <span class="text-green-600 font-medium">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="0"
                                {{ old('status', $item->status ?? 1) == 0 ? 'checked' : '' }}>
                            <span class="text-red-500 font-medium">Inactive</span>
                        </label>
                    </div>
                    <label class="flex items-center gap-2 mt-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured', $item->is_featured ?? 0) ? 'checked' : '' }}>
                        <span class="text-yellow-600 font-medium">⭐ Featured Product</span>
                    </label>
                </div>

                {{-- PRICING & STOCK --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">💵 Inventory & Pricing</h3>

                    <div>
                        <label class="form-label">Base Price</label>
                        <input type="number" step="0.01" min="0"
                               name="price"
                               value="{{ old('price', $item->price ?? '') }}"
                               class="form-input"
                               placeholder="0.00">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Stock</label>
                            <input type="number" min="0"
                                   name="stock"
                                   value="{{ old('stock', $item->stock ?? '') }}"
                                   class="form-input"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="form-label">Sold</label>
                            <input type="number" min="0"
                                   name="sold"
                                   value="{{ old('sold', $item->sold ?? 0) }}"
                                   class="form-input"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- TAX & DISCOUNT --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">🏷️ Tax & Discount</h3>

                    <div>
                        <label class="form-label">Discount</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" step="0.01" min="0"
                                   name="discount"
                                   value="{{ old('discount', $item->discount ?? '') }}"
                                   class="form-input"
                                   placeholder="0">
                            <select name="discount_type" class="form-select">
                                <option value="percent" {{ old('discount_type', $item->discount_type ?? '') == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                                <option value="flat" {{ old('discount_type', $item->discount_type ?? '') == 'flat' ? 'selected' : '' }}>Flat (৳)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Tax</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" step="0.01" min="0"
                                   name="tax"
                                   value="{{ old('tax', $item->tax ?? 0) }}"
                                   class="form-input"
                                   placeholder="5">
                            <select name="tax_type" class="form-select">
                                <option value="percent" {{ old('tax_type', $item->tax_type ?? 'percent') == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                                <option value="flat" {{ old('tax_type', $item->tax_type ?? '') == 'flat' ? 'selected' : '' }}>Flat (৳)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- CATEGORIES --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm">
                    <h3 class="font-bold text-gray-700 border-b pb-2">📁 Categories</h3>

                    <!-- Search Box -->
                    <input type="text"
                           id="category-search"
                           placeholder="🔍 Search categories..."
                           class="form-input mb-3"
                           onkeyup="filterCategories()">

                    <!-- Scrollable Checkbox List -->
                    <div id="category-list" class="border rounded-lg p-3 max-h-56 overflow-auto bg-white">
                        @php
                            $selectedCategoryIds = isset($item) && $item->categories
                                ? $item->categories->pluck('id')->toArray()
                                : [];
                        @endphp
                        @forelse($categories as $cat)
                            <label class="flex items-center gap-2 category-item p-1 hover:bg-gray-50 rounded">
                                <input
                                    type="checkbox"
                                    name="category_ids[]"
                                    value="{{ $cat->id }}"
                                    class="form-checkbox"
                                    data-name="{{ strtolower($cat->name) }}"
                                    @checked(in_array((int) $cat->id, $selectedCategoryIds, true))
                                >
                                <span>{{ $cat->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500">No category found</p>
                        @endforelse
                    </div>
                </div>

                <script>
                function filterCategories() {
                    const searchInput = document.getElementById('category-search');
                    const filter = searchInput.value.toLowerCase();
                    const categoryItems = document.querySelectorAll('.category-item');

                    categoryItems.forEach(function(item) {
                        const name = item.getAttribute('data-name') || item.textContent.toLowerCase();
                        if (name.includes(filter)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
                </script>

                {{-- MEDIA --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">🖼️ Media</h3>

                    {{-- Main Image --}}
                    <div x-data="fileManager('{{ $item->image ?? '' }}', 'image')">
                        <label class="form-label">Main Image</label>
                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Image
                        </button>
                        <input type="hidden" name="image" x-model="fileUrl">
                        <template x-if="filePreview">
                            <img :src="filePreview" class="mt-2 h-32 w-full object-cover rounded border">
                        </template>
                    </div>

                    {{-- Gallery --}}
                    <div x-data='galleryManager(@json(old("gallery", $item->gallery ?? [])))'>
                        <div class="flex items-center justify-between mb-2">
                            <label class="form-label">Gallery Images</label>
                            <button type="button"
                                    @click="openGalleryPicker()"
                                    class="btn btn-sm btn-outline-primary">
                                + Add Images
                            </button>
                        </div>

                        <template x-for="(url, index) in galleryList" :key="index">
                            <input type="hidden" name="gallery[]" :value="url">
                        </template>

                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="(url, index) in galleryList" :key="index">
                                <div class="relative group border rounded p-1 bg-white overflow-hidden">
                                    <img :src="url" class="h-20 w-full object-cover rounded">
                                    <button type="button"
                                            @click="removeImage(index)"
                                            class="absolute top-1 right-1 bg-red-500/95 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition z-10 shadow">
                                        ×
                                    </button>
                                </div>
                            </template>
                            <template x-if="galleryList.length === 0">
                                <div class="col-span-full py-6 border-2 border-dashed rounded text-center text-gray-400 text-sm">
                                    No gallery images
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Video --}}
                    <div x-data="fileManager('{{ $item->video_img ?? '' }}', 'video_img')">
                        <label class="form-label">Video Thumbnail</label>
                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Thumbnail
                        </button>
                        <input type="hidden" name="video_img" x-model="fileUrl">
                        <template x-if="filePreview">
                            <img :src="filePreview" class="mt-2 h-24 w-full object-cover rounded border">
                        </template>
                    </div>
                    <div>
                        <label class="form-label">Video URL</label>
                        <input name="video_link"
                               value="{{ old('video_link', $item->video_link ?? '') }}"
                               class="form-input"
                               placeholder="YouTube / Vimeo link">
                    </div>
                </div>

                {{-- SEO --}}
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-3">
                    <h3 class="font-bold text-gray-700 border-b pb-2">🔍 SEO Meta</h3>
                    <input name="meta_title"
                           value="{{ old('meta_title', $item->meta_title ?? '') }}"
                           class="form-input"
                           placeholder="Meta Title">
                    <textarea name="meta_description"
                              rows="3"
                              class="form-input"
                              placeholder="Meta Description">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
                    <input name="meta_keywords"
                           value="{{ old('meta_keywords', $item->meta_keywords ?? '') }}"
                           class="form-input"
                           placeholder="keyword1, keyword2, keyword3">
                </div>
                <div class="flex items-center gap-2">
                <a href="{{ route('product.list') }}" class="btn btn-outline-primary">← Back</a>
                <button type="submit" class="btn btn-primary">💾 Save Product</button>
            </div>

            </div>{{-- END RIGHT COLUMN --}}

        </div>{{-- END GRID --}}

    </form>

    {{-- ============================= --}}
    {{-- JAVASCRIPT                   --}}
    {{-- ============================= --}}
    <script>
        let vIndex = 0;
        let qIndex = 0;

        const attributeValues = @json($attributeValues);

        /*
        |------------------------------------------------------------------
        | Load attribute values by type
        |------------------------------------------------------------------
        */
        function loadValuesByType(select) {
            const valSelect = document.getElementById('attr-value');
            const typeId = parseInt(select.value);
            valSelect.innerHTML = '<option value="">— Select Value —</option>';

            attributeValues
                .filter(v => v.type_id === typeId)
                .forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v.id;
                    opt.textContent = v.value;
                    valSelect.appendChild(opt);
                });
        }

        /*
        |------------------------------------------------------------------
        | Add Variation Row
        |------------------------------------------------------------------
        */
        function addVariation(data = null) {
            const wrapper = document.getElementById('variation-wrapper');
            let valId, sku, price, stock, variationLabel;

            if (data) {
                valId = data.attribute_value_id ?? '';
                sku   = data.sku ?? '';
                price = data.price ?? '';
                stock = data.stock ?? '';
                const attributeName = data.attribute_value?.attribute?.name ?? 'Attribute';
                const valueName = data.attribute_value?.value ?? data.attribute_value?.name ?? `ID: ${valId}`;
                variationLabel = `${attributeName}: ${valueName}`;
            } else {
                const typeEl = document.getElementById('attr-type');
                const valEl  = document.getElementById('attr-value');

                if (!valEl.value) {
                    alert('Please select an attribute value first.');
                    return;
                }

                const typeName = typeEl.options[typeEl.selectedIndex].text;
                const valText  = valEl.options[valEl.selectedIndex].text;

                // Check duplicate
                const existing = wrapper.querySelector(`input[name$="[attribute_value_id]"][value="${valEl.value}"]`);
                if (existing) {
                    alert('This variation already exists!');
                    return;
                }

                valId = valEl.value;
                sku   = '';
                price = '';
                stock = '';
                variationLabel = `${typeName}: ${valText}`;
            }

            const html = `
                <div class="border rounded-lg p-3 bg-gray-50 relative grid grid-cols-1 md:grid-cols-4 gap-2 items-center variation-row">
                    <input type="hidden"
                           name="variations[${vIndex}][attribute_value_id]"
                           value="${escHtml(valId)}">
                    <div class="md:col-span-4">
                        <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 text-xs font-medium">
                            ${escHtml(variationLabel)}
                        </span>
                    </div>
                    <input type="text"
                           name="variations[${vIndex}][sku]"
                           value="${escHtml(sku)}"
                           placeholder="SKU"
                           class="form-input text-sm">
                    <input type="number"
                           name="variations[${vIndex}][price]"
                           value="${escHtml(price)}"
                           placeholder="Price"
                           min="0" step="0.01"
                           class="form-input text-sm">
                    <input type="number"
                           name="variations[${vIndex}][stock]"
                           value="${escHtml(stock)}"
                           placeholder="Stock"
                           min="0"
                           class="form-input text-sm">
                    <button type="button"
                            onclick="this.closest('.variation-row').remove()"
                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">
                        ×
                    </button>
                </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            vIndex++;
        }

        /*
        |------------------------------------------------------------------
        | Add Quantity Discount Row
        |------------------------------------------------------------------
        */
        function addQtyDiscount(data = null) {
            const wrapper = document.getElementById('qty-discount-wrapper');
            const minQty  = data?.min_qty ?? '';
            const disc    = data?.discount ?? '';
            const type    = data?.type ?? 'percent';

            const html = `
                <div class="flex gap-2 items-center border p-2 rounded bg-gray-50 qty-row">
                    <div class="flex-1">
                        <label class="text-xs text-gray-500">Min Qty</label>
                        <input type="number"
                               name="quantity_discounts[${qIndex}][min_qty]"
                               value="${escHtml(minQty)}"
                               placeholder="e.g. 5"
                               min="1"
                               class="form-input text-sm">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-gray-500">Discount</label>
                        <input type="number"
                               name="quantity_discounts[${qIndex}][discount]"
                               value="${escHtml(disc)}"
                               placeholder="e.g. 10"
                               min="0" step="0.01"
                               class="form-input text-sm">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-gray-500">Type</label>
                        <select name="quantity_discounts[${qIndex}][type]" class="form-select text-sm">
                            <option value="percent" ${type === 'percent' ? 'selected' : ''}>Percent (%)</option>
                            <option value="flat"    ${type === 'flat'    ? 'selected' : ''}>Flat (৳)</option>
                        </select>
                    </div>
                    <button type="button"
                            onclick="this.closest('.qty-row').remove()"
                            class="btn btn-danger btn-sm self-end shrink-0">
                        Remove
                    </button>
                </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
            qIndex++;
        }

        /*
        |------------------------------------------------------------------
        | XSS helper
        |------------------------------------------------------------------
        */
        function escHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        /*
        |------------------------------------------------------------------
        | On page load: restore existing data (edit mode)
        |------------------------------------------------------------------
        */
        window.addEventListener('load', function () {
            @if(isset($item))

                {{-- Restore Variations --}}
                @foreach($item->variations as $v)
                    addVariation(@json($v));
                @endforeach

                {{-- Restore Quantity Discounts --}}
                @php
                    $qtyDiscs = is_string($item->quantity_discounts)
                        ? json_decode($item->quantity_discounts, true)
                        : $item->quantity_discounts;
                @endphp
                @if(!empty($qtyDiscs))
                    @foreach($qtyDiscs as $qd)
                        addQtyDiscount(@json($qd));
                    @endforeach
                @endif

            @endif
        });

        /*
        |------------------------------------------------------------------
        | Alpine.js: Gallery Manager
        |------------------------------------------------------------------
        */
        document.addEventListener('alpine:init', () => {
            Alpine.data('galleryManager', (initialData) => ({
                galleryList: Array.isArray(initialData) ? initialData : [],

                init() {
                    this.galleryList = Array.isArray(this.galleryList) ? this.galleryList : [];

                    window.addEventListener('galleryCallback', (e) => {
                        const selectedImages = Array.isArray(e?.detail?.images)
                            ? e.detail.images
                            : (e?.detail?.url ? [e.detail.url] : []);

                        selectedImages.forEach((url) => {
                            if (url && !this.galleryList.includes(url)) {
                                this.galleryList.push(url);
                            }
                        });
                    });
                },

                openGalleryPicker() {
                    window.dispatchEvent(new CustomEvent('open-file-manager', {
                        detail: { callback: 'galleryCallback', multiple: true }
                    }));
                },

                removeImage(index) {
                    this.galleryList.splice(index, 1);
                }
            }));

            Alpine.data('productFeatureManager', (featureDefinitions, initialSections) => ({
                availableFeatures: Array.isArray(featureDefinitions) ? featureDefinitions : [],
                selectedFeatureId: '',
                sections: [],
                uidCounter: 0,

                init() {
                    this.sections = this.normalizeSections(initialSections);
                },

                nextUid(prefix = 'pf') {
                    this.uidCounter += 1;
                    return `${prefix}_${this.uidCounter}`;
                },

                normalizeSections(rawSections) {
                    if (!Array.isArray(rawSections)) {
                        return [];
                    }

                    return rawSections.map((section) => {
                        const legacyTitle = section?.title ?? '';
                        const legacyValue = section?.value ?? '';
                        const definition = this.availableFeatures.find((item) => String(item.id) === String(section?.product_feature_id ?? ''));
                        const items = Array.isArray(section?.items)
                            ? section.items
                            : (legacyTitle || legacyValue
                                ? [{
                                    title: legacyTitle,
                                    description: legacyValue,
                                    status: 1,
                                    sort_order: 0,
                                    image: '',
                                }]
                                : []);

                        return {
                            uid: this.nextUid('section'),
                            product_feature_id: section?.product_feature_id ?? definition?.id ?? '',
                            feature_title: section?.feature_title ?? definition?.title ?? legacyTitle ?? 'Feature',
                            feature_slug: section?.feature_slug ?? definition?.slug ?? '',
                            feature_sub_title: section?.feature_sub_title ?? definition?.sub_title ?? '',
                            feature_description: section?.feature_description ?? definition?.description ?? '',
                            feature_image: section?.feature_image ?? definition?.image ?? '',
                            feature_sort_order: section?.feature_sort_order ?? definition?.sort_order ?? 0,
                            items: items.length > 0
                                ? items.map((item) => this.normalizeItem(item))
                                : [this.createEmptyItem()],
                        };
                    });
                },

                normalizeItem(item = {}) {
                    return {
                        uid: this.nextUid('item'),
                        title: item?.title ?? '',
                        sub_title: item?.sub_title ?? '',
                        description: item?.description ?? item?.value ?? '',
                        image: item?.image ?? '',
                        sort_order: item?.sort_order ?? 0,
                        status: String(item?.status ?? 1),
                    };
                },

                createEmptyItem() {
                    return this.normalizeItem();
                },

                addSelectedFeature() {
                    const definition = this.availableFeatures.find((item) => String(item.id) === String(this.selectedFeatureId));

                    if (!definition) {
                        alert('Please select a feature definition first.');
                        return;
                    }

                    const existingIndex = this.sections.findIndex((section) => String(section.product_feature_id) === String(definition.id));

                    if (existingIndex !== -1) {
                        this.addContent(existingIndex);
                        this.selectedFeatureId = '';
                        return;
                    }

                    this.sections.push({
                        uid: this.nextUid('section'),
                        product_feature_id: definition.id,
                        feature_title: definition.title,
                        feature_slug: definition.slug,
                        feature_sub_title: definition.sub_title ?? '',
                        feature_description: definition.description ?? '',
                        feature_image: definition.image ?? '',
                        feature_sort_order: definition.sort_order ?? 0,
                        items: [this.createEmptyItem()],
                    });

                    this.selectedFeatureId = '';
                },

                addContent(sectionIndex) {
                    this.sections[sectionIndex].items.push(this.createEmptyItem());
                },

                removeSection(sectionIndex) {
                    this.sections.splice(sectionIndex, 1);
                },

                removeContent(sectionIndex, itemIndex) {
                    this.sections[sectionIndex].items.splice(itemIndex, 1);

                    if (this.sections[sectionIndex].items.length === 0) {
                        this.sections.splice(sectionIndex, 1);
                    }
                },
            }));

            /*
            |------------------------------------------------------------------
            | Alpine.js: File Manager (Single Image)
            |------------------------------------------------------------------
            */
            Alpine.data('fileManager', (initialUrl, fieldName) => ({
                fileUrl: initialUrl || '',
                filePreview: initialUrl || '',
                callbackName: `${fieldName}_callback`,

                init() {
                    // Listen for the callback event
                    window.addEventListener(this.callbackName, (e) => {
                        // Handle both direct URL and event detail format
                        let url = null;

                        if (typeof e === 'string') {
                            url = e;
                        } else if (e?.detail?.url) {
                            url = e.detail.url;
                        } else if (e?.url) {
                            url = e.url;
                        }

                        if (url) {
                            this.fileUrl = url;
                            this.filePreview = url;
                        }
                    });
                }
            }));
        });
    </script>

</x-layout.default>
