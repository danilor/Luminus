<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/css/bootstrap.css">
    <!-- Website Font style -->
    <link rel="stylesheet" href="/assets/fontawesome/{{ config("plugins.font_awesome_version")  }}/css/font-awesome.min.css">

    <link rel="stylesheet" href="/css/exceptionpage.css">

    <title>@yield("title")</title>


  </head>
  <body class="background error-page-wrapper background-color background-image" >

<div class="content-container shadow" style="top: 90px;">
	@yield("content")
</div>
</body>

    <script type="text/javascript" src="/assets/jquery/{{ config("plugins.jquery_version")  }}/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/js/bootstrap.js"></script>
    <script type="text/javascript">
      $(window).on('resize', function() {
        $('body').trigger('resize')
      });
    </script>
    @yield("extra_js")
</html>
