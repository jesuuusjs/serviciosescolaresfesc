<?php 
require 'config.php'; 
include 'layout/header.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM ALUMNO WHERE NUMCTA = ?");
    $stmt->execute([$_GET['delete']]);
    echo "<script>window.location='alumnos.php';</script>";
}

if (isset($_POST['save_alumno'])) {
    $numcta = $_POST['numcta'];
    $nombre = $_POST['nombre'];
    $tel    = $_POST['tel'];
    $email  = $_POST['email'];
    $dom    = $_POST['dom'];
    $cp     = $_POST['cp'];
    $nac    = $_POST['nac'];
    $fnac   = $_POST['fnac'];
    $gen    = $_POST['gen'];

    if ($_POST['mode'] == 'new') {
      
        $sql = "INSERT INTO ALUMNO (NUMCTA, NOMBREAL, TELEFONO, CORREOELECTR, DOMICILIO, CODPOS, NACIONALIDAD, FECHANAC, GENERO) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$numcta, $nombre, $tel, $email, $dom, $cp, $nac, $fnac, $gen]);
    } else {
       
        $sql = "UPDATE ALUMNO SET NOMBREAL=?, TELEFONO=?, CORREOELECTR=?, DOMICILIO=?, CODPOS=?, NACIONALIDAD=?, FECHANAC=?, GENERO=? WHERE NUMCTA=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $tel, $email, $dom, $cp, $nac, $fnac, $gen, $numcta]);
    }
    echo "<script>window.location='alumnos.php';</script>";
}

$alumnos = $pdo->query("SELECT * FROM ALUMNO ORDER BY NUMCTA DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Gestión de Alumnos</h3>
    <button class="btn btn-unam" onclick="abrirModal('new')"><i class="bi bi-person-plus-fill"></i> Nuevo Alumno</button>
</div>

<div class="card shadow-sm card-unam bg-white">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead style="background-color: var(--unam-azul); color: white;">
                <tr>
                    <th>Cuenta</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Género</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($alumnos as $a): ?>
                <tr>
                    <td><strong><?= $a['NUMCTA'] ?></strong></td>
                    <td><?= $a['NOMBREAL'] ?></td>
                    <td><?= $a['CORREOELECTR'] ?></td>
                    <td><?= $a['GENERO'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick='abrirModal("edit", <?= json_encode($a) ?>)'><i class="bi bi-pencil"></i></button>
                        <a href="alumnos.php?delete=<?= $a['NUMCTA'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Borrar alumno?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalAlumno" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="alumnos.php">
            <input type="hidden" name="mode" id="m_mode" value="new">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="m_titulo">Registro Alumno</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="fw-bold">N° Cuenta</label><input type="number" name="numcta" id="m_numcta" class="form-control" required></div>
                <div class="col-md-8"><label class="fw-bold">Nombre Completo</label><input type="text" name="nombre" id="m_nombre" class="form-control" required></div>
                <div class="col-md-6"><label class="fw-bold">Email</label><input type="email" name="email" id="m_email" class="form-control"></div>
                <div class="col-md-6"><label class="fw-bold">Teléfono</label><input type="text" name="tel" id="m_tel" class="form-control"></div>
                <div class="col-md-12"><label class="fw-bold">Domicilio</label><input type="text" name="dom" id="m_dom" class="form-control"></div>
                <div class="col-md-4"><label class="fw-bold">C.P.</label><input type="number" name="cp" id="m_cp" class="form-control"></div>
                <div class="col-md-4"><label class="fw-bold">Nacionalidad</label><input type="text" name="nac" id="m_nac" class="form-control"></div>
                <div class="col-md-4"><label class="fw-bold">Género</label>
                    <select name="gen" id="m_gen" class="form-select">
                        <option value="MASCULINO">MASCULINO</option>
                        <option value="FEMENINO">FEMENINO</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="fw-bold">Fecha Nacimiento</label><input type="date" name="fnac" id="m_fnac" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="submit" name="save_alumno" class="btn btn-unam w-100">GUARDAR</button></div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const elModal = new bootstrap.Modal(document.getElementById('modalAlumno'));
    function abrirModal(modo, datos = null) {
        document.getElementById('m_mode').value = modo;
        const inputCta = document.getElementById('m_numcta');
        if(modo === 'new') {
            document.getElementById('m_titulo').innerText = "Nuevo Registro de Alumno";
            inputCta.readOnly = false;
            document.querySelector('#modalAlumno form').reset();
        } else {
            document.getElementById('m_titulo').innerText = "Editar Datos de Alumno";
            inputCta.value = datos.NUMCTA;
            inputCta.readOnly = true;
            document.getElementById('m_nombre').value = datos.NOMBREAL;
            document.getElementById('m_email').value = datos.CORREOELECTR;
            document.getElementById('m_tel').value = datos.TELEFONO;
            document.getElementById('m_dom').value = datos.DOMICILIO;
            document.getElementById('m_cp').value = datos.CODPOS;
            document.getElementById('m_nac').value = datos.NACIONALIDAD;
            document.getElementById('m_gen').value = datos.GENERO;
            document.getElementById('m_fnac').value = datos.FECHANAC;
        }
        elModal.show();
    }
</script>