<!DOCTYPE html>
<html lang="{{ config("app.locale") }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>{{ config("app.name")  }}</title>
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" type="text/css" href="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/css/bootstrap.min.css">
        <!-- Website Font style -->
        <link rel="stylesheet" href="/assets/fontawesome/{{ config("plugins.font_awesome_version")  }}/css/font-awesome.min.css">


        <link rel="stylesheet" href="/css/login/login_avatar.css">

    </head>
      <body class="loginpages">

                @yield("content")
        <!--JQuery -->
        <script src="/assets/jquery/{{ config("plugins.jquery_version")  }}/jquery.min.js"></script>
        <!-- JQuery UI -->
        <script src="/assets/jquery-ui/{{ config("plugins.jqueryui_version")  }}/jquery-ui.min.js"></script>
        <!-- Bootstrap -->
        <script src="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/js/bootstrap.min.js"></script>
        @include("login.block.general_footer")
        @yield("extra_js")
      </body>
</html>