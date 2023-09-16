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
                    <?= view('Myth\Auth\Views\_message_block') ?>
                    <form action="<?= base_url('/latihan/simpanUsers') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email"><?= lang('Auth.email') ?></label>
                            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                        </div>

                        <div class="form-group">
                            <label for="username"><?= lang('Auth.username') ?></label>
                            <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
                        </div>

                        <div class="form-group">
                            <label for="password"><?= lang('Auth.password') ?></label>
                            <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="role"> Level</label>
                            <select class="custom-select" name="role" id="inputGroupSelect01">
                                <option selected>Pilih...</option>
                                <option value="admin" class="badge-success">Admin</option>
                                <option value="user" class="badge-warning">User</option>
                            </select>

                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary btn-block"> SIMPAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>