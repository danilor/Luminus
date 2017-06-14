<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>{{ config("app.name")  }}</title>
    @include("templates.components.headercss")
    @yield("extra_header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  @include("templates.components.adminlte.header")
  <!-- Left side column. contains the sidebar -->
  <!-- include("templates.components.adminlte.sidebar") -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      @include("templates.components.adminlte.sidebar_user")
      @include("templates.components.sidebar")
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @yield("content")
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@include("templates.components.adminlte.footer")

  <!-- Control Sidebar -->
 @include("templates.components.adminlte.aside")
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("templates.components.footerjs")
    @yield("extra_footer")
</body>
</html>
