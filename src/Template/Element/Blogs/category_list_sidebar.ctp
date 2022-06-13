<?php $this->loadHelper('Link'); ?>
<div class="widget widget_archive">
    <h3 class="widget-title"><?php echo __('Blogs'); ?></h3>
    <ul>
        <?php foreach ($categoryList as $category): ?>
            <li class="<?php echo (!empty($currentPage) && (strpos($currentPage, '-blogCategory-' . $category->id . '-') !== false) ? '' : 'greylinks'); ?>">
                <a href="<?php echo $this->Link->blogCategoryUrl($category); ?>"><?php echo $category->getTitle(); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
