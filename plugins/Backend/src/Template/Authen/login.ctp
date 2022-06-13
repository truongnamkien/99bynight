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
                        <h1><?= __('Sign in');?></h1>
                        <small><?= __('Enter your credentials to access your account.'); ?></small>
                    </div>
                    <?php if (!empty($errorMsg)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-text"><?= implode('<br>', $errorMsg); ?></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?= $this->Form->create(null, [
                        'id' => 'login-form',
                        'url' => '/backend/login',
                        'method' => 'POST',
                        'accept-charset' => 'utf-8',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
                        <div class="form-group mb-3">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control" placeholder="<?= __('Email')?>" name="email" type="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="<?= __('Password'); ?>" name="password" type="password">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary my-4"><?= __('Sign in');?></button>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <a href="<?php echo $this->Url->build('backend/forgot', true); ?>" class="text-light"><small><?php echo __('Forgot password?'); ?></small></a>
                </div>
            </div>
        </div>
    </div>
</div>
