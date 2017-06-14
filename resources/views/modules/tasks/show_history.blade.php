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

              <h3 class="box-title">{{ "Tareas - Historial"  }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="todo-list" id="todoSpaceTasksModule">
                @foreach( \Modules\TaskItem::where( "user_id" , Auth::user()->id )->where("status",0)->orderBy("updated_at","DESC")->get() AS $task    )
                    <li>

                      <!-- <input type="checkbox" value=""> -->
                      <!-- todo text -->
                      <span class="text taskItemInList" tid="{{ $task -> id }}">{{ $task->description }}</span>
                      <!-- Emphasis label -->
                      <small class="label label-info"><i class="fa fa-clock-o"></i>&nbsp; <time class="timeago" datetime="{{ $task->getUpdatedDateISO8601() }}">{{ $task->getUpdatedDateISO8601() }}</time> </small>

                    </li>
                @endforeach
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