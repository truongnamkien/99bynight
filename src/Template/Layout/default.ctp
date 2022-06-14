<?php use Cake\Core\Configure; ?>
<?php $this->loadHelper('Backend.Minify'); ?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="google" content="notranslate">
        <meta name="robots" content="index, follow">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="theme-color" content="#fff">
        <?php $pageTitle = PAGE_TITLE . (!empty($pageTitle) ? ' - '. $pageTitle : ''); ?>
        <?php if (empty($pageDesc)): ?>
            <?php $pageDesc = ''; ?>
        <?php endif; ?>
        <?php if (empty($pageImage)): ?>
            <?php $pageImage = $this->Url->build("/html/images/thumbnail.png", true); ?>
        <?php elseif (strpos($pageImage, $this->Url->build('/', true)) === false): ?>
            <?php $pageImage = $this->Url->build($pageImage, true); ?>
        <?php endif; ?>
        <?php $pageTitle = mb_convert_case($pageTitle, MB_CASE_TITLE); ?>
        <title><?php echo $pageTitle; ?></title>
        <meta name="description" content="<?php echo $pageDesc; ?>"/>
        <meta name="keywords" content="<?php echo (!empty($seoKeyword) ? $seoKeyword : ''); ?>" />
        <meta property="og:title" content="<?php echo $pageTitle; ?>" />
        <meta property="og:description" content="<?php echo $pageDesc; ?>" />
        <meta property="og:url" content="<?= $this->Url->build($this->request->getAttribute('here'), true); ?>" />
        <meta property="og:image" content="<?= $pageImage; ?>" />
        <meta property="og:type" content="website" />
        <?php $favicon = $this->Url->build(Configure::read('Logo.Favicon'), true); ?>
        <link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
        <link rel="shortcut icon" href="<?= $favicon; ?>" type="image/x-icon" />
        <?= $this->Minify->css('css/default') ?>

        <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <?= $this->Minify->css('html/vendor/bootstrap/css/bootstrap') ?>
        <?= $this->Minify->css('html/vendor/font-awesome/css/font-awesome') ?>
        <?= $this->Minify->css('html/vendor/animate') ?>
        <?= $this->Minify->css('html/vendor/nivo-lightbox/nivo-lightbox') ?>
        <?= $this->Minify->css('html/vendor/nivo-lightbox/themes/default/default') ?>
        <?= $this->Minify->css('html/css/styles') ?>
        <?= $this->Minify->fetchCss() ?>
        <script type="text/javascript">
            var contextUrl = '<?php echo $this->Url->build('/', true); ?>';
            var siteTitle = '<?php echo PAGE_TITLE; ?>';
            var alertContent = '<?php echo (!empty($alertContent) ? $alertContent : ''); ?>';
        </script>
    </head>
    <body class="">
        <div class="loader-container" id="page-loader">
            <div class="loading-wrapper loading-wrapper-hide">
                <div class="loader-animation" id="loader-animation">
                    <div class="sk-folding-cube">
                        <div class="sk-cube1 sk-cube"></div>
                        <div class="sk-cube2 sk-cube"></div>
                        <div class="sk-cube4 sk-cube"></div>
                        <div class="sk-cube3 sk-cube"></div>
                    </div>
                </div>
                <!-- Edit With Your Name -->
                <div class="loader-name" id="loader-name">
                    <?php echo $this->Html->image('logo.png'); ?>
                </div>
                <!-- /Edit With Your Name -->
            </div>
        </div>
        <div class="container">
            <?php echo $this->Content->pageHeader((!empty($currentPage) ? $currentPage : false)); ?>
            <section class="main-content">
                <?= $this->fetch('content')?>
            </section>
        </div>
        <?php echo $this->Content->pageFooter((!empty($currentPage) ? $currentPage : false)); ?>
        <?php echo $this->element('alert_modal'); ?>

        <?= $this->Minify->script('html/vendor/jquery') ?>
        <?= $this->Minify->script('html/vendor/bootstrap/js/bootstrap') ?>
        <?= $this->Minify->script('html/vendor/validate') ?>
        <?= $this->Minify->script('html/vendor/nivo-lightbox/nivo-lightbox') ?>
        <?= $this->Minify->script('html/vendor/jquery.nicescroll') ?>
        <?= $this->Minify->script('html/vendor/jquery.nicescroll.plus') ?>
        <?= $this->Minify->script('html/vendor/jquery.countdown') ?>
        <?= $this->Minify->script('html/vendor/imagesloaded.pkgd') ?>
        <?= $this->Minify->script('html/vendor/masonry.pkgd') ?>
        <?= $this->Minify->script('html/vendor/jquery.ba-bbq') ?>
        <?= $this->Minify->script('html/vendor/jquery.isotope2') ?>
        <?= $this->Minify->script('html/vendor/packery-mode.pkgd') ?>
        <?= $this->Minify->script('html/vendor/cross-browser') ?>
        <?= $this->Minify->script('html/vendor/doubletaptogo') ?>
        <?= $this->Minify->script('html/vendor/cross-browser') ?>
        <?= $this->Minify->script('html/js/main') ?>
        <?= $this->Minify->script('js/async_request', true) ?>
        <?= $this->Minify->script('js/common', true) ?>
        <?= $this->Minify->fetchJs() ?>
        <?= $this->Minify->fetchBottomJs() ?>
    </body>
</html>