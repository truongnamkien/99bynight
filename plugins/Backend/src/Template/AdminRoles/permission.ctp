<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<?php use Cake\Utility\Inflector; ?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <?= $this->Form->create(null, [
                        'id' => 'permission-form',
                        'url' => Router::url(['controller' => 'AdminRoles', 'action' => 'submitPermission'], true),
                        'method' => 'POST',
                        'rel' => 'async',
                        'accept-charset' => 'utf-8',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
                <input type="hidden" id="role_id" name="role_id" value="<?php echo $adminRole->id; ?>" />
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $headerTitle; ?></h3>
                        </div>
                        <div class="col-4 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><?php echo __('Update'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-4">
                        <div class="row">
                            <?php foreach ($controllerList as $controller): ?>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php $title = Inflector::humanize(Inflector::underscore($controller)); ?>
                                        <label class="form-control-label" for="permission[<?php echo $controller; ?>]"><?php echo __($title); ?></label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <?php $allowed = !empty($permissionList[$controller]); ?>
                                        <label class="custom-toggle">
                                            <input <?php echo ($allowed ? 'checked="checked"' : ''); ?> type="checkbox" id="permission[<?php echo $controller; ?>]" name="permission[<?php echo $controller; ?>]" />
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
