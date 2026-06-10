<x-layout.default>
@php
    // Safe-fallback injection if ResponseService unpacked it as $data
    $lead = $lead ?? $data ?? null;
@endphp

@section('title', 'Battery Water Lead Details — #' . ($lead->id ?? ''))

<div class="mt-6 max-w-6xl mx-auto space-y-6 pb-16 px-4 sm:px-6">

    {{-- ── TOP ACTIONS BAR ─────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/xl" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Battery Water Lead Details</h1>
                <p class="text-xs sm:text-sm text-gray-500">Manage and view battery water customer requests</p>
            </div>
        </div>
    </div>

    @if($lead)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            {{-- ══ LEFT / MAIN COLUMN: LEAD DATA ════════════════════════════ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ── PERSONAL & CONTACT INFORMATION ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span> Customer Information
                        </h3>
                    </div>
                    <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-1 sm:grid-cols-2">
                        <div>
                            <span class="text-xs font-medium text-gray-400">Full Name</span>
                            <p class="mt-0.5 font-semibold text-gray-900 text-lg">{{ $lead->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Phone Number</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->phone }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-xs font-medium text-gray-400">Email Address</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- ── ORDER & PRODUCT DETAILS ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span> Requirement & Order Details
                        </h3>
                    </div>
                    <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-2 sm:grid-cols-3">
                        <div>
                            <span class="text-xs font-medium text-gray-400">Bottle Size</span>
                            <p class="mt-0.5 font-semibold text-gray-900">{{ $lead->bottle_size ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Quantity</span>
                            <p class="mt-0.5 font-semibold text-gray-900">{{ number_format($lead->quantity) }} Pcs</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Unit Price</span>
                            <p class="mt-0.5 font-semibold text-gray-900">{{ number_format($lead->unit_price, 2) }} BDT</p>
                        </div>
                    </div>
                </div>

                {{-- ── LOCATION DETAILS ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-indigo-500"></span> Delivery Location
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <span class="text-xs font-medium text-gray-400">District</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->district ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Thana</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->thana ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Full Address</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- ── REMARKS & NOTES ── --}}
                @if($lead->note || $lead->admin_note)
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-teal-500"></span> Remarks & Notes
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($lead->note)
                        <div>
                            <span class="text-xs font-medium text-gray-400">Customer Note</span>
                            <p class="mt-0.5 text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $lead->note }}</p>
                        </div>
                        @endif

                        @if($lead->admin_note)
                        <div>
                            <span class="text-xs font-medium text-red-400 block">Internal Admin Note</span>
                            <p class="mt-0.5 text-sm text-red-700 bg-red-50/50 p-3 rounded-lg border border-red-100/50">{{ $lead->admin_note }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            {{-- ══ RIGHT COLUMN: SIDEBAR (METRICS, STATUS & ACTIONS) ═════════ --}}
            <div class="space-y-6">
                
                {{-- META BRIEF CARD --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Order Status</span>
                        <div class="mt-1">
                            @if($lead->status == 1)
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> PENDING
                                </span>
                            @elseif($lead->status == 2)
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> PROCESSING
                                </span>
                            @elseif($lead->status == 3)
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> COMPLETED
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold bg-gray-50 text-gray-700 border border-gray-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> UNKNOWN ({{ $lead->status }})
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-3 space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Lead ID:</span>
                            <span class="font-mono font-bold text-gray-900">#{{ $lead->id ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Created Date:</span>
                            <span class="font-medium text-gray-800">{{ isset($lead->created_at) ? \Carbon\Carbon::parse($lead->created_at)->toFormattedDateString() : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Time:</span>
                            <span class="font-medium text-gray-500">{{ isset($lead->created_at) ? \Carbon\Carbon::parse($lead->created_at)->toTimeString() : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- FINANCIAL PROFILE CARD --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-50 pb-2">💰 Order Invoice</h4>
                    
                    <div class="space-y-3.5">
                        <div>
                            <span class="text-xs font-medium text-gray-400 block">Total Amount</span>
                            <span class="text-2xl font-black text-emerald-600">
                                {{ number_format($lead->total_price, 2) }} <span class="text-xs font-bold text-emerald-500">BDT</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    @else
        <div class="rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-base font-bold text-gray-900">Lead Record Not Found</h3>
            <p class="mt-1 text-sm text-gray-500">The specified battery water lead dataset is missing or inaccessible.</p>
            <div class="mt-6">
                <a href="{{ route('battery.water.lead.show', $lead->id) }}" class="inline-flex items-center justify-center bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 rounded-lg transition">Return to Leads Inventory</a>
            </div>
        </div>
    @endif

</div>
</x-layout.default>