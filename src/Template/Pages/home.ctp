<?php $this->loadHelper('Content'); ?>
<header id="header">
    <nav class="navbar">
        <div class="menu-wrapper">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo $this->Link->homeUrl(); ?>">
                    <?php echo $this->Html->image('logo.png'); ?>
                </a>
                <!-- /logo -->
            </div>
            <div class="collapse navbar-collapse navbar-main-collapse" id="#options">
                <ul class="nav navbar-nav">
                    <?php foreach ($menuTypeList as $menuType => $menuInfo): ?>
                        <li>
                            <a href="#menu-type-<?php echo $menuType; ?>"><?php echo $menuInfo['label']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<section class="main-content">
    <div class="isotope-filter" id="isotope-filter">
        <div class="element element-intro home menu-type-<?php echo implode(' menu-type-', array_keys($menuTypeList)); ?>" id="element-intro">
            <div class="element-wrapper">
                <!-- Intro- Element content -->
                <div class="intro-item" id="intro-item1" style="background-image: url('<?php echo $this->Url->build('/html/img/intro2.jpg', true); ?>');">
                    <div class="intro-item-mask">
                        <div class="intro-item-content">
                            <h2 class="intro-item-title"><strong><?php echo PAGE_TITLE; ?></strong></h2>
                            <div class="intro-item-bar"></div>
                            <p class="intro-item-title2"><?php echo __('Dine & Chill'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($categoryList as $index => $categoryInfo): ?>
            <?php if (!empty($categoryInfo->productList)): ?>
                <div class="element element-menu-list <?php echo $index == 0 ? 'home' : ''; ?> menu-type-<?php echo $categoryInfo->menu_type; ?>">
                    <div class="element-wrapper">
                        <h2 class="element-big-content-title"><strong><?php echo $categoryInfo->getTitle(); ?></strong></h2>
                        <ul class="restaurant-menu-list">
                            <?php foreach ($categoryInfo->productList as $productInfo): ?>
                                <li>
                                    <div class="restaurant-menu-title">
                                        <div class="restaurant-menu-name">
                                            <p style="margin-bottom: 0;"><?php echo ($productInfo->getTitle()); ?></p>
                                        </div>
                                        <div class="restaurant-menu-dots"></div>
                                        <div class="restaurant-menu-price">
                                            <?php echo !empty($productInfo->price) ? $productInfo->price . 'K' : __('Theo thời giá'); ?>
                                        </div>
                                    </div>
                                    <div class="restaurant-menu-description">
                                        <p><?php echo $productInfo->getDescription(); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>


