<form id="searchgame" class="search_form" method="post" action="{{ route('search') }}">
    @csrf
    <div class="d-flex d-md-block flex-wrap">
        <div class="form-group text-center">
            <div class="input-group mb-3">
                <input class="form-control search-field" name="search" type="text" value="{{ old('search') }}"
                    placeholder="@lang('Search game')">
                <button class="cmn-btn w-100"  ><i class="las la-search"></i></button>
                <div class="search-result"></div>
            </div>
        </div>
    </div>
</form>
@push('script')
    <script>
        (function ($) {
            $(window).resize(function () {
                if (window.innerWidth >= 1200) {
                    $(document).on("click", ".search_form .cmn-btn.w-100", function (event) {
                        if ($('.search_form #search').is(':hidden') || $('.search_form #search.show').length == 1) {
                            if ($('.search_form #search').val() == "") {
                                event.preventDefault();
                                $('.search_form #search').toggleClass('show');
                            }
                        }
                    });
                } else {
                    $('.search_form #search').removeClass('show');
                }
            })
            $(document).on("click", ".search_form .cmn-btn.w-100", function (event) {
                if ($('.search_form #search').is(':hidden') || $('.search_form #search.show').length == 1) {
                    if ($('.search_form #search').val() == "") {
                        event.preventDefault();
                        $('.search_form #search').toggleClass('show');
                    }
                }
            }); 
            $(document).on("keyup", ".search_form .search-field", function (e) {

                $.ajax({ 
                    url: '/searchgame',
                    method: "POST",
                    data: { 
                        search: $(this).val(),
                        _token:$('.search_form input[name="_token"]').val(),
                     },
                    success:async  function (data) {
                        let _searchres = `<ul> `;
                        if (data.games.length > 0) {
                            await data.games.map((_gm) => {
                                _searchres = _searchres + '<li><a href="/user/play/game/' + _gm.alias + '">' + _gm.name + '</a></li>';
                            });
                        } else {
                            if($(this).val() != ""){
                                _searchres = _searchres + '<li>No result found</li>';
                            }
                        }
                        _searchres = _searchres + ' </ul>';
                        $('.search_form .search-result').html(_searchres);
                    },
                }); 
            });

        })(jQuery);
    </script>
@endpush