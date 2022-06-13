<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
    <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</div>
<!-- Page content -->
<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary border-0 mb-0">
                <div class="card-body px-lg-5 py-lg-5">
                    <div class="text-center text-muted mb-4">
                        <?= $this->Html->image('/img/logo.png', ['height' => '100px']); ?>
                        <h1><?= __('Reset Password');?></h1>
                    </div>
                    <?= $this->Form->create(null, [
                        'id' => 'reset-form',
                        'url' => Router::url(['controller' => 'Authen', 'action' => 'submitReset'], true),
                        'rel' => 'async',
                        'method' => 'POST',
                        'accept-charset' => 'utf-8',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
                        <input type="hidden" name="code" value="<?php echo $verifyInfo->code; ?>" />
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="<?= __('New Password'); ?>" type="password" id="password" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="<?= __('Confirm Password'); ?>" type="password" id="password_confirm" name="password_confirm">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success my-4"><?= __('Send');?></button>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
