@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table--responsive">
                                <table class="style--two table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Transaction')</th>
                                            <th>@lang('Date')</th>
                                            <th>@lang('Commission From')</th>
                                            <th>@lang('Commission Level')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Percent')</th>
                                            <th>@lang('Total')</th>
                                            <th>@lang('Title')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td>{{ $log->trx }}</td>
                                                <td>{{ showDateTime($log->created_at, 'd M, Y') }}</td>
                                                <td>{{ __($log->userFrom->username) }}</td>
                                                <td>{{ __($log->level) }}</td>
                                                <td>{{ __($log->main_amo) }}</td>
                                                <td>{{ __($log->percent) }}</td>
                                                <td>{{ showAmount($log->amount) }}</td>
                                                <td>{{ __($log->title) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($logs->hasPages())
                            <div class="card-footer">
                                {{ paginateLinks($logs) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
