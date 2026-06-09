<x-layout.default>
@php
    // Safe-fallback injection if ResponseService unpacked it as $data
    $lead = $lead ?? $data ?? null;
@endphp

@section('title', 'Solar Collect Lead Details — #' . ($lead->id ?? ''))

<div class="mt-6 max-w-6xl mx-auto space-y-6 pb-16 px-4 sm:px-6">

    {{-- ── TOP ACTIONS BAR ─────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Solar Collect Lead Details</h1>
                <p class="text-xs sm:text-sm text-gray-500">Manage and view captured lead parameters</p>
            </div>
        </div>
        {{-- <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('collect.lead.list') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 hover:text-gray-900 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
            <a href="{{ route('collect.lead.delete', $lead->id ?? 0) }}"
               onclick="return confirm('Are you sure you want to delete this lead?')"
               class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </a>
        </div> --}}
    </div>

    @if($lead)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            {{-- ══ LEFT / MAIN COLUMN: LEAD DATA ════════════════════════════ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ── PERSONAL INFORMATION ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span> Personal Information
                        </h3>
                    </div>
                    <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-1 sm:grid-cols-2">
                        @if($lead->company_name)
                        <div class="sm:col-span-2 border-b border-gray-50 pb-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Company Name</span>
                            <p class="mt-0.5 font-semibold text-gray-900 text-lg">{{ $lead->company_name }}</p>
                        </div>
                        @endif

                        <div>
                            <span class="text-xs font-medium text-gray-400">Full Name</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->full_name }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Customer Type</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->customer_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Phone</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->phone }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">WhatsApp</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->whatsapp ?? 'N/A' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-xs font-medium text-gray-400">Email Address</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->email ?? 'N/A' }}</p>
                        </div>
                        @if($lead->nid)
                        <div>
                            <span class="text-xs font-medium text-gray-400">NID / Business ID</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->nid }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── ADDRESS INFORMATION ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-indigo-500"></span> Location Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-xs font-medium text-gray-400">Full Address</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->address }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <span class="text-xs font-medium text-gray-400">District</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->district ?? 'N/A' }}</p>
                            </div>
                            @if($lead->google_map)
                            <div>
                                <span class="text-xs font-medium text-gray-400">Google Maps Link</span>
                                <a href="{{ $lead->google_map }}" target="_blank"
                                   class="mt-0.5 block font-medium text-blue-600 hover:underline truncate">
                                    View Link ↗
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── INSTALLATION DETAILS (Residential / General Customer) ── --}}
                @if(!$lead->company_name)
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span> Installation Details
                        </h3>
                    </div>
                    <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-1 sm:grid-cols-2">
                        <div>
                            <span class="text-xs font-medium text-gray-400">Site Type</span>
                            <p class="mt-0.5 font-medium text-gray-900">
                                {{ $lead->installation_site_type ?? 'N/A' }}
                                @if($lead->installation_site_type_other) <span class="text-gray-500 text-xs">({{ $lead->installation_site_type_other }})</span> @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Electricity Source</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->electricity_source ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Meter Type</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->meter_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Daytime Usage</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->daytime_usage ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">System Type</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->system_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">System Size Required</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->system_size_kw ? $lead->system_size_kw . ' kW' : 'N/A' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-xs font-medium text-gray-400">Main Purpose</span>
                            <p class="mt-0.5 font-medium text-gray-900">
                                {{ $lead->main_purpose ?? 'N/A' }}
                                @if($lead->main_purpose_other) <span class="text-gray-500 text-xs">({{ $lead->main_purpose_other }})</span> @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── ROOF CONFIGURATIONS ── --}}
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                            <span class="h-2 w-2 rounded-full bg-teal-500"></span> Structural & Roof Details
                        </h3>
                    </div>
                    <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-1 sm:grid-cols-2">
                        <div>
                            <span class="text-xs font-medium text-gray-400">Roof Size</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->roof_size ?? 'N/A' }} sq. ft.</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Roof Type</span>
                            <p class="mt-0.5 font-medium text-gray-900">
                                {{ $lead->roof_type ?? 'N/A' }}
                                @if($lead->roof_type_other) <span class="text-gray-500 text-xs">({{ $lead->roof_type_other }})</span> @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Available Installation Area</span>
                            <p class="mt-0.5 font-medium text-gray-900">{{ $lead->installation_area ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400">Shadow Issue Present?</span>
                            <p class="mt-0.5 font-medium">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->has_shadow ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700' }}">
                                    {{ $lead->has_shadow ? 'Yes, Shadow Exists' : 'No Shadow' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── COMMERCIAL / INDUSTRIAL SECTIONS ── --}}
                @if($lead->company_name)
                    {{-- Electrical Parameters --}}
                    <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                                <span class="h-2 w-2 rounded-full bg-violet-500"></span> Working & Electrical Parameters
                            </h3>
                        </div>
                        <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-2 sm:grid-cols-3">
                            <div>
                                <span class="text-xs font-medium text-gray-400">Grid Connection</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->grid_connection ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Working Shift</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->working_shift ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Peak Load Time</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->peak_load_time ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Transformer</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->transformer_capacity ? $lead->transformer_capacity.' KVA' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Contract Demand</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->contract_demand ? $lead->contract_demand.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Connected Load</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->total_connected_load ? $lead->total_connected_load.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Max Demand</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->maximum_demand ? $lead->maximum_demand.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Total Motor Load</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->total_motor_load ? $lead->total_motor_load.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Daytime Load</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->daytime_load_percentage ? $lead->daytime_load_percentage.'%' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Demand Factor</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->demand_factor ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Diversity Factor</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->diversity_factor ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Monthly Consumption</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->monthly_consumption ? $lead->monthly_consumption.' kWh' : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- System Requirements --}}
                    <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                                <span class="h-2 w-2 rounded-full bg-cyan-500"></span> Corporate System Requirements
                            </h3>
                        </div>
                        <div class="p-6 grid gap-x-6 gap-y-4 grid-cols-2 sm:grid-cols-4">
                            <div>
                                <span class="text-xs font-medium text-gray-400">Solar Target</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->solar_target_percent ? $lead->solar_target_percent.'%' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Req. Capacity</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->required_capacity_kw ? $lead->required_capacity_kw.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">System Size</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->system_size_kw ? $lead->system_size_kw.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Inverter Size</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->inverter_size ? $lead->inverter_size.' kW' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Panel Size</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->panel_size ? $lead->panel_size.' Wp' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Panel Qty</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->panel_quantity ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Backup Hours</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->backup_hours ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400">Critical Load</span>
                                <p class="mt-0.5 font-medium text-gray-900">{{ $lead->critical_load ? $lead->critical_load.' kW' : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Machinery Load Details --}}
                    @if($lead->machinery_load_details && count($lead->machinery_load_details) > 0)
                    <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                                <span class="h-2 w-2 rounded-full bg-pink-500"></span> Machinery Load Profile
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @foreach($lead->machinery_load_details as $machinery)
                            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-2xs">
                                <p class="font-bold text-gray-900 border-b border-gray-50 pb-1.5 mb-3">{{ $machinery['machine_name'] ?? $machinery->machine_name ?? 'Unknown Machine' }}</p>
                                <div class="grid gap-4 grid-cols-2 md:grid-cols-4 text-sm">
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Quantity</span>
                                        <span class="font-medium text-gray-800">{{ $machinery['qty'] ?? $machinery->qty ?? 0 }} pcs</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Rated Power</span>
                                        <span class="font-medium text-gray-800">{{ $machinery['rated_power_kw'] ?? $machinery->rated_power_kw ?? 0 }} kW</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Daily Runtime</span>
                                        <span class="font-medium text-gray-800">{{ $machinery['running_hours_per_day'] ?? $machinery->running_hours_per_day ?? 0 }} hrs</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Total Capacity</span>
                                        <span class="font-semibold text-emerald-600">{{ $machinery['total_kw'] ?? $machinery->total_kw ?? 0 }} kW</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Motor Load Details --}}
                    @if($lead->motor_load_details && count($lead->motor_load_details) > 0)
                    <div class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                                <span class="h-2 w-2 rounded-full bg-orange-500"></span> Induction Motor Details
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @foreach($lead->motor_load_details as $motor)
                            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-2xs">
                                <p class="font-bold text-gray-900 border-b border-gray-50 pb-1.5 mb-3">{{ $motor['motor_type'] ?? $motor->motor_type ?? 'Standard Motor' }}</p>
                                <div class="grid gap-4 grid-cols-2 md:grid-cols-4 text-sm">
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Quantity</span>
                                        <span class="font-medium text-gray-800">{{ $motor['qty'] ?? $motor->qty ?? 0 }} units</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Horsepower</span>
                                        <span class="font-medium text-gray-800">{{ $motor['hp'] ?? $motor->hp ?? 0 }} HP</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Kilowatts</span>
                                        <span class="font-medium text-gray-800">{{ $motor['kw'] ?? $motor->kw ?? 0 }} kW</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-400 block">Starter Type</span>
                                        <span class="font-semibold text-indigo-600">{{ $motor['starting_type'] ?? $motor->starting_type ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

            </div>

            {{-- ══ RIGHT COLUMN: SIDEBAR (METRICS, STATUS & ACTIONS) ═════════ --}}
            <div class="space-y-6">
                
                {{-- META BRIEF CARD --}}
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                    {{-- <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Lead Status</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-bold bg-blue-50 text-blue-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                {{ strtoupper($lead->status ?? 'PENDING') }}
                            </span>
                        </div>
                    </div> --}}

                    <div class="border-t border-gray-100 pt-3 space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Lead Tracking ID:</span>
                            <span class="font-mono font-bold text-gray-900">#{{ $lead->id ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Form Type:</span>
                            <span class="font-medium text-gray-800">{{ $lead->type_label ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Created Date:</span>
                            <span class="font-medium text-gray-800">{{ isset($lead->created_at) ? \Carbon\Carbon::parse($lead->created_at)->toFormattedDateString() : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Timestamp:</span>
                            <span class="font-medium text-gray-500">{{ isset($lead->created_at) ? \Carbon\Carbon::parse($lead->created_at)->toTimeString() : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- BUDGET & FINANCIAL MATRICES --}}
                @if($lead->budget_range || $lead->payment_preference || $lead->monthly_bill || $lead->estimated_project_cost)
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-50 pb-2">💰 Financial Profiling</h4>
                    
                    <div class="space-y-3.5">
                        @if($lead->monthly_bill)
                        <div>
                            <span class="text-xs font-medium text-gray-400 block">Avg. Monthly Electricity Bill</span>
                            <span class="text-lg font-bold text-gray-900">{{ number_format($lead->monthly_bill) }} <span class="text-xs text-gray-500">BDT</span></span>
                        </div>
                        @endif

                        @if($lead->estimated_project_cost)
                        <div>
                            <span class="text-xs font-medium text-gray-400 block">Estimated Project Cost</span>
                            <span class="text-lg font-bold text-emerald-600">{{ number_format($lead->estimated_project_cost) }} <span class="text-xs text-emerald-500">BDT</span></span>
                        </div>
                        @endif

                        @if($lead->expected_payback_period)
                        <div>
                            <span class="text-xs font-medium text-gray-400 block">Expected Payback Period</span>
                            <span class="text-base font-semibold text-gray-800">{{ $lead->expected_payback_period }} Years</span>
                        </div>
                        @endif

                        @if($lead->budget_range)
                        <div>
                            <span class="text-xs font-medium text-gray-400 block">Customer Budget Range</span>
                            <span class="text-sm font-medium text-gray-800 bg-gray-50 px-2 py-1 rounded block mt-0.5">{{ $lead->budget_range }}</span>
                        </div>
                        @endif

                        @if($lead->payment_preference)
                        <div>
                            <span class="text-xs font-medium text-gray-400 block">Payment Preference</span>
                            <span class="text-sm font-medium text-gray-800">{{ $lead->payment_preference }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- B2B CAPTURE INSIGHTS --}}
                @if($lead->lead_source || $lead->decision_maker || $lead->decision_time)
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-3.5">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-50 pb-2">🎯 Acquisition Data</h4>
                    
                    @if($lead->lead_source)
                    <div>
                        <span class="text-xs font-medium text-gray-400 block">Lead Source</span>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $lead->lead_source }}
                            @if($lead->lead_source_other) <span class="text-xs text-gray-500">({{ $lead->lead_source_other }})</span> @endif
                        </p>
                    </div>
                    @endif

                    @if($lead->decision_maker)
                    <div>
                        <span class="text-xs font-medium text-gray-400 block">Point of Contact / Decision Maker</span>
                        <p class="text-sm font-medium text-gray-800">{{ $lead->decision_maker }}</p>
                    </div>
                    @endif

                    @if($lead->decision_time)
                    <div>
                        <span class="text-xs font-medium text-gray-400 block">Expected Closure Timeline</span>
                        <p class="text-sm font-medium text-gray-800">{{ $lead->decision_time }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- CUSTOMER ATTESTATION --}}
                @if($lead->customer_signature || $lead->declaration_date)
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm space-y-3">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-50 pb-2">📝 Attestation</h4>
                    @if($lead->customer_signature)
                    <div>
                        <span class="text-xs font-medium text-gray-400 block">Digital Signature Auth</span>
                        <p class="text-sm font-mono font-bold text-gray-700 italic">"/ {{ $lead->customer_signature }} /"</p>
                    </div>
                    @endif
                    @if($lead->declaration_date)
                    <div>
                        <span class="text-xs font-medium text-gray-400 block">Signed Date</span>
                        <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($lead->declaration_date)->toFormattedDateString() }}</p>
                    </div>
                    @endif
                </div>
                @endif

            </div>

        </div>
    @else
        <div class="rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-base font-bold text-gray-900">Lead Record Not Found</h3>
            <p class="mt-1 text-sm text-gray-500">The specified lead dataset is missing, deleted, or inaccessible.</p>
            <div class="mt-6">
                <a href="{{ route('collect.lead.list') }}" class="btn btn-primary">Return to Leads Inventory</a>
            </div>
        </div>
    @endif

</div>
</x-layout.default>