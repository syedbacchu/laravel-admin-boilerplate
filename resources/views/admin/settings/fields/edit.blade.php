<x-layout.default>
    @section('title', 'Edit Setting Field')

    <div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-bold mb-6">Edit Setting Field</h2>

        <form method="POST" action="{{ route('settings.fields.update', $field->id) }}">
            @csrf
            @method('PUT')

            <div x-data="{ type: '{{ old('type', $field->type) }}' }" class="space-y-5">

                {{-- GROUP --}}
                <div>
                    <label class="block mb-1 font-medium">Group</label>
                    <input type="text"
                           name="group"
                           class="form-input w-full"
                           value="{{ old('group', $field->group) }}">
                </div>

                {{-- LABEL --}}
                <div>
                    <label class="block mb-1 font-medium">Label</label>
                    <input type="text"
                           name="label"
                           class="form-input w-full"
                           value="{{ old('label', $field->label) }}">
                </div>

                {{-- SLUG --}}
                <div>
                    <label class="block mb-1 font-medium">Slug</label>
                    <input type="text"
                           name="slug"
                           class="form-input w-full"
                           value="{{ old('slug', $field->slug) }}">
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="block mb-1 font-medium">Type</label>
                    <select name="type"
                            class="form-select w-full"
                            x-model="type">
                        @foreach(['text','password','number','select','checkbox','radio','file','textarea'] as $t)
                            <option value="{{ $t }}"
                                @selected(old('type', $field->type) === $t)>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- OPTIONS --}}
                <div x-show="['select','checkbox','radio'].includes(type)" x-cloak>
                    <label class="block mb-1 font-medium">
                        Options (comma separated)
                    </label>
                    <textarea name="options"
                              class="form-textarea w-full">
{{ old('options', is_array($field->options) ? implode(',', $field->options) : '') }}
                    </textarea>
                </div>

                {{-- VALIDATION --}}
                <div>
                    <label class="block mb-1 font-medium">
                        Validation Rules
                    </label>
                    <input type="text"
                           name="validation_rules"
                           class="form-input w-full"
                           value="{{ old('validation_rules', $field->validation_rules) }}">
                </div>

                {{-- SUBMIT --}}
                <div class="pt-4">
                    <button class="btn btn-primary px-6">
                        Update Field
                    </button>
                </div>

            </div>
        </form>
    </div>
</x-layout.default>
