<!-- CUSTOM MODULE MENU -->
@foreach( \App\Module::getNavigationModuleCache() AS $key => $ModuleNavigation )

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
    if( $ModuleNavigation->isUserAdministrator() && !\Auth::user()->isAdmin() ){
        /**
         * Seems like the user is not admin. Sorry, you cannot see this item. Lets return empty
         * ಠ_ಠ
         */
        $show = false;
    }

    /**
     * Now lets check if we have to validate for ALLJOBS
     */
    if( $ModuleNavigation->hasAllJobs() && !\Auth::user()->hasAllJob( $ModuleMenu->getAllJobs() ) ){
        $show = false;
    }

    /**
     * Now lets check if we have to validate for ANYJOBS
     */
    if( $ModuleNavigation->hasAnyJobs() && !\Auth::user()->hasAnyJob( $ModuleMenu->getAnyJobs() ) ){
        $show = false;
    }
    ?>
                  @if($show)
                          <li class="dropdown notifications-menu" @foreach($ModuleNavigation->getAttributes() AS $attribute) {{ $attribute["key"] }}="{{ $attribute["value"] }}"  @endforeach >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              {!! $ModuleNavigation -> getIcon() !!}
                              <!--<span class="label label-warning">0</span>-->
                            </a>
                            <ul class="dropdown-menu">
                              <li class="header"><strong>{!! $ModuleNavigation->getTitle() !!}</strong></li>
                              <li>
                                @if( count( $ModuleNavigation->getItems() ) > 0 )
                                        <ul class="menu">
                                        @foreach( $ModuleNavigation->getItems() AS $DModuleMenuItem )
                                            {!! \App\Classes\Site\Navigation::generateHTMLItem( $key , $DModuleMenuItem )  !!}
                                        @endforeach
                                        </ul>
                                    @endif
                              </li>
                            </ul>
                          </li>
                  @endif
      <!-- / CUSTOM MODULE MENU -->
@endforeach