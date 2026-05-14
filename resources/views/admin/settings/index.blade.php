<x-layout.default>
    @section('title', $pageTitle)
    @vite(['resources/css/quill.snow.css'])
    <script src="{{ asset('assets/js/quill.js') }}"></script>
    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

        <div class="flex items-center justify-between mb-6">
            <h5 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h5>
        </div>

        <div class="panel bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row" x-data="{ tab: '{{ $activeTab }}' }">

                <!-- LEFT TABS -->
                <aside class="w-full md:w-56 border-b md:border-b-0 md:border-r border-gray-200">
                    <ul class="p-4 space-y-1">
                        @foreach($groups as $group => $fields)
                            <li>
                                <button type="button"
                                        @click="tab = '{{ $group }}'"
                                        class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium transition"
                                        :class="tab === '{{ $group }}'
                                    ? 'bg-success/10 text-success'
                                    : 'text-gray-600 hover:bg-gray-100'">

                            <span class="h-2 w-2 rounded-full"
                                  :class="tab === '{{ $group }}' ? 'bg-success' : 'bg-transparent'">
                            </span>

                                    {{ ucfirst($group) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </aside>

                <!-- CONTENT -->
                <section class="flex-1 p-6">
                    @foreach($groups as $group => $fields)
                        <div x-show="tab === '{{ $group }}'" x-cloak>

                            <form method="POST"
                                  action="{{ route('settings.update', $group) }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    @foreach($fields as $field)
                                        @php
                                            $value = $values[$field->slug] ?? null;
                                        @endphp

                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                {{ __($field->label) }}
                                            </label>

                                            {{-- TEXT / NUMBER / PASSWORD --}}
                                            @if(in_array($field->type, ['text','number','password']))
                                                <input type="{{ $field->type }}"
                                                       name="{{ $field->slug }}"
                                                       value="{{ old($field->slug, $value) }}"
                                                       class="form-input w-full">
                                            @endif

                                            @if(in_array($field->type, ['textarea']))
                                                @if(in_array($field->slug, ['privacy_policy', 'terms_condition', 'cookie_policy']))
                                                    <div x-data="quillEditor('{{ $field->slug }}', '{{ old($field->slug, $value) }}')" class="quill-editor-wrapper">
                                                        <input type="hidden" :name="fieldName" x-model="content">
                                                        <div x-ref="editor"></div>
                                                    </div>
                                                @else
                                                    <textarea name="{{ $field->slug }}" class="form-textarea">{{ old($field->slug, $value) }}</textarea>
                                                @endif
                                            @endif

                                            {{-- SELECT --}}
                                            @if($field->type === 'select')
                                                <select name="{{ $field->slug }}"
                                                        class="form-select w-full">
                                                    @foreach($field->options as $opt)
                                                        <option value="{{ $opt }}"
                                                            @selected(old($field->slug, $value) == $opt)>
                                                            {{ ucfirst($opt) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            {{-- RADIO --}}
                                            @if($field->type === 'radio')
                                                <div class="flex flex-wrap gap-4 mt-1">
                                                    @foreach($field->options as $opt)
                                                        <label class="inline-flex items-center">
                                                            <input type="radio"
                                                                   name="{{ $field->slug }}"
                                                                   value="{{ $opt }}"
                                                                   class="form-radio"
                                                                @checked(old($field->slug, $value) == $opt)>
                                                            <span class="ml-1">{{ ucfirst($opt) }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- CHECKBOX --}}
                                            @if($field->type === 'checkbox')
                                                @php
                                                    $vals = is_array($value)
                                                        ? $value
                                                        : json_decode($value, true) ?? [];
                                                @endphp

                                                <div class="flex flex-wrap gap-4 mt-1">
                                                    @foreach($field->options as $opt)
                                                        <label class="inline-flex items-center">
                                                            <input type="checkbox"
                                                                   name="{{ $field->slug }}[]"
                                                                   value="{{ $opt }}"
                                                                   class="form-checkbox"
                                                                @checked(in_array($opt, $vals))>
                                                            <span class="ml-1">{{ ucfirst($opt) }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($field->type === 'file')
                                                <div
                                                    x-data="fileManager(
                                                        '{{ old($field->slug, $value) }}',
                                                        '{{ $field->slug }}'
                                                    )"
                                                    class="space-y-2"
                                                >

                                                    <!-- Trigger File Manager -->
                                                    <button
                                                        type="button"
                                                        @click="$dispatch('open-file-manager', { callback: callbackName })"
                                                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700"
                                                    >
                                                        Choose Image
                                                    </button>

                                                    <!-- Hidden Input (URL will be submitted) -->
                                                    <input type="hidden"
                                                           :name="fieldName"
                                                           x-model="fileUrl">

                                                    <!-- Preview -->
                                                    <template x-if="filePreview">
                                                        <img :src="filePreview"
                                                             class="h-20 mt-2 rounded border object-cover">
                                                    </template>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>

                                <!-- ACTION -->
                                <div class="mt-8">
                                    <button class="btn btn-secondary px-6">
                                        {{ __('Update') }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    @endforeach
                </section>
            </div>
        </div>

        <!-- MAIL TESTING SECTION -->
        <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h5 class="text-2xl font-bold text-gray-800">📧 Test Mail Configuration</h5>
                    <p class="text-sm text-gray-500 mt-1">Test if your email settings are working correctly</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Test Email Form -->
                <div class="lg:col-span-2">
                    <div class="border border-gray-200 rounded-lg p-6">
                        <form id="testMailForm" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Recipient Email Address
                                </label>
                                <div class="flex gap-3">
                                    <input type="email"
                                           id="test_email"
                                           name="test_email"
                                           placeholder="Enter email to receive test mail"
                                           required
                                           class="form-input flex-1">
                                    <button type="submit"
                                            id="sendTestMailBtn"
                                            class="btn btn-primary px-6">
                                        <span id="btnText">Send Test Email</span>
                                        <span id="btnLoader" class="hidden">
                                            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12c0 4.418 3.582 8 8 8s8-3.582 8-8 8-3.582-8-8-8-3.582-8-8-8z"></path>
                                            </svg>
                                            Sending...
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Status Messages -->
                            <div id="mailStatus" class="hidden rounded-lg p-4">
                                <div class="flex items-start">
                                    <div id="statusIcon" class="flex-shrink-0"></div>
                                    <div class="ml-3">
                                        <h3 id="statusTitle" class="text-sm font-medium"></h3>
                                        <div id="statusMessage" class="mt-1 text-sm"></div>
                                        <div id="statusErrors" class="mt-2 text-sm hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Mail Settings Display -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h6 class="text-lg font-semibold text-gray-800 mb-4">Current Mail Settings</h6>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Driver:</span>
                                <span class="font-medium text-gray-800">{{ settings('mail_driver', 'Not Set') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Host:</span>
                                <span class="font-medium text-gray-800">{{ settings('email_host', env('MAIL_HOST', 'Not Set')) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Port:</span>
                                <span class="font-medium text-gray-800">{{ settings('email_port', env('MAIL_PORT', 'Not Set')) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Username:</span>
                                <span class="font-medium text-gray-800">{{ settings('email_username', env('MAIL_USERNAME', 'Not Set')) ? '✓ Configured' : 'Not Set' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">From Email:</span>
                                <span class="font-medium text-gray-800">{{ settings('mail_from_address', env('MAIL_FROM_ADDRESS', 'Not Set')) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-500">
                                <strong>Configuration Priority:</strong><br>
                                1. Admin Settings (Database)<br>
                                2. Environment Variables (.env)<br>
                                3. Not Configured
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .quill-editor-wrapper .ql-toolbar {
            border-radius: 8px 8px 0 0;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .quill-editor-wrapper .ql-container {
            border-radius: 0 0 8px 8px;
            border: 1px solid #e2e8f0;
            min-height: 300px;
            font-size: 14px;
        }
    </style>

    <script>
    function quillEditor(fieldName, initialContent) {
        return {
            fieldName: fieldName,
            content: initialContent || '',
            quill: null,

            init() {
                const toolbarOptions = [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ];

                this.quill = new Quill(this.$refs.editor, {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow',
                    placeholder: 'Start typing...'
                });

                // Set initial content
                if (this.content) {
                    this.quill.root.innerHTML = this.content;
                }

                // Update content on change
                this.quill.on('text-change', () => {
                    this.content = this.quill.root.innerHTML;
                });
            }
        };
    }
    </script>
</x-layout.default>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testMailForm = document.getElementById('testMailForm');
    const sendTestMailBtn = document.getElementById('sendTestMailBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const mailStatus = document.getElementById('mailStatus');

    if (testMailForm) {
        testMailForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const testEmail = document.getElementById('test_email').value;

            if (!testEmail) {
                alert('Please enter an email address');
                return;
            }

            // Show loading state
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            sendTestMailBtn.disabled = true;

            // Hide previous status
            mailStatus.classList.add('hidden');

            fetch('{{ route('settings.testMail') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    test_email: testEmail
                })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
                sendTestMailBtn.disabled = false;

                // Show status
                mailStatus.classList.remove('hidden');

                if (data.success) {
                    // Success
                    mailStatus.className = 'rounded-lg p-4 bg-green-50 border border-green-200';
                    document.getElementById('statusIcon').innerHTML = `
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    `;
                    document.getElementById('statusTitle').className = 'text-sm font-medium text-green-800';
                    document.getElementById('statusTitle').textContent = 'Success!';
                    document.getElementById('statusMessage').className = 'mt-1 text-sm text-green-700';
                    document.getElementById('statusMessage').textContent = data.message;
                    document.getElementById('statusErrors').classList.add('hidden');
                } else {
                    // Error
                    mailStatus.className = 'rounded-lg p-4 bg-red-50 border border-red-200';
                    document.getElementById('statusIcon').innerHTML = `
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m7 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    `;
                    document.getElementById('statusTitle').className = 'text-sm font-medium text-red-800';
                    document.getElementById('statusTitle').textContent = 'Failed';
                    document.getElementById('statusMessage').className = 'mt-1 text-sm text-red-700';
                    document.getElementById('statusMessage').textContent = data.message;

                    // Show errors if available
                    const errorsDiv = document.getElementById('statusErrors');
                    if (data.errors && data.errors.length > 0) {
                        errorsDiv.classList.remove('hidden');
                        errorsDiv.className = 'mt-2 text-sm text-red-600';
                        errorsDiv.innerHTML = '<strong>Details:</strong><br>' +
                            (Array.isArray(data.errors)
                                ? data.errors.join('<br>')
                                : data.errors
                            );
                    } else {
                        errorsDiv.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Reset button state
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
                sendTestMailBtn.disabled = false;

                // Show error status
                mailStatus.classList.remove('hidden');
                mailStatus.className = 'rounded-lg p-4 bg-red-50 border border-red-200';
                document.getElementById('statusIcon').innerHTML = `
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m7 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;
                document.getElementById('statusTitle').className = 'text-sm font-medium text-red-800';
                document.getElementById('statusTitle').textContent = 'Error';
                document.getElementById('statusMessage').className = 'mt-1 text-sm text-red-700';
                document.getElementById('statusMessage').textContent = 'Failed to send test email. Please check your connection.';
                document.getElementById('statusErrors').classList.add('hidden');
            });
        });
    }
});
</script>
