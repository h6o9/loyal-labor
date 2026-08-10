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
                'Product Review' => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="15%">{{ __('Name') }}</th>
                                                <th width="50%">{{ __('Product') }}</th>
                                                <th width="5%">{{ __('Rating') }}</th>
                                                <th width="10%">{{ __('Status') }}</th>
                                                <th width="10%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reviews as $index => $review)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $review->user->name ?? '' }}</td>
                                                    <td><a
                                                            href="{{ route('seller.product.show', $review->product->id) }}">{{ $review->product->name ?? '' }}</a>
                                                    </td>

                                                    <td>{{ $review->rating }}</td>
                                                    <td>
                                                        @if ($review->status == 1)
                                                            <span
                                                                class="badge badge-success">{{ __('Active') }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('seller.show-product-review', $review->id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-center">
                                <div class="text-center">
                                    {{ $reviews->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
