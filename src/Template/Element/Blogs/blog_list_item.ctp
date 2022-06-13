<?php use App\Utility\Utils; ?>
<?php $this->loadHelper('Link'); ?>
<?php if (!empty($blog)): ?>
    <div class="post-image">
        <a href="<?php echo $this->Link->blogDetailUrl($blog); ?>">
            <?php echo $this->Html->image('/' . $blog->thumbnail->path); ?>
        </a>
    </div>
    <div class="blog-post-info">
        <div class="post-date"><?php echo $blog->published_date->i18nFormat('dd'); ?><span><?php echo $blog->published_date->i18nFormat('MM/yyyy'); ?></span></div>
        <div>
            <h2 class="post-title">
                <a href="<?php echo $this->Link->blogDetailUrl($blog); ?>"><?php echo $blog->getTitle(); ?></a>
            </h2>
        </div>
    </div>
    <div class="post-teaser">
        <?php echo Utils::shortDescription($blog->getContent(), 140); ?>
    </div>
    <div class="mt-2">
        <a href="<?php echo $this->Link->blogDetailUrl($blog); ?>" class="btn btn-sm btn-hover-fill">
            <i class="icon-right-arrow"></i><span><?php echo __('Read more'); ?></span><i class="icon-right-arrow"></i>
        </a>
    </div>
<?php endif; ?>