<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mostrar archivo</title>
</head>
<body>
    <h1>Archivo subido</h1>
    <img src="data:image/{{ $file->type }};base64,{{ $file->image64 }}" alt="Imagen subida">
</body>
</html>
