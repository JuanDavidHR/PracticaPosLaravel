<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return $categorias;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $catgoria = new Categoria();
        $catgoria->nombre = $request->nombre;
        $catgoria->descripcion = $request->descripcion;
        $catgoria->condicion = '1';
        $catgoria->save();
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
        //if (!$request->ajax()) return redirect('/');

        $catgoria = Categoria::findOrFail($request->id);
        $catgoria->nombre = $request->nombre;
        $catgoria->descripcion = $request->descripcion;
        $catgoria->condicion = '1';
        $catgoria->save();
    }
    public function desactivar(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $catgoria = Categoria::findOrFail($request->id);
        $catgoria->condicion = '0';
        $catgoria->save();
    }
    public function activar(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        
        $catgoria = Categoria::findOrFail($request->id);
        $catgoria->condicion = '1';
        $catgoria->save();
    }

}
