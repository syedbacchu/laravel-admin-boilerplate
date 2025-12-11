<x-layout.default>
    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

        <div class="flex items-center justify-between mb-6">
            <h5 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h5>
        </div>

        <div class="panel" x-data="{ tab: '{{ str_replace('\\', '_', $models[0] ?? '') }}' }">

            <div class="flex flex-col sm:flex-row gap-4">

                <!-- LEFT: MODEL LIST -->
                <div class="sm:w-1/5">
                    <ul class="space-y-2">
                        @foreach($models as $model)
                            @php
                                $key = str_replace('\\', '_', $model);
                                $short = class_basename($model);
                            @endphp

                            <li>
                                <a href="javascript:;"
                                   @click="tab = '{{ $key }}'"
                                   :class="{ '!bg-success text-white': tab === '{{ $key }}' }"
                                   class="p-3 py-2 block rounded-md hover:bg-success hover:text-white transition-all">
                                    {{ $short }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- RIGHT: FIELD MANAGER -->
                <div class="flex-1">

                    @foreach($models as $model)
                        @php $key = str_replace('\\', '_', $model); @endphp

                        <template x-if="tab === '{{ $key }}'">
                            <div class="space-y-6">

                                <h4 class="text-xl font-bold mb-3">
                                    {{ class_basename($model) }} Custom Fields
                                </h4>

                                <!-- Existing Fields -->
                                <div class="border rounded-lg p-4">
                                    <h5 class="font-semibold mb-3">Existing Fields</h5>

                                    <table class="w-full text-sm">
                                        <thead>
                                        <tr class="border-b">
                                            <th class="py-2 text-left">Label</th>
                                            <th class="py-2 text-left">Name</th>
                                            <th class="py-2 text-left">Type</th>
                                            <th class="py-2 text-left">Required</th>
                                            <th class="py-2 text-left">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody id="field_list_{{ $key }}">
                                        <!-- Load via AJAX -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Add Field Form -->
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <h5 class="font-semibold mb-3">Add New Field</h5>

                                    <form class="space-y-4" onsubmit="return saveField('{{ $model }}')">

                                        <input type="hidden" name="model" value="{{ $model }}">

                                        <div>
                                            <label>Label *</label>
                                            <input type="text" name="label" class="form-input w-full" required>
                                        </div>

                                        <div>
                                            <label>Field Name</label>
                                            <input type="text" name="name" class="form-input w-full"
                                                   placeholder="auto generate">
                                        </div>

                                        <div x-data="{ showOptions:false }">
                                            <label>Type *</label>
                                            <select name="field_type" class="form-select w-full"
                                                    x-on:change="showOptions=($event.target.value=='select'||$event.target.value=='radio')"
                                                    required>
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="radio">Radio</option>
                                                <option value="select">Select</option>
                                                <option value="number">Number</option>
                                                <option value="file">File</option>
                                            </select>

                                            <template x-if="showOptions">
                                                <div class="mt-3">
                                                    <label>Options (comma separated)</label>
                                                    <input type="text" name="options" class="form-input w-full"
                                                           placeholder="red, blue, green">
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
                                            <label>Show In *</label>
                                            <select name="show_in[]" class="form-select w-full" multiple required>
                                                <option value="create">Create Form</option>
                                                <option value="update">Update Form</option>
                                                <option value="api">API</option>
                                            </select>
                                        </div>

                                        <button type="submit"
                                                class="btn btn-success mt-2 px-4 py-2">
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

    <script>
        function saveField(model) {
            alert("This will be saved via AJAX.");
            return false;
        }
    </script>

</x-layout.default>
