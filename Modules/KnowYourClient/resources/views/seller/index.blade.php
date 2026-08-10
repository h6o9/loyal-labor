@extends('vendor::layouts.master')

@section('title')
    <title>{{ __('Manage KYC') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Kyc') }}</h1>
            </div>

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                @if ($myKycInformation)
                                    <div class="card">
                                        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                                            <img class="img-fluid img-thumbnail {{ $isVerified ? 'border border-success' : 'border border-danger' }}"
                                                src="{{ asset($myKycInformation->file) }}" alt="" width="200px">
                                            <div class="service_detail">
                                                <h4>{{ $myKycInformation->shop->shop_name }}</h4>
                                                <p>{{ __('Document Name') }} : {{ $myKycInformation->type->name }}</p>
                                                <p>{{ __('Status') }} :
                                                    <span
                                                        class="badge bg-{{ $myKycInformation->status->color() }}">
                                                        {{ $myKycInformation->status->label() }}
                                                    </span>
                                                </p>
                                                <p>{{ __('File') }}: <a href="{{ asset($myKycInformation->file) }}"
                                                        target="_blank" rel="noopener noreferrer">{{ __('Download') }}</a>
                                                </p>
                                                <p>{{ __('Message') }} : {{ htmlDecode($myKycInformation->message) }}</p>
                                                <p>{{ __('Created At') }} :
                                                    {{ formattedDateTime($myKycInformation->created_at) }}</p>
                                                @if ($isVerified)
                                                    <p>{{ __('Verified At') }} :
                                                        {{ formattedDateTime($myKycInformation->verified_at) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('seller.kyc.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="mb-2 col-12">
                                                <label class="form-label"
                                                    for="kyc_type_id">{{ __('Select Document Type') }}
                                                    <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control select2" id="kyc_type_id" name="kyc_type_id">
                                                    <option value="">{{ __('Select Document Type') }}</option>
                                                    @foreach ($kycType as $type)
                                                        <option value="{{ $type->id }}" @selected($type->id == old('kyc_type_id'))>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-2 col-12">
                                                <label>{{ __('File') }} <span class="text-danger">*</span></label>
                                                <input class="form-control" name="file" type="file">
                                            </div>

                                            <div class="mb-2 col-12">
                                                <label class="form-label" for="message">{{ __('Message') }}</label>
                                                <textarea class="form-control" id="message" name="message" cols="30" rows="10">{{ old('message') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
