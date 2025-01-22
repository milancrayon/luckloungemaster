<div class="gametype dropdown">
    <button class="gametype-wrapper" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="gametype-content">
            <p class="gametype_text_select">
                @if (session('game_type') == 'upcoming') @lang('Upcoming') @endif
                @if (session('game_type') != 'upcoming') @lang('Live') @endif

            </p>
        </div>
        <span class="collapse-icon"><i class="las la-angle-down"></i></span>
    </button>
    <div class="dropdown-menu langList_dropdow py-2" style="">
        <ul class="langList">
            <li class="gametype-list langSel">
                <a class="gametype_text" href="{{ route('switch.type', 'live') }}"> @lang('Live') </a>
            </li>
            <li class="gametype-list langSel">
                <a class="gametype_text" href="{{ route('switch.type', 'upcoming') }}"> @lang('Upcoming') </a>
            </li>
        </ul>
    </div>
</div>
@push('style')
    <style>
        .nav-right .langSel {
            padding: 7px 10px;
            height: 37px;
        }

        .gametype.dropdown {
            margin-left: 20px;
        }

        @media(max-width: 1199px) {
            .gametype.dropdown {
                margin-left: 0;
                margin-top: 20px;
            }
        }

        .gametype-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 5px 12px;
            border-radius: 4px;
            width: 110px;
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            height: 38px;
        }

        .gametype_flag {
            flex-shrink: 0;
            display: flex;
        }

        .gametype_flag img {
            height: 20px;
            width: 20px;
            object-fit: cover;
            border-radius: 50%;
        }

        .gametype-wrapper.show .collapse-icon {
            transform: rotate(180deg)
        }

        .collapse-icon {
            font-size: 14px;
            display: flex;
            transition: all linear 0.2s;
            color: #ffffff;
        }

        .gametype_text_select {
            font-size: 14px;
            font-weight: 400;
            color: #ffffff;
        }

        .gametype-content {
            display: flex;
            align-items: center;
            gap: 6px;
        }


        .gametype_text {
            color: #ffffff;
            border:0;
        }

        .gametype-list {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            cursor: pointer;
        }

        .gametype .dropdown-menu {
            position: absolute;
            -webkit-transition: ease-in-out 0.1s;
            transition: ease-in-out 0.1s;
            opacity: 0;
            visibility: hidden;
            top: 100%;
            display: unset;
            background: #2a313b;
            -webkit-transform: scaleY(1);
            transform: scaleY(1);
            min-width: 150px;
            padding: 7px 0 !important;
            border-radius: 8px;
            border: 1px solid rgb(255 255 255 / 10%);
        }

        .gametype .dropdown-menu.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endpush