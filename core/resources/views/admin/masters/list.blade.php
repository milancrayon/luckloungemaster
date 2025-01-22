@extends('admin.layouts.app')
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
                                @forelse($masters as $master)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $master->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.masters.detail', $master->id) }}"><span>@</span>{{ $master->mastername }}</a>
                                            </span>
                                        </td>


                                        <td>
                                            {{ $master->email }}<br>{{ $master->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold" title="{{ @$master->country_name }}">{{ $master->country_code }}</span>
                                        </td>



                                        <td>
                                            {{ showDateTime($master->created_at) }} <br> {{ diffForHumans($master->created_at) }}
                                        </td>


                                        <td>
                                            <span class="fw-bold">

                                                {{ showAmount($master->balance) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.masters.detail', $master->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                @if (request()->routeIs('admin.masters.kyc.pending'))
                                                    <a href="{{ route('admin.masters.kyc.details', $master->id) }}" target="_blank" class="btn btn-sm btn-outline--dark">
                                                        <i class="las la-master-check"></i>@lang('KYC Data')
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
                @if ($masters->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($masters) }}
                    </div>
                @endif
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
    <a href="{{ route('admin.masters.add') }}" class="btn btn--primary btn--shadow w-100 btn-lg">Add Master</a>
@endpush
