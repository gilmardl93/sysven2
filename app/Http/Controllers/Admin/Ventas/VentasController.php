<?php

namespace App\Http\Controllers\Admin\Ventas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Venta\ClienteRequest;
use App\Http\Requests\Admin\Venta\AnularBoletaRequest;
use App\Models\Venta;
use App\Models\Tipo;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\Inventario;
use App\Models\Inventarioventa;
use DB;

class VentasController extends Controller
{
    public function index()
    {
        $caja = Caja::AperturaAbierta()->count();
        return view('admin.venta.index', compact('caja'));
    }

    public function listado()
    {
        $venta = Venta::where('numero','<>','')->with(['producto'])->get();
        $lista['data'] = $venta;
        return $lista;
    }

    public function boleta()
    {
        $max    = Venta::where('idtipo',1)->max('numero');
        $venta  = Venta::DisponibleBoleta()->with('producto')->get();
        $monto  = Venta::DisponibleBoleta()->sum('monto');
        $pago   = Pago::pluck('nombre','id');
        return view('admin.venta.boleta', compact(['venta',  'max', 'monto', 'pago']));
    }

    public function registrarProductoBoleta(Request $request)
    {
        $cantidad = Inventario::DisponibilidadProducto($request->producto)->get();
        $producto = Producto::where('id',$request->producto)->get();
        foreach($cantidad as $row):
            foreach($producto as $row2):
            if($row->stock >= $request->cantidad)
            {
                $productoExiste = Venta::ProductoExisteBoleta($request->producto)->count();
                if($productoExiste >= 1)
                {
                    $cantidadProducto = Venta::ProductoExisteBoleta($request->producto)->select('cantidad')->get();
                    $totalProducto = ($cantidadProducto->first()->cantidad + $request->cantidad) * $row2->precio_venta;
                    Venta::ProductoExisteBoleta($request->producto)->update(['monto' => $totalProducto ]);
                    Venta::ProductoExisteBoleta($request->producto)->increment('cantidad',$request->cantidad);
                    return redirect('boleta')->with('message','Se agrego productos');
                }else
                {
                    $data = new Venta();
                    $data->idproducto = $request->producto;
                    $data->cantidad = $request->cantidad;
                    $data->monto = $request->cantidad * $row2->precio_venta;
                    $data->idtipo = 1;
                    $data->idusuario = Auth::user()->id;
                    $data->save();
                    
                    return redirect('boleta')->with('message','Se agrego productos');
                }
            }else 
            {
                return redirect('boleta')->with('eliminar','La cantidad ingresada sobre pasa el stock disponible');
            }
            endforeach;
        endforeach;
    }

    public function eliminarProductoBoleta($id)
    {
        Venta::where('id',$id)->delete();

        return redirect('boleta')->with('eliminar','Producto Eliminado');
    }

    public function registrarBoleta(Request $request)
    {
        $producto = Venta::DisponibleBoleta()->get();
        foreach($producto as $row):
            Inventarioventa::where('idproducto',$row->idproducto)->decrement('stock',$row->cantidad);
        endforeach;
        $numero = Venta::where('idtipo',1)->max('id');
        $caja = Caja::where('idusuario',Auth::user()->id)->max('id');
        $productos = Venta::DisponibleBoleta()->update([
            'numero'    => $numero + 1,
            'idtipo'    => 1,
            'idcliente' => $request->idcliente,
            'idtienda'  => Auth::user()->idtienda,
            'idcaja'    => $caja,
            'idpago'    => $request->pago,
            'fecha'     => date('Y-m-d H:i:s')
        ]);

        return redirect('boleta')->with('final','Se registro nueva boleta');
    }

    public function factura()
    {
        $max   = Venta::where('idtipo',2)->max('numero');
        $venta = Venta::DisponibleFactura()->with('producto')->get();
        $monto  = Venta::DisponibleFactura()->sum('monto');
        $pago   = Pago::pluck('nombre','id');
        return view('admin.venta.factura', compact(['venta',  'max', 'monto', 'pago']));
    }

