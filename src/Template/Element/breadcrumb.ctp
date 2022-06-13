<?php use App\Utility\Utils; ?>
<?php if (!empty($breadcrumb)): ?>
    <div class="section mt-0">
        <div class="breadcrumbs-wrap">
            <div class="container">
                <div class="breadcrumbs">
                    <a href="<?php echo $this->Link->homeUrl(); ?>"><?php echo __('Home'); ?></a>
                    <?php $index = 0; ?>
                    <?php foreach ($breadcrumb as $item): ?>
                        <?php if ($index < count($breadcrumb) - 1): ?>
                            <a href="<?php echo $item['url']; ?>" class="<?php echo (!empty($item['class']) ? $item['class'] : ''); ?>" >
                                <?php echo $item['title']; ?>
                            </a>
                        <?php else: ?>
                            <span><?php echo $item['title']; ?></span>
                        <?php endif; ?>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
