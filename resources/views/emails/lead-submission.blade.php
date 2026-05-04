<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .field {
            margin: 10px 0;
            display: flex;
        }
        .field-label {
            font-weight: bold;
            min-width: 200px;
            color: #555;
        }
        .field-value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #777;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-customer {
            background: #e3f2fd;
            color: #1976d2;
        }
        .badge-company {
            background: #fff3e0;
            color: #f57c00;
        }
        .highlight {
            background: #fff9c4;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 New Lead Received!</h1>
        <p>A new {{ $leadType === 'company' ? 'Company' : 'Customer' }} lead has been submitted</p>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">Lead Information</div>

            <div class="field">
                <span class="field-label">Lead Type:</span>
                <span class="field-value">
                    <span class="badge {{ $leadType === 'company' ? 'badge-company' : 'badge-customer' }}">
                        {{ $leadType === 'company' ? 'Company' : 'Customer' }}
                    </span>
                </span>
            </div>

            <div class="field">
                <span class="field-label">Lead ID:</span>
                <span class="field-value">#{{ $lead['id'] ?? 'N/A' }}</span>
            </div>

            @if($leadType === 'company')
            <div class="field">
                <span class="field-label">Company Name:</span>
                <span class="field-value highlight">{{ $lead['company_name'] ?? 'N/A' }}</span>
            </div>
            @endif

            <div class="field">
                <span class="field-label">Full Name:</span>
                <span class="field-value highlight">{{ $lead['full_name'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Phone:</span>
                <span class="field-value highlight">{{ $lead['phone'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['email']))
            <div class="field">
                <span class="field-label">Email:</span>
                <span class="field-value">{{ $lead['email'] }}</span>
            </div>
            @endif

            @if(!empty($lead['whatsapp']))
            <div class="field">
                <span class="field-label">WhatsApp:</span>
                <span class="field-value">{{ $lead['whatsapp'] }}</span>
            </div>
            @endif
        </div>

        @if($leadType === 'customer')
        <div class="section">
            <div class="section-title">Customer Details</div>

            @if(!empty($lead['nid']))
            <div class="field">
                <span class="field-label">NID:</span>
                <span class="field-value">{{ $lead['nid'] }}</span>
            </div>
            @endif

            <div class="field">
                <span class="field-label">Customer Type:</span>
                <span class="field-value">{{ $lead['customer_type'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Address:</span>
                <span class="field-value">{{ $lead['address'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">District:</span>
                <span class="field-value">{{ $lead['district'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['google_map']))
            <div class="field">
                <span class="field-label">Google Map:</span>
                <span class="field-value">
                    <a href="{{ $lead['google_map'] }}" target="_blank">View Location</a>
                </span>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Site & System Information</div>

            <div class="field">
                <span class="field-label">Site Type:</span>
                <span class="field-value">{{ $lead['site_type'] == 1 ? 'Residential' : 'Commercial' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Installation Site Type:</span>
                <span class="field-value">{{ $lead['installation_site_type'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Electricity Source:</span>
                <span class="field-value">{{ $lead['electricity_source'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['monthly_bill']))
            <div class="field">
                <span class="field-label">Monthly Bill:</span>
                <span class="field-value">{{ number_format($lead['monthly_bill'], 2) }}</span>
            </div>
            @endif

            <div class="field">
                <span class="field-label">Meter Type:</span>
                <span class="field-value">{{ $lead['meter_type'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Daytime Usage:</span>
                <span class="field-value">{{ $lead['daytime_usage'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">System Type:</span>
                <span class="field-value">{{ $lead['system_type'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['system_size_kw']))
            <div class="field">
                <span class="field-label">System Size:</span>
                <span class="field-value">{{ $lead['system_size_kw'] }} kW</span>
            </div>
            @endif

            <div class="field">
                <span class="field-label">Main Purpose:</span>
                <span class="field-value">{{ $lead['main_purpose'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Budget Range:</span>
                <span class="field-value">{{ $lead['budget_range'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Payment Preference:</span>
                <span class="field-value">{{ $lead['payment_preference'] ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Roof & Installation Details</div>

            <div class="field">
                <span class="field-label">Roof Size:</span>
                <span class="field-value">{{ $lead['roof_size'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Roof Type:</span>
                <span class="field-value">{{ $lead['roof_type'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Has Shadow:</span>
                <span class="field-value">{{ $lead['has_shadow'] ? 'Yes' : 'No' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Installation Area:</span>
                <span class="field-value">{{ $lead['installation_area'] ?? 'N/A' }}</span>
            </div>
        </div>
        @else
        <div class="section">
            <div class="section-title">Company Details</div>

            <div class="field">
                <span class="field-label">Address:</span>
                <span class="field-value">{{ $lead['address'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['google_map']))
            <div class="field">
                <span class="field-label">Google Map:</span>
                <span class="field-value">
                    <a href="{{ $lead['google_map'] }}" target="_blank">View Location</a>
                </span>
            </div>
            @endif

            <div class="field">
                <span class="field-label">Grid Connection:</span>
                <span class="field-value">{{ $lead['grid_connection'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['transformer_capacity']))
            <div class="field">
                <span class="field-label">Transformer Capacity:</span>
                <span class="field-value">{{ $lead['transformer_capacity'] }}</span>
            </div>
            @endif

            @if(!empty($lead['contract_demand']))
            <div class="field">
                <span class="field-label">Contract Demand:</span>
                <span class="field-value">{{ $lead['contract_demand'] }}</span>
            </div>
            @endif

            @if(!empty($lead['monthly_bill']))
            <div class="field">
                <span class="field-label">Monthly Bill:</span>
                <span class="field-value">{{ number_format($lead['monthly_bill'], 2) }}</span>
            </div>
            @endif

            @if(!empty($lead['monthly_consumption']))
            <div class="field">
                <span class="field-label">Monthly Consumption:</span>
                <span class="field-value">{{ $lead['monthly_consumption'] }}</span>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Load & Technical Details</div>

            @if(!empty($lead['machinery_load_details']))
            <div class="field">
                <span class="field-label">Machinery Load:</span>
                <span class="field-value">{{ $lead['machinery_load_details'] }}</span>
            </div>
            @endif

            @if(!empty($lead['total_connected_load']))
            <div class="field">
                <span class="field-label">Total Connected Load:</span>
                <span class="field-value">{{ $lead['total_connected_load'] }}</span>
            </div>
            @endif

            @if(!empty($lead['motor_load_details']))
            <div class="field">
                <span class="field-label">Motor Load Details:</span>
                <span class="field-value">{{ $lead['motor_load_details'] }}</span>
            </div>
            @endif

            @if(!empty($lead['total_motor_load']))
            <div class="field">
                <span class="field-label">Total Motor Load:</span>
                <span class="field-value">{{ $lead['total_motor_load'] }}</span>
            </div>
            @endif

            <div class="field">
                <span class="field-label">Working Shift:</span>
                <span class="field-value">{{ $lead['working_shift'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Peak Load Time:</span>
                <span class="field-value">{{ $lead['peak_load_time'] ?? 'N/A' }}</span>
            </div>

            @if(!empty($lead['daytime_load_percentage']))
            <div class="field">
                <span class="field-label">Daytime Load %:</span>
                <span class="field-value">{{ $lead['daytime_load_percentage'] }}%</span>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">System Requirements</div>

            @if(!empty($lead['solar_target_percent']))
            <div class="field">
                <span class="field-label">Solar Target %:</span>
                <span class="field-value">{{ $lead['solar_target_percent'] }}%</span>
            </div>
            @endif

            @if(!empty($lead['required_capacity_kw']))
            <div class="field">
                <span class="field-label">Required Capacity:</span>
                <span class="field-value">{{ $lead['required_capacity_kw'] }} kW</span>
            </div>
            @endif

            @if(!empty($lead['system_size_kw']))
            <div class="field">
                <span class="field-label">System Size:</span>
                <span class="field-value">{{ $lead['system_size_kw'] }} kW</span>
            </div>
            @endif

            @if(!empty($lead['inverter_size']))
            <div class="field">
                <span class="field-label">Inverter Size:</span>
                <span class="field-value">{{ $lead['inverter_size'] }}</span>
            </div>
            @endif

            @if(!empty($lead['panel_size']))
            <div class="field">
                <span class="field-label">Panel Size:</span>
                <span class="field-value">{{ $lead['panel_size'] }}</span>
            </div>
            @endif

            @if(!empty($lead['panel_quantity']))
            <div class="field">
                <span class="field-label">Panel Quantity:</span>
                <span class="field-value">{{ $lead['panel_quantity'] }}</span>
            </div>
            @endif

            @if(!empty($lead['estimated_project_cost']))
            <div class="field">
                <span class="field-label">Estimated Project Cost:</span>
                <span class="field-value">{{ number_format($lead['estimated_project_cost'], 2) }}</span>
            </div>
            @endif

            @if(!empty($lead['expected_payback_period']))
            <div class="field">
                <span class="field-label">Payback Period:</span>
                <span class="field-value">{{ $lead['expected_payback_period'] }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="section">
            <div class="section-title">Lead Source & Decision Info</div>

            <div class="field">
                <span class="field-label">Lead Source:</span>
                <span class="field-value">{{ $lead['lead_source'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Decision Maker:</span>
                <span class="field-value">{{ $lead['decision_maker'] ?? 'N/A' }}</span>
            </div>

            <div class="field">
                <span class="field-label">Decision Time:</span>
                <span class="field-value">{{ $lead['decision_time'] ?? 'N/A' }}</span>
            </div>
        </div>

        @if(!empty($lead['customer_signature']) || !empty($lead['declaration_date']))
        <div class="section">
            <div class="section-title">Declaration</div>

            @if(!empty($lead['customer_signature']))
            <div class="field">
                <span class="field-label">Customer Signature:</span>
                <span class="field-value">
                    <img src="{{ $lead['customer_signature'] }}" alt="Signature" style="max-height: 60px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                </span>
            </div>
            @endif

            @if(!empty($lead['declaration_date']))
            <div class="field">
                <span class="field-label">Declaration Date:</span>
                <span class="field-value">{{ $lead['declaration_date'] }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This email was automatically generated by the Lead Collection System.</p>
        <p>Please follow up with the lead as soon as possible.</p>
        <p><strong>Sent at:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
