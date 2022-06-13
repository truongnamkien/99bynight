<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0"><?php echo $headerTitle; ?></h3>
                </div>
                <div class="table-responsive" style="min-height: 250px;">
                    <table class="table align-items-center table-flush">
                        <tbody class="list" id="detail-data">
                            <?php foreach ($detailViewCols as $field => $fieldInfo): ?>
                                <?php $fieldSections = explode('.', $field); ?>
                                <?php $value = $currentRecord; ?>
                                <?php foreach ($fieldSections as $subSection): ?>
                                    <?php $value = !empty($value->$subSection) ? $value->$subSection : false; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <th class="text-wrap col-4" scope="row"><?php echo __($fieldInfo['label']); ?></th>
                                    <td class="text-wrap col-8">
                                        <?php if (!empty($activationFields) && !empty($activationFields[$field]) && !empty($activationFields[$field][$value])): ?>
                                            <span class="badge badge-<?php echo (!empty($activationFields[$field][$value]['iconClass']) ? $activationFields[$field][$value]['iconClass'] : 'info'); ?>"><?php echo $activationFields[$field][$value]['label']; ?></span>
                                        <?php elseif (!empty($activationFields) && !empty($activationFields["$modelName.$field"]) && !empty($activationFields["$modelName.$field"][$value])): ?>
                                            <span class="badge badge-<?php echo (!empty($activationFields["$modelName.$field"][$value]['iconClass']) ? $activationFields["$modelName.$field"][$value]['iconClass'] : 'info'); ?>"><?php echo $activationFields["$modelName.$field"][$value]['label']; ?></span>
                                        <?php elseif (!empty($toggleFields) && in_array($field, $toggleFields)): ?>
                                            <span class="badge badge-<?php echo ($value ? 'success' : 'danger'); ?>"><?php echo __($value ? 'On' : 'Off'); ?></span>
                                        <?php elseif (!empty($fieldInfo['type']) && $fieldInfo['type'] == 'file' && !empty($value)): ?>
                                            <?php $filePath = $this->Url->build($value, true); ?>
                                            <a href="<?php echo $filePath; ?>" target="_blank">
                                                <?php echo __('Click Here To Download'); ?>
                                            </a>
                                        <?php elseif (!empty($singlePhotos) && !empty($singlePhotos[$field]) && $value instanceof Backend\Model\Entity\Photo): ?>
                                            <?php $photoPath = $this->Url->build($value->path, true); ?>
                                            <a href="<?php echo $photoPath; ?>" class="thumbnail-link">
                                                <img src="<?php echo $photoPath; ?>" width="400" />
                                            </a>
                                        <?php elseif ($value instanceof Cake\I18n\FrozenTime): ?>
                                            <?php echo $value->i18nFormat('dd/MM/yyyy HH:mm:ss'); ?>
                                        <?php else: ?>
                                            <?php echo nl2br($value); ?>
                                        <?php endif; ?>
                                        <?php if ($value !== false && !empty($fieldInfo['suffix'])): ?>
                                            <?php echo $fieldInfo['suffix']; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
