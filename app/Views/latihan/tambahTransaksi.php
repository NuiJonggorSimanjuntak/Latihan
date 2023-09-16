<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h2><?= $title; ?></h2>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('/latihan/simpanTransaksi') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control <?php if (session('errors.tanggal')) : ?>is-invalid<?php endif ?>" value="<?= old('tanggal') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="no_nota">No. Nota</label>
                            <input id="no_nota" type="text" class="form-control <?php if (session('errors.no_nota')) : ?>is-invalid<?php endif ?>" name="no_nota" placeholder="" value="<?= $kodeBarang ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="id_user"> Nama Pelanggan</label>
                            <select class="custom-select <?php if (session('errors.id_user')) : ?>is-invalid<?php endif ?>" name="id_user">
                                <option selected disabled>Pilih...</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user['id']; ?>" <?= old('id_user')  ? 'selected' : '' ?>><?= $user['username']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.id_user'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_biaya"> Nama Barang</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="custom-select <?php if (session('errors.id_biaya')) : ?>is-invalid<?php endif ?>" name="id_biaya" id="id_biaya">
                                        <option selected disabled>Pilih...</option>
                                        <?php foreach ($barang as $brg) : ?>
                                            <option value="<?= $brg['id']; ?>" <?= (old('id_biaya')) ? 'selected' : '' ?>><?= $brg['jenis_barang']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= session('errors.id_biaya'); ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <input id="harga_barang" type="text" name="harga_barang" class="form-control" placeholder="" value="<?= old('harga_barang'); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_barang">Jumlah Barang</label>
                            <input id="jumlah_barang" type="number" class="form-control <?php if (session('errors.jumlah_barang')) : ?>is-invalid<?php endif ?>" name="jumlah_barang" placeholder="" value="<?= old('jumlah_barang') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.jumlah_barang'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total">Total Harga</label>
                            <input id="total" type="number" class="form-control" name="total" placeholder="" value="<?= old('total') ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="bayaran">Uang Bayaran</label>
                            <input id="bayaran" type="number" class="form-control <?php if (session('errors.bayaran')) : ?>is-invalid<?php endif ?>" name="bayaran" placeholder="" value="<?= old('bayaran') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.bayaran'); ?>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary btn-block"> SIMPAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var BarangData = <?= json_encode($barang) ?>;
        var idBarangSelect = document.getElementById('id_biaya');
        var hargaInput = document.getElementById('harga_barang');
        var jumlahInput = document.getElementById('jumlah_barang');
        var totalInput = document.getElementById('total');
        
        idBarangSelect.addEventListener('change', function() {
            var selectedId = idBarangSelect.value;
            var selectedBarang = BarangData.find(function(barang) {
                return barang.id == selectedId;
            });

            if (selectedBarang) {
                hargaInput.value = selectedBarang.harga_barang;
            } else {
                hargaInput.value = '';
            }
        });

        jumlahInput.addEventListener('input', updateTotal);
        hargaInput.addEventListener('input', updateTotal);

        function updateTotal() {
            var jumlah_barang = parseFloat(jumlahInput.value) || 0;
            var harga_barang = parseFloat(hargaInput.value) || 0;
            var total = jumlah_barang * harga_barang;
            totalInput.value= total;
        }
        updateTotal();
    });
</script>

<?= $this->endSection(); ?>