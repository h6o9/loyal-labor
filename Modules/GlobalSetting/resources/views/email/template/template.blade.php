@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Template') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Template') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Email Configuration') => route('admin.email-configuration'),
                __('Edit Template') => '#',
            ]" />

            @if (isset($variables) && count($variables ?? 0) > 0)
                <div class="section-body">
                    <div class="row mt-4">
                        <div class="col">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <x-admin.form-title :text="__('Edit Template')" />
                                    <div>
                                        <x-admin.back-button :href="route('admin.email-configuration')" />
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>{{ __('Variable') }}</th>
                                            <th>{{ __('Meaning') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($variables as $variable)
                                                @php
                                                    $placeholder = '{{ ' . $variable . ' }}';
                                                    $name = (string) str($variable)->replace('_', ' ')->title();
                                                @endphp
                                                <tr>
                                                    <td>{{ (string) str($placeholder)->replace(' ', '') ?? '' }}</td>
                                                    <td>{{ __($name) ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-email-template', $template->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <x-admin.form-input id="subject" name="subject" value="{{ $template->subject }}"
                                            label="{{ __('Subject') }}" required="true" />
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-editor id="message" name="message" value="{!! $template->message !!}"
                                            label="{{ __('Message') }}" required="true" />
                                    </div>
                                    <x-admin.update-button :text="__('Update')" />
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
