@extends("templates.main_template")

@section("extra_header")
    <!-- EXTRA HEADER SECTION  -->

    {!!  $ModuleResponse->printAllHeaderAssets( $ModuleClass->getAssetsBaseUrl() ) !!}
@stop

@section("content")

    <!-- MAIN CONTENT AREA  -->

    <section class="content-header">
      <h1>
        {{ $ModuleRegistry->label }}
        <!--<small>it all starts here</small>-->
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">
      {!!  $ModuleResponse->executeView() !!}
    </section>


@stop

@section("extra_footer")
    <!-- EXTRA FOOTER  -->
    {!!  $ModuleResponse->printAllFooterAssets( $ModuleClass->getAssetsBaseUrl() ) !!}
@stop