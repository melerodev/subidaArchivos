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
            <input type="file" name="file" required>
            <input type="text" name="action" placeholder="URL de la imagen">
            <button type="submit">Subir</button>
        </form>
    </section>
    <footer>
        <a href="https://github.com/melerodev/subidaArchivos" target="_blank"><i class="fa-brands fa-github"></i></a>
        <p>Designed by Alejandro Melero Zhohal</p>
    </footer>
</body>
</html>