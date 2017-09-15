<?php

namespace App\Http\Controllers\Admin\Pedidos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Pago;
use App\Models\Inventario;
use Auth;

class PedidosController extends Controller
{
    public function index()
    {
        return view('admin.pedido.index');
    }

    public function listado()
    {
        $pedidos = Pedido::select('p.nombre as producto','c.nombre as categoria','m.nombre as marca','pre.nombre as presentacion', 'fecha_hora','pedidos.id')
                        ->join('producto as p','p.id','=','pedidos.idproducto')
                        ->join('categoria as c','c.id','=','p.idcategoria')
                        ->join('marca as m','m.id','=','p.idmarca')
                        ->join('presentacion as pre','pre.id','=','p.idpresentacion')
                        ->get();
        $lista['data'] = $pedidos;
        return $lista;        
    }

    public function agregar(Request $request)
    {
        $data = new Pedido();
        $data->idproducto   = $request->producto;
        $data->cantidad     = $request->cantidad;
        $data->fecha_hora   = date("Y-m-d H:i:s");
        $data->save();
        return redirect('realizar-pedidos')->with('message','Se registro pedido');
    }

    public function eliminar($id)
    {
        Pedido::where('id',$id)->delete();
        return redirect('realizar-pedidos')->with('eliminar','Pedido eliminado');

    }

    public function acuenta()
    {
        $max   = Pedido::max('numero');
        $pedido = Pedido::DisponiblePedido()->with('producto')->get();
        $monto  = Pedido::DisponiblePedido()->sum('monto_total');
        $pago   = Pago::pluck('nombre','id');
        return view('admin.pedido.acuenta', compact(['pedido',  'max', 'monto', 'pago']));
    }
    
    public function agregarproducto(Request $request)
    {
        $cantidad = Inventario::DisponibilidadProducto($request->producto)->get();
        $producto = Producto::where('id',$request->producto)->get();
        foreach($cantidad as $row):
            foreach($producto as $row2):
            if($row->stock >= $request->cantidad)
            {
                $productoExiste = Pedido::ProductoExistePedido($request->producto)->count();
                if($productoExiste >= 1)
                {
                    $cantidadProducto = Pedido::ProductoExistePedido($request->producto)->select('cantidad')->get();
                    $totalProducto = ($cantidadProducto->first()->cantidad + $request->cantidad) * $row2->precio_venta;
                    Pedido::ProductoExistePedido($request->producto)->update(['monto_total' => $totalProducto ]);
                    Pedido::ProductoExistePedido($request->producto)->increment('cantidad',$request->cantidad);
                    return redirect('a-cuenta')->with('message','Se agrego productos');
                }else
                {
                    $data = new Pedido();
                    $data->idproducto = $request->producto;
                    $data->cantidad = $request->cantidad;
                    $data->monto_total = $request->cantidad * $row2->precio_venta;
                    $data->idusuario = Auth::user()->id;
                    $data->fecha_hora = date("Y-m-d H:i:s");
                    $data->save();

                    return redirect('a-cuenta')->with('message','Se agrego productos');
                }
            }else 
            {
                return redirect('a-cuenta')->with('eliminar','La cantidad ingresada sobre pasa el stock disponible');
            }
            endforeach;
        endforeach;
    }

    public function eliminarProducto($id)
    {
        Pedido::where('id',$id)->delete();

        return redirect('a-cuenta')->with('eliminar','Producto Eliminado');
    }
}
