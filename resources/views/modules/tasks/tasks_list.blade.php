@foreach( \Modules\TaskItem::where( "user_id" , Auth::user()->id )->where("status",1)->orderBy("order","ASC")->get() AS $task    )
<li>
  <!-- drag handle -->
      <span class="handle">
        <i class="fa fa-ellipsis-v"></i>
        <i class="fa fa-ellipsis-v"></i>
      </span>
  <!-- checkbox -->
  <!-- <input type="checkbox" value=""> -->
  <!-- todo text -->
  <span class="text taskItemInList" tid="{{ $task -> id }}">{{ $task->description }}</span>
  <!-- Emphasis label -->
  <small class="label label-info"><i class="fa fa-clock-o"></i>&nbsp; <time class="timeago" datetime="{{ $task->getCreationDateISO8601() }}">{{ $task->getCreationDateISO8601() }}</time> </small>
  <!-- General tools such as edit or delete-->
  <div class="tools">
    <a class="taskModuleAction" href="{{ $Module->url("action",["done"=>$task->id]) }}"><i title="{{ "Marcar como hecho" }}" uid="{{ $task->id  }}" class="fa fa-check-square-o"></i></a>
    <a class="taskModuleAction" href="{{ $Module->url("action",["delete"=>$task->id]) }}"><i title="{{ "Borrar" }}" uid="{{ $task->id  }}" class="fa fa-trash-o"></i></a>
  </div>
</li>
@endforeach