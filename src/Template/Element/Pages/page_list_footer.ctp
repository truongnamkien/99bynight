<?php $this->loadHelper('Link'); ?>
<div class="widget widget_nav_menu">
    <h3 class="widget-title">
        <?php echo __("Useful <span class='highlight regular'>Info</span>"); ?>
    </h3>
    <div>
        <ul class="menu fontsize_16">
            <?php foreach ($categoryList as $category): ?>
                <li class="<?php echo (!empty($currentPage) && (strpos($currentPage, '-pageCategory-' . $category->id . '-') !== false) ? 'active' : 'greylinks'); ?>">
                    <a href="<?php echo $this->Link->pageCategoryUrl($category); ?>"><?php echo $category->getTitle(); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
