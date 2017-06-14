@extends("templates.main_template")

@section("extra_header")
    <!-- EXTRA HEADER SECTION  -->
    <style>
        .ellipsis {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
@stop

@section("content")
    <!-- MAIN CONTENT AREA  -->

    <section class="content-header">
      <h1>
        {{ "Sys Info" }}
        <!--<small>it all starts here</small>-->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
            <iframe style="height: 85vh;" width="100%" allowfullscreen="allowfullscreen" scrolling="true" frameborder="0" src="{{ config("app.php_sysinfo_url")  }}"  allowtransparency="true" ></iframe>
    </section>


@stop

@section("extra_footer")
    <!-- EXTRA FOOTER  -->
    <script type="text/javascript" charset="utf-8">
    </script>
@stop