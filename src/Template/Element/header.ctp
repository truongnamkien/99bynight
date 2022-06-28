<?php use Cake\Core\Configure; ?>
<?php $this->loadHelper('Link'); ?>
<?php $this->loadHelper('Content'); ?>
<header class="header clearfix element_to_stick">
    <div class="layer"></div><!-- Opacity Mask Menu Mobile -->
    <div class="container-fluid">
        <div id="logo">
            <a href="<?php echo $this->Link->homeUrl(); ?>">
                <?php echo $this->Html->image('logo.png', ['class' => 'logo_normal']); ?>
                <?php echo $this->Html->image('logo.png', ['class' => 'logo_sticky']); ?>
            </a>
        </div>
        <a href="#0" class="open_close">
            <i class="icon_menu"></i><span>Menu</span>
        </a>
        <nav class="main-menu">
            <div id="header_menu">
                <a href="#0" class="open_close">
                    <i class="icon_close"></i><span>Menu</span>
                </a>
                <a href="<?php echo $this->Link->homeUrl(); ?>">
                    <?php echo $this->Html->image('logo.png'); ?>
                </a>
            </div>
        </nav>
    </div>
</header>
