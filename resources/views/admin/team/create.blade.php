<x-layout.default>
@section('title', $pageTitle)

<form method="POST" action="{{ route('team.store') }}" class="mt-4">
    @csrf

    @if(isset($item))
        <input type="hidden" name="edit_id" value="{{ $item->id }}">
    @endif

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ $pageTitle }}
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('team.list') }}" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-primary">Save Team</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="xl:col-span-2 space-y-6">
            <div class="panel !p-0 overflow-hidden border">

                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Team Information</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- NAME -->
                    <label class="text-xs uppercase text-gray-500">Name</label>
                    <input name="name"
                           data-slug-source
                           value="{{ $item->name ?? old('name') }}"
                           class="form-input text-lg font-semibold"
                           placeholder="Name">

                    <!-- SLUG -->
                    <div>
                        <label class="text-xs uppercase text-gray-500">Slug</label>
                        <input name="slug"
                               data-slug-target
                               value="{{ $item->slug ?? old('slug') }}"
                               class="form-input mt-1"
                               placeholder="slug-auto-generate">
                    </div>

                    <!-- EMAIL -->
                    <label class="text-xs uppercase text-gray-500">Email</label>
                    <input name="email"
                           value="{{ $item->email ?? old('email') }}"
                           class="form-input"
                           placeholder="Email">

                    <!-- PHONE -->
                    <label class="text-xs uppercase text-gray-500">Phone</label>
                    <input name="phone"
                           value="{{ $item->phone ?? old('phone') }}"
                           class="form-input"
                           placeholder="Phone">

                    <!-- DESIGNATION -->
                    <label class="text-xs uppercase text-gray-500">Designation</label>
                    <input name="designation"
                           value="{{ $item->designation ?? old('designation') }}"
                           class="form-input"
                           placeholder="Designation">

                    <!-- BIO -->
                    <label class="text-xs uppercase text-gray-500">Bio</label>
                    <textarea name="bio"
                              class="form-input"
                              rows="4"
                              placeholder="Bio">{{ $item->bio ?? old('bio') }}</textarea>

                    <!-- JOIN DATE -->
                    <label class="text-xs uppercase text-gray-500">Join Date</label>
                    <input type="date"
                           name="join_date"
                           value="{{ $item->join_date ?? old('join_date') }}"
                           class="form-input">

                    <!-- SOCIAL LINKS -->
                    <div class="grid grid-cols-2 gap-3">
                    <label class="text-xs uppercase text-gray-500">Facebook</label>
                    <input name="facebook_url" value="{{ $item->facebook_url ?? old('facebook_url') }}" class="form-input" placeholder="Facebook URL">
                    <label class="text-xs uppercase text-gray-500">Twitter</label>

                    <input name="twitter_url" value="{{ $item->twitter_url ?? old('twitter_url') }}" class="form-input" placeholder="Twitter URL">
                    <label class="text-xs uppercase text-gray-500">LinkedIn</label>

                    <input name="linkedin_url" value="{{ $item->linkedin_url ?? old('linkedin_url') }}" class="form-input" placeholder="LinkedIn URL">
                    <label class="text-xs uppercase text-gray-500">Instagram</label>

                    <input name="instagram_url" value="{{ $item->instagram_url ?? old('instagram_url') }}" class="form-input" placeholder="Instagram URL">
                    <label class="text-xs uppercase text-gray-500">GitHub</label>
                    <input name="github_url" value="{{ $item->github_url ?? old('github_url') }}" class="form-input" placeholder="GitHub URL">
                    <label class="text-xs uppercase text-gray-500">YouTube</label>
                    <input name="youtube_url" value="{{ $item->youtube_url ?? old('youtube_url') }}" class="form-input" placeholder="YouTube URL">
                    </div>

                </div>

                {{-- Custom Fields --}}
                @if(isset($item))
                    @customFields($item)
                @else
                    @customFields(\App\Models\Team::class)
                @endif

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6 xl:sticky xl:top-20 self-start">

            <!-- STATUS -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Publish</h3>
                </div>

                <div class="p-4 space-y-4">

                    <!-- STATUS -->
                    <label class="text-xs uppercase text-gray-500">Status</label>
                    <select name="status" class="form-select w-full">
                        <option value="1" @selected(($item->status ?? 1)==1)>Active</option>
                        <option value="0" @selected(($item->status ?? 1)==0)>Inactive</option>
                    </select>

                    <!-- FEATURED -->
                    <label class="text-xs uppercase text-gray-500">Featured</label>
                    <select name="is_featured" class="form-select w-full">
                        <option value="0">Not Featured</option>
                        <option value="1" @selected(($item->is_featured ?? 0)==1)>Featured</option>
                    </select>

                    <!-- SITE TYPE -->
                    <label class="text-xs uppercase text-gray-500">Site Type</label>
                    <select name="site_type" class="form-select w-full">
                        <option value="">Select Site Type</option>
                        @foreach(\App\Enums\SliderSiteType::getSliderSiteTypeArray() as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('site_type', $item->site_type ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>

            <!-- PROFILE IMAGE -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Profile Image</h3>
                </div>

                <div class="p-4">
                    <div x-data="fileManager('{{ $item->image ?? old('image', '') }}', 'image')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Image
                        </button>

                        <input type="hidden" name="image" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="mt-3 rounded-lg border object-cover w-full max-h-[160px]">
                        </template>

                    </div>
                </div>
            </div>

            <!-- COVER IMAGE -->
            <div class="panel border">
                <div class="px-4 py-3 border-b bg-gray-50">
                    <h3 class="font-semibold text-base">Cover Image</h3>
                </div>

                <div class="p-4">
                    <div x-data="fileManager('{{ $item->cover_image ?? old('cover_image', '') }}', 'cover_image')">

                        <button type="button"
                                @click="$dispatch('open-file-manager', { callback: callbackName })"
                                class="btn btn-outline-primary w-full">
                            Choose Cover Image
                        </button>

                        <input type="hidden" name="cover_image" x-model="fileUrl">

                        <template x-if="filePreview">
                            <img :src="filePreview"
                                 class="mt-3 rounded-lg border object-cover w-full max-h-[160px]">
                        </template>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="btn btn-primary">
            Save Team
        </button>
    </div>

</form>
</x-layout.default>