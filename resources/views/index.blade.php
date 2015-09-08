<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Angular-Laravel Authentication</title>
    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    {!! Html::style('public/node_modules/bootstrap/dist/css/bootstrap.css')!!}
    <!--link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css"-->
    {!! Html::script('public/node_modules/angular/angular.js') !!}
    {!! Html::script('public/node_modules/angular-ui-router/build/angular-ui-router.js') !!}
    {!! Html::script('public/node_modules/satellizer/satellizer.js') !!}

    {!! Html::script('public/scripts/app.js') !!}
    {!! Html::script('public/scripts/authController.js') !!}
    {!! Html::script('public/scripts/userController.js') !!}
</head>
<body ng-app="authApp">

<div class="container">
    <div ui-view></div>
</div>

</body>

<!-- Application Dependencies -->
<!--script src="node_modules/node_modules/angular/angular.js"></script>
<script src="node_modules/angular-ui-router/build/angular-ui-router.js"></script>
<script src="node_modules/satellizer/satellizer.js"></script-->

<!-- Application Scripts -->
<!--script src="scripts/app.js"></script>
<script src="scripts/authController.js"></script>
<script src="scripts/userController.js"></script-->
</html>