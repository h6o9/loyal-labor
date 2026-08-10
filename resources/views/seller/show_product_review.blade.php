@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product Review') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Product Review') }}" :list="[
                'Dashboard' => route('seller.dashboard'),
                'Product Review' => route('seller.product-review'),
                'Review Details' => '#',
            ]" />

            <div class="section-body">
                <a class="btn btn-primary" href="{{ route('seller.product-review') }}"><i class="fas fa-list"></i>
                    {{ __('Product Review') }}</a>
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <td>{{ __('User Name') }}</td>
                                            <td>{{ $review->user->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('User Email') }}</td>
                                            <td>{{ $review->user->email ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Product') }}</td>
                                            <td><a
                                                    href="{{ route('seller.product.show', $review->product->id) }}">{{ $review->product->name }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Rating') }}</td>
                                            <td>{{ $review->rating }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Review') }}</td>
                                            <td>{{ $review->review }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
