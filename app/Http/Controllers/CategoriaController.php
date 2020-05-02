<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
//use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $buscar=$request->buscar;
        $criterio= $request->criterio;
        if($buscar==''){
            $categorias =Categoria::orderBy('id','desc')->paginate(4);
        }else{
            $categorias = Categoria::where($criterio,'like','%'. $buscar . '%')->orderBy('id','desc')->paginate(4);
        }

          
        return [
            'pagination' =>[
                'total'=> $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page' =>$categorias->perPage(),
                'last_page' => $categorias->lastPage(),
                'from' => $categorias->firstItem(),
                'to' => $categorias->lastItem(),
            ],
            'categorias' => $categorias
        ];
    }

    public function selectCategoria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias =Categoria::where('condicion','=',1)
        ->select('id','nombre')->orderBy('nombre','asc')->get();

        return ['categorias'=>$categorias];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $categorias = new Categoria();
        $categorias->nombre = $request->nombre;
        $categorias->descripcion = $request->descripcion;
        $categorias->condicion = '1';
        $categorias->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $categorias = Categoria::findOrFail($request->id);
        $categorias->nombre = $request->nombre;
        $categorias->descripcion = $request->descripcion;
        $categorias->condicion = '1';
        $categorias->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $categorias = Categoria::findOrFail($request->id);
        $categorias->condicion = '0';
        $categorias->save();
    }
    public function activar(Request $request)
    {
       if (!$request->ajax()) return redirect('/');
        
        $categorias = Categoria::findOrFail($request->id);
        $categorias->condicion = '1';
        $categorias->save();
    }

}
