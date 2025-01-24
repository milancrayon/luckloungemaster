<script src="{{asset('assets/global/js/firebase/firebase-8.3.2.js')}}"></script>

<script>
    "use strict";

    var permission = null;
    var authenticated = '{{ auth()->user() ? true : false }}';
    var pushNotify = @json(gs('pn'));
    var firebaseConfig = @json(gs('firebase_config'));

    function pushNotifyAction(){
        permission = Notification.permission;

        if(!('Notification' in window)){
            notify('info', 'Push notifications not available in your browser. Try Chromium.')
        }
        else if(permission === 'denied' || permission == 'default'){ //Notice for users dashboard
            $('.notice').append(`
                <div class="row notification-alert">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header justify-content-between d-flex flex-wrap notice_notify">
                                <h5 class="alert-heading">@lang('Please Allow / Reset Browser Notification') <i class='las la-bell text--danger'></i></h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 small">@lang('If you want to get push notification then you have to allow notification from your browser')</p>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
    }

    //If enable push notification from admin panel
    if(pushNotify == 1){
        pushNotifyAction();
    }

   
</script>
