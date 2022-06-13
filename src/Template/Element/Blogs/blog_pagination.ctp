<?php if ($totalPage > 1): ?>
    <li class="page-item active"><a class="page-link" href="#">1</a></li>
    <?php if ($page > 1): ?>
        <li class="page-item">
            <a class="page-link" href="#" data-page="<?php echo ($page - 1); ?>"><</a>
        </li>
    <?php endif; ?>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <?php if ($i <= $totalPage): ?>
            <li class="page-item <?php echo ($i == $page ? 'active' : ''); ?>">
                <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($page < $totalPage): ?>
        <li class="page-item">
            <a class="page-link" href="#" data-page="<?php echo ($page + 1); ?>">></a>
        </li>
    <?php endif; ?>
<?php endif; ?>