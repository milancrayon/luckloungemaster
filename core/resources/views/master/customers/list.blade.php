@extends('master.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Email-Mobile')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $customer->fullname }}</span>
                                    <br>
                                    <span class="small">
                                        <a href="{{ route('master.customers.detail', $customer->id) }}"><span>@</span>{{ $customer->customername }}</a>
                                    </span>
                                </td>
                                <td>
                                    {{ $customer->email }}<br>{{ $customer->mobileNumber }}
                                </td>
                                <td>
                                    <span class="fw-bold" title="{{ @$customer->country_name }}">{{ $customer->country_code }}</span>
                                </td>
                                <td>
                                    {{ showDateTime($customer->created_at) }} <br> {{ diffForHumans($customer->created_at) }}
                                </td>
                                <td>
                                    <span class="fw-bold">
                                        {{ showAmount($customer->balance) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('master.customers.detail', $customer->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>
                                        @if (request()->routeIs('master.customers.kyc.pending'))
                                        <a href="{{ route('master.customers.kyc.details', $customer->id) }}" target="_blank" class="btn btn-sm btn-outline--dark">
                                            <i class="las la-customer-check"></i>@lang('KYC Data')
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($customers->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($customers) }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Username / Email" />
<a href="{{ route('masters.customer.add') }}" class="btn btn--primary btn--shadow w-max custom_add_button btn-lg">Add Customer</a>
@endpush