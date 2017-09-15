<?php

namespace App\Http\Controllers\Admin\Cobrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cobrar;

class CobrarController extends Controller
{
    public function registrarPedido(Request $request)
    {

        $data = new Cobrar();
        $data->numero   = $request->numero;
        $data->a_cuenta = $request->acuenta;
        $data->debe     = $request->monto_total - $request->acuenta;
        $data->monto_total = $request->monto_total;
        $data->save();
    }
}
