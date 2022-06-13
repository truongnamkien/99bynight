<?php $this->loadHelper('Link'); ?>
<?php foreach ($pageList as $pageInfo): ?>
    <li class="nav-item <?php echo (!empty($currentPage) && (strpos($currentPage, '-pageDetail-' . $pageInfo->id . '-') !== false) ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo $this->Link->pageDetailUrl($pageInfo); ?>"><?php echo $pageInfo->getTitle(); ?></a>
    </li>
<?php endforeach; ?>