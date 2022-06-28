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
        <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lora:ital@1&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <?= $this->Minify->css('html/css/vendors') ?>
        <?= $this->Minify->css('html/css/style') ?>
        <?= $this->Minify->css('html/css/custom') ?>
        <?= $this->Minify->fetchCss() ?>
        <script type="text/javascript">
            var contextUrl = '<?php echo $this->Url->build('/', true); ?>';
            var siteTitle = '<?php echo PAGE_TITLE; ?>';
            var alertContent = '<?php echo (!empty($alertContent) ? $alertContent : ''); ?>';
        </script>
    </head>
    <body class="">
	<div id="preloader">
            <div data-loader="circle-side"></div>
	</div>
        <main>
            <?= $this->fetch('content')?>
        </main>
        <?php echo $this->Content->pageFooter((!empty($currentPage) ? $currentPage : false)); ?>
        <?php echo $this->element('alert_modal'); ?>

        <?= $this->Minify->script('html/js/common_scripts') ?>
        <?= $this->Minify->script('html/js/common_func') ?>
        <?= $this->Minify->script('js/async_request', true) ?>
        <?= $this->Minify->script('js/common', true) ?>
        <?= $this->Minify->fetchJs() ?>
        <?= $this->Minify->fetchBottomJs() ?>
    </body>
</html>