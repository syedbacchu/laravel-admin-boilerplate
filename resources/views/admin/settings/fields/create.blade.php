<x-layout.default>
    @section('title', 'Create Setting Field')

    <div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-bold mb-6">Create Setting Field</h2>

        <form method="POST" action="{{ route('settings.fields.store') }}">
            @csrf

            <div x-data="{ type: '{{ old('type') }}' }" class="space-y-5">

                {{-- GROUP --}}
                <div>
                    <label class="block mb-1 font-medium">Group</label>
                    <input type="text"
                           name="group"
                           class="form-input w-full"
                           value="{{ old('group') }}"
                           placeholder="sms">
                </div>

                {{-- LABEL --}}
                <div>
                    <label class="block mb-1 font-medium">Label</label>
                    <input type="text"
                           name="label"
                           class="form-input w-full"
                           value="{{ old('label') }}"
                           placeholder="SMS Provider">
                </div>

                {{-- SLUG --}}
                <div>
                    <label class="block mb-1 font-medium">Slug</label>
                    <input type="text"
                           name="slug"
                           class="form-input w-full"
                           value="{{ old('slug') }}"
                           placeholder="sms_provider">
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="block mb-1 font-medium">Type</label>
                    <select name="type"
                            class="form-select w-full"
                            x-model="type">
                        <option value="">-- Select Type --</option>
                        <option value="text">Text</option>
                        <option value="password">Password</option>
                        <option value="number">Number</option>
                        <option value="select">Select</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="radio">Radio</option>
                        <option value="file">File</option>
                        <option value="textarea">Textarea</option>
                    </select>
                </div>

                {{-- OPTIONS --}}
                <div x-show="['select','checkbox','radio'].includes(type)" x-cloak>
                    <label class="block mb-1 font-medium">
                        Options (comma separated)
                    </label>
                    <textarea name="options"
                              class="form-textarea w-full"
                              placeholder="twilio, sslwireless, nexmo">{{ old('options') }}</textarea>
                </div>

                {{-- VALIDATION RULES --}}
                <div>
                    <label class="block mb-1 font-medium">
                        Validation Rules
                    </label>
                    <input type="text"
                           name="validation_rules"
                           class="form-input w-full"
                           value="{{ old('validation_rules') }}"
                           placeholder="required|string|max:255">
                </div>

                {{-- SUBMIT --}}
                <div class="pt-4">
                    <button class="btn btn-primary px-6">
                        Save Field
                    </button>
                </div>

            </div>
        </form>
    </div>
</x-layout.default>
