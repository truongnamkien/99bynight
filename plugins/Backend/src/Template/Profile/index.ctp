<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <?= $this->Form->create(null, [
                        'id' => 'profile-form',
                        'url' => Router::url(['controller' => 'Profile', 'action' => 'updateProfile'], true),
                        'method' => 'POST',
                        'rel' => 'async',
                        'accept-charset' => 'utf-8',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo __('Edit profile'); ?></h3>
                        </div>
                        <div class="col-4 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><?php echo __('Update'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4"><?php echo __('User information'); ?></h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo __('Email'); ?></label>
                                    <input type="text" class="form-control disable-input" autocomplete="off" data-value="<?php echo $authUser->email; ?>" value="<?php echo $authUser->email; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo __('Role'); ?></label>
                                    <input type="text" class="form-control disable-input" autocomplete="off" data-value="<?php echo $authUser->admin_role->name; ?>" value="<?php echo $authUser->admin_role->name; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="current_password"><?php echo __('Current Password'); ?></label>
                                    <input type="password" id="current_password" name="current_password" class="form-control non-disabled" autocomplete="off" placeholder="<?php echo __('Current Password'); ?>" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="password"><?php echo __('New Password'); ?></label>
                                    <input type="password" id="password" name="password" class="form-control non-disabled" autocomplete="off" placeholder="<?php echo __('New Password'); ?>" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="password_confirm"><?php echo __('Confirm Password'); ?></label>
                                    <input type="password" id="password_confirm" name="password_confirm" class="form-control non-disabled" autocomplete="off" placeholder="<?php echo __('Confirm Password'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
