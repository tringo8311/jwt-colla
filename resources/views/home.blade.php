@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome to MySpa247</div>
                <div class="panel-body">
                    <p class="">Everything you need to start your day off.</p>
                    <p>Receive offer - locate your best salon - make appointment.</p>
                    @if (session('status'))
                    <div class="alert alert-success">
                         {{ session('status') }}
                    </div>
                    @endif

                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                       @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                       @endforeach
                    </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection