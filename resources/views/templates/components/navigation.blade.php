@foreach( \App\Module::getMenuModuleCache() AS $key => $ModuleMenu )
    <?php

        /**
         * We need to make some basic validations here in case we want to break the foreach statement and they can be only made using normal PHP for now.
         * I have to search if there is another way to do this
        */
    /**
     * This variable will indicate if we can show this item
     */
        $show = true;
        /**
         * lets check if we need the user as administrator or not
         */
        if(\Auth::check() && \Auth::user() != null) if( $ModuleMenu->isUserAdministrator() && !\Auth::user()->isAdmin() ){
            /**
             * Seems like the user is not admin. Sorry, you cannot see this item. Lets return empty
             * ಠ_ಠ
             */
            $show = false;
        }

        /**
         * Now lets check if we have to validate for ALLJOBS
         */
        if(\Auth::check() && \Auth::user() != null) if( $ModuleMenu->hasAllJobs() && !\Auth::user()->hasAllJob( $ModuleMenu->getAllJobs() ) ){
            $show = false;
        }

        /**
         * Now lets check if we have to validate for ANYJOBS
         */
        if(\Auth::check() && \Auth::user() != null )  if(  $ModuleMenu->hasAnyJobs() && !\Auth::user()->hasAnyJob( $ModuleMenu->getAnyJobs() ) ){
            $show = false;
        }
    ?>
        @if($show)
                <li class="treeview @if(\Request::segment(2) == $key) active @endif" id="Module_menu_{{ $key }}">
                    <a href="javascript:void(0)">
                        {!! $ModuleMenu->getIcon()  !!} <span>{{ $ModuleMenu->getTitle()  }}</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    @if( count( $ModuleMenu->getItems() ) > 0 )
                        <ul class="treeview-menu">
                        @foreach( $ModuleMenu->getItems() AS $DModuleMenuItem )
                            {!! \App\Classes\Site\Navigation::generateHTMLItem( $key , $DModuleMenuItem )  !!}
                        @endforeach
                        </ul>
                    @endif
                    </li>
        @endif
  @endforeach


