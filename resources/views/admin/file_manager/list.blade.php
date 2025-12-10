<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <title></title>

    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <link rel="icon" type="image/svg" href="/assets/images/favicon.svg" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
          rel="stylesheet" />

    <script src="/assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="/assets/js/popper.min.js"></script>
    <script defer src="/assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="/assets/js/sweetalert.min.js"></script>
    @vite(['resources/css/app.css'])

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body >

@include('components.toastr.toastr')

    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h5 class="text-2xl font-bold text-gray-800">{{ $title ?? __('Title') }}</h5>

            <!-- Upload Button -->
            <div
                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600
    text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 relative cursor-pointer">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>

                <span>Upload Photo</span>

                <input
                    type="file"
                    name="photo"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    onchange="uploadFile(this)"
                >
            </div>

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

                                <!-- Select (for popup mode) -->
                                <button
                                    type="button"
                                    onclick="selectFile('{{ $item->id }}', '{{ asset($item->full_url) }}')"
                                    class="bg-blue-600 text-black text-xs px-3 py-1 rounded-lg shadow hover:bg-blue-700">
                                    Select
                                </button>

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
        function selectFile(id, url) {
            window.parent.dispatchEvent(
                new CustomEvent('file-selected', {
                    detail: { id: id, url: url }
                })
            );
        }


        function uploadFile(input) {
            let file = input.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append("photo", file);
            formData.append("_token", "{{ csrf_token() }}");

            axios.post("{{ route('fileManager.storeFile') }}", formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(function (response) {
                console.log(response)
                if (response?.data?.success) {
                    toastr.success(response?.data?.message);
                } else {
                    toastr.error(response?.data?.message);
                }

                loadImages();

                input.value = ""; // reset file input
            })
            .catch(function (error) {

                toastr.error(error.response?.data?.error_message ?? "Upload failed!");
                input.value = "";
            });
        }


            // Auto reload images
        function loadImages() {
            axios.get("{{ request()->fullUrl() }}") // same page URL returning blade?
                .then(function (res) {
                    let parser = new DOMParser();
                    let html = parser.parseFromString(res.data, "text/html");

                    let newGrid = html.querySelector(".grid");
                    document.querySelector(".grid").innerHTML = newGrid.innerHTML;
                });
        }


</script>
<script src="/assets/js/alpine-collaspe.min.js"></script>
<script src="/assets/js/alpine-persist.min.js"></script>
<script defer src="/assets/js/alpine-ui.min.js"></script>
<script defer src="/assets/js/alpine-focus.min.js"></script>
<script defer src="/assets/js/alpine.min.js"></script>
<script src="/assets/js/custom.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</body>

</html>
