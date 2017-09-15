<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Inventario extends Model
{
    protected $table = "inventario";

    public function tienda()
    {
        return $this->HasOne(Tienda::class,'id','idtienda');
    }

    public function producto()
    {
        return $this->hasOne(Producto::class,'id','idtienda');
    }

    public function scopeBuscarTienda($cadenaSQL, $tienda)
    {
        return $cadenaSQL->where('idtienda', $tienda);
    }

    public function scopeDisponibilidadProducto($cadenaSQL, $producto)
    {
        return $cadenaSQL->where('idtienda', Auth::user()->idtienda)
                        ->where('idproducto',$producto);
    }
}
