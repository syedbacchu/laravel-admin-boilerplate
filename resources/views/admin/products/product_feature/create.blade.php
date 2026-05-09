<x-layout.default>
    @section('title', $pageTitle)

    <form method="POST"
          action="{{ ($function_type ?? 'create') === 'update' ? route('product.feature.update', $item->id) : route('product.feature.store') }}"
          class="mt-4">
        @csrf
        @if(($function_type ?? 'create') === 'update')
            @method('PUT')
            <input type="hidden" name="edit_id" value="{{ $item->id }}">
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('product.feature.list') }}" class="btn btn-outline-primary">Back</a>
                <button type="submit" class="btn btn-primary">{{ ($function_type ?? 'create') === 'update' ? 'Update Product Feature' : 'Save Product Feature' }}</button>
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
            <div class="xl:col-span-2 space-y-6">
                <div class="panel border rounded-xl p-5 space-y-4 bg-white shadow-sm">
                    <h3 class="font-bold text-gray-700 border-b pb-2">Product Feature Information</h3>

                    <div>
                        <label class="form-label">Title <span class="text-red-500">*</span></label>
                        <input name="title"
                               data-slug-source
                               value="{{ old('title', $item->title ?? '') }}"
                               class="form-input @error('title') border-red-500 @enderror"
                               required
                               placeholder="Enter feature title">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Slug</label>
                            <input name="slug"
                                   data-slug-target
                                   value="{{ old('slug', $item->slug ?? '') }}"
                                   class="form-input"
                                   placeholder="auto-generated-if-empty">
                        </div>
                        <div>
                            <label class="form-label">Sub Title</label>
                            <input name="sub_title"
                                   value="{{ old('sub_title', $item->sub_title ?? '') }}"
                                   class="form-input"
                                   placeholder="Optional short sub title">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  rows="6"
                                  class="form-input"
                                  placeholder="Feature description">{{ old('description', $item->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">Publish</h3>

                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" @selected(old('status', $item->status ?? 1) == 1)>Active</option>
                            <option value="0" @selected(old('status', $item->status ?? 1) == 0)>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Sort Order</label>
                        <input type="number"
                               min="0"
                               name="sort_order"
                               value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                               class="form-input">
                    </div>
                </div>

                <div class="panel border rounded-xl p-5 bg-white shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">Image</h3>

                    <div x-data="fileManager('{{ old('image', $item->image ?? '') }}', 'product_feature_image')">
                        <label class="form-label">Image</label>
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
                </div>
            </div>
        </div>
    </form>

    <script src="{{ asset('assets/js/slugify.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initializeSlugify();
        });
    </script>
</x-layout.default>
