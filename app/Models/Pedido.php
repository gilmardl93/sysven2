<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Pedido extends Model
{
    use softDeletes;

    protected $table = "pedidos";

    protected $dates = ['deleted_at']; 

    public function producto()
    {
        return $this->hasOne(Producto::class,'id','idproducto');
    }

    public function scopeProductoExistePedido($cadenaSQL, $producto)
    {
        return $cadenaSQL->where('numero','000000')
                        ->where('idproducto',$producto)
                        ->where('idusuario',Auth::user()->id);
    }

    public function scopeDisponiblePedido($cadenaSQL)
    {
        return $cadenaSQL->where('numero','000000') 
                        ->where('idusuario',Auth::user()->id);
    }


}
