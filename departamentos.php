<?php 
require 'config.php'; 
include 'layout/header.php';


if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM DEPARTAMENTO WHERE CVEDEPTO = ?");
    $stmt->execute([$_GET['delete']]);
    echo "<script>window.location='departamentos.php';</script>";
}

if (isset($_POST['save_depto'])) {
    $cve   = $_POST['cvedepto'];
    $nom   = $_POST['nombre'];
    $tel   = $_POST['tel'];
    $email = $_POST['email'];

    if ($_POST['mode'] == 'new') {
        $sql = "INSERT INTO DEPARTAMENTO (CVEDEPTO, NOMBREDEPTO, TELEFONODEPTO, CORREODEPTO) VALUES (?,?,?,?)";
        $pdo->prepare($sql)->execute([$cve, $nom, $tel, $email]);
    } else {
        $sql = "UPDATE DEPARTAMENTO SET NOMBREDEPTO=?, TELEFONODEPTO=?, CORREODEPTO=? WHERE CVEDEPTO=?";
        $pdo->prepare($sql)->execute([$nom, $tel, $email, $cve]);
    }
    echo "<script>window.location='departamentos.php';</script>";
}

$departamentos = $pdo->query("SELECT * FROM DEPARTAMENTO ORDER BY CVEDEPTO ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--unam-azul); font-weight: bold; border-left: 5px solid var(--unam-oro); padding-left: 10px;">Departamentos Académicos</h3>
    <button class="btn btn-unam shadow" onclick="modalD('new')">
        <i class="bi bi-building-add"></i> Nuevo Departamento
    </button>
</div>

<div class="row">
    <?php foreach($departamentos as $d): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 card-unam">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title text-primary fw-bold">ID: <?= $d['CVEDEPTO'] ?></h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick='modalD("edit", <?= json_encode($d) ?>)'><i class="bi bi-pencil me-2"></i>Editar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="departamentos.php?delete=<?= $d['CVEDEPTO'] ?>" onclick="return confirm('¿Eliminar departamento? Esto podría afectar a los profesores asignados.')"><i class="bi bi-trash me-2"></i>Eliminar</a></li>
                        </ul>
                    </div>
                </div>
                <h6 class="card-subtitle mb-3 text-muted text-uppercase small"><?= $d['NOMBREDEPTO'] ?></h6>
                <div class="mt-3 small">
                    <p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><?= $d['TELEFONODEPTO'] ?></p>
                    <p class="mb-0"><i class="bi bi-envelope text-primary me-2"></i><?= $d['CORREODEPTO'] ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="modalDepto" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content border-0 shadow" method="POST">
            <input type="hidden" name="mode" id="d_mode" value="new">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="d_titulo text-warning">Datos del Departamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 row g-3">
                <div class="col-md-4">
                    <label class="fw-bold small">Clave (ID)</label>
                    <input type="number" name="cvedepto" id="d_cve" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="fw-bold small">Nombre del Departamento</label>
                    <input type="text" name="nombre" id="d_nom" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="fw-bold small">Teléfono de Contacto</label>
                    <input type="text" name="tel" id="d_tel" class="form-control">
                </div>
                <div class="col-12">
                    <label class="fw-bold small">Correo Electrónico</label>
                    <input type="email" name="email" id="d_email" class="form-control">
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="submit" name="save_depto" class="btn btn-unam w-100 fw-bold py-2">GUARDAR DEPARTAMENTO</button>
            </div>
        </form>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script>
    const myModalD = new bootstrap.Modal(document.getElementById('modalDepto'));
    
    function modalD(modo, d = null) {
        document.getElementById('d_mode').value = modo;
        const inputCve = document.getElementById('d_cve');
        
        if(modo === 'new') {
            document.querySelector('#modalDepto form').reset();
            inputCve.readOnly = false;
        } else {
            inputCve.value = d.CVEDEPTO;
            inputCve.readOnly = true; // No se edita la llave primaria
            document.getElementById('d_nom').value = d.NOMBREDEPTO;
            document.getElementById('d_tel').value = d.TELEFONODEPTO;
            document.getElementById('d_email').value = d.CORREODEPTO;
        }
        myModalD.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>