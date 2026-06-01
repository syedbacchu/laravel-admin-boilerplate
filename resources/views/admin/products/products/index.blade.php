<x-layout.default>
@section('title', $pageTitle)

<link rel="stylesheet" href="{{ asset('assets/common/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('assets/common/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('assets/common/datatables/jquery.dataTables.min.js') }}"></script>

<div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <h5 class="text-2xl font-bold text-gray-800">
            {{ $pageTitle ?? __('Product List') }}
        </h5>

        <a href="{{ route('product.create') }}"
           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>

            {{ __('Create Product') }}
        </a>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <x-common.datatable
            id="itemsTable"
            ajax="{{ route('product.list') }}"
            :columns="[
                ['data' => 'image', 'name' => 'image', 'title' => 'Image', 'orderable' => false],
                ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                ['data' => 'category', 'name' => 'category_id', 'title' => 'Category'],
                ['data' => 'slug', 'name' => 'slug', 'title' => 'Slug'],
                ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
                ['data' => 'stock', 'name' => 'stock', 'title' => 'Stock'],
                ['data' => 'status_toggle', 'name' => 'status', 'title' => 'Status', 'orderable' => false],
                ['data' => 'is_featured_toggle', 'name' => 'is_featured', 'title' => 'Featured', 'orderable' => false, 'searchable' => false],

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
