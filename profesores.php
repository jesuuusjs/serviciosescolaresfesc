<?php 
require 'config.php'; 
include 'layout/header.php';


if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM PROFESOR WHERE NUMTRAB = ?")->execute([$_GET['delete']]);
    echo "<script>window.location='profesores.php';</script>";
}

if (isset($_POST['save_prof'])) {
    $nt = $_POST['numtrab']; $nom = $_POST['nombre']; $gen = $_POST['genero'];
    $grado = $_POST['grado']; $area = $_POST['area']; $tel = $_POST['tel'];
    $email = $_POST['email']; $depto = $_POST['depto'];

    if ($_POST['mode'] == 'new') {
        $sql = "INSERT INTO PROFESOR VALUES (?,?,?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$nt, $nom, $gen, $grado, $area, $tel, $email, $depto]);
    } else {
        $sql = "UPDATE PROFESOR SET NOMBREPROF=?, GENEROPROF=?, GRADOMAXEST=?, AREAESTUDIO=?, TELEFONOPROF=?, CORREOE=?, CVEDEPTO=? WHERE NUMTRAB=?";
        $pdo->prepare($sql)->execute([$nom, $gen, $grado, $area, $tel, $email, $depto, $nt]);
    }
    echo "<script>window.location='profesores.php';</script>";
}

$deptos = $pdo->query("SELECT * FROM DEPARTAMENTO")->fetchAll();
$profesores = $pdo->query("SELECT P.*, D.NOMBREDEPTO FROM PROFESOR P JOIN DEPARTAMENTO D ON P.CVEDEPTO = D.CVEDEPTO")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Plantilla de Profesores</h3>
    <button class="btn btn-unam" onclick="modalP('new')"><i class="bi bi-person-badge"></i> Nuevo Profesor</button>
</div>

<div class="card shadow-sm card-unam">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>N° Trab</th>
                    <th>Nombre</th>
                    <th>Grado</th>
                    <th>Departamento</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($profesores as $p): ?>
                <tr>
                    <td><strong><?= $p['NUMTRAB'] ?></strong></td>
                    <td><?= $p['NOMBREPROF'] ?></td>
                    <td><span class="badge bg-warning text-dark"><?= $p['GRADOMAXEST'] ?></span></td>
                    <td><?= $p['NOMBREDEPTO'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick='modalP("edit", <?= json_encode($p) ?>)'><i class="bi bi-pencil"></i></button>
                        <a href="profesores.php?delete=<?= $p['NUMTRAB'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar profesor?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalProf" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="profesores.php">
            <input type="hidden" name="mode" id="p_mode" value="new">
            <div class="modal-header bg-dark text-white"><h5>Datos del Profesor</h5></div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label>N° Trabajador</label><input type="number" name="numtrab" id="p_nt" class="form-control" required></div>
                <div class="col-md-8"><label>Nombre</label><input type="text" name="nombre" id="p_nom" class="form-control" required></div>
                <div class="col-md-6"><label>Grado Máximo</label>
                    <select name="grado" id="p_grado" class="form-select">
                        <option>LICENCIATURA</option><option>MAESTRIA</option><option>DOCTORADO</option>
                    </select>
                </div>
                <div class="col-md-6"><label>Departamento</label>
                    <select name="depto" id="p_depto" class="form-select">
                        <?php foreach($deptos as $d): ?>
                        <option value="<?= $d['CVEDEPTO'] ?>"><?= $d['NOMBREDEPTO'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label>Área de Estudio</label><input type="text" name="area" id="p_area" class="form-control"></div>
                <div class="col-md-6"><label>Email</label><input type="email" name="email" id="p_email" class="form-control"></div>
                <div class="col-md-6"><label>Teléfono</label><input type="text" name="tel" id="p_tel" class="form-control"></div>
                <div class="col-md-6"><label>Género</label><input type="text" name="genero" id="p_gen" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="submit" name="save_prof" class="btn btn-unam w-100">GUARDAR CAMBIOS</button></div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const mP = new bootstrap.Modal(document.getElementById('modalProf'));
    function modalP(modo, d = null) {
        document.getElementById('p_mode').value = modo;
        if(modo === 'new') {
            document.getElementById('p_nt').readOnly = false;
            document.querySelector('#modalProf form').reset();
        } else {
            document.getElementById('p_nt').value = d.NUMTRAB;
            document.getElementById('p_nt').readOnly = true;
            document.getElementById('p_nom').value = d.NOMBREPROF;
            document.getElementById('p_grado').value = d.GRADOMAXEST;
            document.getElementById('p_depto').value = d.CVEDEPTO;
            document.getElementById('p_area').value = d.AREAESTUDIO;
            document.getElementById('p_email').value = d.CORREOE;
            document.getElementById('p_tel').value = d.TELEFONOPROF;
            document.getElementById('p_gen').value = d.GENEROPROF;
        }
        mP.show();
    }
</script>