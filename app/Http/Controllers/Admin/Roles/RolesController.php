<?php

namespace App\Http\Controllers\Admin\Roles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use app\Models\Rol;

class RolesController extends Controller
{
    public function listadoJson(Request $request)
    {
        $name = $request->varsearch ?:'';
        $name = trim(strtoupper($name));
        $rol = Rol::where('nombre','like',"%$name%")->select('id','nombre as text')->get();
        return $rol;
    }
}
