<!doctype html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        {{-- Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
        <title>{{ env('APP_NAME') }} - @yield('page_title')</title>

        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <!-- You can change the theme colors from here -->
        <link href="{{ asset('css/colors/green.css') }}" id="theme" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body class="fix-header card-no-border">
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <section id="wrapper" class="error-page">
            <div class="error-box">
                <div class="error-body text-center">
                    <h1>403</h1>
                    <h3 class="text-uppercase">Acesso Negado!</h3>
                    <p class="text-muted m-t-30 m-b-30">VOCÊ NÃO TEM PERMISSÃO PARA ACESSAR ESSA PÁGINA.</p>
                    <a href="{{ route('home') }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Página inicial</a> </div>
                <footer class="footer text-center">© {{ date('Y') }} - {{ env('APP_PUBLISHER') }}.</footer>
            </div>
        </section>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        
        <!-- Bootstrap tether Core JavaScript -->
        <script src="{{ asset('plugins/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>

        <!--Wave Effects -->
        <script src="{{ asset('js/waves.js') }}"></script>
    </body>
      
</html>

