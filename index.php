<?php require 'config.php'; include 'layout/header.php'; ?>

<div class="row text-center">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <h3><?= $pdo->query("SELECT count(*) FROM ALUMNO")->fetchColumn() ?></h3>
                <p>Alumnos Inscritos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <h3><?= $pdo->query("SELECT count(*) FROM PROFESOR")->fetchColumn() ?></h3>
                <p>Profesores</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark shadow">
            <div class="card-body">
                <h3><?= $pdo->query("SELECT count(*) FROM GRUPO")->fetchColumn() ?></h3>
                <p>Grupos Abiertos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                <h3><?= $pdo->query("SELECT count(*) FROM ASIGNATURA")->fetchColumn() ?></h3>
                <p>Asignaturas</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4 shadow-sm">
    <div class="card-header bg-white"><strong>Últimas Matrículas</strong></div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Asignatura</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = "SELECT AL.NOMBREAL, ASIG.NOMBREASIG, M.CALIFICACION 
                          FROM MATRICULA M
                          JOIN ALUMNO AL ON M.NUMCTA = AL.NUMCTA
                          JOIN ASIGNATURA ASIG ON M.CLAVEASIG = ASIG.CLAVEASIG
                          ORDER BY M.PERIODO DESC LIMIT 5";
                foreach($pdo->query($query) as $row): ?>
                <tr>
                    <td><?= $row['NOMBREAL'] ?></td>
                    <td><?= $row['NOMBREASIG'] ?></td>
                    <td><span class="badge bg-secondary"><?= $row['CALIFICACION'] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>