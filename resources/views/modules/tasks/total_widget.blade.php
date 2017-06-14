<div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ \DB::table( $Module->table( "tasks" ) ) -> where("user_id" , \Auth::user()->id ) -> where("status",1) ->count()  }}</h3>

              <p>{{ "Tareas Pendientes" }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="{{ $Module->url("show",["ref"=>"dashboard"]) }}" class="small-box-footer">{{ "Ver todas" }} <i class="fa fa-arrow-circle-right"></i></a>
          </div>