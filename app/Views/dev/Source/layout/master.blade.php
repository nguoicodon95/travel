<!DOCTYPE html>
<html lang="en" class="full">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <title>{{ Config::get('laraedit.title') }}</title>

        <link rel="stylesheet" href="{{asset('developer/css/bootstrap/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('developer/css/font-awesome/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('developer/js/jquery-ui/jquery-ui.min.css')}}">
        <link rel="stylesheet" href="{{asset('developer/js/js-tree/themes/default-dark/style.min.css')}}">
        
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Code+Pro:200,400">

        <link rel="stylesheet" href="{{asset('developer/css/terminal/screen.css')}}">
        <link rel="stylesheet" href="{{asset('developer/css/laraedit/laraedit.css')}}">

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .middle {
                position: relative;
                top: 47%;
            }
        </style>
    </head>
    <body class="full">        
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="sidebar-toggle">
                        <a href="{{ asset($adminCpAccess) }}">Return Back</a>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="nav-collapse">
                    <div class="nav navbar-nav navbar-right">
                        <button type="button" class="sidebar-buttons-toggle">
                            <span class="sr-only">Toggle Sidebar</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        @if(Session::has('msg'))
        <div class="alert alert-success">
            {{ Session::get('msg') }}
        </div>
        @endif
        <div class="container-fluid window">
            <div class="tab-content full">
                <div class="tab-pane fade full active in" id="tab1">
                    <div class="row full">
                        <div class="col-md-2 sidebar full">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="jstree_q">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                            </div>
                            <div id="tree"></div>
                        </div>
                        <div class="col-md-10 content full">
                            <pre id="editor"></pre>
                        </div>
                    </div>             
                </div>
                <div class="tab-pane fade full" id="tab4">
                    <div class="row full">
                        <div id="terminal">
                            <div id="output"></div>
                            <div id="command">
                                <div id="prompt">&gt;_</div>
                                <input type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade full" id="tab5">
                    <div class="row full" align="center">
                        <div class="middle">
                            <a href="{{ route('config_clear') }}" class="btn btn-default">Clear config</a>
                            <a href="{{ route('config_cache') }}" class="btn btn-primary">Cache config</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-fixed sidebar-buttons full">
                <ul class="nav nav-tabs nav-stacked">
                    <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-code"></i></a></li>
                    <li><a href="#tab4" data-toggle="tab"><i class="fa fa-terminal"></i></a></li>
                    <li><a href="#tab5" data-toggle="tab"><i class="fa fa-cogs"></i></a></li>
                </ul>
            </div>
        </div>
        
        <script src="{{asset('developer/js/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('developer/js/bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{asset('developer/js/jquery-ui/jquery-ui.min.js')}}"></script> 
        <script src="{{asset('developer/js/ace/ace.js')}}"></script>
        <script src="{{asset('developer/js/js-tree/jstree.min.js')}}"></script>
        <script src="{{asset('developer/js/terminal/system.js')}}"></script>
        <script src="{{asset('developer/js/laraedit/laraedit.js')}}"></script>
    </body>
</html>