<x-layout.default>
    @section('title', $pageTitle)

    <form method="POST" action="{{ route('comparismArea.store') }}" class="mt-4">
        @csrf

        @if(isset($item))
            <input type="hidden" name="edit_id" value="{{ $item->id }}">
        @endif

        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">{{ $pageTitle }}</h1>

            <div class="flex gap-2">
                <a href="{{ route('comparismArea.list') }}" class="btn btn-outline-primary">Back</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- LEFT -->
            <div class="xl:col-span-2 space-y-6">

                <div class="panel border p-4">

                    <h3 class="font-semibold mb-3">Select Comparism</h3>

                    <!-- PARENT SELECT -->
                    <select name="compare_id" class="form-select">
                        <option value="">Select Comparism</option>
                        @php
                            $siteTypes = \App\Enums\SliderSiteType::getSliderSiteTypeArray();
                        @endphp

                        @foreach($comparisms as $c)
                            <option value="{{ $c->id }}" @selected(old('compare_id', $item->compare_id ?? '') == $c->id)>

                                {{ $c->area }} - {{ $siteTypes[$c->site_type] ?? 'N/A' }}

                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- REPEATER -->
                <div class="panel border p-4">

                    <div class="flex justify-between mb-3">
                        <h3 class="font-semibold">Comparison Data</h3>
                        <button type="button" id="addRow" class="btn btn-sm btn-primary">
                            + Add Row
                        </button>
                    </div>

                    <div id="repeater">
                        @forelse(($areas ?? collect()) as $area)
                            <div class="grid grid-cols-3 gap-3 mb-3 repeater-row items-center">
                                <input type="hidden" name="area_ids[]" value="{{ $area->id }}">

                                <input type="text" name="left_side[]" value="{{ old('left_side.'.$loop->index, $area->left_side) }}"
                                    class="form-input" placeholder="Left Side">

                                <input type="text" name="right_side[]" value="{{ old('right_side.'.$loop->index, $area->right_side) }}"
                                    class="form-input" placeholder="Right Side">

                                <div class="flex gap-2">
                                    <input type="number" name="sort_order[]"
                                        value="{{ old('sort_order.'.$loop->index, $area->sort_order) }}" class="form-input"
                                        placeholder="Sort">

                                    <button type="button" class="removeRow btn btn-sm btn-outline-danger">&times;</button>
                                </div>
                            </div>
                        @empty
                            <div class="grid grid-cols-3 gap-3 mb-3 repeater-row items-center">
                                <input type="hidden" name="area_ids[]" value="">
                                <input type="text" name="left_side[]" class="form-input" placeholder="Left Side">
                                <input type="text" name="right_side[]" class="form-input" placeholder="Right Side">
                                <div class="flex gap-2">
                                    <input type="number" name="sort_order[]" class="form-input" placeholder="Sort">
                                    <button type="button" class="removeRow btn btn-sm btn-outline-danger">&times;</button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="space-y-6">

                <div class="panel border p-4">

                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="1" @selected(($item->status ?? 1) == 1)>Active</option>
                        <option value="0" @selected(($item->status ?? 1) == 0)>Inactive</option>
                    </select>

                </div>

            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn btn-primary">
                Save
            </button>
        </div>

    </form>

    <!-- JS -->
    <script>
        document.getElementById('addRow').addEventListener('click', function () {
            let row = `
        <div class="grid grid-cols-3 gap-3 mb-3 repeater-row items-center">
            <input type="hidden" name="area_ids[]" value="">
            <input type="text" name="left_side[]" class="form-input" placeholder="Left Side">
            <input type="text" name="right_side[]" class="form-input" placeholder="Right Side">
            <div class="flex gap-2">
                <input type="number" name="sort_order[]" class="form-input" placeholder="Sort">
                <button type="button" class="removeRow btn btn-sm btn-outline-danger">&times;</button>
            </div>
        </div>`;
            document.getElementById('repeater').insertAdjacentHTML('beforeend', row);
        });

        document.getElementById('repeater').addEventListener('click', function (e) {
            if (e.target.classList.contains('removeRow')) { 
                let rows = document.querySelectorAll('#repeater .repeater-row');
                if (rows.length > 1) {
                    e.target.closest('.repeater-row').remove();
                } else {
                    // keep at least one row, just clear its inputs
                    e.target.closest('.repeater-row').querySelectorAll('input').forEach(function (input) {
                        input.value = '';
                    });
                }
            }
        });
    </script>

</x-layout.default>