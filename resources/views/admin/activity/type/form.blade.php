@extends('layout.dashboard')
@section('content')
<main x-data="activity"
	  x-init="
        showOnLandingPage = @php echo !empty($item) && $item->show_landing_page == 1 ? true : '0' @endphp;
	  	image_icon_url = '@php echo empty($item) ? '' : $item->icon @endphp';
	  	image_banner_url = '@php echo empty($item) ? '' : $item->banner @endphp';
        recurring = @php echo !empty($selected_days) && count($selected_days) > 0 ? true : '0' @endphp;
	  "
	>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="mx-auto max-w-270">
        @include('partials.alert')
        <!-- ====== Settings Section Start -->
            <div
                class="w-full rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
					@if (empty($item))
						Menambahkan jenis kegiatan baru
					@else
						Mengupdate jenis kegiatan baru 
					@endif
                </h3>
                </div> 
                <div class="p-7">
				@php
					$form = Form::open(['route' => 'activity.type.update', 'method' => 'POST', 'enctype' => 'multipart/form-data']);
					if (empty($item)) $form = Form::open(['route' => 'activity.type.create', 'method' => 'POST', 'enctype' => 'multipart/form-data']);	
				@endphp 
                {{ $form }}
                    <div class="mb-5.5">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Nama
                          </label>
						@if (!empty($item))
						  <input type="hidden" name="id" value="{{ $item->id }}">
						@endif
                        <input type="text" placeholder="Isi nama jenis kegiatan" name="name"
                            value="{{ old('name', !empty($item->name) ? $item->name : '') }}"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        @error('name')
                            <span class="text-sm text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-5.5">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Nama Partisipan
                          </label>
                        <input type="text" placeholder="Isi dengan nama orang yang ikut serta atau yang memimpin kegiatan ini" name="leader"
                            value="{{ old('leader', !empty($item->leader) ? $item->leader : '') }}"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        @error('leader')
                            <span class="text-sm text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-5.5">
                        <div class="flex justify-between w-full gap-4">
                            <div class="w-1/2">
                                <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                                    Tanggal & Waktu Mulai
                                  </label>
                                <input type="datetime-local" name="start_time" value="{{ old('start_time', !empty($item->start_time) ? $item->start_time : '') }}" class="custom-input-date custom-input-date-2 w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                @error('start_time')
                                    <span class="text-sm text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                                    Tanggal & Waktu Selesai
                                  </label>
                                <input type="datetime-local" name="end_time" value="{{ old('end_time', !empty($item->end_time) ? $item->end_time : '') }}" class="custom-input-date custom-input-date-2 w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                @error('end')
                                    <span class="text-sm text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-5.5">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Berulang
                        </label>
                        <div class="relative">
                          <input type="checkbox" name="recurring" id="toggle4" class="sr-only" @change="recurring = !recurring">
                          <div :class="recurring && '!right-1 !translate-x-full' && '!bg-primary'" class="block h-8 w-14 rounded-full bg-black" @click="changeRecurring"></div>
                            <div :class="recurring && '!right-1 !translate-x-full'" @click="changeRecurring" class="absolute left-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-white transition"></div>
                        </div>
                    </div>

                    <div class="mb-5.5" x-show="recurring">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Hari Berulang
                        </label>
                        @foreach ($days as $each_day)
                            @php
                                $checked = FALSE;
                                if (!empty($selected_days) && in_array($each_day['id'], $selected_days))
                                    $checked = TRUE;
                            @endphp
                            <label for="{{ $each_day['id'] }}" class="flex cursor-pointer select-none items-center">
                                <div class="relative">
                                <input name="recurring_days[]" type="checkbox" id="{{ $each_day['id'] }}" value="{{ $each_day['id'] }}" {{ $checked ? 'checked' : '' }}>
                                </div>
                                <span class="ml-2">{{ $each_day['name'] }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-5.5">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Deskripsi
                        </label>
                        <textarea rows="6" placeholder="Isi deskripsi jenis kegiatan" name="description"
                          class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">{{ old('description', !empty($item->description) ? $item->description : '') }}</textarea>
                        @error('description')
                            <span class="text-sm text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="mb-5.5  lg:flex lg:gap-4.5">
                        <div class="flex-1">
                            <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                                Banner
                            </label>
                            <div id="FileUpload"
                                class="relative mb-5.5 block w-full cursor-pointer appearance-none rounded border-2 border-dashed border-primary bg-gray py-4 px-4 dark:bg-meta-4 sm:py-7.5">
                                <input type="file" accept="image/*"
                                    name="banner"
                                    @change.debounce="handleChangeImageBanner"
                                    class="absolute inset-0 z-50 m-0 h-full w-full cursor-pointer p-0 opacity-0 outline-none" />
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M1.99967 9.33337C2.36786 9.33337 2.66634 9.63185 2.66634 10V12.6667C2.66634 12.8435 2.73658 13.0131 2.8616 13.1381C2.98663 13.2631 3.1562 13.3334 3.33301 13.3334H12.6663C12.8431 13.3334 13.0127 13.2631 13.1377 13.1381C13.2628 13.0131 13.333 12.8435 13.333 12.6667V10C13.333 9.63185 13.6315 9.33337 13.9997 9.33337C14.3679 9.33337 14.6663 9.63185 14.6663 10V12.6667C14.6663 13.1971 14.4556 13.7058 14.0806 14.0809C13.7055 14.456 13.1968 14.6667 12.6663 14.6667H3.33301C2.80257 14.6667 2.29387 14.456 1.91879 14.0809C1.54372 13.7058 1.33301 13.1971 1.33301 12.6667V10C1.33301 9.63185 1.63148 9.33337 1.99967 9.33337Z"
                                        fill="#3C50E0" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.5286 1.52864C7.78894 1.26829 8.21106 1.26829 8.4714 1.52864L11.8047 4.86197C12.0651 5.12232 12.0651 5.54443 11.8047 5.80478C11.5444 6.06513 11.1223 6.06513 10.8619 5.80478L8 2.94285L5.13807 5.80478C4.87772 6.06513 4.45561 6.06513 4.19526 5.80478C3.93491 5.54443 3.93491 5.12232 4.19526 4.86197L7.5286 1.52864Z"
                                        fill="#3C50E0" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.99967 1.33337C8.36786 1.33337 8.66634 1.63185 8.66634 2.00004V10C8.66634 10.3682 8.36786 10.6667 7.99967 10.6667C7.63148 10.6667 7.33301 10.3682 7.33301 10V2.00004C7.33301 1.63185 7.63148 1.33337 7.99967 1.33337Z"
                                        fill="#3C50E0" />
                                    </svg>
                                    </span>
                                    <p class="font-medium text-sm">
                                    <span class="text-primary">Click to upload</span>
                                    or drag and drop
                                    </p>
                                    <p class="mt-1.5 font-medium text-sm">SVG, PNG, JPG or GIF</p>
                                    <p class="font-medium text-sm">(max, 800 X 800px)</p>
                                </div>
                            </div>
                            @error('banner')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>
							<div class="flex-1" x-show="image_banner_url != ''">
								<label class="mb-3 block font-medium text-sm text-black dark:text-white">
									Preview Gambar
								</label>
								<div class="h-24 w-24">
									<img src="/img/user/user-01.png" :src="image_banner_url" alt="User" />
								</div>
							</div>
                    </div>

					<div class="mb-5.5  lg:flex lg:gap-4.5">
                        <div class="flex-1">
                            <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                                Icon
                            </label>
                            <div id="FileUpload"
                                class="relative mb-5.5 block w-full cursor-pointer appearance-none rounded border-2 border-dashed border-primary bg-gray py-4 px-4 dark:bg-meta-4 sm:py-7.5">
                                <input type="file" accept="image/*"
                                    name="icon"
                                    @change.debounce="handleChangeImageIcon"
                                    class="absolute inset-0 z-50 m-0 h-full w-full cursor-pointer p-0 opacity-0 outline-none" />
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M1.99967 9.33337C2.36786 9.33337 2.66634 9.63185 2.66634 10V12.6667C2.66634 12.8435 2.73658 13.0131 2.8616 13.1381C2.98663 13.2631 3.1562 13.3334 3.33301 13.3334H12.6663C12.8431 13.3334 13.0127 13.2631 13.1377 13.1381C13.2628 13.0131 13.333 12.8435 13.333 12.6667V10C13.333 9.63185 13.6315 9.33337 13.9997 9.33337C14.3679 9.33337 14.6663 9.63185 14.6663 10V12.6667C14.6663 13.1971 14.4556 13.7058 14.0806 14.0809C13.7055 14.456 13.1968 14.6667 12.6663 14.6667H3.33301C2.80257 14.6667 2.29387 14.456 1.91879 14.0809C1.54372 13.7058 1.33301 13.1971 1.33301 12.6667V10C1.33301 9.63185 1.63148 9.33337 1.99967 9.33337Z"
                                        fill="#3C50E0" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.5286 1.52864C7.78894 1.26829 8.21106 1.26829 8.4714 1.52864L11.8047 4.86197C12.0651 5.12232 12.0651 5.54443 11.8047 5.80478C11.5444 6.06513 11.1223 6.06513 10.8619 5.80478L8 2.94285L5.13807 5.80478C4.87772 6.06513 4.45561 6.06513 4.19526 5.80478C3.93491 5.54443 3.93491 5.12232 4.19526 4.86197L7.5286 1.52864Z"
                                        fill="#3C50E0" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.99967 1.33337C8.36786 1.33337 8.66634 1.63185 8.66634 2.00004V10C8.66634 10.3682 8.36786 10.6667 7.99967 10.6667C7.63148 10.6667 7.33301 10.3682 7.33301 10V2.00004C7.33301 1.63185 7.63148 1.33337 7.99967 1.33337Z"
                                        fill="#3C50E0" />
                                    </svg>
                                    </span>
                                    <p class="font-medium text-sm">
                                    <span class="text-primary">Click to upload</span>
                                    or drag and drop
                                    </p>
                                    <p class="mt-1.5 font-medium text-sm">SVG, PNG, JPG or GIF</p>
                                    <p class="font-medium text-sm">(max, 800 X 800px)</p>
                                </div>
                            </div>
                            @error('icon')
                                <span class="text-sm text-danger">{{ $message }}</span>
                            @enderror
                        </div>
							<div class="flex-1" x-show="image_icon_url != ''">
								<label class="mb-3 block font-medium text-sm text-black dark:text-white">
									Preview Gambar
								</label>
								<div class="h-24 w-24">
									<img src="/img/user/user-01.png" :src="image_icon_url" alt="User" />
								</div>
							</div>
                    </div>
                    <div class="mb-5.5">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Tampilan dihalaman depan
                        </label>
                        <div class="relative">
                          <input type="checkbox" x-model="showOnLandingPage" name="show_landing_page" id="toggle4" class="sr-only" @change="showOnLandingPage = !showOnLandingPage">
                          <div :class="showOnLandingPage && '!right-1 !translate-x-full' && '!bg-primary'" class="block h-8 w-14 rounded-full bg-black" @click="changeShowOnLandingPage"></div>
                            <div :class="showOnLandingPage && '!right-1 !translate-x-full'" @click="changeShowOnLandingPage" class="absolute left-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-white transition"></div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4.5">
                    <button
                        class="flex justify-center rounded bg-primary py-2 px-6 font-medium text-gray hover:bg-opacity-90"
                        type="submit">
                        Simpan
                    </button>
                    </div>
                {{ Form::close() }}
                </div>
            </div>
    
        <!-- ====== Settings Section End -->
        </div>
    </div>
</main>
@endsection
@push('scripts')
    <script src="{{ asset('tinymce/tinymce.js') }}"></script>
    <script>
        tinymce.init({
            selector:'textarea',
            height: 700
        });
    </script>
@endpush