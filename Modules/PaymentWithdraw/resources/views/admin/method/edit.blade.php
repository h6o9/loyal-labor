@extends('admin.master_layout')
@section('title')
<title>{{ __('Edit Withdraw Method') }}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Withdraw Method') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Withdraw Method') => route('admin.withdraw-method.index'),
                __('Edit Withdraw Method') => '#',
            ]" />
          <div class="section-body">
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <x-admin.form-title :text="__('Edit Withdraw Method')" />
                        <div>
                            <x-admin.back-button :href="route('admin.withdraw-method.index')" />
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.withdraw-method.update',$method->id) }}" method="POST" >
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <x-admin.form-input id="name"  name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}" value="{{ $method->name }}" required="true"/>
                                </div>

                                <div class="form-group col-12">
                                    <x-admin.form-input id="minimum_amount"  name="minimum_amount" label="{{ __('Minimum Amount') }}" placeholder="{{ __('Enter Minimum Amount') }}" value="{{ $method->min_amount }}" required="true"/>
                                </div>

                                <div class="form-group col-12">
                                    <x-admin.form-input id="maximum_amount"  name="maximum_amount" label="{{ __('Maximum Amount') }}" placeholder="{{ __('Enter Maximum Amount') }}" value="{{ $method->max_amount }}" required="true"/>
                                </div>

                                <div class="form-group col-12">
                                    <x-admin.form-input id="withdraw_charge"  name="withdraw_charge" label="{{ __('Withdraw Charge') }}(%)" placeholder="{{ __('Enter Withdraw Charge Percentage') }}" value="{{$method->withdraw_charge}}" required="true"/>
                                </div>

                                <div class="form-group col-12">
                                    <x-admin.form-editor data-translate="true" id="description" name="description" label="{{ __('Description') }}" value="{!! $method->description !!}" required="true"/>
                                </div>

                                <div class="form-group col-12">
                                    <x-admin.form-switch name="status" label="{{ __('Status') }}" active_value="active" inactive_value="inactive" :checked="$method->status == 'active'"/>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <x-admin.update-button :text="__('Update')" />
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>
@endsection
