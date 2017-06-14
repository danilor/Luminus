@extends("templates.main_template")

@section("extra_header")
    <!-- EXTRA HEADER SECTION  -->
@stop

@section("content")
    <!-- MAIN CONTENT AREA  -->

    <section class="content-header">
      <h1>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">



        </div>
        <div class="box-body">
          {!! @$content !!}
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>


@stop

@section("extra_footer")
    <!-- EXTRA FOOTER  -->
@stop