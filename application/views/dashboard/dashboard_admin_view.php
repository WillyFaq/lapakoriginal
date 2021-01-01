<div class="row">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pesanan Belum dikirim</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
            <div class="card-body">
            	<?= isset($table)?$table:''; ?>
        	</div>
        </div>
    </div>
    <div class="col mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pesanan Sudah dikirim</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
            <div class="card-body">
                <?= isset($table2)?$table2:''; ?>
            </div>
        </div>
    </div>
    <div class="col mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pesanan Pending</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
            <div class="card-body">
                <?= isset($table3)?$table3:''; ?>
            </div>
        </div>
    </div>
</div>