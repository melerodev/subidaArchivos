<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio</title>
    <link rel="stylesheet" href="{{ url('/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <nav class="container">
            <ul>
                <li><a href="{{ route('inicio') }}">Inicio</a></li>
                <li><a href="{{ route('subida-archivos') }}">Añadir foto</a></li>
            </ul>
        </nav>
    </header>
    <br/>
    <h1>Imágenes Almacenadas</h1>
    <section class="container-fotos">
    @if(isset($sinArchivos) && $sinArchivos)
        <div class="no-archivos">
            <img src="{{ asset('img/bart.gif') }}" alt="No hay archivos">
            <p>No hay imágenes almacenados</p>
        </div>
    @endif
    @foreach($files as $file)
        <div class="foto-container">
            <img src="data:image/{{ $file->type }};base64,{{ $file->image64 }}" alt="Imagen subida">
            <form action="{{ route('delete', $file->id) }}" method="POST" class="papelera" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border: none; cursor: pointer;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    @endforeach
    </section>
    <footer>
        <a href="https://github.com/melerodev/subidaArchivos" target="_blank"><i class="fa-brands fa-github"></i></a>
        <p>By: Alejandro Melero Zhohal</p>
    </footer>
    <script>
        function confirmDelete() {
            return confirm('¿Estás seguro de que deseas eliminar esta imagen?');
        }
    </script>
</body>
</html>