<?php

// Juan Camilo Pulgarin - Alejandro Díaz Ruiz

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CursoController extends Controller{
    private $archivoCursos = 'cursos.json';

    // Datos iniciales
    private function getDatosIniciales(){
        return [
            [
                'id' => 1,
                'nombre' => 'Programación I',
                'codigo' => '123',
                'creditos' => 3,
                'docente' => 'Carlos Ramirez'
            ],
            [
                "id"=> 2,
                "nombre"=> "Programación ",
                "codigo"=> "009",
                "creditos"=> 4,
                "docente"=> "simon"

            ],
            [
                "id"=> 3,
                "nombre"=> "Bases de Datos",
                "codigo"=> "B2093",
                "creditos"=> 3,
                "docente"=> "Luis perez"

            ],
            [
                "id" => 4,
                "nombre" => "Estructuras de Datos",
                "codigo" => "009",
                "creditos" => 4,
                "docente" => "Maria g"
            ],
            [
                "id"=> 5,
                "nombre"=> "Ingeniería de Software",
                "codigo"=> "2039",
                "creditos"=> 3,
                "docente"=> "Juan jo"
            ]
        ];
    }

    private function getCursos(){
        if (!Storage::exists($this->archivoCursos)) {
            $this->guardarCursos($this->getDatosIniciales());
            return $this->getDatosIniciales();
        }

        $contenido = Storage::get($this->archivoCursos);
        $cursos = json_decode($contenido, true);

        return $cursos ?? $this->getDatosIniciales();
    }

    private function guardarCursos($cursos){
        Storage::put($this->archivoCursos, json_encode($cursos, JSON_PRETTY_PRINT));
    }

    public function listarCursos(){
        return response()->json([
            "message" => "Lista de cursos obtenida exitosamente",
            "data" => $this->getCursos()
        ], 200);
    }

    public function consultarCurso($id){
        $cursos = $this->getCursos();

        $curso = collect($cursos)->firstWhere('id', $id);

        if (!$curso) {
            return response()->json([
                "message" => "Curso no encontrado"
            ], 404);
        }

        return response()->json([
            "message" => "Curso encontrado exitosamente",
            "data" => $curso
        ], 200);
    }

    public function insertarCurso(Request $request){
        $request->validate([
            'id' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'creditos' => 'required|integer|min:1',
            'docente' => 'required|string|max:255'
        ]);

        $cursos = $this->getCursos();

        $nuevoCurso = [
            'id' => $request->id,
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'creditos' => $request->creditos,
            'docente' => $request->docente
        ];

        $cursos[] = $nuevoCurso;

        $this->guardarCursos($cursos);

        return response()->json([
            "message" => "Curso creado exitosamente",
            "data" => $nuevoCurso
        ], 201);
    }

    public function actualizarCurso(Request $request, $id){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'creditos' => 'required|integer|min:1',
            'docente' => 'required|string|max:255'
        ]);

        $cursos = $this->getCursos();

        $indice = collect($cursos)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Curso no encontrado"
            ], 404);
        }

        $cursos[$indice] = [
            'id' => (int)$id,
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'creditos' => $request->creditos,
            'docente' => $request->docente
        ];

        $this->guardarCursos($cursos);

        return response()->json([
            "message" => "Curso actualizado exitosamente",
            "data" => $cursos[$indice]
        ], 200);
    }

    public function eliminarCurso($id){
        $cursos = $this->getCursos();

        $indice = collect($cursos)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Curso no encontrado"
            ], 404);
        }

        $cursoEliminado = $cursos[$indice];

        unset($cursos[$indice]);
        $cursos = array_values($cursos);

        $this->guardarCursos($cursos);

        return response()->json([
            "message" => "Curso eliminado exitosamente",
            "data" => $cursoEliminado
        ], 200);
    }
}