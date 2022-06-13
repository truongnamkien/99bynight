<?php use Cake\Core\Configure; ?>
<?php if ($totalPage > 1): ?>
    <nav class="mb-4">
        <ul class="pagination justify-content-center">
            <?php if ($pageIndex - 2 > 0): ?>
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">
                        <i class="fas fa-angles-left"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($pageIndex > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="#" data-page="<?php echo ($pageIndex - 1); ?>">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php for ($i = $pageIndex - 2; $i <= $pageIndex + 2; $i++): ?>
                <?php if ($i <= $totalPage && $i > 0): ?>
                    <li class="page-item <?php echo ($i == $pageIndex ? 'active' : ''); ?>">
                        <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($pageIndex < $totalPage): ?>
                <li class="page-item">
                    <a class="page-link" href="#" data-page="<?php echo ($pageIndex + 1); ?>">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($pageIndex + 2 <= $totalPage): ?>
                <li class="page-item">
                    <a class="page-link" href="#" data-page="<?php echo $totalPage; ?>">
                        <i class="fas fa-angles-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<div class="row justify-content-md-center">
    <div class="col-md-auto">
        <?php echo __('Total item'); ?> (<?php echo $totalRecords; ?>) - <?php echo __('Current page'); ?> (<?php echo $pageIndex; ?> / <?php echo $totalPage;?>)
        <?php $pageSizeList = [5, 10, 20, 50, 100, 200, 500]; ?>
        <br>
        <br>
        <select class="form-control form-control-sm" id="pagination-size">
            <option value="0"><?php echo __('Select Page Size'); ?></option>
            <?php foreach ($pageSizeList as $size): ?>
                <option <?php echo (!empty($pageSize) && $pageSize == $size ? 'selected' : ''); ?> value="<?php echo $size; ?>"><?php echo $size; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>