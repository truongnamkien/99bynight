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
        <?= $this->Minify->css('html/css/bootstrap') ?>
        <?= $this->Minify->css('html/css/animations') ?>
        <?= $this->Minify->css('html/css/fonts') ?>
        <?= $this->Minify->css('html/css/main') ?>
        <?= $this->Minify->fetchCss() ?>
        <script type="text/javascript">
            var contextUrl = '<?php echo $this->Url->build('/', true); ?>';
            var siteTitle = '<?php echo PAGE_TITLE; ?>';
            var alertContent = '<?php echo (!empty($alertContent) ? $alertContent : ''); ?>';
        </script>
    </head>
    <body data-page="<?php echo (!empty($currentPage) ? $currentPage : false); ?>">
        <!--[if lt IE 9]>
            <div class="bg-danger text-center">
                You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" class="highlight">upgrade your browser</a> to improve your experience.
            </div>
        <![endif]-->
        <div class="preloader">
            <div class="preloader_image"></div>
        </div>
        <div id="canvas">
            <div id="box_wrapper">
                <section class="page_toplogo table_section ls">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4 text-center text-sm-left">&nbsp;</div>
                            <div class="col-sm-4 text-center">
                                <a href="<?php echo $this->Link->homeUrl(); ?>" class="logo top_logo">
                                    <?php echo $this->Html->image('logo_noname.png'); ?>
                                    <span class="logo_text">
                                        <strong class="playfair"><?php echo PAGE_TITLE; ?></strong>
                                    </span>
                                </a>
                            </div>

                            <div class="col-sm-4 text-center text-sm-right">&nbsp;</div>
                        </div>
                    </div>
                </section>
                <section class="ls section_404 background_cover section_padding_top_150 section_padding_bottom_150">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 text-center text-center">
                                <div class="inline-block text-center">
                                    <p class="not_found light">
                                        <span class="playfair">404</span>
                                    </p>
                                    <h2><?php echo __('Page Not Found!'); ?></h2>
                                    <p>
                                        <a href="<?php echo $this->Link->homeUrl(); ?>" class="theme_button color1"><?php echo __('Back to Home'); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?= $this->Minify->script('html/js/vendor/modernizr-2.6.2') ?>
        <?= $this->Minify->script('html/js/vendor/html5shiv') ?>
        <?= $this->Minify->script('html/js/vendor/respond') ?>
        <?= $this->Minify->script('html/js/vendor/jquery-1.12.4') ?>
        <?= $this->Minify->script('html/js/compressed') ?>
        <?= $this->Minify->script('html/js/main') ?>
        <?= $this->Minify->script('js/async_request', true) ?>
        <?= $this->Minify->script('js/common', true) ?>
        <?= $this->Minify->fetchJs() ?>
        <?= $this->Minify->fetchBottomJs() ?>
    </body>
</html>