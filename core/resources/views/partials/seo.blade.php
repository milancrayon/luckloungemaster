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

<meta name="title" Content="{{ gs()->sitename(__($pageTitle)) }}">
@if(Request::is('user/play/game/roulettee'))
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui">
<meta name="msapplication-tap-highlight" content="no" />
@else
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@endif
@if ($seoContents)
    <meta name="description" content="{{ $seoContents->meta_description ?? $seoContents->description }}">
    <meta name="keywords" content="{{ $seoContents->keywords  }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">

 
@endif
