<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;


class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'stock' => 'required|integer'
        ]);
     
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock
        ]);



        return redirect()->route('productos.index');
    }
    public function edit(Producto $producto)
{
    return view('productos.edit', compact('producto'));
}

public function update(Request $request, Producto $producto)
{
    $request->validate([
        'nombre' => 'required',
        'descripcion' => 'required',
        'precio' => 'required|numeric',
        'stock' => 'required|integer'
    ]);

    $producto->update($request->all());

    return redirect()->route('productos.index');
}

public function destroy(Producto $producto)
{
    $producto->delete();

    return redirect()->route('productos.index');
}
}