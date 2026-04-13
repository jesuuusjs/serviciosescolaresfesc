<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNAM FESC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --unam-azul: #003b5c;
            --unam-oro: #c89211;
        }
        body { background-color: #f8f9fa; }
        .navbar { background-color: var(--unam-azul) !important; border-bottom: 4px solid var(--unam-oro); }
        .btn-unam { background-color: var(--unam-azul); color: white; border: none; }
        .btn-unam:hover { background-color: #002a41; color: var(--unam-oro); }
        .card-unam { border-top: 4px solid var(--unam-oro); }
        .nav-link { color: white !important; font-weight: 500; }
        .nav-link:hover { color: var(--unam-oro) !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../images/logoFESC.png" width="40">
            <span class="d-none d-md-inline">GESTIÓN ACADÉMICA FESC</span>
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="alumnos.php">Alumnos</a></li>
                <li class="nav-item"><a class="nav-link" href="profesores.php">Profesores</a></li>
                <li class="nav-item"><a class="nav-link" href="materias.php">Materias</a></li>
                <li class="nav-item"><a class="nav-link" href="grupos.php">Grupos</a></li>
                <li class="nav-item"><a class="nav-link" href="matricula.php">Matricula</a></li>
                <li class="nav-item"><a class="nav-link" href="asigna.php">Registros</a></li>
                <li class="nav-item"><a class="nav-link" href="departamentos.php">Departamentos</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container"></div>