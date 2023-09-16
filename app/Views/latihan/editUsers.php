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
                    <form action="<?= base_url('/latihan/ubahUsers/' . $users['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email"><?= lang('Auth.email') ?></label>
                            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email', $users['email']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="username"><?= lang('Auth.username') ?></label>
                            <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username', $users['username']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="password"><?= lang('Auth.password') ?></label>
                            <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off" value="<?= old('password'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="role"> Level</label>
                            <select class="custom-select" name="group_id" id="inputGroupSelect01">
                                <option selected>Pilih...</option>
                                <?php foreach ($role as $r) : ?>
                                    <option value="<?= $r['id']; ?>" <?= old('role', $r['id'] == $users['group_id'] )  ? 'selected' : '' ?> class="badge badge-<?= ($r['id'] == 1) ? 'success' : 'warning'; ?>"><?= $r['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block"> UBAH</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>