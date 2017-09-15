<?php

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacion;
use App\Models\Rol;
use App\Models\Tienda;

/**
 * devuelve el id estado de catalogo
 */
if (! function_exists('categoria')) {
    /**
     * funcion que retorna el prefijo para nombres de archivos
     * @return [type] [description]
     */
    function categoria($id)
    {
        if (isset($id)) {
            $categoria = Categoria::where('id',$id)->pluck('nombre','id')->toarray();
        } else {
            $categoria=[];
        }
        return $categoria;
    }
}

/**
 * devuelve el id estado de catalogo
 */
if (! function_exists('presentacion')) {
    /**
     * funcion que retorna el prefijo para nombres de archivos
     * @return [type] [description]
     */
    function presentacion($id)
    {
        if (isset($id)) {
            $presentacion = Presentacion::where('id',$id)->pluck('nombre','id')->toarray();
        } else {
            $presentacion=[];
        }
        return $presentacion;
    }
}

/**
 * devuelve el id estado de catalogo
 */
if (! function_exists('marca')) {
    /**
     * funcion que retorna el prefijo para nombres de archivos
     * @return [type] [description]
     */
    function marca($id)
    {
        if (isset($id)) {
            $marca = Marca::where('id',$id)->pluck('nombre','id')->toarray();
        } else {
            $marca=[];
        }
        return $marca;
    }
}


/**
 * devuelve el id estado de catalogo
 */
if (! function_exists('rol')) {
    /**
     * funcion que retorna el prefijo para nombres de archivos
     * @return [type] [description]
     */
    function rol($id)
    {
        if (isset($id)) {
            $categoria = Rol::where('id',$id)->pluck('nombre','id')->toarray();
        } else {
            $categoria=[];
        }
        return $categoria;
    }
}


/**
 * devuelve el id estado de catalogo
 */
if (! function_exists('tienda')) {
    /**
     * funcion que retorna el prefijo para nombres de archivos
     * @return [type] [description]
     */
    function tienda($id)
    {
        if (isset($id)) {
            $categoria = Tienda::where('id',$id)->pluck('nombre','id')->toarray();
        } else {
            $categoria=[];
        }
        return $categoria;
    }
}