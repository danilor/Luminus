
<section class="col-lg-7 connectedSortable sortable_left" side="l">
            <!-- CUSTOM DASHBOARD BOXES -->
            @if( isset($boxes["l"]) )
                @foreach( $boxes["l"] AS $box )
                    <div>
                            <input type="hidden" value="{{ $box[ "id" ]  }}" class="sortable_module_item_dashboard" />
                             <div class="box box-primary">
                                <div class="box-header">
                                  <!--<i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>-->
                                  <strong>{!! @$box[ "title" ] !!} </strong>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    {!! $box[ "content" ] !!}
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix no-border">

                                </div>
                            </div>

                    </div>
                @endforeach
            @endif
        </section>


<section class="col-lg-5 connectedSortable sortable_right" side="r">
          <!-- CUSTOM DASHBOARD BOXES -->
          @if( isset($boxes["r"]) )
                @foreach( $boxes["r"] AS $box )
                    <div>
                            <input type="hidden" value="{{ $box[ "id" ]  }}" class="sortable_module_item_dashboard" />
                             <div class="box box-primary">
                                <div class="box-header">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                  <strong>{!! @$box[ "title" ] !!} </strong>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    {!! $box[ "content" ] !!}
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix no-border">

                                </div>
                            </div>

                    </div>
                @endforeach

            @endif
    </section>