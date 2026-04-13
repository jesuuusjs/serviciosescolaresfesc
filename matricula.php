<?php 
require 'config.php'; 
include 'layout/header.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM MATRICULA WHERE NUMCTA = ? AND NUMGPO = ? AND PERIODO = ? AND CLAVEASIG = ?");
    $stmt->execute([$_GET['cta'], $_GET['gpo'], $_GET['per'], $_GET['asig']]);
    echo "<script>window.location='matricula.php';</script>";
}

if (isset($_POST['save_mat'])) {
    $params = [$_POST['cta'], $_POST['gpo'], $_POST['per'], $_POST['lic'], $_POST['asig'], $_POST['cal']];
    if ($_POST['mode'] == 'new') {
        $sql = "INSERT INTO MATRICULA VALUES (?,?,?,?,?,?)";
    } else {
        $sql = "UPDATE MATRICULA SET CALIFICACION=? WHERE NUMCTA=? AND NUMGPO=? AND PERIODO=? AND CLAVEASIG=?";
        $params = [$_POST['cal'], $_POST['cta'], $_POST['gpo'], $_POST['per'], $_POST['asig']];
    }
    $pdo->prepare($sql)->execute($params);
    echo "<script>window.location='matricula.php';</script>";
}

$alumnos = $pdo->query("SELECT NUMCTA, NOMBREAL FROM ALUMNO")->fetchAll();
$grupos = $pdo->query("SELECT NUMGPO FROM GRUPO")->fetchAll();
$materias = $pdo->query("SELECT CLAVEASIG, NOMBREASIG, CVALIC FROM ASIGNATURA")->fetchAll();


$query = "SELECT M.*, A.NOMBREAL, S.NOMBREASIG 
          FROM MATRICULA M 
          JOIN ALUMNO A ON M.NUMCTA = A.NUMCTA 
          JOIN ASIGNATURA S ON M.CLAVEASIG = S.CLAVEASIG";
$inscripciones = $pdo->query($query)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Control de Calificaciones</h3>
    <button class="btn btn-unam" onclick="modalM('new')"><i class="bi bi-pencil-fill"></i> Inscribir / Calificar</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Alumno</th>
                    <th>Asignatura</th>
                    <th>Grupo</th>
                    <th>Periodo</th>
                    <th>Calif.</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($inscripciones as $i): ?>
                <tr>
                    <td><small><?= $i['NUMCTA'] ?></small><br><strong><?= $i['NOMBREAL'] ?></strong></td>
                    <td><?= $i['NOMBREASIG'] ?></td>
                    <td><?= $i['NUMGPO'] ?></td>
                    <td><?= $i['PERIODO'] ?></td>
                    <td><span class="badge <?= $i['CALIFICACION'] >= 6 ? 'bg-success' : 'bg-danger' ?> fs-6"><?= $i['CALIFICACION'] ?></span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick='modalM("edit", <?= json_encode($i) ?>)'><i class="bi bi-pencil"></i></button>
                        <a href="matricula.php?delete=1&cta=<?= $i['NUMCTA'] ?>&gpo=<?= $i['NUMGPO'] ?>&per=<?= $i['PERIODO'] ?>&asig=<?= $i['CLAVEASIG'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar registro?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalMat" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <input type="hidden" name="mode" id="m_mode" value="new">
            <div class="modal-header bg-dark text-white"><h5>Registro de Acta</h5></div>
            <div class="modal-body row g-3">
                <div class="col-12"><label>Alumno</label>
                    <select name="cta" id="m_cta" class="form-select" required>
                        <?php foreach($alumnos as $al): ?><option value="<?= $al['NUMCTA'] ?>"><?= $al['NOMBREAL'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12"><label>Asignatura</label>
                    <select name="asig" id="m_asig" class="form-select" onchange="actualizarLic(this)" required>
                        <option value="">Seleccione...</option>
                        <?php foreach($materias as $ma): ?>
                        <option value="<?= $ma['CLAVEASIG'] ?>" data-lic="<?= $ma['CVALIC'] ?>"><?= $ma['NOMBREASIG'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="lic" id="m_lic">
                </div>
                <div class="col-md-6"><label>Grupo</label>
                    <select name="gpo" id="m_gpo" class="form-select">
                        <?php foreach($grupos as $gr): ?><option value="<?= $gr['NUMGPO'] ?>"><?= $gr['NUMGPO'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label>Periodo (Ej: 20262)</label><input type="number" name="per" id="m_per" class="form-control" required></div>
                <div class="col-12"><label>Calificación (0-10)</label><input type="number" name="cal" id="m_cal" class="form-control" min="0" max="10" required></div>
            </div>
            <div class="modal-footer"><button type="submit" name="save_mat" class="btn btn-unam w-100">GUARDAR CALIFICACIÓN</button></div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const mMat = new bootstrap.Modal(document.getElementById('modalMat'));
    function actualizarLic(sel) {
        const lic = sel.options[sel.selectedIndex].getAttribute('data-lic');
        document.getElementById('m_lic').value = lic;
    }
    function modalM(modo, d = null) {
        document.getElementById('m_mode').value = modo;
        if(modo === 'new') {
            document.querySelector('#modalMat form').reset();
            document.getElementById('m_cta').disabled = false;
            document.getElementById('m_asig').disabled = false;
        } else {
            document.getElementById('m_cta').value = d.NUMCTA;
            document.getElementById('m_asig').value = d.CLAVEASIG;
            document.getElementById('m_gpo').value = d.NUMGPO;
            document.getElementById('m_per').value = d.PERIODO;
            document.getElementById('m_cal').value = d.CALIFICACION;
            document.getElementById('m_lic').value = d.CVALIC;
        }
        mMat.show();
    }
</script>