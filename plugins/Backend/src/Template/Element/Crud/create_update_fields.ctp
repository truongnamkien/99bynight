<?php use Cake\Routing\Router; ?>
<?php foreach ($fieldList as $field => $fieldInfo): ?>
    <div class="col-lg-<?php echo ($fieldInfo['input'] == 'ckeditor' || $fieldInfo['input'] == 'textarea' ? 12 : 6); ?> <?php echo ($fieldInfo['input'] == 'hidden' ? 'dpn' : ''); ?>">
        <div class="form-group form-group-<?php echo $fieldInfo['input']; ?>" id="form-group-<?php echo $field; ?>">
            <label class="form-control-label" for="<?php echo $field; ?>"><?php echo __($fieldInfo['label']); ?></label>
            <br />
            <?php if ($fieldInfo['input'] == 'dropdown'): ?>
                <select id="<?php echo $field; ?>" name="<?php echo $field; ?>" class="form-control select2">
                    <?php foreach ($fieldInfo['options'] as $optionVal => $optionKey): ?>
                        <option value="<?php echo $optionVal; ?>" <?php echo ($optionVal == $fieldInfo['currentValue'] ? 'selected="selected"' : ''); ?>><?php echo $optionKey; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($fieldInfo['input'] == 'multi_select'): ?>
                <select id="<?php echo $field; ?>" name="<?php echo $field; ?>[]" class="form-control select2" multiple="multiple">
                    <?php foreach ($fieldInfo['options'] as $optionVal => $optionKey): ?>
                        <option value="<?php echo $optionVal; ?>" <?php echo (!empty($fieldInfo['currentValue'][$optionVal]) ? 'selected="selected"' : ''); ?>><?php echo $optionKey; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($fieldInfo['input'] == 'multi_tag'): ?>
                <select id="<?php echo $field; ?>" name="<?php echo $field; ?>[]" class="form-control select2-tag" multiple="multiple">
                    <?php foreach ($fieldInfo['options'] as $optionVal => $optionKey): ?>
                        <option value="<?php echo $optionVal; ?>" <?php echo (!empty($fieldInfo['currentValue'][$optionVal]) ? 'selected="selected"' : ''); ?>><?php echo $optionKey; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($fieldInfo['input'] == 'textarea'): ?>
                <textarea class="form-control" id="<?php echo $field; ?>" name="<?php echo $field; ?>" rows="10" placeholder="<?php echo __($fieldInfo['label']); ?>"><?php echo $fieldInfo['currentValue']; ?></textarea>
            <?php elseif ($fieldInfo['input'] == 'ckeditor'): ?>
                <textarea class="form-control textarea-ckeditor" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>"><?php echo $fieldInfo['currentValue']; ?></textarea>
            <?php elseif ($fieldInfo['input'] == 'datepicker'): ?>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <input type="text" class="form-control crud-datepicker" value="<?php echo htmlentities($fieldInfo['currentValue']); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
                </div>
            <?php elseif ($fieldInfo['input'] == 'colorpicker'): ?>
                <div class="input-group my-colorpicker2">
                    <div class="input-group-addon">
                        <i></i>
                    </div>
                    <input type="text" class="form-control" value="<?php echo htmlentities($fieldInfo['currentValue']); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
                </div>
            <?php elseif ($fieldInfo['input'] == 'timepicker'): ?>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control timepicker" value="<?php echo htmlentities($fieldInfo['currentValue']); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
                </div>
            <?php elseif ($fieldInfo['input'] == 'suffix'): ?>
                <div class="input-group">
                    <input type="<?php echo (!empty($fieldInfo['type']) ? $fieldInfo['type'] : 'text'); ?>" class="form-control" value="<?php echo htmlentities($fieldInfo['currentValue']); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <?php if (!empty($fieldInfo['icon'])): ?>
                                <i class="fa fa-<?php echo $fieldInfo['icon']; ?>"></i>
                            <?php else: ?>
                                <?php echo $fieldInfo['extra']; ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php elseif ($fieldInfo['input'] == 'prefix'): ?>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <?php if (!empty($fieldInfo['icon'])): ?>
                                <i class="fa fa-<?php echo $fieldInfo['icon']; ?>"></i>
                            <?php else: ?>
                                <?php echo $fieldInfo['extra']; ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <input type="<?php echo (!empty($fieldInfo['type']) ? $fieldInfo['type'] : 'text'); ?>" class="form-control" value="<?php echo htmlentities($fieldInfo['currentValue']); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
                </div>
            <?php elseif ($fieldInfo['input'] == 'bothfix'): ?>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <?php if (!empty($fieldInfo['prefix_icon'])): ?>
                                <i class="fa fa-<?php echo $fieldInfo['prefix_icon']; ?>"></i>
                            <?php else: ?>
                                <?php echo $fieldInfo['prefix_extra']; ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <input type="<?php echo (!empty($fieldInfo['type']) ? $fieldInfo['type'] : 'text'); ?>" class="form-control" value="<?php echo htmlentities($fieldInfo['currentValue']); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <?php if (!empty($fieldInfo['suffix_icon'])): ?>
                                <i class="fa fa-<?php echo $fieldInfo['suffix_icon']; ?>"></i>
                            <?php else: ?>
                                <?php echo $fieldInfo['suffix_extra']; ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php elseif ($fieldInfo['input'] == 'checkbox_toggle'): ?>
                <label class="custom-toggle">
                    <input <?php echo ($fieldInfo['currentValue'] ? 'checked="checked"' : ''); ?> type="checkbox" data-toggle="toggle" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                    <span class="custom-toggle-slider rounded-circle" data-label-off="<?php echo __('Off'); ?>" data-label-on="<?php echo __('On'); ?>"></span>
                </label>
            <?php elseif ($fieldInfo['input'] == 'none'): ?>
                <?php echo $fieldInfo['currentValue']; ?>
            <?php elseif ($fieldInfo['input'] == 'photo'): ?>
                <?php if (!empty($fieldInfo['currentValue'])): ?>
                    <?php $photoPath = $this->Url->build($fieldInfo['currentValue']->path, true); ?>
                    <a href="<?php echo $photoPath; ?>" class="thumbnail-link">
                        <img src="<?php echo $photoPath; ?>" width="200" />
                    </a>
                    <input type="hidden" name="<?php echo $field; ?>_id" value="<?php echo $fieldInfo['currentValue']->id; ?>" />
                <?php endif; ?>
            <?php elseif ($fieldInfo['input'] == 'uploadPhoto'): ?>
                <div id="<?php echo $field; ?>_photo">
                    <?php if (!empty($fieldInfo['currentValue'])): ?>
                        <?php $photoPath = $this->Url->build($fieldInfo['currentValue']->path, true); ?>
                        <a href="<?php echo $photoPath; ?>" class="thumbnail-link">
                            <img src="<?php echo $photoPath; ?>" width="200" />
                        </a>
                    <?php endif; ?>
                </div>
                <button class="btn btn-icon btn-primary mt-3 btn-upload" data-upload="<?php echo $field; ?>_upload">
                    <span class="btn-inner--icon"><i class="fas fa-file-import"></i></span>
                    <span class="btn-inner--text"><?php echo __('Choose file'); ?></span>
                </button>
                <input type="file" class="dpn single-photo-uploader" data-url="<?php echo Router::url(['controller' => $currentController, 'action' => 'submitPhoto'], true); ?>" data-queue="<?php echo $field; ?>" id="<?php echo $field; ?>_upload" <?php echo (!empty($fieldInfo['accept']) ? 'accept="' . $fieldInfo['accept'] . '"' : ''); ?> />
                <input type="hidden" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                <div class="clearfix mt-3"></div>
                <?php if (!empty($fieldInfo['width'])): ?>
                    <span class="badge badge-info">
                        <?php echo __('Min Width: ') . $fieldInfo['width'] . ' px'; ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($fieldInfo['height'])): ?>
                    <span class="badge badge-info">
                        <?php echo __('Min Height: ') . $fieldInfo['height'] . ' px'; ?>
                    </span>
                <?php endif; ?>
            <?php elseif ($fieldInfo['input'] == 'uploadFile'): ?>
                <div id="<?php echo $field; ?>_file">
                    <?php if (!empty($fieldInfo['currentValue'])): ?>
                        <?php $filePath = $this->Url->build($fieldInfo['currentValue'], true); ?>
                        <a href="<?php echo $filePath; ?>" target="_blank">
                            <?php echo __('Click Here To Download'); ?>
                        </a>
                        <input type="hidden" name="<?php echo $field; ?>" value="<?php echo $fieldInfo['currentValue']; ?>" />
                    <?php endif; ?>
                </div>
                <button class="btn btn-icon btn-primary mt-3 btn-upload" data-upload="<?php echo $field; ?>_upload">
                    <span class="btn-inner--icon"><i class="fas fa-file-import"></i></span>
                    <span class="btn-inner--text"><?php echo __('Choose file'); ?></span>
                </button>
                <input type="file" class="dpn single-file-uploader" data-url="<?php echo $fieldInfo['url']; ?>" data-queue="<?php echo $field; ?>" id="<?php echo $field; ?>_upload" <?php echo (!empty($fieldInfo['accept']) ? 'accept="' . $fieldInfo['accept'] . '"' : ''); ?> />
                <input type="hidden" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo $filePath; ?>" />
                <div class="clearfix mt-3"></div>
            <?php elseif ($fieldInfo['input'] == 'element'): ?>
                <?= $this->element('Backend.' . $fieldInfo['element'], ['field' => $field, 'currentValue' => $fieldInfo['currentValue']]) ?>
            <?php elseif ($fieldInfo['input'] == 'google-map'): ?>
                <?= $this->element('Backend.Crud/pagelet_google_map', ['field' => $field, 'value' => $fieldInfo['currentValue']]) ?>
            <?php else: ?>
                <input type="<?php echo $fieldInfo['input']; ?>" class="form-control" value="<?php echo ($fieldInfo['input'] == 'password' ? '' : htmlentities($fieldInfo['currentValue'])); ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo __($fieldInfo['label']); ?>" />
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
