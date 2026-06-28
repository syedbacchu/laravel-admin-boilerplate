<x-layout.default>
@php
    $lead = $lead ?? $data ?? null;
@endphp

@section('title', 'Dropshipping Lead Details — #' . ($lead->id ?? ''))

<div class="mt-6 max-w-6xl mx-auto space-y-6 pb-16 px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">
                Dropshipping Lead Details
            </h1>
            <p class="text-sm text-gray-500">Customer lead information overview</p>
        </div>
    </div>

    @if($lead)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- CUSTOMER --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-bold mb-4">Customer Information</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-400">Name</span>
                            <p class="font-semibold">{{ $lead->name }}</p>
                        </div>

                        <div>
                            <span class="text-xs text-gray-400">Phone</span>
                            <p class="font-medium">{{ $lead->phone }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="text-xs text-gray-400">Email</span>
                            <p>{{ $lead->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- PRODUCT --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-bold mb-4">Product Details</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-400">Product</span>
                            <p class="font-semibold">
                                {{ optional($lead->product)->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-xs text-gray-400">Product Range</span>
                            <p>{{ $lead->product_range ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- LOCATION --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-bold mb-4">Delivery Location</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-400">District</span>
                            <p>{{ $lead->district ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <span class="text-xs text-gray-400">Thana</span>
                            <p>{{ $lead->thana ?? 'N/A' }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="text-xs text-gray-400">Address</span>
                            <p>{{ $lead->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- NOTES --}}
                @if($lead->note)
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-bold mb-4">Note</h3>
                    <p class="text-sm text-gray-700">{{ $lead->note }}</p>
                </div>
                @endif

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                {{-- STATUS --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-bold mb-4">Status</h3>

                    @if($lead->status == 1)
                        <span class="text-yellow-600 font-bold">PENDING</span>
                    @elseif($lead->status == 2)
                        <span class="text-blue-600 font-bold">PROCESSING</span>
                    @elseif($lead->status == 3)
                        <span class="text-green-600 font-bold">COMPLETED</span>
                    @else
                        <span class="text-gray-500 font-bold">UNKNOWN</span>
                    @endif
                </div>

                {{-- META --}}
                <div class="bg-white rounded-xl border p-6 text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Lead ID</span>
                        <span>#{{ $lead->id }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-400">Created</span>
                        <span>{{ $lead->created_at }}</span>
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="text-center py-20 text-gray-500">
            Lead not found
        </div>
    @endif

</div>
</x-layout.default>