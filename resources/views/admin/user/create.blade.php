<x-layout.default>
    @section('title', $pageTitle)
    <div class="panel mt-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">{{ $pageTitle }}</h1>
        <div>
            <form method="POST"  action="{{ route('user.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="mb-2">
                        <label for="name" class="">{{ __('Name') }}</label>
                        @if(isset($item))
                            <input type="hidden" name="edit_id" value="{{ $item->id }}">
                        @endif
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="name" type="text" value="{{ old('name', $item->name ?? '') }}" class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="username" class="">{{ __('Username') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="username" type="text" value="{{ old('username', $item->username ?? '') }}" class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="mb-2">
                        <label for="phone" class="">{{ __('Phone') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="phone" type="text" value="{{ old('phone', $item->phone ?? '') }}" class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="link" class="">{{ __('Email') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <input name="email" type="email" value="{{ old('email', $item->email ?? '') }}" class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="serial" class="block text-gray-700 font-medium mb-2">{{ __('Role') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <select name="role_id" id="" class="form-select">
                                <option value="">{{__('Select')}}</option>
                                @if(isset($roles[0]))
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="serial" class="block text-gray-700 font-medium mb-2">{{ __('Activation Status') }}</label>
                        <div class="flex">
                            {!! defaultInputIcon() !!}
                            <select name="status" id="" class="form-select">
                                <option value="">{{__('Select')}}</option>
                                @foreach(\App\Enums\StatusEnum::getStatusArray() as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        {{ old('status', $item->status ?? '') == $value ? 'selected' : '' }}
                                    >
                                        {{ __($label) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div
                        x-data="fileManager('{{ $item->image ?? '' }}', 'image')"
                        class="space-y-2"
                    >

                        <label class="font-semibold text-gray-700">
                            {{__('Avatar')}}
                        </label>

                        <!-- Trigger File Manager -->
                        <button
                            type="button"
                            @click="$dispatch('open-file-manager', { callback: callbackName })"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg
                   shadow hover:bg-blue-700"
                        >
                            Choose Image
                        </button>

                        <!-- Hidden input -->
                        <input type="hidden" name="image" x-model="fileUrl">

                        <!-- Preview -->
                        <template x-if="filePreview">
                            <div class="mt-3">
                                <img
                                    :src="filePreview"
                                    class="rounded-xl border object-cover shadow-sm"
                                    width="200"
                                >
                            </div>
                        </template>

                    </div>

                </div>
                @if(isset($item)) @customFields($item) @else @customFields(\App\Models\User::class) @endif

                <div>
                    <button type="submit" class="btn btn-secondary mt-6">{{__('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>
