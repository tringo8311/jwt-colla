<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MySpa247 - @yield('title')</title>
    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <!-- Application Dependencies -->
    {!! Html::style('assets/css/admin.css')!!}
    {!! Html::script('assets/js/admin.js') !!}
    <!-- Application Scripts -->
    <!--{!! Html::script('public/scripts/app.js') !!}-->
</head>
<body>
    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <img src="/assets/img/logo_512.png" width="60" height="79" alt="Logo"/>
                    <h3>MySpa247</h3>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
</body>
</html>