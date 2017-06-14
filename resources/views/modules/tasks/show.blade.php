<section class="content">
	  <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-xs-12">
          <!-- Custom tabs (Charts with tabs)-->

          <!-- TO DO List -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">{{ "Mis tareas pendientes"  }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul orderurl="{{ $Module->url( "action" , [ "order_ids" => "" ] ) }}" class="todo-list" id="todoSpaceTasksModule" url="{{ $Module->url("list") }}">

              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button href="{{ $Module->url("add") }}" class="btn btn-default pull-right addTaskGlobalModule"><i class="fa fa-plus"></i> {{ "Añadir Tarea" }}</button>
            </div>
          </div>
          <!-- /.box -->
          <!-- quick email widget -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->

        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </section>