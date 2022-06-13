<?php $this->loadHelper('Content'); ?>
<?php echo $this->element('breadcrumb'); ?>
<div class="section page-content-first">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 aside">
                <div class="blog-post blog-post-single">
                    <div class="blog-post-info">
                        <div class="post-date"><?php echo $blog->published_date->i18nFormat('dd'); ?><span><?php echo $blog->published_date->i18nFormat('MM/yyyy'); ?></span></div>
                        <div>
                            <h2 class="post-title"><?php echo $blog->getTitle(); ?></h2>
                            <?php echo $this->element('share_social'); ?>
                        </div>
                    </div>
                    <div class="post-image">
                        <?php echo $this->Html->image('/' . $blog->thumbnail->path); ?>
                    </div>
                    <div class="post-text body-content">
                        <?php echo $blog->getContent(); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 aside-left mt-5 mt-lg-0">
                <?php echo $this->Content->blogRelated($blog); ?>
            </div>
        </div>
    </div>
</div>
