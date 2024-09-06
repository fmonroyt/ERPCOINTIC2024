<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title><?= $titulo ?></title>
    <meta name="description" content="Sistema Gasolineras G500">
    <style>
        .centrado {
            text-align: center;
        }
        .imagenPrincipal {
            max-width: 20%;
            min-width: 20%;
        }
        .divBoton {
            background-color:#01224e;
            width: 50%;
            color: #FFFFFF;
            margin-left: 25%;
            padding-top: 30px;
            padding-bottom: 30px;
            border: #021b3b 1px solid;
            border-radius: 20px;
            font-weight: bold;
        }
        .bordeado {
            border: 1px solid black;
            border-radius: 20px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="bordeado">
        <div class="centrado">
            <h1 class="centrado"><?= $encabezado ?></h1>
        </div>
        <div class="centrado">
			<img class="imagenPrincipal" src='https://www.appsiee.com/Gasolineras/assets/img/logog500.png'/>
        </div>
        <div class="centrado">
            <p><?= $cuerpo ?></p>
        </div>
        <div class="centrado" align="center">
            <div class="divBoton centrado">
                <span>Gracias por confiar en nosotros.</span>
            </div>
        </div>
    </div>
    
</body>

</html>
