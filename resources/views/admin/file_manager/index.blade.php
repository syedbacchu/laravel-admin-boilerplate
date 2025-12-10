<x-layout.default>


    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
    <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h5 class="text-2xl font-bold text-gray-800">{{ $title ?? __('Title') }}</h5>

            <a href="{{ route('fileManager.create') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                {{__('Upload')}}
            </a>
        </div>

        <div class="py-4">
            @if(isset($items['data'][0]))
                <div class="grid
                    grid-cols-2
                    sm:grid-cols-4
                    lg:grid-cols-5
                    xl:grid-cols-6
                    gap-4">

                    @foreach($items['data'] as $item)
                        <div class="relative group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border">

                            <!-- Image -->
                            <div class="w-full h-80 rounded-t-xl overflow-hidden bg-gray-100">
                                <img
                                    src="{{ asset($item->full_url) }}"
                                    alt="{{ $item->alt_text }}"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300"
                                >
                            </div>

                            <!-- Footer info -->
                            <div class="p-3 text-sm">
                                <p class="font-medium truncate">{{ $item->original_name }}</p>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ $item->size ? number_format($item->size/1024, 1) .' KB' : '' }}
                                </p>
                            </div>

                            <!-- Hover Action Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100
    flex flex-col items-center justify-center gap-3 rounded-xl transition">

                                <!-- View -->
                                <a href="{{ asset($item->full_url) }}" target="_blank"
                                   class="bg-white text-black text-xs px-3 py-1 rounded-lg shadow">
                                    View
                                </a>


                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-red-500 text-center py-6 text-lg">{{ __('No item found') }}</p>
            @endif
        </div>
    </div>

    <script>

    </script>


</x-layout.default>
