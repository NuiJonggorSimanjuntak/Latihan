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
                    <form action="<?= base_url('/latihan/ubahTransaksi/' . $data['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control <?php if (session('errors.tanggal')) : ?>is-invalid<?php endif ?>" value="<?= old('tanggal', $data['tanggal']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="no_nota">No. Nota</label>
                            <input id="no_nota" type="text" class="form-control <?php if (session('errors.no_nota')) : ?>is-invalid<?php endif ?>" name="no_nota" placeholder="" value="<?= $data['no_nota'] ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="id_user"> Nama Pelanggan</label>
                            <select class="custom-select <?php if (session('errors.id_user')) : ?>is-invalid<?php endif ?>" name="id_user">
                                <option selected disabled>Pilih...</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user['id']; ?>" <?= old('id_user', $data['userid'] == $user['id'])  ? 'selected' : '' ?>><?= $user['username']; ?></option>
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
                                            <option value="<?= $brg['id']; ?>" <?= old('id_biaya', $data['barangid'] == $brg['id'])  ? 'selected' : '' ?>><?= $brg['jenis_barang']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= session('errors.id_biaya'); ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <input id="harga_barang" type="text" name="harga_barang" class="form-control" placeholder="" value="<?= old('harga_barang', $data['harga_barang']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_barang">Jumlah Barang</label>
                            <input id="jumlah_barang" type="number" class="form-control <?php if (session('errors.jumlah_barang')) : ?>is-invalid<?php endif ?>" name="jumlah_barang" placeholder="" value="<?= old('jumlah_barang', $data['jumlah_barang']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.jumlah_barang'); ?>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary btn-block"> UBAH</button>
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

        idBarangSelect.addEventListener('change', function() {
            var selectedId = idBarangSelect.value;
            var selectedBarang = BarangData.find(function(barang) {
                return barang.id == selectedId;
            });

            if (selectedBarang) {
                var formattedPrice = formatRupiah(selectedBarang.harga_barang);
                hargaInput.value = formattedPrice;
            } else {
                hargaInput.value = '';
            }
        });

        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formattedPrice = 'Rp.' + ribuan.join('.').split('').reverse().join('');
            return formattedPrice;
        }
    });
</script>

<?= $this->endSection(); ?>