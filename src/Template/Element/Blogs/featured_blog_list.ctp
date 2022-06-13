<?php $this->loadHelper('Link'); ?>
<section id="blog" class="ls section_padding_top_100 section_padding_bottom_100">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="entry-title text-center">
                    <?php echo __('Latest Posts'); ?>
                </h3>
            </div>
            <?php foreach ($blogList as $index => $blog): ?>
                <div class="isotope-item col-lg-4 col-md-6 col-sm-12">
                    <?php echo $this->element('Blogs/blog_list_item', ['blog' => $blog]); ?>
                </div>
                <?php if ($index % 3 == 2): ?>
                    <div class="clearfix"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>


