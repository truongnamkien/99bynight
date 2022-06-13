<?php use Cake\Core\Configure; ?>
<?php $this->loadHelper('Link'); ?>
<div id="mainSliderWrapper">
    <div class="loading-content">
        <div class="inner-circles-loader"></div>
    </div>
    <div class="main-slider mb-0 arrows-white arrows-bottom" id="mainSlider" data-slick='{"arrows": false, "dots": true}'>
        <?php foreach ($bannerList as $bannerInfo): ?>
            <div class="slide">
                <div class="img--holder" data-bg="<?php echo $this->Url->build('/' . $bannerInfo->photo->path, true); ?>"></div>
                <div class="slide-content center">
                    <div class="vert-wrap container">
                        <div class="vert">
                            <div class="container">
                                <div class="slide-txt1" data-animation="fadeInDown" data-animation-delay="1s"><?php echo nl2br($bannerInfo->getTitle()); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
