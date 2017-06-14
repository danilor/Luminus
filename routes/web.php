<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

/**
 * MAIN ROUTING
 * These are the routes for the whole site. They are divided by 3 sections: public, Authorized and Admin Authorized
 * Estas son las rutas definidas para todo el sitio. Están divididos en tres secciones: pública, autorizada y autorizada por administrador
 */
Route::get('/',                                                 'MainController@showDashboard')                 -> middleware( 'CheckAuth' );

/**
 * The following routes are made for the module management
 * Las próximas rutas están hechas para el mantenimiento de los módulos
 */
Route::get('/modules/installed',                                'ModulesController@showInstalledModules')       -> middleware( 'CheckAuthAdmin' );
Route::get('/modules/not_installed',                            'ModulesController@showNotInstalledModules')    -> middleware( 'CheckAuthAdmin' );
Route::any('/modules/action',                                   'ModulesController@actionModule')               -> middleware( 'CheckAuthAdmin' );
Route::get('/modules/action_regular_users',                     'ModulesController@regularActionModule')        -> middleware( 'CheckAuth' );
Route::any('/modules/install_from_zip',                         'ModulesController@installFromZip')             -> middleware( 'CheckAuth' );

/**
 * END of the module management
 * FIN del mantenimiento de los módulos
 */

/**
 * Login Routes
 * Rutas de logueo
 * */
 Route::get('/login',                                           'LoginController@login');
 Route::post('/login',                                          'LoginController@doLogin');
 Route::get('/forgot_password',                                 'LoginController@forgot');
 Route::post('/forgot_password',                                'LoginController@doForgot');
 Route::get('/logout',                                          'LoginController@doLogout');

/**
 * Profile routes
 * Rutas de perfil
 */
Route::get('/profile',                                           'ProfileController@show')                  -> middleware( 'CheckAuth' );
Route::get('/profile/login_registries',                          'ProfileController@loginRegistry')         -> middleware( 'CheckAuth' );
Route::get('/profile/avatar',                                    'ProfileController@avatar')                -> middleware( 'CheckAuth' );
Route::get('/profile/password',                                  'ProfileController@password')              -> middleware( 'CheckAuth' );
Route::get('/profile/basic',                                     'ProfileController@basic')              -> middleware( 'CheckAuth' );

Route::any('/profile/process',                                   'ProfileController@process')                -> middleware( 'CheckAuth' );


 /**
  * I will add the avatar process to the login controller
  * Voy a añadir el proceso de los avatar al controlador del logueo
  *
  * The idea of these routes is to ask for the avatar of any user and it will return the Image resource of the user without any other process
  * La idea de estas rutas es de pedir al sistema por el avatar de un usuario y éste le tiene que devolver el recurso de imágen sin ningún otro proceso
  * */

Route::get('/avatar/{id}/',                                                     'LoginController@getUserAvatar');
Route::get('/username_avatar/{id}/',                                            'LoginController@getUsernameAvatar');

/**
 * Modules view
 * This is the main module route
 * Esta es la principal ruta para los módulos
 */
 Route::any('/m/{name}/{url?}/{extra1?}/{extra2?}/{extra3?}',    'ModulesController@showModule');

/**
 * System
 */
 Route::get('/system/phpinfo',                                  'SystemController@showPHPInfo') -> middleware( 'CheckAuthAdmin' );
 Route::get('/system/sysinfo',                                  'SystemController@showSysInfo') -> middleware( 'CheckAuthAdmin' );

/**
 * Documentation
 * In this case is only GET because I am sure nothing is going to be posted.
 */
 Route::get('/documentation/development',                           'DocumentationController@showDevelopment') ->middleware('auth') ;
