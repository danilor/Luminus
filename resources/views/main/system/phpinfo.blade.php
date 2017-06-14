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
        {{ "PHP Info" }}
        <!--<small>it all starts here</small>-->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="phpinfo">
            {!! $phpinfo !!}
        </div>
    </section>


@stop

@section("extra_footer")
    <!-- EXTRA FOOTER  -->
    <script type="text/javascript" charset="utf-8">

        $(document).ready(function() {
            /**
             * This will clean everything related to the PHP info and try to show it to the site
             */
            $('.phpinfo table').addClass('table table-bordered table-striped table-condensed ellipsis').attr('width','100%').attr('style','overflow-x:hidden;');
        } );
    </script>
@stop