<?php use App\Utility\Utils; ?>
<?php $this->loadHelper('Link'); ?>
<div class="side-block">
    <h3 class="side-block-title"><?php echo __('Latest Posts'); ?></h3>
    <?php foreach ($blogList as $blog): ?>
        <div class="blog-post post-preview">
            <div class="post-image">
                <a href="<?php echo $this->Link->blogDetailUrl($blog); ?>">
                    <?php echo $this->Html->image('/' . $blog->thumbnail->path); ?>
                </a>
            </div>
            <div>
                <h4 class="post-title">
                    <a href="<?php echo $this->Link->blogDetailUrl($blog); ?>">
                        <?php echo $blog->getTitle(); ?>
                    </a>
                </h4>
                <div class="post-meta">
                    <div class="post-meta-date text-nowrap"><i class="icon icon-clock3"></i><?php echo $blog->published_date->i18nFormat('dd/MM/yyyy'); ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
