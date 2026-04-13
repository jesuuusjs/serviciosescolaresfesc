<?php 
require 'config.php'; 
include 'layout/header.php';

// --- LÓGICA CRUD ---
if (isset($_GET['del_h'])) {
    $pdo->prepare("DELETE FROM ASIGNA WHERE IDHORARIO = ? AND PERIODO = ?")->execute([$_GET['id'], $_GET['per']]);
    echo "<script>window.location='asigna.php';</script>";
}

if (isset($_POST['save_h'])) {
    $data = [$_POST['id'], $_POST['per'], $_POST['aula'], $_POST['gpo'], 
             $_POST['d1'], $_POST['hi1'], $_POST['hf1'], 
             $_POST['d2'], $_POST['hi2'], $_POST['hf2'], 
             $_POST['d3'], $_POST['hi3'], $_POST['hf3']];
    
    if ($_POST['mode'] == 'new') {
        $sql = "INSERT INTO ASIGNA VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    } else {
        $sql = "UPDATE ASIGNA SET IDAULA=?, NUMGPO=?, DIA1=?, HORAINI_1=?, HORAFIN_1=?, DIA2=?, HORAINI_2=?, HORAFIN_2=?, DIA3=?, HORAINI_3=?, HORAFIN_3=? WHERE IDHORARIO=? AND PERIODO=?";
        // Reordenar array para el UPDATE
        $data = [$_POST['aula'], $_POST['gpo'], $_POST['d1'], $_POST['hi1'], $_POST['hf1'], $_POST['d2'], $_POST['hi2'], $_POST['hf2'], $_POST['d3'], $_POST['hi3'], $_POST['hf3'], $_POST['id'], $_POST['per']];
    }
    $pdo->prepare($sql)->execute($data);
    echo "<script>window.location='asigna.php';</script>";
}

$aulas = $pdo->query("SELECT * FROM AULA")->fetchAll();
$grupos = $pdo->query("SELECT NUMGPO FROM GRUPO")->fetchAll();
$horarios = $pdo->query("SELECT A.*, AU.TIPOAULA FROM ASIGNA A JOIN AULA AU ON A.IDAULA = AU.IDAULA")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Programación de Horarios</h3>
    <button class="btn btn-unam" onclick="modalH('new')"><i class="bi bi-calendar-event"></i> Asignar Horario</button>
</div>

<div class="row">
    <?php foreach($horarios as $h): ?>
    <div class="col-md-6 mb-3">
        <div class="card card-unam shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title text-primary">Grupo <?= $h['NUMGPO'] ?> | <?= $h['TIPOAULA'] ?></h5>
                    <span>ID: <?= $h['IDHORARIO'] ?></span>
                </div>
                <p class="mb-1 text-muted">Periodo: <?= $h['PERIODO'] ?></p>
                <div class="small border-top pt-2">
                    <strong><?= $h['DIA1'] ?>:</strong> <?= $h['HORAINI_1'] ?>-<?= $h['HORAFIN_1'] ?> | 
                    <strong><?= $h['DIA2'] ?>:</strong> <?= $h['HORAINI_2'] ?>-<?= $h['HORAFIN_2'] ?>
                </div>
                <div class="mt-3 text-end">
                    <button class="btn btn-sm btn-light" onclick='modalH("edit", <?= json_encode($h) ?>)'><i class="bi bi-pencil"></i></button>
                    <a href="asigna.php?del_h=1&id=<?= $h['IDHORARIO'] ?>&per=<?= $h['PERIODO'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('¿Borrar horario?')"><i class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="modalH" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST">
            <input type="hidden" name="mode" id="h_mode" value="new">
            <div class="modal-header bg-dark text-white"><h5>Configuración de Horario</h5></div>
            <div class="modal-body row g-2">
                <div class="col-md-3"><label>ID Horario</label><input type="number" name="id" id="h_id" class="form-control" required></div>
                <div class="col-md-3"><label>Periodo</label><input type="number" name="per" id="h_per" class="form-control" required></div>
                <div class="col-md-3"><label>Aula</label>
                    <select name="aula" id="h_aula" class="form-select">
                        <?php foreach($aulas as $au): ?><option value="<?= $au['IDAULA'] ?>"><?= $au['TIPOAULA'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><label>Grupo</label>
                    <select name="gpo" id="h_gpo" class="form-select">
                        <?php foreach($grupos as $gp): ?><option value="<?= $gp['NUMGPO'] ?>"><?= $gp['NUMGPO'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 border-end"><label>Día 1</label><input type="text" name="d1" id="h_d1" class="form-control" placeholder="LUN"></div>
                <div class="col-md-4"><label>Ini 1</label><input type="text" name="hi1" id="h_hi1" class="form-control" placeholder="07:00"></div>
                <div class="col-md-4"><label>Fin 1</label><input type="text" name="hf1" id="h_hf1" class="form-control" placeholder="09:00"></div>
                <div class="col-md-4 border-end"><label>Día 2</label><input type="text" name="d2" id="h_d2" class="form-control" placeholder="MIE"></div>
                <div class="col-md-4"><label>Ini 2</label><input type="text" name="hi2" id="h_hi2" class="form-control"></div>
                <div class="col-md-4"><label>Fin 2</label><input type="text" name="hf2" id="h_hf2" class="form-control"></div>
                <div class="col-md-4 border-end"><label>Día 3</label><input type="text" name="d3" id="h_d3" class="form-control" value="VIE"></div>
                <div class="col-md-4"><label>Ini 3</label><input type="text" name="hi3" id="h_hi3" class="form-control"></div>
                <div class="col-md-4"><label>Fin 3</label><input type="text" name="hf3" id="h_hf3" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="submit" name="save_h" class="btn btn-unam w-100">PROGRAMAR CLASE</button></div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const mHor = new bootstrap.Modal(document.getElementById('modalH'));
    function modalH(modo, d = null) {
        document.getElementById('h_mode').value = modo;
        if(modo === 'new') {
            document.querySelector('#modalH form').reset();
            document.getElementById('h_id').readOnly = false;
        } else {
            document.getElementById('h_id').value = d.IDHORARIO;
            document.getElementById('h_id').readOnly = true;
            document.getElementById('h_per').value = d.PERIODO;
            document.getElementById('h_aula').value = d.IDAULA;
            document.getElementById('h_gpo').value = d.NUMGPO;
            document.getElementById('h_d1').value = d.DIA1; document.getElementById('h_hi1').value = d.HORAINI_1; document.getElementById('h_hf1').value = d.HORAFIN_1;
            document.getElementById('h_d2').value = d.DIA2; document.getElementById('h_hi2').value = d.HORAINI_2; document.getElementById('h_hf2').value = d.HORAFIN_2;
            document.getElementById('h_d3').value = d.DIA3; document.getElementById('h_hi3').value = d.HORAINI_3; document.getElementById('h_hf3').value = d.HORAFIN_3;
        }
        mHor.show();
    }
</script>