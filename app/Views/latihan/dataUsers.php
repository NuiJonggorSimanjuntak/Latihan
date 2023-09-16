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
            <a href="<?= base_url('/latihan/tambahUsers/'); ?>" class="btn btn-primary mb-3"> Tambah User</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Email</th>
                        <th scope="col">Username</th>
                        <th style="text-align: center;" scope="col">Level</th>
                        <th style="text-align: center;" scope="col">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1 ?>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $u['email']; ?></td>
                            <td><?= $u['username']; ?></td>
                            <td style="text-align: center;">
                                <p class="badge badge-<?= ($u['role'] == 'admin') ? 'success' : 'warning'; ?>"><?= $u['role']; ?></p>
                            </td>
                            <td style="text-align: center;">
                                <a class="btn btn-warning" href="<?= base_url('/latihan/editUsers/' . $u['id']); ?>"> EDIT</a>
                                <form action="/latihan/hapusUsers/<?= $u['id']; ?>" method="post" class="d-inline">
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