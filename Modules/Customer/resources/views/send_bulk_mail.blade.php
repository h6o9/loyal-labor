@extends('admin.master_layout')
@section('title')
<title>{{ __('Send bulk mail to all') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Send bulk mail to all') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Send bulk mail to all') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.send-bulk-mail-to-all') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <x-admin.form-input id="subject"  name="subject" label="{{ __('Subject') }}" placeholder="{{ __('Enter Subject') }}" value="{{ old('subject') }}" required="true"/>
                                    </div>

                                    <div class="form-group">
                                        <x-admin.form-editor id="description" name="description" label="{{ __('Description') }}" value="{!! old('description') !!}" required="true"/>
                                    </div>
                                    <x-admin.button type="submit" :text="__('Send Mail')" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
