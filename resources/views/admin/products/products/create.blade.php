<x-layout.default>
    @section('title', $pageTitle)

    <form method="POST" action="{{ route('product.store') }}" class="mt-4">
        @csrf
        @if(isset($item))
            <input type="hidden" name="edit_id" value="{{ $item->id }}">
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('product.list') }}" class="btn btn-outline-primary">Back</a>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- LEFT SIDE -->
            <div class="xl:col-span-2 space-y-6">
                <div class="panel border p-5 space-y-4">
                    <h3 class="font-bold border-b pb-2">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Product Name</label>
                            <input name="name" value="{{ old('name', $item->name ?? '') }}" class="form-input" required>
                        </div>
                        <div>
                            <label>Slug</label>
                            <input name="slug" value="{{ old('slug', $item->slug ?? '') }}" class="form-input">
                        </div>
                    </div>

                    <div>
                        <label>Tagline</label>
                        <input name="tagline" value="{{ old('tagline', $item->tagline ?? '') }}" class="form-input">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (old('category_id', $item->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}" {{ (old('brand_id', $item->brand_id ?? '') == $brand->id) ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC VARIATIONS (ATTRIBUTES) -->
                <div class="panel border p-5">
                    <h3 class="font-bold border-b pb-2 mb-4">Product Variations</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50 p-3 rounded">
                        <div>
                            <label>Type</label>
                            <select id="attr-type" class="form-select" onchange="loadValuesByType(this)">
                                <option value="">Select Type</option>
                                @foreach($attributes as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Value</label>
                            <select id="attr-value" class="form-select">
                                <option value="">Select Value</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="addVariation()" class="btn btn-sm btn-secondary mb-4">+ Add Variation</button>
                    
                    <div id="variation-wrapper" class="space-y-3"></div>
                </div>

                <!-- FEATURES -->
                <div class="panel border p-5">
                    <h3 class="font-bold border-b pb-2 mb-4">Features</h3>
                    <div id="feature-wrapper" class="space-y-3"></div>
                    <button type="button" onclick="addNewFeature()" class="btn btn-sm btn-secondary mt-3">+ Add Feature</button>
                </div>

                <!-- DESCRIPTIONS -->
                <div class="panel border p-5 space-y-4">
                    <div>
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-input">{{ old('short_description', $item->short_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label>Full Description</label>
                        <textarea name="description" rows="5" class="form-input">{{ old('description', $item->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label>Usage Instructions</label>
                        <textarea name="usage_instructions" class="form-input">{{ old('usage_instructions', $item->usage_instructions ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="space-y-6">
                <!-- PRICING & STOCK -->
                <div class="panel border p-5 space-y-4">
                    <h3 class="font-bold border-b pb-2">Inventory & Pricing</h3>
                    <div>
                        <label>Base Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $item->price ?? '') }}" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label>Stock</label>
                            <input type="number" name="stock" value="{{ old('stock', $item->stock ?? '') }}" class="form-input">
                        </div>
                        <div>
                            <label>Sold</label>
                            <input type="number" name="sold" value="{{ old('sold', $item->sold ?? 0) }}" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- TAX & DISCOUNT -->
                <div class="panel border p-5 space-y-4">
                    <h3 class="font-bold border-b pb-2">Tax & Discount</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <input name="discount" value="{{ $item->discount ?? '' }}" class="form-input" placeholder="Discount">
                        <select name="discount_type" class="form-select">
                            <option value="percent" {{ ($item->discount_type ?? '') == 'percent' ? 'selected' : '' }}>Percent</option>
                            <option value="flat" {{ ($item->discount_type ?? '') == 'flat' ? 'selected' : '' }}>Flat</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <input name="tax" value="{{ $item->tax ?? 0 }}" class="form-input" placeholder="Tax">
                        <select name="tax_type" class="form-select">
                            <option value="percent" {{ ($item->tax_type ?? 'percent') == 'percent' ? 'selected' : '' }}>Percent</option>
                            <option value="flat" {{ ($item->tax_type ?? '') == 'flat' ? 'selected' : '' }}>Flat</option>
                        </select>
                    </div>
                </div>

                <!-- MEDIA -->
                <div class="panel border p-5 space-y-4">
                    <h3 class="font-bold border-b pb-2">Media</h3>
                    <!-- Main Image -->
                    <div x-data="fileManager('{{ $item->image ?? '' }}', 'image')">
                        <label>Main Image</label>
                        <button type="button" @click="$dispatch('open-file-manager', { callback: callbackName })" class="btn btn-outline-primary w-full">Choose Image</button>
                        <input type="hidden" name="image" x-model="fileUrl">
                        <template x-if="filePreview"><img :src="filePreview" class="mt-2 h-32 w-full object-cover rounded"></template>
                    </div>

                    <!-- PRODUCT GALLERY -->
                    <div class="panel border p-5">
                        <h3 class="font-bold border-b pb-2 mb-4">Product Gallery</h3>
                        <!-- ডাটা যদি স্ট্রিং হিসেবে থাকে তবে JSON.parse হবে, নাহলে সরাসরি অ্যারে -->
                        <div x-data="galleryManager({{ isset($item->gallery) ? (is_string($item->gallery) ? $item->gallery : json_encode($item->gallery)) : '[]' }})">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-semibold">Gallery Images</label>
                                <button type="button" 
                                        @click="$dispatch('open-file-manager', { callback: 'galleryCallback' })" 
                                        class="btn btn-sm btn-outline-primary">
                                    + Add Images
                                </button>
                            </div>

                            <!-- Hidden Inputs for Form Submission -->
                            <template x-for="(url, index) in galleryList" :key="index">
                                <input type="hidden" name="gallery[]" :value="url">
                            </template>

                            <!-- Preview Grid -->
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4">
                                <template x-for="(url, index) in galleryList" :key="index">
                                    <div class="relative group border rounded p-1 bg-white">
                                        <img :src="url" class="h-24 w-full object-cover rounded">
                                        <button type="button" 
                                                @click="removeImage(index)"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            ×
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Empty State -->
                                <template x-if="galleryList.length === 0">
                                    <div class="col-span-full py-8 border-2 border-dashed rounded text-center text-gray-400">
                                        No gallery images selected
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Video Section -->
                    <div x-data="fileManager('{{ $item->video_img ?? '' }}', 'video_img')">
                        <label>Video Thumbnail</label>
                        <button type="button" @click="$dispatch('open-file-manager', { callback: callbackName })" class="btn btn-outline-primary w-full">Choose Thumbnail</button>
                        <input type="hidden" name="video_img" x-model="fileUrl">
                        <template x-if="filePreview"><img :src="filePreview" class="mt-2 h-32 w-full object-cover rounded"></template>
                    </div>
                    <input name="video_link" value="{{ $item->video_link ?? '' }}" class="form-input" placeholder="Video URL (YouTube/Vimeo)">
                </div>

                <!-- SEO -->
                <div class="panel border p-5 space-y-3">
                    <h3 class="font-bold border-b pb-2">SEO Meta</h3>
                    <input name="meta_title" value="{{ $item->meta_title ?? '' }}" class="form-input" placeholder="Meta Title">
                    <textarea name="meta_description" class="form-input" placeholder="Meta Description">{{ $item->meta_description ?? '' }}</textarea>
                    <input name="meta_keywords" value="{{ $item->meta_keywords ?? '' }}" class="form-input" placeholder="Keywords (comma separated)">
                </div>
            </div>
        </div>
    </form>

    <script>
        let vIndex = 0;
        let fIndex = 0;
        const attributeValues = @json($attributeValues);

        function loadValuesByType(select) {
            const valSelect = document.getElementById('attr-value');
            valSelect.innerHTML = '<option value="">Select Value</option>';
            attributeValues.filter(v => v.type_id == select.value).forEach(v => {
                valSelect.innerHTML += `<option value="${v.id}">${v.value}</option>`;
            });
        }

        function addVariation(data = null) {
            const wrapper = document.getElementById('variation-wrapper');
            let name, valId, sku, price, stock;

            if (data) {
                name = data.name; valId = data.attribute_value_id; sku = data.sku; price = data.price; stock = data.stock;
            } else {
                const typeEl = document.getElementById('attr-type');
                const valEl = document.getElementById('attr-value');
                if (!valEl.value) return alert("Select value");
                name = `${typeEl.options[typeEl.selectedIndex].text} - ${valEl.options[valEl.selectedIndex].text}`;
                valId = valEl.value; sku = ''; price = ''; stock = '';
            }

            wrapper.insertAdjacentHTML('beforeend', `
                <div class="border p-3 rounded bg-white relative grid grid-cols-1 md:grid-cols-4 gap-2">
                    <input type="text" name="variations[${vIndex}][name]" value="${name}" class="form-input font-bold" readonly>
                    <input type="hidden" name="variations[${vIndex}][attribute_value_id]" value="${valId}">
                    <input type="text" name="variations[${vIndex}][sku]" value="${sku}" placeholder="SKU" class="form-input">
                    <input type="number" name="variations[${vIndex}][price]" value="${price}" placeholder="Price" class="form-input">
                    <input type="number" name="variations[${vIndex}][stock]" value="${stock}" placeholder="Stock" class="form-input">
                    <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5">×</button>
                </div>
            `);
            vIndex++;
        }

        function addNewFeature(data = null) {
            const wrapper = document.getElementById('feature-wrapper');
            const title = data ? data.title : '';
            const val = data ? data.value : '';
            const icon = data ? data.icon : '';

            wrapper.insertAdjacentHTML('beforeend', `
                <div class="flex gap-2 items-center border p-2 rounded">
                    <input type="text" name="features[${fIndex}][title]" value="${title}" placeholder="Feature Title" class="form-input">
                    <input type="text" name="features[${fIndex}][value]" value="${val}" placeholder="Value" class="form-input">
                    <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger btn-sm">Remove</button>
                </div>
            `);
            fIndex++;
        }

        window.onload = function() {
            @if(isset($item))
                @foreach($item->variations as $v) addVariation(@json($v)); @endforeach
                @php $feats = is_string($item->features) ? json_decode($item->features, true) : $item->features; @endphp
                @if($feats) @foreach($feats as $f) addNewFeature(@json($f)); @endforeach @endif
            @endif
        };

        document.addEventListener('alpine:init', () => {
    Alpine.data('galleryManager', (initialData) => ({
        galleryList: Array.isArray(initialData) ? initialData : [],

        init() {
            window.addEventListener('galleryCallback', (e) => {
                const data = e.detail;

                if (Array.isArray(data)) {
                    this.galleryList = [...this.galleryList, ...data];
                } else if (data && typeof data === 'string') {
                    this.galleryList.push(data);
                }
                
                this.galleryList = [...new Set(this.galleryList)];
            });
        },

        removeImage(index) {
            this.galleryList.splice(index, 1);
        }
    }));
});

window.galleryCallback = function(data) {
    window.dispatchEvent(new CustomEvent('galleryCallback', { detail: data }));
};

    </script>
</x-layout.default>