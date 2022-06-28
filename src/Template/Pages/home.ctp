<?php $this->loadHelper('Content'); ?>
<div class="hero_single inner_pages background-image" data-background="url(<?php echo $this->Url->build('/html/img/hero_menu.jpg'); ?>">
    <div class="opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.6)">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10 col-md-8">
                    <?php echo $this->Html->image('logo.png'); ?>
                    <h1><?php echo __('Dine & Chill'); ?></h1>
                </div>
            </div>
            <!-- /row -->
        </div>
    </div>
    <div class="frame white"></div>
</div>
<div class="pattern_2">
    <div class="container margin_60_40" data-cue="slideInUp">
        <div class="tabs_menu add_bottom_25">
            <ul class="nav nav-tabs" role="tablist">
                <?php $index = 0; ?>
                <?php foreach ($menuTypeList as $menuValue => $menuOption): ?>
                    <?php foreach ($menuOption['categoriesList'] as $categoryInfo): ?>
                        <li class="nav-item">
                            <a id="menu-tab<?php echo $categoryInfo->id; ?>" href="#pane-<?php echo $categoryInfo->id; ?>" class="nav-link <?php echo ($index == 0 ? 'active' : ''); ?>" data-bs-toggle="tab" role="tab"><?php echo $categoryInfo->getTitle(); ?></a>
                        </li>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content add_bottom_25" role="tablist">
                <?php $index = 0; ?>
                <?php foreach ($menuTypeList as $menuValue => $menuOption): ?>
                    <?php foreach ($menuOption['categoriesList'] as $categoryInfo): ?>
                        <div id="pane-<?php echo $categoryInfo->id; ?>" class="card tab-pane fade <?php echo ($index == 0 ? 'show active' : ''); ?>" role="tabpanel" aria-labelledby="menu-tab<?php echo $categoryInfo->id; ?>">
                            <div class="card-header" role="tab" id="heading-<?php echo $categoryInfo->id; ?>">
                                <h5>
                                    <a class="collapsed" data-bs-toggle="collapse" href="#collapse-<?php echo $categoryInfo->id; ?>" aria-expanded="<?php echo ($index == 0 ? 'true' : 'false'); ?>" aria-controls="collapse-<?php echo $categoryInfo->id; ?>">
                                        <?php echo $categoryInfo->getTitle(); ?>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse-<?php echo $categoryInfo->id; ?>" class="collapse" role="tabpanel" aria-labelledby="heading-A">
                                <div class="card-body">
                                    <?php if (!empty($categoryInfo->productList)): ?>
                                        <div class="row add_bottom_25 magnific-gallery">
                                            <?php foreach ($categoryInfo->productList as $productInfo): ?>
                                                <div class="col-lg-6">
                                                    <div class="menu_item">
                                                        <figure>
                                                            <?php $thumbnail = $productInfo->getThumbnail(); ?>
                                                            <?php if (!empty($thumbnail)): ?>
                                                                <a href="<?php echo $this->Url->build('/' . $thumbnail, true); ?>" data-effect="mfp-zoom-in">
                                                                    <img src="<?php echo $this->Url->build('/' . $thumbnail, true); ?>" data-src="<?php echo $this->Url->build('/' . $thumbnail, true); ?>" class="lazy" alt="">
                                                                </a>
                                                            <?php else: ?>
                                                                <?php echo $this->Html->image('/html/img/fork.png'); ?>
                                                            <?php endif; ?>
                                                        </figure>
                                                        <div class="menu_title">
                                                            <h3><?php echo $productInfo->getTitle(); ?></h3>
                                                            <em><?php echo !empty($productInfo->price) ? $productInfo->price . 'K' : __('Thời giá'); ?></em>
                                                        </div>
                                                        <?php foreach ($languageList as $subCode => $subLabel): ?>
                                                            <p><?php echo $productInfo->getTitle($subCode, $subLabel); ?></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
