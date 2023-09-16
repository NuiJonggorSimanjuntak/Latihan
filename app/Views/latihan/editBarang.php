<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h1><?= $title; ?></h1>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('/latihan/ubahBarang/' . $barang['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="jenis_barang">Nama Barang</label>
                            <input type="text" class="form-control <?php if (session('errors.jenis_barang')) : ?>is-invalid<?php endif ?>" name="jenis_barang" placeholder="Masukkan Nama Barang" value="<?= old('jenis_barang', $barang['jenis_barang']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.jenis_barang'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="harga_barang">Harga Barang</label>
                            <input type="number" class="form-control <?php if (session('errors.harga_barang')) : ?>is-invalid<?php endif ?>" name="harga_barang" placeholder="Masukkan Nama Barang" value="<?= old('harga_barang', $barang['harga_barang']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.harga_barang'); ?>
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

<?= $this->endSection(); ?>