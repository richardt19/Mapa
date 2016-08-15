<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () { //Ruta inicial
    return view('welcome');
});

/**
**Rutas de prueba
**/
Route::get('mapas', function (){
	return view('mapa');
});

Route::get('prueba', function(){
	return view('prueba');
});

/**
** Ruta para cuando se recibe el número de conductores y de clientes
**/
Route::post('/', function() {
	return view('mapa1');
});