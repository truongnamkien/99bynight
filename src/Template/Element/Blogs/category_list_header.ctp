<?php $this->loadHelper('Link'); ?>
<?php if (!empty($categoryList)): ?>
    <?php foreach ($categoryList as $category): ?>
        <li class="nav-item <?php echo (!empty($currentPage) && (strpos($currentPage, '-blogCategory-' . $category->id . '-') !== false) ? 'active' : ''); ?>">
            <a class="nav-link" href="<?php echo $this->Link->blogCategoryUrl($category); ?>"><?php echo $category->getTitle(); ?></a>
        </li>
    <?php endforeach; ?>
<?php endif; ?>