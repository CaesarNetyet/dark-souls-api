<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Personaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CaracteristicasController extends Controller
{
    public function agregarCaracteristica(Request $request,  Response $response){
        
        $caracteristicas = new Caracteristica;
        
        $validator = Validator::make($request->all(),
        [
            "personaje_id" => "required | integer | exists:personajes,id",
            "nivel" => "required | integer",
            "vitalidad" => "required | integer",
            "aguante" => "required | integer",
            "vigor" => "required | integer",
            "fuerza" => "required | integer",
            "destreza" => "required | integer",
            "aprendizaje" => "required | integer",
            "inteligencia" => "required | integer",
            "fe" => "required | integer",
        ],
        [
            "personaje_id.required" => "El personaje es requerido",
            "personaje_id.integer" => "El personaje debe ser un número entero",
            "personaje_id.exists" => "El personaje no existe",
        
            "required" => "El campo :attribute es requerido",
            "integer" => "El campo :attribute debe ser un número entero",
        ]);
        if ($validator->fails()) {
            return response()->json(["errores" => $validator->errors()], 400);
        }
        $response = Http::post('http://192.168.123.139:8000/api/caracteristicas/agregar',[
            
                "personaje_id"=> $request->personaje_id,
                "nivel"=> $request->nivel,
                "vitalidad"=> $request->vitalidad,
                "aguante"=> $request->aguante,
                "vigor"=> $request->vigor,
                "fuerza"=> $request->fuerza,
                "destreza"=>$request->destreza,
                "aprendizaje"=> $request->aprendizaje,
                "inteligencia"=> $request->inteligencia,
                "fe"=> $request->fe
            
        ]);
        if ($response->failed())
        return response()->json($response->json(),400);
        $personaje = Personaje::find($request->personaje_id);
        if(!$personaje)
            return response()->json(["error" => "El personaje no existe"], 400);
        if($caracteristicas->where("personaje_id", $request->personaje_id)->first())
            return response()->json(["error" => "El personaje ya tiene caracteristicas"], 400);
        $caracteristicas->personaje_id = $request->personaje_id;
        $caracteristicas->nivel = $request->nivel;
        $caracteristicas->vitalidad = $request->vitalidad;
        $caracteristicas->aguante = $request->aguante;
        $caracteristicas->vigor = $request->vigor;
        $caracteristicas->fuerza = $request->fuerza;
        $caracteristicas->destreza = $request->destreza;
        $caracteristicas->aprendizaje = $request->aprendizaje;
        $caracteristicas->inteligencia = $request->inteligencia;
        $caracteristicas->fe = $request->fe;
        $caracteristicas->save();
        if($caracteristicas){
            return response()->json([
                "mensaje" => "Características agregadas correctamente",
                "personaje" => $personaje,
                "caracteristicas" => $caracteristicas
            ], $response->status());
        }else{
            return response()->json([
                "mensaje" => "Error al agregar características"
            ], 500);
        }
    }


    public function actualizarCaracteristica(Request $request)
    {
        $user = $request->user();
       
            $validator = Validator::make(
            $request->all(),    
            [
                "personaje_id" => "required | integer | exists:personajes,id",
                "nivel" => "required | integer",
                "vitalidad" => "required | integer",
                "aguante" => "required | integer",
                "vigor" => "required | integer",
                "fuerza" => "required | integer",
                "destreza" => "required | integer",
                "aprendizaje" => "required | integer",
                "inteligencia" => "required | integer",
                "fe" => "required | integer",
            ],
            [
                "personaje_id.required" => "El personaje es requerido",
                "personaje_id.integer" => "El personaje debe ser un número entero",
                "personaje_id.exists" => "El personaje no existe",
            
                "required" => "El campo :attribute es requerido",
                "integer" => "El campo :attribute debe ser un número entero",
            ]
        );

        if ($validator->fails()) {
            return response()->json(["errores" => $validator->errors()], 400);
        }

            $caracteristica = new Caracteristica;
            
            $caracteristica->nivel = $request->nivel;
            $caracteristica->vitalidad = $request->vitalidad;
            $caracteristica->aguante = $request->aguante;
            $caracteristica->vigor = $request->vigor;
            $caracteristica->fuerza = $request->fuerza;
            $caracteristica->destreza = $request->destreza;
            $caracteristica->aprendizaje = $request->aprendizaje;
            $caracteristica->inteligencia = $request->inteligencia;
            $caracteristica->fe = $request->fe;
            $user->caracteristica()->save($caracteristica);
        $personaje =Personaje::find($request->personaje_id);
        return response()->json([
            'mensaje' => 'caracteristica actualizado correctamente',
            'caracteristica' => [
                $caracteristica,
                "personaje"=> $personaje->nombre
            ]
        ], 201);
    }
}
