<?php $this->loadHelper('Content'); ?>
<?php echo $this->element('breadcrumb'); ?>
<div class="section page-content-first">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 aside">
                <div class="blog-post blog-post-single">
                    <div class="blog-post-info">
                        <div>
                            <h2 class="post-title"><?php echo $page->getTitle(); ?></h2>
                            <?php echo $this->element('share_social'); ?>
                        </div>
                    </div>
                    <div class="post-text body-content">
                        <?php echo $page->getContent(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
