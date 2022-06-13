<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <?= $this->Form->create(null, [
                        'id' => 'move-form',
                        'url' => Router::url(['controller' => $currentController, 'action' => 'submitMove'], true),
                        'method' => 'POST',
                        'rel' => 'async',
                        'accept-charset' => 'utf-8',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
                <input type="hidden" id="recordId" name="recordId" value="<?php echo $record->id; ?>" />
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
                    <h6 class="heading-small text-muted mb-4"><?php echo __('Change Display Order'); ?></h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo __('Current Item'); ?></label>
                                    <input type="text" class="form-control disable-input" disabled autocomplete="off" data-value="<?php echo $record->displayField; ?>" value="<?php echo $record->displayField; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">&nbsp;</div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="position"><?php echo __('Position'); ?></label>
                                    <select id="position" name="position" class="form-control select2">
                                        <option value="-1" selected="selected"><?php echo __('Before'); ?></option>
                                        <option value="1"><?php echo __('After'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="target_id"><?php echo __('Item'); ?></label>
                                    <select id="target_id" name="target_id" class="form-control select2">
                                        <?php foreach ($sameLevelObjects as $targetObject): ?>
                                            <?php if ($targetObject->id != $record->id):?>
                                                <option value="<?php echo $targetObject->id; ?>"><?php echo $targetObject->displayField; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
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
