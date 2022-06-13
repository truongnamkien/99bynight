<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <?= $this->Form->create(null, [
                        'id' => 'create-update-form',
                        'url' => Router::url(['controller' => $currentController, 'action' => 'submitUpdate'], true),
                        'method' => 'POST',
                        'accept-charset' => 'utf-8',
                        'rel' => 'async',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
                <input type="hidden" id="recordId" name="recordId" value="<?php echo (!empty($currentRecord) ? $currentRecord->id : ''); ?>" />
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
                    <?php if (!empty($inputList)): ?>
                        <h6 class="heading-small text-muted mb-4"><?php echo __('Main Info'); ?></h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <?= $this->element('Crud/create_update_fields', ['fieldList' => $inputList]) ?>
                            </div>
                        </div>
                        <hr class="my-4" />
                    <?php endif; ?>
                    <?php if (!empty($multiLangInputTypes)): ?>
                        <?php foreach ($languageList as $languageCode => $languageLabel): ?>
                            <?php if (count($languageList) > 1): ?>
                                <h6 class="heading-small text-muted mb-4"><?php echo __('Multi Language Content'); ?> - <?php echo __($languageLabel); ?></h6>
                            <?php endif; ?>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <?= $this->element('Crud/create_update_fields', ['fieldList' => $multiLangInputTypes[$languageCode]]) ?>
                                </div>
                            </div>
                            <hr class="my-4" />
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var $connector = '/filemanager/connectors/connector.php';
    var $filebrowserImageBrowseUrl =  '/filemanager/browser/browser.html?Type=Image&Connector=' + $connector;
    var $filebrowserImageUploadUrl =  '/filemanager/connectors/upload.php?Type=Image';
    var $filebrowserBrowseUrl =  '/filemanager/browser/browser.html?Connector=' + $connector;
</script>