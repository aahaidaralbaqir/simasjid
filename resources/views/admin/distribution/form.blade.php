@extends('layout.dashboard')
@section('content')
<main>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="mx-auto max-w-270">
        @include('partials.alert')
        <!-- ====== Settings Section Start -->
            <div
                class="w-full rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">
					@if (empty($item))
						Menambahkan distribusi dana
					@else
						Mengupdate distribusi dana
					@endif
                </h3>
                </div> 
                <div class="p-7">
				@php
					$form = Form::open(['route' => 'distribution.update', 'method' => 'POST', 'enctype' => 'multipart/form-data']);
					if (empty($item)) $form = Form::open(['route' => 'distribution.create', 'method' => 'POST', 'enctype' => 'multipart/form-data']);	
				@endphp 
                {{ $form }}
				<div class="mb-5.5">
					<label class="mb-3 block font-medium text-sm text-black dark:text-white">
					Unit
					</label>
					<div class="relative z-20 bg-transparent dark:bg-form-input">
						<select name="unit_id" class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
							<option value="0">Pilih Satuan</option>
							@foreach ($unit as $each_unit)
								@php
									$selected = FALSE;
									if (old('unit_id', !empty($item) ? $item->unit_id : 0) == $each_unit->id)
										$selected = TRUE;
								@endphp 
								<option {{ $selected ? 'selected' : '' }} value="{{ $each_unit->id }}">{{  $each_unit->name }}</option>
							@endforeach
						</select>
						<span class="absolute top-1/2 right-4 z-30 -translate-y-1/2">
							<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g opacity="0.8">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 8.29289C5.68342 7.90237 6.31658 7.90237 6.70711 8.29289L12 13.5858L17.2929 8.29289C17.6834 7.90237 18.3166 7.90237 18.7071 8.29289C19.0976 8.68342 19.0976 9.31658 18.7071 9.70711L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L5.29289 9.70711C4.90237 9.31658 4.90237 8.68342 5.29289 8.29289Z" fill=""></path>
							</g>
							</svg>
						</span>
					</div>
					@error('unit_id')
						<span class="text-sm text-danger">{{ $message }}</span>
					@enderror
				</div>
					<div class="mb-5.5">
						<input type="hidden" name="id" value="{{ !empty($item) ? $item->id : 0 }}">
						<label class="mb-3 block font-medium text-sm text-black dark:text-white">
						Jenis Sumber Dana 
						</label>
						<div class="relative z-20 bg-transparent dark:bg-form-input">
							<select name="id_transaction_type" class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
								<option value="0">Pilih Sumber Dana</option>
								@foreach ($transaction_type as $transaction)
									@php
										$selected = FALSE;
										if (old('id_transaction_type', !empty($item) ? $item->id_transaction_type : 0) == $transaction->id)
											$selected = TRUE;
									@endphp 
									<option {{ $selected ? 'selected' : '' }} value="{{ $transaction->id }}">{{  $transaction->name }}</option>
								@endforeach
							</select>
							<span class="absolute top-1/2 right-4 z-30 -translate-y-1/2">
								<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g opacity="0.8">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 8.29289C5.68342 7.90237 6.31658 7.90237 6.70711 8.29289L12 13.5858L17.2929 8.29289C17.6834 7.90237 18.3166 7.90237 18.7071 8.29289C19.0976 8.68342 19.0976 9.31658 18.7071 9.70711L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L5.29289 9.70711C4.90237 9.31658 4.90237 8.68342 5.29289 8.29289Z" fill=""></path>
								</g>
								</svg>
							</span>
						</div>
						<input 
							type="text" 
							name="total_amount"
							disabled
                            class="hidden mt-2 w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" 
						/>
						@error('id_transaction_type')
							<span class="text-sm text-danger">{{ $message }}</span>
						@enderror
					</div>
					
					<div class="mb-5.5">
						<label class="mb-3 block font-medium text-sm text-black dark:text-white">
							Metode Pengeluaran Dana
						</label>
						<div class="relative z-20 bg-transparent dark:bg-form-input">
							<select name="id_payment_type" class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
								<option value="0">Pilih Metode Pengeluaran Dana</option>
								@foreach ($payments as $payment)
									@php
										$selected = FALSE;
										if (old('id_payment_type', !empty($item) ? $item->id_payment_type : 0) == $payment->id)
											$selected = TRUE;
									@endphp 
									<option {{ $selected ? 'selected' : '' }} value="{{ $payment->id }}">{{  $payment->name }}</option>
								@endforeach
							</select>
							<span class="absolute top-1/2 right-4 z-30 -translate-y-1/2">
								<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g opacity="0.8">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 8.29289C5.68342 7.90237 6.31658 7.90237 6.70711 8.29289L12 13.5858L17.2929 8.29289C17.6834 7.90237 18.3166 7.90237 18.7071 8.29289C19.0976 8.68342 19.0976 9.31658 18.7071 9.70711L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L5.29289 9.70711C4.90237 9.31658 4.90237 8.68342 5.29289 8.29289Z" fill=""></path>
								</g>
								</svg>
							</span>
						</div>
						@error('id_payment_type')
							<span class="text-sm text-danger">{{ $message }}</span>
						@enderror
					</div>
					<div class="mb-5.5">
						<label class="mb-3 block font-medium text-sm text-black dark:text-white">
							Penerima Dana
						</label>
						<div class="relative z-20 bg-transparent dark:bg-form-input">
							<select name="id_beneficiery" class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
								<option value="0">Pilih Penerima Dana</option>
								@foreach ($beneficiary as $each_beneficiary)
									@php
										$selected = FALSE;
										if (old('id_beneficiery', !empty($item) ? $item->beneficiary_id : 0) == $each_beneficiary->id)
											$selected = TRUE;
									@endphp 
									<option {{ $selected ? 'selected' : '' }} value="{{ $each_beneficiary->id }}">{{  $each_beneficiary->name }}</option>
								@endforeach
							</select>
							<span class="absolute top-1/2 right-4 z-30 -translate-y-1/2">
								<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g opacity="0.8">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 8.29289C5.68342 7.90237 6.31658 7.90237 6.70711 8.29289L12 13.5858L17.2929 8.29289C17.6834 7.90237 18.3166 7.90237 18.7071 8.29289C19.0976 8.68342 19.0976 9.31658 18.7071 9.70711L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L5.29289 9.70711C4.90237 9.31658 4.90237 8.68342 5.29289 8.29289Z" fill=""></path>
								</g>
								</svg>
							</span>
						</div>
						@error('id_beneficiery')
							<span class="text-sm text-danger">{{ $message }}</span>
						@enderror
					</div>
					@if (!empty($item) && $item->user_id > 0)
						<div class="mb-5.5">
							<label class="mb-3 block font-medium text-sm text-black dark:text-white">
								Status Transaksi
							</label>
							<select name="transaction_status" class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
								<option value="0">Pilih Status Transaksi</option>
								@foreach($transaction_statuses as $transaction_id => $transaction_name)
									@php
										if (!in_array($transaction_id, [\App\Constant\Constant::TRANSACTION_DISTRIBUTED, \App\Constant\Constant::TRANSACTION_REQUESTED])) continue;
										$selected = FALSE;
										if (old('id_payment_type', !empty($item) ? $item->transaction_status : 0) == $transaction_id)
											$selected = TRUE;
									@endphp
									<option value="{{ $transaction_id }}" {{ $selected  ? 'selected' : ''}}  >{{ $transaction_name }}</option>
								@endforeach
							</select>
							@error('transaction_status')
								<span class="text-sm text-danger">{{ $message }}</span>
							@enderror
						</div>
					@endif
					<div class="mb-5.5">
						<label class="mb-3 block font-medium text-sm text-black dark:text-white">
							Nominal
						</label>
						<input 
							type="text" name="nominal"
                            value="{{ old('nominal', !empty($item->paid_amount) ? $item->paid_amount : '') }}"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" 
						/>
						@error('nominal')
							<span class="text-sm text-danger">{{ $message }}</span>
						@enderror
					</div>
					<div class="mb-5.5">
                        <label class="mb-3 block font-medium text-sm text-black dark:text-white">
                            Deskripsi
                          </label>
                        <textarea 
							rows="6"
							name="description" 
							class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">{{ old('description', !empty($item->description) ? $item->description : '') }}</textarea>
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
	<script>
		window.addEventListener('DOMContentLoaded', () => {
			let transactionType = document.querySelector('select[name="id_transaction_type"]');
			let unitId = document.querySelector('select[name="unit_id"]');
			let totalAmount = document.querySelector('input[name="total_amount"]')
			transactionType.addEventListener('change', (e) => {
				let transactionTypeId = e.target.value
				totalAmount.classList.remove('hidden')
				let userInput = {
					id_transaction_type: parseInt(transactionTypeId),
					unit_id: parseInt(unitId.value)
				}
				fetch("/api/transaction/type/summary", {
					method: "POST",
					headers: {
						"Content-type": "application/json; charset=UTF-8"
					},
					body: JSON.stringify(userInput),
				})
				.then(result => result.json())
				.then(({data}) => {
					totalAmount.value = data.data.total
				})
				.catch(error => {
				}); 
			})
		})
	</script>
@endpush