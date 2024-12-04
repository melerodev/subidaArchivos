<?php

// app/Http/Controllers/UploadController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class UploadController extends Controller
{
    function upload(Request $request) {
        if($request->hasFile('file') && $request->file('file')->isValid()) {
        //el archivo se guarda en el storage private
        $path = $request->file('file')->store('privado', 'local');
        //se obtiene la ruta al archivo guardado
        $realPath = storage_path('app/private') . '/' . $path;
        //se obtiene el contenido del archivo
        $data = file_get_contents($realPath);
        //se obtiene el contenido del archivo en base 64
        $base64 = base64_encode($data);
        //se obtiene la extensión del archivo
        $type = pathinfo($realPath, PATHINFO_EXTENSION);
        //se construye el objeto que se va a almacenar en la base de datos
        $file = new File();
        $file->path = $path;
        $file->image64 = $base64;
        $file->image = $data;
        $file->type = $type;

        //se guarda el objeto en la base de datos
        $file->save();
        return redirect()->route('inicio');
        } else {
            return redirect()->route('inicio')->with('error', 'No se pudo subir el archivo');
        }
    }

    public function index()
    {
        $files = File::all();
        return view('inicio', ['files' => $files]);
    }
}
