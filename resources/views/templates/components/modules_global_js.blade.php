<!-- MODULES GLOBAL ASSETS JS -->
<?php
	$Modules = \App\Module::orderBy("order","ASC")->get();
	$ModulesClases = [];

	try{
		foreach($Modules AS $Module){
			$ModulesClases[ $Module->name ] = \App\Module::loadModuleClass( $Module->name );

			$ModulesClases[ $Module->name ]->setBaseModuleUrl( \App\Http\Controllers\ModulesController::$module_url . '/' . $ModulesClases[ $Module->name ]->getClassName());
            $ModulesClases[ $Module->name ]->setAssetsBaseUrl($ModulesClases[ $Module->name ]->getBaseModuleUrl() . '?asset=');
            $ModulesClases[ $Module->name ]->setDocumentsBaseUrl($ModulesClases[ $Module->name ]->getBaseModuleUrl() . '?document=');
			$ModulesClases[ $Module->name ] -> start();


		}
	}catch(\App\Classes\System\SystemException $err){} catch(\App\Classes\System\SystemError $err){}
	//getGlobalCSS
?>
@foreach($ModulesClases AS $ModuleClass)
	@foreach( $ModuleClass->getGlobalJS() AS $js )
	<script type="text/javascript" src="{!! $ModuleClass->getAssetUrl($js) !!}"></script>
	@endforeach
@endforeach

<!-- /MODULES GLOBAL ASSETS JS -->