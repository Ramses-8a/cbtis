<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="header">
        <div class="logo">
            CBTis
        </div>
        <div class="school-name" >CBTis No.152</div>
        <ul>
            <li class="pro"><a href="">Proyectos</a></li>
            <li class="pro"><a href="">Torneos</a></li>
            <li class="pro"><a href="">Recursos Tecnológicos</a></li>
        </ul>
    </div>


    <div class="content-area">
    </div>
</body>
</html>

<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .header {
            background-color: #9d0707;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #b91c3c;
            font-size: 12px;
            text-align: center;
        }

        .school-name {
            font-size: 28px;
            font-weight: bold;
        }

        /* Estilos del menú */
        ul {
            list-style: none;
            margin: 0;
            padding: 0;
            background-color: #9d0707;
            overflow: hidden;
            display: flex;
            justify-content: flex-end;
        }
        
        li {
            float: left;
            position: right;
           
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 20px 40px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            border-right: 2px solid rgba(255, 255, 255, 0.3);
        }

        li:last-child a {
            border-right: none;
        }

        li a:hover {
            background-color: #9d0707;
        }

    </style>