    public function registrarProductoFactura(Request $request)
    {
        $cantidad = Inventario::DisponibilidadProducto($request->producto)->get();
        $producto = Producto::where('id',$request->producto)->get();
        foreach($cantidad as $row):
            foreach($producto as $row2):
            if($row->stock >= $request->cantidad)
            {
                $productoExiste = Venta::ProductoExisteFactura($request->producto)->count();
                if($productoExiste >= 1)
                {
                    $cantidadProducto = Venta::ProductoExisteFactura($request->producto)->select('cantidad')->get();
                    $totalProducto = ($cantidadProducto->first()->cantidad + $request->cantidad) * $row2->precio_venta;
                    Venta::ProductoExisteFactura($request->producto)->update(['monto' => $totalProducto ]);
                    Venta::ProductoExisteFactura($request->producto)->increment('cantidad',$request->cantidad);
                    return redirect('factura')->with('message','Se agrego productos');
                }else
                {
                    $data = new Venta();
                    $data->idproducto = $request->producto;
                    $data->cantidad = $request->cantidad;
                    $data->monto = $request->cantidad * $row2->precio_venta;
                    $data->idtipo = 2;
                    $data->idusuario = Auth::user()->id;
                    $data->save();

                    return redirect('factura')->with('message','Se agrego productos');
                }
            }else 
            {
                return redirect('factura')->with('eliminar','La cantidad ingresada sobre pasa el stock disponible')
                                        ->with('imprimir','<a href="{!! url("reporte-factura") !!}" class="btn default green-stripe">VER BOLETA</a>');
            }
            endforeach;
        endforeach;
    }

    public function eliminarProductoFactura($id)
    {
        Venta::where('id',$id)->delete();

        return redirect('factura')->with('eliminar','Producto Eliminado');
    }

    public function registrarFactura(ClienteRequest $request)
    {
        $producto = Venta::DisponibleFactura()->get();
        foreach($producto as $row):
            Inventarioventa::where('idproducto',$row->idproducto)->decrement('stock',$row->cantidad);
        endforeach;
        $numero = Venta::where('idtipo',2)->max('numero');
        $caja = Caja::where('idusuario',Auth::user()->id)->max('id');
        $productos = Venta::DisponibleFactura()->update([
            'numero'    => $numero + 1,
            'idtipo'    => 2,
            'idcliente' => $request->idcliente,
            'idtienda'  => Auth::user()->idtienda,
            'idcaja'    => $caja,
            'idpago'    => $request->pago,
            'fecha'     => date('Y-m-d H:i:s')
        ]);

        return redirect('factura')->with('final','Se registro nueva factura');
    }

    public function ticket()
    {
        $max   = Venta::where('idtipo',3)->max('numero');
        $venta = Venta::DisponibleTicket()->with('producto')->get();
        $monto  = Venta::DisponibleTicket()->sum('monto');
        $pago   = Pago::pluck('nombre','id');
        return view('admin.venta.ticket', compact(['venta',  'max', 'monto', 'pago']));
    }

