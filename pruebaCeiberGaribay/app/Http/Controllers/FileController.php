<?php

namespace App\Http\Controllers;

use App\FilesPruebas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function index()
    {
        // Obtener todos los registros de la tabla
        //$datos = FilesPruebas::all();

        // Devolver los datos como respuesta
        //return response()->json($datos);
        /*$files = Storage::files('uploads');
        return response()->json($files);*/
        
        // Directorio donde se encuentran los archivos
        $directorio = public_path('archivos');

        // Obtener la lista de archivos en el directorio
        $archivos = File::files($directorio);

        // Formatear los nombres de los archivos
        $archivosFormateados = [];
        foreach ($archivos as $archivo) {
            $archivosFormateados[] = [
                'nombre' => basename($archivo),
                'ruta' => url('archivos/' . basename($archivo)),
                'tamaño' => File::size($archivo),
                'fecha_modificacion' => date('Y-m-d H:i:s', File::lastModified($archivo)),
            ];
        }
        // Devolver la lista de archivos como respuesta JSON
        return response()->json($archivosFormateados);
    }

    public function store(Request $request)
    {
                
        //Insertar datos en la base de datos, validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string',
        ]);

        // Crear un nuevo registro en la tabla
        $nuevoRegistro = FilesPruebas::create([
            'nombreArchivo' => $request->input('nombre'),
        ]);

        // Devolver la respuesta
        return response()->json($nuevoRegistro, 201); // 201 significa "Created"
    }

    public function upload(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'nombre' => 'required|string',
            'contenido' => 'required|string',
        ]);

        // Obtener nombre y contenido del archivo de la solicitud
        $nombre = $request->input('nombre');
        $contenido = $request->input('contenido');

        // Guardar el archivo en el sistema de archivos
        file_put_contents(public_path('archivos/' . $nombre), $contenido);

        // Opcional: Puedes devolver una respuesta de éxito
        return response()->json(['mensaje' => 'Archivo creado correctamente'], 200);
    }

    public function logicalDelete($id)
    {
        // Lógica para eliminar un archivo de forma lógica
    }

    public function physicalDelete($nombreArchivo)
    {
        // Ruta del archivo en la carpeta pública
        $rutaArchivo = public_path('archivos/' . $nombreArchivo);

        // Verificar si el archivo existe
        if (file_exists($rutaArchivo)) {
            // Borrar el archivo
            unlink($rutaArchivo);

            // Devolver una respuesta de éxito
            return response()->json(['mensaje' => 'Archivo borrado correctamente'], 200);
        } else {
            // Devolver una respuesta de error si el archivo no existe
            return response()->json(['error' => 'El archivo no existe'], 404);
        }
    }

    public function massUpload(Request $request)
    {
        // Validar que se hayan enviado archivos
        if ($request->hasFile('archivos')) {
            // Iterar sobre cada archivo enviado
            foreach ($request->file('archivos') as $archivo) {
                // Guardar el archivo en la carpeta pública
                $archivo->store('archivos', 'public');
            }

            // Devolver una respuesta de éxito
            return response()->json(['mensaje' => 'Archivos subidos correctamente'], 200);
        } else {
            // Devolver una respuesta de error si no se enviaron archivos
            return response()->json(['error' => 'No se han enviado archivos'], 400);
        }
    }
}
