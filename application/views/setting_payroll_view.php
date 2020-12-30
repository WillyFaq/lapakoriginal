
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-money-check-alt"></i> Setting Payroll</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Setting Payroll</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
            <div class="card-body">
                <?php
                    echo form_open($form, '', '');
                ?>
                    <div class="form-group">
                        <label for="gaji_sales_penjualan">Gaji Sales Setiap Penjualan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon-rp">Rp.</span>
                            </div>
                            <input type="number" min="0" class="form-control" name="gaji_sales_penjualan" id="gaji_sales_penjualan" placeholder="Gaji Sales Setiap Penjualan" <?= isset($gaji_sales_penjualan)?"value='$gaji_sales_penjualan'":''; ?> required="">
                        </div>
                        <small id="sales_gaji" class="form-text text-muted">Inputan diatas akan dikalikan dengan jumlah penjualan sales.</small>
                    </div>
                    <div class="form-group">
                        <label for="gaji_admin_diterima">Gaji Admin Setiap Penjualan Diterima</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon-rp">Rp.</span>
                            </div>
                            <input type="number" min="0" class="form-control" name="gaji_admin_diterima" id="gaji_admin_diterima" placeholder="Gaji Admin Setiap Penjualan Diterima" <?= isset($gaji_admin_diterima)?"value='$gaji_admin_diterima'":''; ?> required="">
                        </div>
                        <small id="sales_gaji" class="form-text text-muted">Inputan diatas akan dikalikan dengan jumlah input penjualan sales yang diterima.</small>
                    </div>
                    <div class="form-group">
                        <label for="gaji_admin_ditolak">Gaji Admin Setiap Penjualan Ditolak</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon-rp">Rp.</span>
                            </div>
                            <input type="number" min="0" class="form-control" name="gaji_admin_ditolak" id="gaji_admin_ditolak" placeholder="Gaji Admin Setiap Penjualan Ditolak" <?= isset($gaji_admin_ditolak)?"value='$gaji_admin_ditolak'":''; ?> required="">
                        </div>
                        <small id="sales_gaji" class="form-text text-muted">Inputan diatas akan dikalikan dengan jumlah input penjualan sales yang ditolak.</small>
                    </div>
                    <div class="form-group">
                        <label for="gaji_admin_iklan">Gaji Admin Iklan Setiap Penjualan Diterima</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="addon-rp">Rp.</span>
                            </div>
                            <input type="number" min="0" class="form-control" name="gaji_admin_iklan" id="gaji_admin_iklan" placeholder="Gaji Admin Iklan Setiap Penjualan" <?= isset($gaji_admin_iklan)?"value='$gaji_admin_iklan'":''; ?> required="">
                        </div>
                        <small id="sales_gaji" class="form-text text-muted">Inputan diatas akan dikalikan dengan jumlah input penjualan sales yang diterima.</small>
                    </div>

                    <div class="form-group row">
                        <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>