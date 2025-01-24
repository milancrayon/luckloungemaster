@php
    $statistics = getContent('statistics.element', false, null, true);

@endphp
<section class="statistics-section  section--bg">
    <div class="shape-1"></div>
    <div class="shape-2"></div>
    <div class="container">
        <div class="row  ">
            <div class="col-md-6 col-6 mb-10">
                <div class="stat-card">
                    <div class="stat-card__icon wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <i class="las la-users"></i>
                    </div>
                    <div class="stat-card__content wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        <h6 class="title">Total Users</h6>
                        <!-- <span class="numbers">{{ __(@$totalUsers) }}</span> -->
                        <span class="numbers">
                            @php
                                echo mt_rand(1000, 25000);
                            @endphp
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-6 mb-10">
                <div class="stat-card">
                    <div class="stat-card__icon wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <i class="las la-trophy"></i>
                    </div>
                    <div class="stat-card__content wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        <h6 class="title">Total Winners</h6>
                        <!-- <span class="numbers">{{ __(@$totalWin) }}</span> --> 
                        <span class="numbers">
                            @php
                                echo mt_rand(1000, 25000);
                            @endphp
                        </span> 
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3 col-6 mb-10">
                <div class="stat-card">
                    <div class="stat-card__icon wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <i class="las la-credit-card"></i>
                    </div>
                    <div class="stat-card__content wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        <h6 class="title">Total Deposit</h6>
                        <span class="numbers">{{ __(@$totalDeposit) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-10">
                <div class="stat-card">
                    <div class="stat-card__icon wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <i class="las la-download"></i>
                    </div>
                    <div class="stat-card__content wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        <h6 class="title">Total Withdraw</h6>
                        <span class="numbers">{{ __(@$totalWin) }}</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</section>