<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subida de imágenes</title>
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

    <section class="container-subida-foto">
        <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h1>Subir archivos</h1>
            <input id="file" type="file" name="file" required>
            <input id="text" type="text" name="action" placeholder="URL de la imagen">
            <button id="button" type="submit">Subir</button>
            <p class="error"><em>Solo se permiten archivos de tipo <strong>.jpg</strong>, <strong>.jpeg</strong> y <strong>.png</strong> y con un máximo de <strong>2MB</strong></em></p>        </form>
    </section>
    <footer>
        <a href="https://github.com/melerodev/subidaArchivos" target="_blank"><i class="fa-brands fa-github"></i></a>
        <p>By: Alejandro Melero Zhohal</p>
    </footer>
    @if (session('error'))
        <script>
            document.querySelector('.error').style.display = 'block';
        </script>
    @endif
    <script src={{ url('/js/subidaArchivos.js') }}></script>
</body>
</html>