<x-layout.default>
    @section('title', $pageTitle)
    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

        <div class="flex items-center justify-between mb-6">
            <h5 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h5>
        </div>

        <div class="panel bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row" x-data="{ tab: '{{ $activeTab }}' }">

                <!-- LEFT TABS -->
                <aside class="w-full md:w-56 border-b md:border-b-0 md:border-r border-gray-200">
                    <ul class="p-4 space-y-1">
                        @foreach($groups as $group => $items)
                            <li>
                                <button
                                    type="button"
                                    @click="tab = '{{ $group }}'"
                                    class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium transition"
                                    :class="tab === '{{ $group }}'
                                ? 'bg-success/10 text-success'
                                : 'text-gray-600 hover:bg-gray-100'">

                                    <!-- Active dot -->
                                    <span
                                        class="h-2 w-2 rounded-full"
                                        :class="tab === '{{ $group }}' ? 'bg-success' : 'bg-transparent'">
                            </span>

                                    {{ ucfirst($group) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </aside>

                <!-- CONTENT -->
                <section class="flex-1 p-6">
                    @foreach($groups as $group => $items)
                        <div x-show="tab === '{{ $group }}'" x-cloak>
                            <form method="POST"  action="{{ route('settings.update', $group) }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="group" value="{{$group}}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($items as $setting)
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                {{ __($setting->label ?? Str::headline($setting->slug)) }}
                                            </label>

                                            {{-- TEXT --}}
                                            @if($setting->type !== 'file')
                                                <input
                                                    type="text"
                                                    name="{{ $setting->slug }}"
                                                    value="{{ old($setting->slug, $setting->value) }}"
                                                    class="form-input w-full"
                                                >
                                            @endif

                                            {{-- FILE --}}
                                            @if($setting->type === 'file')
                                                <input
                                                    type="file"
                                                    name="{{ $setting->slug }}"
                                                    class="form-input w-full">

                                                @if($setting->value)
                                                    <img src="{{ asset($setting->value) }}"
                                                         class="mt-2 h-16 rounded border">
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- ACTION -->
                                <div class="mt-8">
                                    <button class="btn btn-secondary px-6">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </section>

            </div>
        </div>

    </div>

    <!-- JS -->
    <script>
        function sliderForm(existingImage = '') {
            return {
                bannerImage: existingImage,
                bannerPreview: existingImage ? existingImage : '',

                init() {
                    window.addEventListener('bannerSelected', (e) => {
                        this.bannerImage = e.detail.url;
                        this.bannerPreview = e.detail.url;
                    });
                }
            }
        }
    </script>
</x-layout.default>
