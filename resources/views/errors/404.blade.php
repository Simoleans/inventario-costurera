<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
        }
        h1 {
            font-size: 100px;
            margin: 0;
            color: #ff6b6b;
        }
        h2 {
            font-size: 36px;
            margin: 10px 0;
            color: #333;
        }
        p {
            font-size: 18px;
            color: #777;
            margin: 10px 0 20px;
        }
        a {
            text-decoration: none;
            background-color: #ff6b6b;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #ff4b4b;
        }
        .illustration {
            margin-top: 20px;
            font-size: 100px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>Lo sentimos, no pudimos encontrar la página que buscas.</p>
        <!-- Cambié el href para que dirija al dashboard -->
        @auth
            <a href="{{ route('dashboard') }}">Volver a la aplicación</a>
        @endauth

        @guest
            <a href="{{ route('login.view') }}">Iniciar sesión</a>
        @endguest
        <div class="illustration">😕</div>
    </div>

</body>
</html>
