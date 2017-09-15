<?php

    Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function() 
    {
        Route::group(['namespace' => 'Pedidos', 'middleware' => 'auth'], function() 
        {
            Route::get('realizar-pedidos','PedidosController@index');
            Route::get('listado-pedidos','PedidosController@listado');
            Route::post('registrar-pedido','PedidosController@agregar')->name('pedido.registrar');
            Route::get('eliminar-pedido/{id}','PedidosController@eliminar');
            Route::get('a-cuenta','PedidosController@acuenta');
            Route::post('agregar-producto-pedido','PedidosController@agregarproducto');
            Route::get('eliminar-producto-pedido/{id}','PedidosController@eliminarProducto');
        });        
    });