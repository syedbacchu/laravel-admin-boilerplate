<x-layout.default>
@section('title', $pageTitle)

<link rel="stylesheet" href="{{ asset('assets/common/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('assets/common/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('assets/common/datatables/jquery.dataTables.min.js') }}"></script>

<div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <h5 class="text-2xl font-bold text-gray-800">
            {{ $pageTitle ?? __('Lead List') }}
        </h5>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <x-common.datatable
            id="leadsTable"
            ajax="{{ route('collect.lead.list') }}" {{-- FIXED: Name pointed to route profile --}}
            :columns="[
                ['data' => 'id',           'name' => 'id',           'title' => '#'],
                ['data' => 'full_name',    'name' => 'full_name',    'title' => 'Name'],
                ['data' => 'company_name', 'name' => 'company_name', 'title' => 'Company'],
                ['data' => 'phone',        'name' => 'phone',        'title' => 'Phone'],
                ['data' => 'district',     'name' => 'district',     'title' => 'District'],
                ['data' => 'type_label',   'name' => 'type_label',   'title' => 'Type', 'orderable' => false],
                // ['data' => 'status_badge', 'name' => 'status',       'title' => 'Status', 'orderable' => false],
                ['data' => 'created_at',   'name' => 'created_at',   'title' => 'Submitted'],
                [
                    'data'       => 'actions',
                    'title'      => 'Actions',
                    'orderable'  => false,
                    'searchable' => false,
                ],
            ]"

            {{-- :filters="[
                [
                    'type'    => 'select',
                    'name'    => 'status',
                    'label'   => 'Status',
                    'options' => [
                        ''         => 'All',
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ],
                ],
                [
                    'type'    => 'select',
                    'name'    => 'type',
                    'label'   => 'Lead Type',
                    'options' => [
                        ''         => 'All',
                        'customer' => 'Customer',
                        'company'  => 'Company',
                    ],
                ],
            ]" --}}

            :enableSearch="true"
        />
    </div>
</div>

</x-layout.default>