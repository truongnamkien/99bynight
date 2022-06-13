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
                        <thead class="thead-light">
                            <tr>
                                <?php foreach ($listViewCols as $field => $fieldInfo): ?>
                                    <?php if (!empty($fieldInfo['filter'])): ?>
                                        <th scope="col" class="sort">
                                            <a href="#" class="list-sorting" data-field="<?php echo $fieldInfo['filter']; ?>" data-order="ASC">
                                                <i class="fas fa-angle-down"></i>
                                                <?php echo __($fieldInfo['label']); ?>
                                            </a>
                                        </th>
                                    <?php else: ?>
                                        <th scope="col"><?php echo __($fieldInfo['label']); ?></th>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="list-data"></tbody>
                    </table>
                </div>
                <div class="card-footer py-4" id="list-pagination"></div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->create(null, [
    'id' => 'ajax-list-form',
    'url' => Router::url(['controller' => $currentController, 'action' => 'loadList'], true),
    'method' => 'POST',
    'rel' => 'async',
    'accept-charset' => 'utf-8',
    'enctype' => 'multipart/form-data',
]); ?>
    <input type="hidden" autocomplete="off" id="pageIndex" name="pageIndex" value="1" />
    <input type="hidden" autocomplete="off" id="sortField" name="sortField" value="<?php echo (!empty($defaultSorting['field']) ? $defaultSorting['field'] : ''); ?>" />
    <input type="hidden" autocomplete="off" id="sortOrder" name="sortOrder" value="<?php echo (!empty($defaultSorting['order']) ? $defaultSorting['order'] : ''); ?>" />
    <input type="hidden" autocomplete="off" id="pageSize" name="pageSize" />
    <?php echo $this->element('Crud/list_view_filter'); ?>
<?= $this->Form->end() ?>