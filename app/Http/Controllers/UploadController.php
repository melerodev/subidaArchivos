<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $urlRegex = '/\b((http|https):\/\/)?((www\.)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,})(\/[a-zA-Z0-9-._~:\/?#[\]@!$&\'()*+,;=]*)?\b/';
        try {
            if ($request->input("action") > 0) {
                $url = $request->input('action');
                if (preg_match($urlRegex, $url)) {
                    $imageContents = file_get_contents($url);
                    if ($url === false) {
                        return redirect()->route('subida-archivos')->with('error', 'Error al obtener el contenido de la imagen.');
                    }

                    // si lo subido no es una imagen error
                    if (getimagesize($url) === false) {
                        return redirect()->route('subida-archivos')->with('error', 'El archivo no es una imagen.');
                    }

                    $path = storage_path('app/private/') . '/' . basename($url);
                    file_put_contents($path, $imageContents);  // Guardar el archivo en el almacenamiento privado

                    // Redimensionar la imagen
                    if (!$this->redimensionarImagen($path, 300, 300)) {
                        return redirect()->route('subida-archivos')->with('error', 'Error al redimensionar la imagen.');
                    }

                    $file = new File();
                    $file->path = $path;
                    $file->image64 = base64_encode(file_get_contents($path));
                    $file->image = file_get_contents($path);
                    $file->type = pathinfo($path, PATHINFO_EXTENSION);
                    $file->save();
                    return redirect()->route('inicio');
                } else {
                    return redirect()->route('subida-archivos')->with('error', 'URL no válida.');
                }
            } else {
                if ($this->esValido($request)) {
                    // Guardar el archivo subido en el almacenamiento privado
                    $path = $request->file('file')->store('private', 'local');
                    $realPath = storage_path('app/private') . '/' . $path; // Ruta real del archivo
    
                    // Redimensionar la imagen
                    if (!$this->redimensionarImagen($realPath, 300, 300)) {
                        return redirect()->route('subida-archivos')->with('error', 'Error al redimensionar la imagen.');
                    }
    
                    // Guardar el archivo en la base de datos
                    $file = new File();
                    $file->path = $realPath;
                    $file->image64 = base64_encode(file_get_contents($realPath));
                    $file->image = file_get_contents($realPath);
                    $file->type = pathinfo($realPath, PATHINFO_EXTENSION);
                    $file->save();
                    return redirect()->route('inicio');
                } else {
                    return redirect()->route('subida-archivos')->with('error', 'Archivo no válido.');
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('subida-archivos')->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
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

    private function redimensionarImagen($realPath, $anchoNuevo, $altoNuevo) {
        // Obtener las dimensiones originales de la imagen
        list($anchoOriginal, $altoOriginal) = getimagesize($realPath);
    
        // Crear una imagen de destino con las nuevas dimensiones
        $imagenDestino = imagecreatetruecolor($anchoNuevo, $altoNuevo);
    
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
            return false;
        }
    
        // Redimensionar la imagen
        imagecopyresampled(
            $imagenDestino,
            $imagenFuente,
            0, 0, 0, 0,
            $anchoNuevo,
            $altoNuevo,
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
    
        return true;
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->path;

        // Eliminar el archivo del almacenamiento
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Eliminar el registro de la base de datos
        $file->delete();

        return redirect()->route(route: 'inicio')->with('success', 'Imagen eliminada correctamente.');
    }

    public function index()
    {
        $files = File::all();
        if ($files->count() === 0) {
            return view('inicio', ['files' => [], 'sinArchivos' => true]);
        } else {
            return view('inicio', ['files' => $files]);
        }
    }
}
