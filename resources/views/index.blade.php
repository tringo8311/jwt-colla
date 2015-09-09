<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Angular-Laravel Authentication</title>
    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <!-- Application Dependencies -->
    {!! Html::style('public/assets/css/admin.css')!!}
    <!--link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css"-->
    {!! Html::script('public/assets/js/admin.js') !!}
    <!-- Application Scripts -->
    <!--{!! Html::script('public/scripts/app.js') !!}-->
</head>
<body ng-app="authApp">
<div class="container">
    <div ui-view></div>
</div>
</body>
</html>