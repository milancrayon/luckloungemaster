<div class="oddtype dropdown">
    <button class="oddtype-wrapper" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="oddtype-content">
            <p class="oddtype_text_select">
                @if (session('odds_type') == 'decimal') @lang('Decimal Odds') @endif
                @if (session('game_type') == 'fraction') @lang('Fraction Odds') @endif 
                @if (session('game_type') == 'american') @lang('American Odds') @endif 
            </p>
        </div>
        <span class="collapse-icon"><i class="las la-angle-down"></i></span>
    </button>
    <div class="dropdown-menu langList_dropdow py-2" style="">
        <ul class="langList">
            <li class="oddtype-list langSel">
                <a class="oddtype_text" href="{{ route('odds.type', 'decimal') }}"> @lang('Decimal Odds') </a>
            </li>
            <li class="oddtype-list langSel">
                <a class="oddtype_text" href="{{ route('odds.type', 'fraction') }}"> @lang('Fraction Odds') </a>
            </li>
            <li class="oddtype-list langSel">
                <a class="oddtype_text" href="{{ route('odds.type', 'american') }}"> @lang('American Odds') </a>
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

        .oddtype.dropdown {
            margin-left: 20px;
        }

        @media(max-width: 1199px) {
            .oddtype.dropdown {
                margin-left: 0;
                margin-top: 20px;
            }
        }

        .oddtype-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 5px 12px;
            border-radius: 4px;
            width: 200px;
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            height: 38px;
        }

        .oddtype_flag {
            flex-shrink: 0;
            display: flex;
        }

        .oddtype_flag img {
            height: 20px;
            width: 20px;
            object-fit: cover;
            border-radius: 50%;
        }

        .oddtype-wrapper.show .collapse-icon {
            transform: rotate(180deg)
        }

        .collapse-icon {
            font-size: 14px;
            display: flex;
            transition: all linear 0.2s;
            color: #ffffff;
        }

        .oddtype_text_select {
            font-size: 14px;
            font-weight: 400;
            color: #ffffff;
        }

        .oddtype-content {
            display: flex;
            align-items: center;
            gap: 6px;
        }


        .oddtype_text {
            color: #ffffff;
            border:0;
            padding: 0;
        }

        .oddtype-list {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            cursor: pointer;
        }

        .oddtype .dropdown-menu {
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

        .oddtype .dropdown-menu.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endpush