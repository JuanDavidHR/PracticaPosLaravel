<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\User;
use App\Persona;
use App\Rol;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','users.usuario','users.password',
            'users.condicion','users.idrol','roles.nombre as rol')
            ->orderBy('personas.id', 'desc')->paginate(5);
        }
        else{
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','users.usuario','users.password',
            'users.condicion','users.idrol','roles.nombre as rol')            
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id', 'desc')->paginate(5);
        }
        

        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $personas = new Persona();
            $personas->nombre = $request->nombre;
            $personas->tipo_documento = $request->tipo_documento;
            $personas->num_documento = $request->num_documento;
            $personas->direccion = $request->direccion;
            $personas->telefono = $request->telefono;
            $personas->email = $request->email;
            $personas->save();

            $users = new User();
            $users->usuario = $request->usuario;
            $users->password = bcrypt( $request->password);
            $users->condicion = '1';
            $users->idrol = $request->idrol;          

            $users->id = $personas->id;

            $users->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

        
        
    }

    public function update(Request $request)
    {
       if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();

            //Buscar primero el usuario a modificar
            $users = User::findOrFail($request->id);

            $personas = Persona::findOrFail($users->id);

            $personas->nombre = $request->nombre;
            $personas->tipo_documento = $request->tipo_documento;
            $personas->num_documento = $request->num_documento;
            $personas->direccion = $request->direccion;
            $personas->telefono = $request->telefono;
            $personas->email = $request->email;
            $personas->save();

            
            $users->usuario = $request->usuario;
            $users->password = bcrypt( $request->password);
            $users->condicion = '1';
            $users->idrol = $request->idrol;
            $users->save();


            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

}