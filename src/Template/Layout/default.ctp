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
        <?= $this->Minify->css('html/vendor/slick/slick') ?>
        <?= $this->Minify->css('html/vendor/animate/animate') ?>
        <?= $this->Minify->css('html/icons/style') ?>
        <?= $this->Minify->css('html/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker') ?>
        <?= $this->Minify->css('html/css/style-color-3') ?>
        <?= $this->Minify->fetchCss() ?>
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
        <script type="text/javascript">
            var contextUrl = '<?php echo $this->Url->build('/', true); ?>';
            var siteTitle = '<?php echo PAGE_TITLE; ?>';
            var alertContent = '<?php echo (!empty($alertContent) ? $alertContent : ''); ?>';
        </script>
    </head>
    <body class="shop-page">
        <?php echo $this->Content->pageHeader((!empty($currentPage) ? $currentPage : false)); ?>
        <div class="page-content">
            <?php echo $this->Content->quickLinkPanel(); ?>
            <?= $this->fetch('content')?>
        </div>
        <?php echo $this->Content->pageFooter((!empty($currentPage) ? $currentPage : false)); ?>
        <?php echo $this->element('alert_modal'); ?>
        
        <?= $this->Minify->script('html/vendor/jquery/jquery-3.2.1') ?>
	<?= $this->Minify->script('html/vendor/jquery-migrate/jquery-migrate-3.0.1') ?>
	<?= $this->Minify->script('html/vendor/cookie/jquery.cookie') ?>
	<?= $this->Minify->script('html/vendor/bootstrap-datetimepicker/moment') ?>
	<?= $this->Minify->script('html/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker') ?>
	<?= $this->Minify->script('html/vendor/popper/popper') ?>
	<?= $this->Minify->script('html/vendor/bootstrap/bootstrap') ?>
	<?= $this->Minify->script('html/vendor/waypoints/jquery.waypoints') ?>
	<?= $this->Minify->script('html/vendor/waypoints/sticky') ?>
	<?= $this->Minify->script('html/vendor/imagesloaded/imagesloaded.pkgd') ?>
	<?= $this->Minify->script('html/vendor/slick/slick') ?>
	<?= $this->Minify->script('html/vendor/scroll-with-ease/jquery.scroll-with-ease') ?>
	<?= $this->Minify->script('html/vendor/countTo/jquery.countTo') ?>
	<?= $this->Minify->script('html/vendor/form-validation/jquery.form') ?>
	<?= $this->Minify->script('html/vendor/form-validation/jquery.validate') ?>
	<?= $this->Minify->script('html/vendor/isotope/isotope.pkgd') ?>
	<!-- Custom Scripts -->
	<?= $this->Minify->script('html/js/app') ?>
	<?= $this->Minify->script('html/js/app-shop') ?>
	<?= $this->Minify->script('html/form/forms') ?>
        <?= $this->Minify->script('js/async_request', true) ?>
        <?= $this->Minify->script('js/common', true) ?>
        <?= $this->Minify->fetchJs() ?>
        <?= $this->Minify->fetchBottomJs() ?>
    </body>
</html>