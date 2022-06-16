<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="modal fade" id="modal-list-filter" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <h4><?php echo __('Filter'); ?></h4>
                        </div>
                        <?php if (!empty($searchingFields)): ?>
                            <div class="form-group">
                                <label class="form-control-label" for="filter_keyword"><?php echo __('Keyword'); ?></label>
                                <input autocomplete="off" class="form-control filter-input" id="filter_keyword" name="filter_keyword" placeholder="<?php echo __('Keyword'); ?>" type="text">
                            </div>
                        <?php endif; ?>
                        <?php foreach ($filterFields as $field => $fieldInfo): ?>
                            <div class="form-group">
                                <label class="form-control-label" for="filter[<?php echo $field; ?>]"><?php echo __(ucwords($field)); ?></label>
                                <select autocomplete="off" class="form-control select2 filter-input" name="filter[<?php echo $field; ?>]">
                                    <option value=""><?php echo __('All'); ?></option>
                                    <?php foreach ($fieldInfo['options'] as $key => $label): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                        <div class="text-center">
                            <button id="filter-submit" class="btn btn-primary my-4">
                                <i class="fas fa-search"></i>
                                <?php echo __('Filter'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
