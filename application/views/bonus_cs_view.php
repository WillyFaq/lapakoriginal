
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-hand-holding-usd"></i> Bonus</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Bonus</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                    <?php foreach($main as $k => $v): ?>
                        <tr>
                            <th width="20%"><?= $k; ?></th>
                            <td><?= $v; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p><strong>Detail Transaksi</strong></p>
                <?= $transaksi; ?>
            </div>
        </div>
    </div>
</div>


<!--  -->