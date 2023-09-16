<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="header">
                <h1><?= $title; ?></h1>
            </div>
            <div class="col-sm-12">
                <?php if (session()->getFlashdata('pesan')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->getFlashdata('pesan') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-sm-12">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
            </div>
            <a href="<?= base_url('/latihan/tambahBarang/'); ?>" class="btn btn-primary mb-3"> Tambah Barang</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Jenis Barang</th>
                        <th scope="col">Harga Barang</th>
                        <th style="text-align: center;" scope="col">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1 ?>
                    <?php foreach ($data as $u) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $u['jenis_barang']; ?></td>
                            <td><?= "Rp." . number_format($u['harga_barang'], 0, ',', '.'); ?></td>
                            <td style="text-align: center;">
                                <a class="btn btn-warning" href="<?= base_url('/latihan/editBarang/' . $u['id']); ?>"> EDIT</a>
                                <form action="/latihan/hapusBarang/<?= $u['id']; ?>" method="post" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('apakah anda yakin')"> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>