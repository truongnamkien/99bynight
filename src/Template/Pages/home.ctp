<?php use App\Model\Entity\Banner; ?>
<?php $this->loadHelper('Content'); ?>
<?php echo $this->Content->bannerSlider(Banner::BANNER_POSITION_HOME); ?>
<?php echo $this->Content->healthCarePackageComparision(); ?>