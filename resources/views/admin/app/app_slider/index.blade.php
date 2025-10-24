<x-layout.default>

<link rel="stylesheet" href="{{ asset('assets/common/datatables/jquery.dataTables.min.css') }}">
<script src="{{ asset('assets/common/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('assets/common/datatables/jquery.dataTables.min.js') }}"></script>

    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
    <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h5 class="text-2xl font-bold text-gray-800">{{ $title ?? __('App Slider') }}</h5>

            <a href="{{ route('appSlider.create') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Create Slider
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="sliderTable" class="min-w-full border border-gray-200 rounded-xl text-sm text-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">Banner</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">Title</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">Offer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">Published</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">Link</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">Serial</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        $('#sliderTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('appSlider.list') }}",
            columns: [
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'offer', name: 'offer' },
                { data: 'published', name: 'published' },
                { data: 'link', name: 'link' },
                { data: 'serial', name: 'serial' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            layout: {
                topStart: 'search',
                bottomEnd: 'paging',
            },
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search sliders...",
                paginate: {
                    next: '→',
                    previous: '←'
                }
            },
            classes: {
                table: 'min-w-full divide-y divide-gray-200 border border-gray-100 rounded-lg shadow-sm',
                thead: 'bg-gray-100 text-gray-700 uppercase text-xs font-semibold',
                tbody: 'divide-y divide-gray-100',
                tr: 'hover:bg-gray-50 transition-colors duration-150',
                th: 'px-4 py-3 text-left',
                td: 'px-4 py-3 text-sm text-gray-700'
            },
            pageLength: 10,
            order: [[0, 'desc']],
        });
    });
    </script>


</x-layout.default>
