<x-layout.default>
@section('title', $pageTitle)

<link rel="stylesheet" href="{{ asset('assets/common/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('assets/common/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('assets/common/datatables/jquery.dataTables.min.js') }}"></script>

<div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <h5 class="text-2xl font-bold text-gray-800">
            {{ $pageTitle ?? __('Battery Water Lead List') }}
        </h5>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <x-common.datatable
            id="batteryWaterLeadTable"
            ajax="{{ route('battery.water.lead.list') }}"
            :columns="[
                ['data' => 'id', 'name' => 'id', 'title' => '#'],
                ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
                ['data' => 'district', 'name' => 'district', 'title' => 'District'],
                ['data' => 'bottle_size', 'name' => 'bottle_size', 'title' => 'Bottle Size'],
                ['data' => 'quantity', 'name' => 'quantity', 'title' => 'Qty'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Submitted'],
                [
                    'data' => 'actions',
                    'title' => 'Actions',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]"
            :enableSearch="true"
        />
    </div>
</div>

</x-layout.default>