<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<?php foreach ($recordList as $record): ?>
    <tr>
        <?php foreach ($listViewCols as $field => $fieldInfo): ?>
            <?php $fieldSections = explode('.', $field); ?>
            <?php $value = $record; ?>
            <?php foreach ($fieldSections as $subSection): ?>
                <?php $value = !empty($value->$subSection) ? $value->$subSection : false; ?>
            <?php endforeach; ?>
            <td style="<?php echo (!empty($fieldInfo['width']) ? 'max-width:' . $fieldInfo['width'] . ';overflow: hidden;' : ''); ?>">
                <?php if (!empty($activationFields) && !empty($activationFields[$field]) && !empty($activationFields[$field][$value])): ?>
                    <span class="badge badge-<?php echo (!empty($activationFields[$field][$value]['iconClass']) ? $activationFields[$field][$value]['iconClass'] : 'info'); ?>"><?php echo $activationFields[$field][$value]['label']; ?></span>
                <?php elseif (!empty($activationFields) && !empty($activationFields["$modelName.$field"]) && !empty($activationFields["$modelName.$field"][$value])): ?>
                    <span class="badge badge-<?php echo (!empty($activationFields["$modelName.$field"][$value]['iconClass']) ? $activationFields["$modelName.$field"][$value]['iconClass'] : 'info'); ?>"><?php echo $activationFields["$modelName.$field"][$value]['label']; ?></span>
                <?php elseif (!empty($toggleFields) && in_array($field, $toggleFields)): ?>
                    <span class="badge badge-<?php echo ($record->$field ? 'success' : 'danger'); ?>"><?php echo __($record->$field ? 'On' : 'Off'); ?></span>
                <?php elseif (!empty($singlePhotos) && !empty($singlePhotos[$field]) && $value instanceof Backend\Model\Entity\Photo): ?>
                    <?php $photoPath = $this->Url->build($value->path, true); ?>
                    <a href="<?php echo $photoPath; ?>" class="thumbnail-link">
                        <img src="<?php echo $photoPath; ?>" height="150" />
                    </a>
                <?php elseif ($value instanceof Cake\I18n\FrozenTime): ?>
                    <?php echo $value->i18nFormat('dd/MM/yyyy HH:mm:ss'); ?>
                <?php else: ?>
                    <?php echo $value; ?>
                <?php endif; ?>
            </td>
        <?php endforeach; ?>
        <td class="text-right">
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <?php foreach ($record->actionList as $actionInfo): ?>
                        <a class="dropdown-item text-<?php echo (!empty($actionInfo['textColor']) ? $actionInfo['textColor'] : 'primary'); ?> " href="<?php echo $actionInfo['url']; ?>">
                            <i class="fas fa-<?php echo $actionInfo['icon']; ?>"></i>
                            <?php echo __($actionInfo['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
