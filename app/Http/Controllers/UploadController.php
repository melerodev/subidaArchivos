<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($this->esValido($request)) {
            // Guardar el archivo subido en el almacenamiento privado
            $path = $request->file('file')->store('private', 'local');
            $realPath = storage_path('app/private') . '/' . $path; // Ruta real del archivo

            // Obtener las dimensiones originales de la imagen
            list($anchoOriginal, $altoOriginal) = getimagesize($realPath);

            // Crear una imagen de destino con las nuevas dimensiones
            $imagenDestino = imagecreatetruecolor(300, 300);

            // Crear una imagen desde el archivo original dependiendo del tipo
            $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            if ($extension === 'jpeg' || $extension === 'jpg') {
                $imagenFuente = imagecreatefromjpeg($realPath);
            } elseif ($extension === 'png') {
                $imagenFuente = imagecreatefrompng($realPath);
                imagealphablending($imagenDestino, false);
                imagesavealpha($imagenDestino, true);
            } elseif ($extension === 'gif') {
                $imagenFuente = imagecreatefromgif($realPath);
            } else {
                return redirect()->route('inicio')->with('error', 'Formato de imagen no soportado.');
            }

            // Redimensionar la imagen
            imagecopyresampled(
                $imagenDestino,
                $imagenFuente,
                0, 0, 0, 0,
                300,
                300,
                $anchoOriginal,
                $altoOriginal
            );

            // Guardar la imagen redimensionada (reemplaza la original)
            if ($extension === 'jpeg' || $extension === 'jpg') {
                imagejpeg($imagenDestino, $realPath, 90);
            } elseif ($extension === 'png') {
                imagepng($imagenDestino, $realPath);
            } elseif ($extension === 'gif') {
                imagegif($imagenDestino, $realPath);
            }

            // Liberar memoria
            imagedestroy($imagenDestino);
            imagedestroy($imagenFuente);

            // Obtener el contenido del archivo redimensionado
            $data = file_get_contents($realPath);

            // Construir el objeto para guardar en la base de datos
            $file = new File();
            $file->path = $path;
            $file->image64 = base64_encode($data);
            $file->image = $data;
            $file->type = $extension;

            // Guardar en la base de datos
            $file->save();

            return redirect()->route('inicio')->with('success');
        } else {
            return redirect()->route('subida-archivos')->with('error', 'Archivo no válido.');
        }
    }

    public function index()
    {
        $files = File::all();
        return view('inicio', ['files' => $files]);
    }
    public function esValido(Request $request) {
        $file = $request->file('file');
        $valido = true;
    
        switch (true) {
            case !$request->hasFile('file'):
                $valido = false;
                break;
            case !$file->isValid():
                $valido = false;
                break;
            case getimagesize($file->getPathname()) === false:
                $valido = false;
                break;
            case $file->getSize() > 2097152: // 
                $valido = false;
                break;
        }
    
        return $valido;
    }
}
