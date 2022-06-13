<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <?= $this->Form->create(null, [
                        'id' => 'seo-form',
                        'url' => Router::url(['controller' => $currentController, 'action' => 'seo', $record->id], true),
                        'method' => 'POST',
                        'accept-charset' => 'utf-8',
                        'enctype' => 'multipart/form-data',
                    ]); ?>
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
                    <?php foreach ($languageList as $languageCode => $languageLabel): ?>
                        <?php if (count($languageList) > 1): ?>
                            <h6 class="heading-small text-muted mb-4"><?php echo __('Multi Language Content'); ?> - <?php echo __($languageLabel); ?></h6>
                        <?php endif; ?>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="seo_data[<?php echo $languageCode; ?>][keyword]"><?php echo __('Keyword'); ?></label>
                                        <select id="seo_data[<?php echo $languageCode; ?>][keyword]" name="seo_data[<?php echo $languageCode; ?>][keyword][]" class="form-control select2-tag" multiple="multiple">
                                            <?php if (!empty($seoList[$languageCode]->content['keyword'])): ?>
                                                <?php foreach ($seoList[$languageCode]->content['keyword'] as $key): ?>
                                                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $key; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="seo_data[<?php echo $languageCode; ?>][description]"><?php echo __('Page Description'); ?></label>
                                        <textarea class="form-control" id="seo_data[<?php echo $languageCode; ?>][description]" name="seo_data[<?php echo $languageCode; ?>][description]"><?php echo (!empty($seoList[$languageCode]->content['description']) ? $seoList[$languageCode]->content['description'] : ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="seo_data[<?php echo $languageCode; ?>][thumbnail]"><?php echo __('Thumbnail'); ?></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="seo_data_<?php echo $languageCode; ?>_thumbnail_photo" name="seo_data_<?php echo $languageCode; ?>_thumbnail_photo" accept="<?php echo implode(',', Configure::read('photoUpload.uploadAccept')); ?>">
                                            <label class="custom-file-label" for="seo_data_<?php echo $languageCode; ?>_thumbnail_photo"><?php echo __('Choose file'); ?></label>
                                        </div>
                                    </div>
                                    <?php if (!empty($seoList[$languageCode]->content['thumbnail'])): ?>
                                        <div class="form-group">
                                            <?php $photoPath = $this->Url->build($seoList[$languageCode]->content['thumbnail'], true); ?>
                                            <a href="<?php echo $photoPath; ?>" class="thumbnail-link">
                                                <img src="<?php echo $photoPath; ?>" width="100" />
                                            </a>
                                            <input type="hidden" name="seo_data[<?php echo $languageCode; ?>][thumbnail]" value="<?php echo $photoPath; ?>" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                    <?php endforeach; ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
