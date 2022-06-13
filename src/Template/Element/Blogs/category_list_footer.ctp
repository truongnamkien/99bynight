<?php $this->loadHelper('Link'); ?>
<div class="widget widget_categories">
    <h3 class="widget-title">
        <?php echo __("Useful <span class='highlight regular'>News</span>"); ?>
    </h3>
    <ul class="menu fontsize_16">
        <?php foreach ($categoryList as $category): ?>
            <li class="<?php echo (!empty($currentPage) && (strpos($currentPage, '-blogCategory-' . $category->id . '-') !== false) ? 'active' : 'greylinks'); ?>">
                <a href="<?php echo $this->Link->blogCategoryUrl($category); ?>"><?php echo $category->getTitle(); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
