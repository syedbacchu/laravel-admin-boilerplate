<x-layout.default>
    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

        <div class="flex items-center justify-between mb-6">
            <h5 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h5>
        </div>

        <div class="panel" x-data="{ tab: '{{ md5($models[0] ?? '') }}' }">

            <div class="flex flex-col sm:flex-row gap-6">

                <!-- LEFT: MODEL LIST -->
                <div class="sm:w-1/5">
                    <ul class="space-y-2">
                        @foreach($models as $model)
                            @php
                                $key = md5($model);
                                $short = class_basename($model);
                            @endphp
                            <li>
                                <a href="javascript:;"
                                   @click="tab='{{ $key }}'; loadFields('{{ $model }}','{{ $key }}')"
                                   :class="{ '!bg-success text-white': tab === '{{ $key }}' }"
                                   class="p-3 py-2 block rounded-md hover:bg-success hover:text-white transition-all">
                                    {{ $short }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- RIGHT PANEL -->
                <div class="flex-1">

                    @foreach($models as $model)
                        @php $key = md5($model); @endphp

                        <template x-if="tab === '{{ $key }}'">
                            <div class="space-y-6">

                                <h4 class="text-xl font-bold">
                                    {{ class_basename($model) }} Custom Fields
                                </h4>

                                <!-- EXISTING FIELDS -->
                                <div class="border rounded-lg p-4">
                                    <h5 class="font-semibold mb-3">Existing Fields</h5>

                                    <table class="w-full text-sm">
                                        <thead>
                                        <tr class="border-b">
                                            <th>Label</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="field_list_{{ $key }}">
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-gray-400">
                                                Select model to load fields
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- ADD FIELD -->
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <h5 class="font-semibold mb-3">Add New Field</h5>

                                    <form onsubmit="return saveField(this,'{{ $model }}','{{ $key }}')" class="space-y-4">

                                        @csrf

                                        <input type="hidden" name="module" value="{{ $model }}">
                                        <input type="hidden" name="status" value="1">

                                        <div>
                                            <label>Label *</label>
                                            <input type="text" name="label" class="form-input w-full" required>
                                        </div>

                                        <div>
                                            <label>Field Name</label>
                                            <input type="text" name="name" class="form-input w-full"
                                                   placeholder="auto-generate">
                                        </div>

                                        <div x-data="{ show:false }">
                                            <label>Type *</label>
                                            <select name="type" class="form-select w-full"
                                                    @change="show=['select','radio','checkbox'].includes($event.target.value)"
                                                    required>
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="number">Number</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="radio">Radio</option>
                                                <option value="select">Select</option>
                                                <option value="file">File</option>
                                            </select>

                                            <template x-if="show">
                                                <div class="mt-3">
                                                    <label>Options (comma separated)</label>
                                                    <input type="text" name="options"
                                                           class="form-input w-full"
                                                           placeholder="red, green, blue">
                                                </div>
                                            </template>
                                        </div>

                                        <div>
                                            <label>Required</label>
                                            <select name="is_required" class="form-select w-full">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label>Default Value</label>
                                            <input type="text" name="default_value"
                                                   class="form-input w-full">
                                        </div>

                                        <div>
                                            <label>Validation Rules</label>
                                            <input type="text" name="validation_rules"
                                                   class="form-input w-full"
                                                   placeholder="nullable|string|max:255">
                                        </div>

                                        <button class="btn btn-success px-4 py-2">
                                            Save Field
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </template>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        function saveField(form, module, key) {
            const formData = new FormData(form);

            fetch("{{ route('customField.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(res => res.json())
                .then(res => {
                    if(res.success){
                        form.reset();
                        loadFields(module, key);
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                });

            return false;
        }

        function loadFields(module, key) {
            fetch("{{ route('customField.index') }}?module=" + module)
                .then(res => res.json())
                .then(res => {
                    const tbody = document.getElementById('field_list_' + key);
                    tbody.innerHTML = '';

                    if(!res.data || res.data.length === 0){
                        tbody.innerHTML = `<tr>
                            <td colspan="5" class="text-center py-4 text-gray-400">No fields found</td>
                        </tr>`;
                        return;
                    }

                    res.data.forEach(f => {
                        tbody.innerHTML += `
                            <tr class="border-b">
                                <td>${f.label}</td>
                                <td>${f.name}</td>
                                <td>${f.type}</td>
                                <td>${f.is_required ? 'Yes' : 'No'}</td>
                                <td>${f.status ? 'Active' : 'Inactive'}</td>
                            </tr>`;
                    });
                });
        }
    </script>
</x-layout.default>
