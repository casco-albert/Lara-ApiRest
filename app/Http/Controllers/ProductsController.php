<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
  
    public function index()
    {
        $products = Products::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if(!is_array($validar)){
            Products::create($request->all());
            array_push($respuesta,['status'=>'success']);
            return response()->json($respuesta);
        }
        else{
            return response()->json($validar);
        }
    }

    public function show(string $id)
    {
        $product = Products::find($id);
        return response()->json($product);
    }

    public function update(Request $request, string $id)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());
        if(!is_array($validar)){
            $product = Products::find($id);
            if($product){
                $product->fill($request->all())->save();
                array_push($respuesta, ['status'=>'success']);
            }
            else{
                array_push($respuesta, ['status'=>'success']);
                array_push($respuesta, ['errors'=>['id'=>['No existe el id']]]);
            }
            return response()->json($respuesta);
        }
        else{
            return response()->json($validar);

        }
    }

    public function destroy(string $id)
    {
        $respuesta = [];
        $product = Products::find($id);
        if($product){
            $product->delete();
            array_push($respuesta, ['status'=>'success']);
        }
        else{
            array_push($respuesta, ['status'=>'errors']);
            array_push($respuesta, ['errors'=>['id'=>['No existe el id']]]);
        }
        return response()->json($respuesta);

    }
    public function validar($parametros){
        $respuesta = [];
        $messages = [
            'max' => 'El campo :attribute no debe tener más de :max caracteres',
            'required' => 'El campo :attribute no debe estar vacío',
            'price.numeric' => 'El precio debe ser numérico'
        ];
        $attributes = [
            'name' => 'nombre',
            'description' => 'descripción',
            'price' => 'precio'
        ];
        $validacion = Validator::make($parametros,
        [
            'name' => 'required|max:80',
            'description' => 'required|max:150',
            'price' => 'required|numeric|max:999999'
        ], $messages, $attributes);
    
        if ($validacion->fails()) {
            $respuesta['status'] = 'error';
            $respuesta['errors'] = $validacion->errors();
            return $respuesta;
        } else {
            return true;
        }
    }
    
}
