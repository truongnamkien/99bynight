<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="google" content="notranslate">
        <meta name="robots" content="noindex" />
        <meta name="googlebot" content="noindex" />
        <meta name="googlebot-news" content="noindex" />
        <meta name="googlebot" content="noindex">
        <meta name="googlebot-news" content="nosnippet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <?php $pageTitle = PAGE_TITLE . (!empty($pageTitle) ? ' - '. $pageTitle : ''); ?>
        <title><?= $pageTitle; ?></title>
        
        <!-- Favicon -->
        <?php $favicon = $this->Url->build('/img/favicon.ico'); ?>
        <link rel="icon" href="<?= $favicon; ?>" type="image/x-icon" />
        <link rel="shortcut icon" href="<?= $favicon; ?>" type="image/x-icon" />

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <?php $this->Minify->css('argon/assets/vendor/nucleo/css/nucleo'); ?>
        <?php $this->Minify->css('argon/assets/vendor/@fortawesome/fontawesome-free/css/all'); ?>
        <?php $this->Minify->css('argon/assets/vendor/select2/dist/css/select2'); ?>
        <?php $this->Minify->css('argon/assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker'); ?>
        <?php $this->Minify->css('argon/assets/vendor/ajax-uploader/src/fileup'); ?>
        <?php $this->Minify->css('argon/assets/css/argon'); ?>
        <?php $this->Minify->css('css/default'); ?>
        <?= $this->Minify->fetchCss(); ?>
        <?= $this->Minify->script('argon/assets/vendor/jquery/dist/jquery'); ?>
        <?= $this->Minify->script('argon/assets/vendor/bootstrap/dist/js/bootstrap.bundle'); ?>
        <?= $this->Minify->script('argon/assets/vendor/js-cookie/js.cookie'); ?>
        <?= $this->Minify->script('argon/assets/vendor/jquery.scrollbar/jquery.scrollbar.min'); ?>
        <?= $this->Minify->script('argon/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min'); ?>
        <?= $this->Minify->script('argon/assets/vendor/clipboard/dist/clipboard'); ?>
        <?= $this->Minify->script('argon/assets/vendor/select2/dist/js/select2'); ?>
        <?= $this->Minify->script('argon/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker'); ?>
        <?= $this->Minify->script('argon/assets/vendor/ajax-uploader/src/fileup'); ?>
        <?= $this->Minify->script('backend/ckeditor/ckeditor'); ?>
        <?= $this->Minify->script('argon/assets/js/argon'); ?>
        <?= $this->Minify->script('backend/js/async_request', true); ?>
        <?= $this->Minify->script('backend/js/crud', true); ?>
        <?= $this->Minify->script('backend/js/common', true); ?>
        <?= $this->Minify->fetchJs(); ?>
        <script type="text/javascript">
            var alertContent = '<?php echo (!empty($alertContent) ? $alertContent : ''); ?>';
        </script>
    </head>
    <body>
        <?php echo $this->element('sidebar'); ?>
        <div class="main-content" id="panel">
            <?php echo $this->element('Header/headerbar'); ?>
            <?php echo $this->element('breadcrumb'); ?>
            <?= $this->fetch('content'); ?>
        </div>
        <footer class="footer pt-0">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-12">
                    <div class="copyright text-center text-muted">
                        <?php echo sprintf(__('Copyright © %s %s.'), date('Y'), '<a href="' . $this->Link->homeUrl() . '" class="font-weight-bold ml-1">' . PAGE_TITLE . '</a>'); ?>
                    </div>
                </div>
            </div>
        </footer>
        <?php echo $this->element('modal_alert'); ?>
        <?= $this->Minify->fetchBottomJs(); ?>
    </body>
</html>