    public function registrarProductoTicket(Request $request)
    {
        $cantidad = Inventario::DisponibilidadProducto($request->producto)->get();
        $producto = Producto::where('id',$request->producto)->get();
        foreach($cantidad as $row):
            foreach($producto as $row2):
            if($row->stock >= $request->cantidad)
            {
                $productoExiste = Venta::ProductoExisteTicket($request->producto)->count();
                if($productoExiste >= 1)
                {
                    $cantidadProducto = Venta::ProductoExisteTicket($request->producto)->select('cantidad')->get();
                    $totalProducto = ($cantidadProducto->first()->cantidad + $request->cantidad) * $row2->precio_venta;
                    Venta::ProductoExisteTicket($request->producto)->update(['monto' => $totalProducto ]);
                    Venta::ProductoExisteTicket($request->producto)->increment('cantidad',$request->cantidad);
                    return redirect('ticket')->with('message','Se agrego productos');
                }else
                {
                    $data = new Venta();
                    $data->idproducto = $request->producto;
                    $data->cantidad = $request->cantidad;
                    $data->monto = $request->cantidad * $row2->precio_venta;
                    $data->idtipo = 3;
                    $data->idusuario = Auth::user()->id;
                    $data->save();

                    return redirect('ticket')->with('message','Se agrego productos');
                }
            }else 
            {
                return redirect('ticket')->with('eliminar','La cantidad ingresada sobre pasa el stock disponible');
            }
            endforeach;
        endforeach;
    }

    public function eliminarProductoTicket($id)
    {
        Venta::where('id',$id)->delete();

        return redirect('ticket')->with('eliminar','Producto Eliminado');
    }

    public function registrarTicket(Request $request)
    {
        $producto = Venta::DisponibleTicket()->get();
        foreach($producto as $row):
            Inventarioventa::where('idproducto',$row->idproducto)->decrement('stock',$row->cantidad);
        endforeach;
        $numero = Venta::where('idtipo',3)->max('numero');
        $caja = Caja::where('idusuario',Auth::user()->id)->max('id');
        $productos = Venta::DisponibleTicket()->update([
            'numero'    => $numero + 1,
            'idtipo'    => 3,
            'idcliente' => $request->idcliente,
            'idtienda'  => Auth::user()->idtienda,
            'idpago'    => $request->pago,
            'fecha'     => date('Y-m-d H:i:s')
        ]);

        return redirect('ticket')->with('final','Se registro nuevo ticket');
    }

    public function anular()
    {
        return view('admin.venta.anular');
    }

    public function anularBoleta()
    {
        return view('admin.venta.anular_boleta');
    }

    public function BoletaAnulada(AnularBoletaRequest $request)
    {
        $existe = Venta::ExisteBoleta($request->numero)->count();
        if($existe >= 1)
        {
            $motivo = Venta::ExisteBoleta($request->numero)->update(['motivo' => $request->motivo , 'anulado' => true ]);
            return redirect('anular-boleta')->with('eliminar','Se anulo Boleta: 001-'.str_pad($request->numero, 6, "0", STR_PAD_LEFT));
        }else
        {
            return redirect('anular-boleta')->with('eliminar','El numero de boleta no existe. Ingrese nuevamente.');
        }
    }

    public function anularFactura()
    {
        return view('admin.venta.anular_factura');
    }

    public function FacturaAnulada(AnularBoletaRequest $request)
    {
        $existe = Venta::ExisteFactura($request->numero)->count();
        if($existe >= 1)
        {
            $motivo = Venta::ExisteBoleta($request->numero)->update(['motivo' => $request->motivo , 'anulado' => true ]);
            return redirect('anular-factura')->with('eliminar','Se anulo Boleta: 001-'.str_pad($request->numero, 6, "0", STR_PAD_LEFT));
        }else
        {
            return redirect('anular-factura')->with('eliminar','El numero de boleta no existe. Ingrese nuevamente.');
        }
    }

    public function anularTicket()
    {
        return view('admin.venta.anular_ticket');
    }

    public function TicketAnulada(AnularBoletaRequest $request)
    {
        $existe = Venta::ExisteTicket($request->numero)->count();
        if($existe >= 1)
        {
            $motivo = Venta::ExisteBoleta($request->numero)->update(['motivo' => $request->motivo , 'anulado' => true ]);
            return redirect('anular-ticket')->with('eliminar','Se anulo Boleta: 001-'.str_pad($request->numero, 6, "0", STR_PAD_LEFT));
        }else
        {
            return redirect('anular-ticket')->with('eliminar','El numero de boleta no existe. Ingrese nuevamente.');
        }
    }
}
