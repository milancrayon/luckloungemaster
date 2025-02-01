@php
    if (isset($seoContents)) {
        $seoContents = (object) $seoContents;
        $socialImageSize = explode('x', getFileSize('seo'));
    } elseif ($seo) {
        $seoContents = $seo;
        $socialImageSize = explode('x', getFileSize('seo'));
        $seoContents->image = getImage(getFilePath('seo') . '/' . $seo->image);
    } else {
        $seoContents = null;
    }
@endphp

<!-- <meta name="title" Content="{{ gs()->sitename(__($pageTitle)) }}"> -->
@if(Request::is('user/play/game/roulettee'))
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui">
<meta name="msapplication-tap-highlight" content="no" />
@else
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@endif
@if ($seoContents)
    <!-- <meta name="description" content="{{ $seoContents->meta_description ?? $seoContents->description }}">
    <meta name="keywords" content="{{ $seoContents->keywords  }}"> -->
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">

    {{--
    <!-- Apple Stuff --> --}}
    <link rel="apple-touch-icon" href="{{ siteLogo() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- <meta name="apple-mobile-web-app-title" content="{{ gs()->sitename($pageTitle) }}"> -->
    {{--
    <!-- Google / Search Engine Tags --> --}}
    <!-- <meta itemprop="name" content="{{ gs()->sitename($pageTitle) }}"> -->
    <meta itemprop="description" content="{{ $seoContents->description }}">
    <meta itemprop="image" content="{{ $seoContents->image }}">
    {{--
    <!-- Facebook Meta Tags --> --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoContents->social_title }}">
    <meta property="og:description" content="{{ $seoContents->social_description }}">
    <meta property="og:image" content="/{{ getFilePath('seo') ."/". $seoContents->image }}" />
    @php
        if(isset(pathinfo($seoContents->image)['extension'])){
           echo '<meta property="og:image:type" content="'.pathinfo($seoContents->image)["extension"] .'" />';
        }
    @endphp
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}" />
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    {{--
    <!-- Twitter Meta Tags --> --}}
    <meta name="twitter:card" content="summary_large_image">
@endif
