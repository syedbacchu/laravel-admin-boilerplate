<x-layout.default>
    <div class="panel mt-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">{{ $pageTitle }}</h1>
        <div>
            <form method="POST" @if($function_type == 'create') action="{{ route('appSlider.store') }}" @else action="{{ route('appSlider.update') }}" @endif enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="mb-2">
                        <label for="title" class="">{{ __('Title') }}</label>
                        <input type="hidden" name="type" value="{{ $type }}">
                        @if(isset($item))
                            <input type="hidden" name="edit_id" value="{{ $item->id }}">
                        @endif
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="title" type="text" @if(isset($item)) value="{{ $item->title }}" @else value="{{ old('title') }}" @endif class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="subtitle" class="">{{ __('Sub Title') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="subtitle" type="text" @if(isset($item)) value="{{ $item->subtitle }}" @else value="{{ old('subtitle') }}" @endif class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="mb-2">
                        <label for="offer" class="">{{ __('Offer') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="offer" type="text" @if(isset($item)) value="{{ $item->offer }}" @else value="{{ old('offer') }}" @endif class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="link" class="">{{ __('Link') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="link" type="text" @if(isset($item)) value="{{ $item->link }}" @else value="{{ old('link') }}" @endif class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Serial Input -->
                    <div class="mb-4">
                        <label for="serial" class="block text-gray-700 font-medium mb-2">{{ __('Serial') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input
                                name="serial"
                                type="text"
                                @if(isset($item)) value="{{ $item->serial }}" @else value="{{ old('serial') }}" @endif
                                class="flex-1 border border-gray-300 rounded-r-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            />
                        </div>
                    </div>

                    <!-- Banner Upload with Preview inside -->
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 font-medium mb-2">Banner</label>

                        <div id="dropzone"
                            class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer">

                            <input id="fileInput" name="photo" type="file" accept="image/*" class="hidden" />

                            <!-- Preview image -->
                            <img id="imagePreview"
                                class="absolute inset-0 w-full h-full object-contain bg-white"
                                @if(isset($item) && !empty($item->photo))
                                    src="{{ $item->photo }}"
                                    style="display: block;"
                                @else
                                    style="display: none;"
                                @endif />

                            <!-- Default message -->
                            <p id="dropMessage" class="text-gray-400" @if(isset($item) && !empty($item->photo)) style="display: none;" @endif>Drag & drop or click to upload</p>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-secondary mt-6">Submit</button>
                </div>
            </form>
        </div>
    </div>

<script>
    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const preview = document.getElementById("imagePreview");
    const message = document.getElementById("dropMessage");

    dropzone.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                preview.src = ev.target.result;
                preview.style.display = "block";   // make visible
                message.style.display = "none";    // hide message
            };
            reader.readAsDataURL(file);
        }
    });
</script>
</x-layout.default>
