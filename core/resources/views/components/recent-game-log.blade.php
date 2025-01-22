<section class="recent-game-log">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="mybet-tab" data-bs-toggle="tab" data-bs-target="#mybet" type="button"
                role="tab" aria-controls="mybet" aria-selected="true">@lang('My Bets')</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="allbets-tab" data-bs-toggle="tab" data-bs-target="#allbets" type="button"
                role="tab" aria-controls="allbets" aria-selected="false">@lang('All Bets')</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="mybet" role="tabpanel" aria-labelledby="mybet-tab">
            <div class="table--responsive">
                <table class="table style--two">
                    <thead>
                        <tr>
                            <th>@lang('Game')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Win or Lost')</th>
                            <!-- <th>@lang('Payout')</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mylogs as $_log) 
                            <tr>
                                <td>{{$_log->game->name}}</td>
                               
                                <td> {{ showDateTime($_log->created_at) }}<br>{{ diffForHumans($_log->created_at) }}
                                </td>
                                <td>{{ showAmount($_log->invest) }}</td>
                                <td> @if ($_log->win_status != Status::LOSS)
                                    <span class="badge badge--success">@lang('Win')</span>
                                @else
                                    <span class="badge badge--danger">@lang('Loss')</span>
                                @endif
                                </td>
                                <!-- <<td>{{ showAmount($_log->win_amo) }}</td> -->
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __("No data fetch !!") }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade mt-3 ms-2 text-center" id="allbets" role="tabpanel" aria-labelledby="allbets-tab">
            <div class="table--responsive">
                <table class="table style--two">
                    <thead>
                        <tr>
                            <th>@lang('Game')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Win or Lost')</th>
                            <!-- <th>@lang('Payout')</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alllogs as $_log) 
                            <tr>
                                <td>{{$_log->game->name}}</td>
                                <td>{{$_log->user->username}}</td>
                                <td> {{ showDateTime($_log->created_at) }}<br>{{ diffForHumans($_log->created_at) }}
                                </td>
                                <td>{{ showAmount($_log->invest) }}</td>
                                <td> @if ($_log->win_status != Status::LOSS)
                                    <span class="badge badge--success">@lang('Win')</span>
                                @else
                                    <span class="badge badge--danger">@lang('Loss')</span>
                                @endif
                                </td>
                                <!-- <td>{{ showAmount($_log->win_amo) }}</td> -->
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __("No data fetch !!") }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>