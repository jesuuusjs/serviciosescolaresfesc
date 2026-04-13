<?php 
require 'config.php'; 
include 'layout/header.php';


if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM GRUPO WHERE NUMGPO = ?")->execute([$_GET['delete']]);
    echo "<script>window.location='grupos.php';</script>";
}


if (isset($_POST['save_gpo'])) {
    $num = $_POST['numgpo']; $max = $_POST['cupo']; $ins = $_POST['inscritos'];
    if ($_POST['mode'] == 'new') {
        $pdo->prepare("INSERT INTO GRUPO VALUES (?,?,?)")->execute([$num, $max, $ins]);
    } else {
        $pdo->prepare("UPDATE GRUPO SET CUPOMAX=?, INSCRITOS=? WHERE NUMGPO=?")->execute([$max, $ins, $num]);
    }
    echo "<script>window.location='grupos.php';</script>";
}

$grupos = $pdo->query("SELECT * FROM GRUPO ORDER BY NUMGPO ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Grupos y Cupos</h3>
    <button class="btn btn-unam" onclick="modalG('new')"><i class="bi bi-grid-3x3-gap-fill"></i> Crear Grupo</button>
</div>

<div class="row">
    <?php foreach($grupos as $g): 
        $porcentaje = ($g['INSCRITOS'] / $g['CUPOMAX']) * 100;
        $color = ($porcentaje > 90) ? 'bg-danger' : 'bg-success';
    ?>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between">
                <span class="fw-bold text-primary">Grupo: <?= $g['NUMGPO'] ?></span>
                <div>
                    <button class="btn btn-sm btn-light" onclick='modalG("edit", <?= json_encode($g) ?>)'><i class="bi bi-pencil"></i></button>
                    <a href="grupos.php?delete=<?= $g['NUMGPO'] ?>" class="text-danger ms-2" onclick="return confirm('¿Borrar grupo?')"><i class="bi bi-x-circle-fill"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <small>Ocupación:</small>
                    <small class="fw-bold"><?= $g['INSCRITOS'] ?> / <?= $g['CUPOMAX'] ?></small>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar <?= $color ?>" style="width: <?= $porcentaje ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="modalGrupo" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form class="modal-content" method="POST">
            <input type="hidden" name="mode" id="g_mode" value="new">
            <div class="modal-header bg-dark text-white"><h5>Datos del Grupo</h5></div>
            <div class="modal-body g-3">
                <div class="mb-3"><label>Número de Grupo</label><input type="number" name="numgpo" id="g_num" class="form-control" required></div>
                <div class="mb-3"><label>Cupo Máximo</label><input type="number" name="cup" id="g_max" class="form-control" required></div>
                <div class="mb-3"><label>Inscritos Actuales</label><input type="number" name="inscritos" id="g_ins" class="form-control" value="0"></div>
            </div>
            <div class="modal-footer"><button type="submit" name="save_gpo" class="btn btn-unam w-100">GUARDAR GRUPO</button></div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const mG = new bootstrap.Modal(document.getElementById('modalGrupo'));
    function modalG(modo, d = null) {
        document.getElementById('g_mode').value = modo;
        if(modo === 'new') {
            document.getElementById('g_num').readOnly = false;
            document.querySelector('#modalGrupo form').reset();
        } else {
            document.getElementById('g_num').value = d.NUMGPO;
            document.getElementById('g_num').readOnly = true;
            document.getElementById('g_max').value = d.CUPOMAX;
            document.getElementById('g_ins').value = d.INSCRITOS;
        }
        mG.show();
    }
</script>