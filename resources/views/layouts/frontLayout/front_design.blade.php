<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if (!empty($meta_title)) {{ $meta_title }} @else Home | E-Shopper @endif</title>
    @if (!empty($meta_description))<meta name="description" content="{{ $meta_description }}">
    @endif
    
    @if (!empty($meta_keywords))<meta name="keywords" content="{{ $meta_keywords }}">
    @endif
    
    <link href="{{asset('css/frontend_css')}}/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('css/frontend_css')}}/font-awesome.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css"> --}}
    <link href="{{asset('css/frontend_css')}}/prettyPhoto.css" rel="stylesheet">
    <link href="{{asset('css/frontend_css')}}/price-range.css" rel="stylesheet">
    <link href="{{asset('css/frontend_css')}}/animate.css" rel="stylesheet">
	<link href="{{asset('css/frontend_css')}}/main.css" rel="stylesheet">
	<link href="{{asset('css/frontend_css')}}/responsive.css" rel="stylesheet">
	<link href="{{asset('css/frontend_css')}}/easyzoom.css" rel="stylesheet">
	<link href="{{asset('css/frontend_css')}}/passtrength.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="{{asset('images/frontend_images')}}/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('images/frontend_images')}}/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('images/frontend_images')}}/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('images/frontend_images')}}/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/frontend_images')}}/ico/apple-touch-icon-57-precomposed.png">
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5f967c24fade1a001278964c&product=inline-share-buttons" async="async"></script>
</head><!--/head-->

<body>
    
    @include('layouts.frontLayout.front_header')
	
	@yield('content')
	
	@include('layouts.frontLayout.front_footer')
	

  
    <script src="{{asset('js/frontend_js')}}/jquery.js"></script>
	<script src="{{asset('js/frontend_js')}}/bootstrap.min.js"></script>
	<script src="{{asset('js/frontend_js')}}/jquery.scrollUp.min.js"></script>
	<script src="{{asset('js/frontend_js')}}/price-range.js"></script>
    <script src="{{asset('js/frontend_js')}}/jquery.prettyPhoto.js"></script>
    <script src="{{asset('js/frontend_js')}}/easyzoom.js"></script>
    <script src="{{asset('js/frontend_js')}}/main.js"></script>
    <script src="{{asset('js/frontend_js')}}/jquery.validate.js"></script>
    <script src="{{asset('js/frontend_js')}}/passtrength.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <!-- Go to www.addthis.com/dashboard to customize your tools --> 
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f95d3ed6a546c19"></script>

</body>
</html>