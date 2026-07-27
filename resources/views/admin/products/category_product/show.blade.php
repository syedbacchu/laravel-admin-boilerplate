<x-layout.default>
@php
    // Safe-fallback injection
    $category = $category ?? null;
    $products = $products ?? [];
@endphp

@section('title', 'Category Details — ' . ($category->name ?? ''))

<div class="mt-6 max-w-7xl mx-auto space-y-6 pb-16 px-4 sm:px-6">

    {{-- ── TOP ACTIONS BAR ─────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Category Products</h1>
                <p class="text-xs sm:text-sm text-gray-500">View category details and associated products</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('category-product.list') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 hover:text-gray-900 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>

            @if($category && $category->id)
            <a href="{{ route('product.category.edit', $category->id) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 hover:text-gray-900 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Category
            </a>
            @endif
        </div>
    </div>

    @if($category)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ══ LEFT COLUMN: CATEGORY INFO ════════════════════════════ --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- ── CATEGORY BASIC INFO ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span> Category Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">

                        @if($category->image)
                        <div class="flex justify-center">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}"
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                        @endif

                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Category Name</span>
                            <p class="mt-1 font-semibold text-gray-900 text-lg">{{ $category->name }}</p>
                        </div>

                        @if($category->slug)
                        <div>
                            <span class="text-xs font-medium text-gray-400">Slug</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $category->slug }}</p>
                        </div>
                        @endif

                        @if($category->parent)
                        <div>
                            <span class="text-xs font-medium text-gray-400">Parent Category</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $category->parent->name }}</p>
                        </div>
                        @endif

                        @if($category->description)
                        <div>
                            <span class="text-xs font-medium text-gray-400">Description</span>
                            <p class="mt-0.5 text-sm text-gray-700">{{ $category->description }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <span class="text-xs font-medium text-gray-400">Sort Order</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $category->sort_order ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Products</span>
                                <p class="mt-0.5 font-bold text-blue-600">{{ $category->products_count ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <span class="text-xs font-medium text-gray-400">Status</span>
                                <p class="mt-0.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Featured</span>
                                <p class="mt-0.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_featured ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-50 text-gray-700' }}">
                                        {{ $category->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if($category->meta_title || $category->meta_description)
                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            @if($category->meta_title)
                            <div>
                                <span class="text-xs font-medium text-gray-400">Meta Title</span>
                                <p class="mt-0.5 text-sm text-gray-700">{{ $category->meta_title }}</p>
                            </div>
                            @endif

                            @if($category->meta_description)
                            <div>
                                <span class="text-xs font-medium text-gray-400">Meta Description</span>
                                <p class="mt-0.5 text-sm text-gray-700">{{ $category->meta_description }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>

                {{-- ── ACTIONS CARD ── --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-3">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-50 pb-2">Quick Actions</h4>

                    @if($category && $category->id)
                    <a href="{{ route('product.category.edit', $category->id) }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Category
                    </a>
                    @endif

                    <a href="{{ route('product.create') }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700 transition hover:bg-green-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Product
                    </a>

                    <a href="{{ route('category-product.list') }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        All Categories
                    </a>
                </div>

            </div>

            {{-- ══ RIGHT COLUMN: PRODUCTS LIST ════════════════════════════ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ── PRODUCTS HEADER ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            Products in "{{ $category->name }}"
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold bg-blue-50 text-blue-700">
                                {{ count($products) }} Products
                            </span>
                            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition" onclick="toggleAddProductForm()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Product
                            </button>
                        </div>
                    </div>

                    {{-- ── ADD PRODUCT FORM ── --}}
                    <div id="addProductForm" class="hidden border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h4 class="font-semibold text-gray-800 mb-4">Add Product to Category</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Product Search --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Search Products</label>
                                <div class="relative">
                                    <input type="text" id="productSearch" placeholder="Type to search products..."
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <div id="searchResults" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                                </div>
                            </div>

                            {{-- Sort Order --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" id="sortOrder" value="0" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Selected Product Display --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Product</label>
                                <div id="selectedProduct" class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500">
                                    No product selected
                                </div>
                            </div>

                            {{-- Hidden fields --}}
                            <input type="hidden" id="selectedProductId" value="">

                            {{-- Actions --}}
                            <div class="md:col-span-2 flex items-center gap-2">
                                <button type="button" onclick="addProductToCategory()"
                                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                    Add Product
                                </button>
                                <button type="button" onclick="toggleAddProductForm()"
                                        class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ── PRODUCTS LIST ── --}}
                    <div class="p-6">
                        @if(count($products) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($products as $product)
                                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                                        <div class="flex items-start gap-4">
                                            {{-- Product Image --}}
                                            <div class="flex-shrink-0">
                                                @if($product['image'])
                                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                @else
                                                <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Product Info --}}
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 truncate">{{ $product['name'] }}</h4>

                                                @if($product['tagline'])
                                                <p class="text-xs text-gray-500 mt-1 truncate">{{ $product['tagline'] }}</p>
                                                @endif

                                                @if($product['price'])
                                                <div class="mt-2 flex items-center gap-2">
                                                    <span class="text-lg font-bold text-green-600">
                                                        {{ number_format($product['price'], 2) }}
                                                    </span>
                                                    @if($product['discount'])
                                                    <span class="text-xs text-red-500 line-through">
                                                        {{ number_format($product['price'] + $product['discount'], 2) }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @endif

                                                {{-- Product Meta --}}
                                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                                    @if($product['stock'] !== null)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $product['stock'] > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                                        {{ $product['stock'] > 0 ? 'Stock: ' . $product['stock'] : 'Out of Stock' }}
                                                    </span>
                                                    @endif

                                                    @if($product['is_featured'])
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                        Featured
                                                    </span>
                                                    @endif

                                                    @if($product['status'])
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">
                                                        Active
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Actions --}}
                                            <div class="flex-shrink-0 flex items-center gap-2">
                                                {{-- Sort Order Input --}}
                                                <div class="flex items-center gap-1">
                                                    <input type="number"
                                                           value="{{ $product['pivot']['sort_order'] ?? 0 }}"
                                                           min="0"
                                                           class="w-16 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                           onchange="updateProductSortOrder({{ $product['id'] }}, this.value)">
                                                    <span class="text-xs text-gray-500">Sort</span>
                                                </div>

                                                {{-- Edit Button --}}
                                                <a href="{{ route('product.edit', $product['id']) }}"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                                                   title="Edit Product">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>

                                                {{-- Remove Button --}}
                                                <button type="button"
                                                        onclick="removeProductFromCategory({{ $product['id'] }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition"
                                                        title="Remove from Category">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Categories --}}
                                        @if(isset($product['categories']) && count($product['categories']) > 0)
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($product['categories'] as $cat)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                    {{ $cat['name'] }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <h3 class="mt-2 text-base font-bold text-gray-900">No Products Found</h3>
                                <p class="mt-1 text-sm text-gray-500">This category doesn't have any products yet.</p>
                                <div class="mt-6">
                                    <a href="{{ route('product.create') }}" class="btn btn-primary">Add First Product</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    @else
        <div class="rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-base font-bold text-gray-900">Category Not Found</h3>
            <p class="mt-1 text-sm text-gray-500">The specified category was not found or has been deleted.</p>
            <div class="mt-6">
                <a href="{{ route('category-product.list') }}" class="btn btn-primary">Return to Category List</a>
            </div>
        </div>
    @endif

</div>

{{-- ── JAVASCRIPT ───────────────────────────────────────────────────── --}}
<script>
let categoryId = {{ $category->id ?? 0 }};
let searchTimeout;

// Toggle add product form
function toggleAddProductForm() {
    const form = document.getElementById('addProductForm');
    form.classList.toggle('hidden');
}

// Product search functionality
document.getElementById('productSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value;

    if (searchTerm.length < 2) {
        document.getElementById('searchResults').classList.add('hidden');
        return;
    }

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        searchProducts(searchTerm);
    }, 300);
});

function searchProducts(searchTerm) {
    fetch('{{ route('category-product.search-products') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            search: searchTerm,
            category_id: categoryId
        })
    })
    .then(response => response.json())
    .then(data => {
        displaySearchResults(data);
    })
    .catch(error => {
        console.error('Error searching products:', error);
    });
}

function displaySearchResults(data) {
    const resultsContainer = document.getElementById('searchResults');

    if (!data.success || !data.data || data.data.length === 0) {
        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No products found</div>';
        resultsContainer.classList.remove('hidden');
        return;
    }

    let html = '';
    data.data.forEach(product => {
        html += `
            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                 onclick="selectProduct(${product.id}, '${product.name}', '${product.image || ''}')">
                <div class="flex items-center gap-3">
                    ${product.image ?
                        `<img src="${product.image}" class="w-10 h-10 object-cover rounded">` :
                        `<div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>`
                    }
                    <div>
                        <div class="font-medium text-gray-900">${product.name}</div>
                        ${product.price ? `<div class="text-sm text-green-600">${parseFloat(product.price).toFixed(2)}</div>` : ''}
                    </div>
                </div>
            </div>
        `;
    });

    resultsContainer.innerHTML = html;
    resultsContainer.classList.remove('hidden');
}

function selectProduct(productId, productName, productImage) {
    document.getElementById('selectedProductId').value = productId;
    document.getElementById('selectedProduct').innerHTML = `
        <div class="flex items-center gap-2">
            ${productImage ?
                `<img src="${productImage}" class="w-8 h-8 object-cover rounded">` :
                `<div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>`
            }
            <span class="font-medium text-gray-900">${productName}</span>
        </div>
    `;
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('productSearch').value = '';
}

function addProductToCategory() {
    const productId = document.getElementById('selectedProductId').value;
    const sortOrder = document.getElementById('sortOrder').value;

    if (!productId) {
        alert('Please select a product first');
        return;
    }

    fetch('{{ route('category-product.add-product') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            category_id: categoryId,
            product_id: productId,
            sort_order: sortOrder
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Error adding product to category');
        }
    })
    .catch(error => {
        console.error('Error adding product:', error);
        alert('Error adding product to category');
    });
}

function removeProductFromCategory(productId) {
    if (!confirm('Are you sure you want to remove this product from the category?')) {
        return;
    }

    fetch('{{ route('category-product.remove-product') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            category_id: categoryId,
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Error removing product from category');
        }
    })
    .catch(error => {
        console.error('Error removing product:', error);
        alert('Error removing product from category');
    });
}

function updateProductSortOrder(productId, sortOrder) {
    fetch('{{ route('category-product.update-sort-order') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            category_id: categoryId,
            product_id: productId,
            sort_order: sortOrder
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            console.log('Sort order updated successfully');
        } else {
            alert(data.message || 'Error updating sort order');
        }
    })
    .catch(error => {
        console.error('Error updating sort order:', error);
        alert('Error updating sort order');
    });
}

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    const searchResults = document.getElementById('searchResults');
    const searchInput = document.getElementById('productSearch');

    if (!searchResults.contains(e.target) && e.target !== searchInput) {
        searchResults.classList.add('hidden');
    }
});
</script>

</x-layout.default>