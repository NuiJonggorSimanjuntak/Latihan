<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="text-primary">Selamat Datang di Toko Kami</h1>
            <h4 class="text-monospace">Halo, <strong><?= user()->email; ?></strong> Anda Login Sebagai <p class="badge badge-<?= ($role['role'] == 'admin') ? 'success' : 'warning'; ?>"><?= $role['role']; ?></p></h4>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>