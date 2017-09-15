<?php

    Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function() 
    {
        Route::group(['namespace' => 'Roles', 'middleware' => 'auth'], function() 
        {
            Route::get('listado-roles-json','RolesController@listadoJson');
        });        
    });