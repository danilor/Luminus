<div class="user-panel">
    <div class="pull-left image">
        <img src="{{ \Auth::user()->getAvatarUrl( 100 , 100 ) }}" class="img-circle" alt="{{ \Auth::user()->getFullName() }}">
    </div>
    <div class="pull-left info">
        <p>{{ @Auth::user() -> getFullName() }}</p>
        <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
    </div>
</div>