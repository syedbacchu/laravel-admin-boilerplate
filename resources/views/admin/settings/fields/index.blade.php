<x-layout.default>
    <div class="panel mt-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Setting Fields</h1>

            <a href="{{ route('settings.fields.create') }}"
               class="btn btn-primary">
                + Add New Field
            </a>
        </div>

        @foreach($fields as $group => $groupFields)
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-3 capitalize">
                    {{ str_replace('-', ' ', $group) }}
                </h2>

                <div class="overflow-x-auto">
                    <table class="table table-bordered w-full sortable-table"
                           data-group="{{ $group }}">
                        <thead>
                        <tr class="bg-gray-100">
                            <th width="40">☰</th>
                            <th>Label</th>
                            <th>Slug</th>
                            <th>Type</th>
                            <th width="140">Action</th>
                        </tr>
                        </thead>

                        <tbody class="sortable-body">
                        @foreach($groupFields as $field)
                            <tr data-id="{{ $field->id }}">
                                <td class="cursor-move text-center">☰</td>
                                <td>{{ $field->label }}</td>
                                <td>
                                    <code>{{ $field->slug }}</code>
                                </td>
                                <td>
                                    <span class="">
                                        {{ ucfirst($field->type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('settings.fields.edit', $field) }}"
                                           class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('settings.fields.destroy', $field) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this field?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        @endforeach
    </div>
</x-layout.default>
