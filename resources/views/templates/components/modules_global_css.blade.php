<!-- MODULES GLOBAL ASSETS CSS -->
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
	@foreach( $ModuleClass->getGlobalCSS() AS $css )
	<link rel="stylesheet" href="{!! $ModuleClass->getAssetUrl($css) !!}">
	@endforeach
@endforeach

<!-- /MODULES GLOBAL ASSETS CSS -->