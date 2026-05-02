<x-layout.default>
@section('title', $pageTitle)

<form method="POST" action="{{ route('product.category.store') }}" class="mt-4">
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
            <a href="{{ route('product.category.list') }}" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-primary">Save Category</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="xl:col-span-2 space-y-6">
            <div class="panel border">

                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Category Information</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- NAME -->
                    <label class="text-xs uppercase text-gray-500">Name<span class="text-red-500">*</span></label>
                    <input name="name"
                           value="{{ $item->name ?? old('name') }}"
                           class="form-input"
                           placeholder="Category Name">

                    <!-- SLUG -->
                    <label class="text-xs uppercase text-gray-500">Slug</label>
                    <input name="slug"
                           value="{{ $item->slug ?? old('slug') }}"
                           class="form-input"
                           placeholder="auto-generate-slug">

                    <!-- PARENT CATEGORY -->
                    <label class="text-xs uppercase text-gray-500">Parent Category</label>
                    <select name="parent_id" class="form-select">
                        <option value="">None</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('parent_id', $item->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- META TITLE -->
                    <label class="text-xs uppercase text-gray-500">Meta Title</label>
                    <input name="meta_title"
                           value="{{ $item->meta_title ?? old('meta_title') }}"
                           class="form-input">

                    <!-- META DESCRIPTION -->
                    <label class="text-xs uppercase text-gray-500">Meta Description</label>
                    <textarea name="meta_description"
                              class="form-input"
                              rows="3">{{ $item->meta_description ?? old('meta_description') }}</textarea>

                </div>

                {{-- Custom Fields --}}
                @if(isset($item))
                    @customFields($item)
                @else
                    @customFields(\App\Models\ProductCategory::class)
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
                    <select name="status" class="form-select">
                        <option value="1" @selected(($item->status ?? 1)==1)>Active</option>
                        <option value="0" @selected(($item->status ?? 1)==0)>Inactive</option>
                    </select>

                    <!-- SORT ORDER -->
                    <label class="text-xs uppercase text-gray-500">Sort Order</label>
                    <input type="number"
                           name="sort_order"
                           value="{{ $item->sort_order ?? old('sort_order', 0) }}"
                           class="form-input">

                </div>
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

            <!-- COVER IMAGE -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Cover Image</h3>
                </div>

                <div class="p-4">
                    <div x-data="fileManager('{{ $item->cover_image ?? old('cover_image', '') }}', 'cover_image')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Cover Image
                        </button>

                        <input type="hidden" name="cover_image" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="mt-3 rounded border w-full max-h-[160px]">
                        </template>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="btn btn-primary">
            Save Category
        </button>
    </div>

</form>
</x-layout.default>