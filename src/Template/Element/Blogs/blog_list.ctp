<?php if (empty($blogList)): ?>
    <p class="text-center"><?php echo __('The list is empty.'); ?></p>
<?php endif; ?>
<div class="blog-isotope">
    <?php foreach ($blogList as $index => $blog): ?>
        <div class="blog-post <?php echo ($index % 2 == 0 ? '' : 'bg-grey'); ?>">
            <?php echo $this->element('Blogs/blog_list_item', ['blog' => $blog]); ?>
        </div>
    <?php endforeach; ?>
</div>
