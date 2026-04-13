<?php 
require 'config.php'; 
include 'layout/header.php';

if (isset($_GET['delete_asig'])) {
    $stmt = $pdo->prepare("DELETE FROM ASIGNATURA WHERE CVALIC = ? AND CLAVEASIG = ?");
    $stmt->execute([$_GET['lic'], $_GET['delete_asig']]);
    echo "<script>window.location='materias.php';</script>";
}


if (isset($_POST['save_asig'])) {
    $cvalic = $_POST['cvalic'];
    $clave  = $_POST['claveasig'];
    $nombre = $_POST['nombreasig'];
    $sem    = $_POST['semestre'];
    $cred   = $_POST['creditos'];

    if ($_POST['mode'] == 'new') {
        $sql = "INSERT INTO ASIGNATURA VALUES (?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$cvalic, $clave, $nombre, $sem, $cred]);
    } else {
        $sql = "UPDATE ASIGNATURA SET NOMBREASIG=?, SEMESTRE=?, CREDITOS=? WHERE CVALIC=? AND CLAVEASIG=?";
        $pdo->prepare($sql)->execute([$nombre, $sem, $cred, $cvalic, $clave]);
    }
    echo "<script>window.location='materias.php';</script>";
}

$licenciaturas = $pdo->query("SELECT * FROM LICENCIATURA")->fetchAll();
$materias = $pdo->query("SELECT A.*, L.NOMBRELIC FROM ASIGNATURA A JOIN LICENCIATURA L ON A.CVALIC = L.CVALIC ORDER BY SEMESTRE ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Catálogo de Asignaturas</h3>
    <button class="btn btn-unam" onclick="modalA('new')"><i class="bi bi-book-fill"></i> Nueva Materia</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Clave</th>
                    <th>Nombre de la Asignatura</th>
                    <th>Semestre</th>
                    <th>Créditos</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($materias as $m): ?>
                <tr>
                    <td><small class="text-muted"><?= $m['NOMBRELIC'] ?></small><br><strong><?= $m['CLAVEASIG'] ?></strong></td>
                    <td><?= $m['NOMBREASIG'] ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $m['SEMESTRE'] ?>°</span></td>
                    <td><?= $m['CREDITOS'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick='modalA("edit", <?= json_encode($m) ?>)'><i class="bi bi-pencil"></i></button>
                        <a href="materias.php?delete_asig=<?= $m['CLAVEASIG'] ?>&lic=<?= $m['CVALIC'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar materia?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalAsig" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <input type="hidden" name="mode" id="a_mode" value="new">
            <div class="modal-header bg-dark text-white"><h5>Datos de la Asignatura</h5></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="fw-bold">Licenciatura</label>
                    <select name="cvalic" id="a_lic" class="form-select" required>
                        <?php foreach($licenciaturas as $l): ?>
                        <option value="<?= $l['CVALIC'] ?>"><?= $l['NOMBRELIC'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label class="fw-bold">Clave Asig.</label><input type="number" name="claveasig" id="a_clave" class="form-control" required></div>
                <div class="col-md-6"><label class="fw-bold">Créditos</label><input type="number" name="creditos" id="a_cred" class="form-control" required></div>
                <div class="col-12"><label class="fw-bold">Nombre</label><input type="text" name="nombreasig" id="a_nom" class="form-control" required></div>
                <div class="col-md-6"><label class="fw-bold">Semestre</label><input type="number" name="semestre" id="a_sem" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="submit" name="save_asig" class="btn btn-unam w-100">GUARDAR MATERIA</button></div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const mA = new bootstrap.Modal(document.getElementById('modalAsig'));
    function modalA(modo, d = null) {
        document.getElementById('a_mode').value = modo;
        const inputClave = document.getElementById('a_clave');
        const selectLic = document.getElementById('a_lic');
        if(modo === 'new') {
            inputClave.readOnly = false;
            selectLic.disabled = false;
            document.querySelector('#modalAsig form').reset();
        } else {
            inputClave.value = d.CLAVEASIG;
            inputClave.readOnly = true;
            selectLic.value = d.CVALIC;
            // selectLic.disabled = true; // Se recomienda no cambiar la llave primaria en edición
            document.getElementById('a_nom').value = d.NOMBREASIG;
            document.getElementById('a_sem').value = d.SEMESTRE;
            document.getElementById('a_cred').value = d.CREDITOS;
        }
        mA.show();
    }
</script>