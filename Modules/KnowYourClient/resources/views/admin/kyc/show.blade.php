@extends('admin.master_layout')
@section('title')
    <title>{{ __('Update Kyc') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Update Kyc') }}</h1>
            </div>

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <form action="{{ route('admin.kyc-list.update-kyc-status', $kyc->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <p>
                                        <img class="img-fluid img-thumbnail" src="{{ asset($kyc->file) }}" alt=""
                                            width="100%" height="500px">
                                    </p>

                                    <p>{{ __('File') }}: <a href="{{ asset($kyc->file) }}" target="_blank"
                                            rel="noopener noreferrer">{{ __('Download') }}</a>
                                    </p>

                                    <p><b>{{ __('Message') }}:</b> {{ $kyc->message }}</p>

                                    <div class="form-group">
                                        <label class="form-label">{{ __('Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="" name="status">
                                            @foreach ($kycStatusEnum as $kycStatus)
                                                <option value="{{ $kycStatus->value }}" @selected($kyc->status->value == $kycStatus->value)>
                                                    {{ $kycStatus->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
