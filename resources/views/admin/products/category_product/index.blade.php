<x-layout.default>
@section('title', $pageTitle)

<link rel="stylesheet" href="{{ asset('assets/common/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('assets/common/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('assets/common/datatables/jquery.dataTables.min.js') }}"></script>

<div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <h5 class="text-2xl font-bold text-gray-800">
            {{ $pageTitle ?? __('Category Product List') }}
        </h5>

        <div class="flex items-center gap-3">
            <a href="{{ route('product.category.list') }}"
               class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>

                {{ __('Manage Categories') }}
            </a>

            <a href="{{ route('product.list') }}"
               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>

                {{ __('All Products') }}
            </a>
        </div>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <x-common.datatable
            id="categoryProductsTable"
            ajax="{{ route('category-product.list') }}"
            :columns="[
                ['data' => 'image', 'name' => 'image', 'title' => 'Image', 'orderable' => false],
                ['data' => 'name', 'name' => 'name', 'title' => 'Category Name'],
                ['data' => 'slug', 'name' => 'slug', 'title' => 'Slug'],
                ['data' => 'parent', 'name' => 'parent_id', 'title' => 'Parent Category'],
                ['data' => 'product_count', 'name' => 'product_count', 'title' => 'Products', 'orderable' => false, 'searchable' => false],
                ['data' => 'status_toggle', 'name' => 'status', 'title' => 'Status', 'orderable' => false],
                [
                    'data' => 'actions',
                    'title' => 'Actions',
                    'orderable' => false,
                    'searchable' => false
                ],
            ]"

            :filters="[
                [
                    'type' => 'select',
                    'name' => 'status',
                    'label' => 'Active Status',
                    'options' => [
                        '' => 'All',
                        1 => 'Active',
                        0 => 'Inactive',
                    ]
                ],
            ]"

            :enableSearch="true"
        />
    </div>
</div>

</x-layout.default>