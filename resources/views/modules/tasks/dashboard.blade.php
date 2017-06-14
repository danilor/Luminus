<ul orderurl="{{ $Module->url( "action" , [ "order_ids" => "" ] ) }}" class="todo-list" url="{{ $Module->url("list") }}">
            @foreach( \Modules\TaskItem::where( "user_id" , Auth::user()->id )->where("status",1)->orderBy("order","ASC")-> take( 10 ) -> get() AS $task    )
                <li>
                  <!-- checkbox -->
                  <!-- <input type="checkbox" value=""> -->
                  <!-- todo text -->
                  <span class="text taskItemInList" tid="{{ $task -> id }}">{{ $task->description }}</span>
                  <!-- Emphasis label -->
                  <small class="label label-info"><i class="fa fa-clock-o"></i>&nbsp; <time class="timeago" datetime="{{ $task->getCreationDateISO8601() }}">{{ $task->getCreationDateISO8601() }}</time> </small>
                  <!-- General tools such as edit or delete-->
                </li>
            @endforeach
  </ul>