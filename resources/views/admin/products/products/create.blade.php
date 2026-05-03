<x-layout.default>
@section('title', $pageTitle)

<form method="POST" action="{{ route('product.store') }}" class="mt-4">
    @csrf

    @if(isset($item))
        <input type="hidden" name="edit_id" value="{{ $item->id }}">
    @endif

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $pageTitle }}
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('product.list') }}" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="xl:col-span-2 space-y-6">
            <div class="panel border">

                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Product Information</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- NAME -->
                    <label>Name</label>
                    <input name="name" value="{{ $item->name ?? '' }}" class="form-input">

                    <!-- SLUG -->
                    <label>Slug</label>
                    <input name="slug" value="{{ $item->slug ?? '' }}" class="form-input">

                    <!-- TAGLINE -->
                    <label>Tagline</label>
                    <input name="tagline" value="{{ $item->tagline ?? '' }}" class="form-input">

                    <!-- CATEGORY -->
                    <div>
                        <label class="text-xs uppercase text-gray-500">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ ($item->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ATTRIBUTES -->
                    <div>
                        @php
                            $selectedAttributes = old('attributes', $item->attributes ?? []);
                            $selectedAttributes = is_string($selectedAttributes)
                                ? json_decode($selectedAttributes, true)
                                : $selectedAttributes;

                            $selectedAttributes = is_array($selectedAttributes)
                                ? $selectedAttributes
                                : [];
                        @endphp

                        <div>
                            <label class="text-xs uppercase text-gray-500">Attributes</label>

                            <select name="attributes[]" class="form-select" multiple>

                                @foreach($attributes as $attr)

                                    <option value="{{ $attr->id }}"
                                        {{ in_array($attr->id, $selectedAttributes) ? 'selected' : '' }}>
                                        {{ $attr->name }}
                                    </option>

                                @endforeach

                            </select>

                            <small class="text-gray-400">You can select multiple attributes</small>
                        </div>

                        <small class="text-gray-400">You can select multiple attributes</small>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg mb-3">Features</h3>

                        <div id="stat-wrapper">

                            @php
                                $features = old('features', $item->features ?? []);
                                $features = is_string($features) ? json_decode($features, true) : $features;
                                $features = is_array($features) ? $features : [];
                            @endphp

                            @foreach($features as $index => $feature)
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 border p-3 rounded">

                                    <!-- IMAGE -->
                                    <div x-data="fileManager('{{ $feature['icon'] ?? '' }}', 'feature_{{ $index }}')">

                                        <button type="button"
                                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700">
                                            Choose Image
                                        </button>

                                        <input type="hidden"
                                            name="features[{{ $index }}][icon]"
                                            x-model="fileUrl">

                                        <template x-if="filePreview">
                                            <img :src="filePreview" class="w-16 mt-2">
                                        </template>

                                    </div>

                                    <!-- TITLE -->
                                    <input type="text"
                                        name="features[{{ $index }}][title]"
                                        value="{{ $feature['title'] ?? '' }}"
                                        placeholder="Title"
                                        class="form-input">

                                    <!-- VALUE -->
                                    <input type="text"
                                        name="features[{{ $index }}][value]"
                                        value="{{ $feature['value'] ?? '' }}"
                                        placeholder="Value"
                                        class="form-input">

                                    <!-- REMOVE -->
                                    <button type="button"
                                            onclick="this.closest('.grid').remove()"
                                            class="btn btn-danger btn-sm">
                                        Remove
                                    </button>

                                </div>
                            @endforeach

                        </div>

                        <button type="button" onclick="addFeature()" class="btn btn-primary btn-sm">
                            + Add Feature
                        </button>
                    </div>
                    <!-- PRICE -->
                    <label>Price</label>
                    <input name="price" value="{{ $item->price ?? '' }}" class="form-input">

                    <!-- STOCK -->
                    <label>Stock</label>
                    <input name="stock" value="{{ $item->stock ?? '' }}" class="form-input">

                    <!-- SHORT DESC -->
                    <label>Short Description</label>
                    <textarea name="short_description" class="form-input">{{ $item->short_description ?? '' }}</textarea>

                    <!-- DESCRIPTION -->
                    <label>Description</label>
                    <textarea name="description" class="form-input">{{ $item->description ?? '' }}</textarea>

                    <!-- USAGE -->
                    <label>Usage Instructions</label>
                    <textarea name="usage_instructions" class="form-input">{{ $item->usage_instructions ?? '' }}</textarea>

                </div>

                {{-- Custom Fields --}}
                @if(isset($item))
                    @customFields($item)
                @else
                    @customFields(\App\Models\Product::class)
                @endif

            </div>

            <!-- PRICING -->
            <div class="panel border p-4 space-y-3">
                <h3>Pricing</h3>

                <input name="discount" value="{{ $item->discount ?? '' }}" class="form-input" placeholder="Discount">

                <select name="discount_type" class="form-select">
                    <option value="percent">Percent</option>
                    <option value="flat">Flat</option>
                </select>

                <input name="tax" value="{{ $item->tax ?? 0 }}" class="form-input">

                <select name="tax_type" class="form-select">
                    <option value="percent">Percent</option>
                    <option value="flat">Flat</option>
                </select>
            </div>

            <!-- SEO -->
            <div class="panel border p-4 space-y-3">
                <h3>SEO</h3>

                <input name="meta_title" value="{{ $item->meta_title ?? '' }}" class="form-input">

                <textarea name="meta_description" class="form-input">{{ $item->meta_description ?? '' }}</textarea>

                <input name="meta_keywords" value="{{ $item->meta_keywords ?? '' }}" class="form-input">
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">

            <!-- STATUS -->
            <div class="panel border p-4">
                <select name="status" class="form-select">
                    <option value="1" @selected(($item->status ?? 1)==1)>Active</option>
                    <option value="0" @selected(($item->status ?? 1)==0)>Inactive</option>
                </select>

                <select name="is_featured" class="form-select mt-2">
                    <option value="0">Not Featured</option>
                    <option value="1">Featured</option>
                </select>
            </div>

            <!-- IMAGE -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Image</h3>
                </div>

                <div class="p-4">
                    <div x-data="fileManager('{{ $item->image ?? old('image', '') }}', 'image')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Image
                        </button>

                        <input type="hidden" name="image" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="mt-3 rounded border w-full max-h-[160px]">
                        </template>

                    </div>
                </div>
            </div>

            <!-- GALLERY -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Gallery</h3>
                </div>

                <div class="p-4">
                    <div x-data="fileManager('{{ json_encode($item->gallery ?? []) }}', 'gallery', true)">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Gallery
                        </button>

                        <input type="hidden" name="gallery" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="mt-3 rounded border w-full max-h-[160px]">
                        </template>

                    </div>
                </div>
            </div>

            <!-- VIDEO IMAGE -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Video Image</h3>
                </div>

                <div class="p-4">
                    <div x-data="fileManager('{{ $item->video_img ?? old('video_img', '') }}', 'video_img')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Video Image
                        </button>

                        <input type="hidden" name="video_img" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="mt-3 rounded border w-full max-h-[160px]">
                        </template>

                    </div>
                </div>
            </div>

            <!-- VIDEO LINK -->
            <div class="panel border p-4">
                <input name="video_link" value="{{ $item->video_link ?? '' }}" class="form-input" placeholder="Video Link">
            </div>

        </div>

    </div>

</form>

<script>
let featureIndex = {{ count($features ?? []) }};

function addFeature() {
    const wrapper = document.getElementById('stat-wrapper');

    const html = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 border p-3 rounded">

            <div x-data="fileManager('', 'feature_${featureIndex}')">
                <button type="button"
                        @click="$dispatch('open-file-manager', { callback: callbackName })"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow">
                    Choose Image
                </button>

                <input type="hidden" name="features[${featureIndex}][icon]" x-model="fileUrl">

                <template x-if="filePreview">
                    <img :src="filePreview" class="w-16 mt-2">
                </template>
            </div>

            <input type="text" name="features[${featureIndex}][title]" placeholder="Title" class="form-input">

            <input type="text" name="features[${featureIndex}][value]" placeholder="Value" class="form-input">

            <button type="button" onclick="this.closest('.grid').remove()" class="btn btn-danger btn-sm">
                Remove
            </button>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    featureIndex++;
}
</script>

</x-layout.default>