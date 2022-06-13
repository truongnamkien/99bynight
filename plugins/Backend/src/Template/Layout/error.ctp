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
        <?php $this->Minify->css('argon/assets/css/argon'); ?>
        <?php $this->Minify->css('backend/css/default'); ?>
        <?= $this->Minify->fetchCss(); ?>
    </head>
    <body class="bg-default">
        <div class="main-content">
            <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
                <div class="container">
                    <div class="header-body text-center mb-7 mt50">
                        <div class="row justify-content-center">
                            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                                <h1 class="text-white fs36">404!</h1>
                                <p class="text-lead text-white fs24"><?php echo __('Content not found!'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator separator-bottom separator-skew zindex-100">
                    <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                    </svg>
                </div>
            </div>
        </div>
        <footer class="py-5" id="footer-main">
            <div class="container">
                <div class="row align-items-center justify-content-xl-between">
                    <div class="col-xl-12">
                        <div class="copyright text-center text-muted">
                            <?php echo sprintf(__('Copyright © %s %s.'), date('Y'), '<a href="' . $this->Link->homeUrl() . '" class="font-weight-bold ml-1">' . PAGE_TITLE . '</a>'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <?php echo $this->element('modal_alert'); ?>
        <?= $this->Minify->script('argon/assets/vendor/jquery/dist/jquery'); ?>
        <?= $this->Minify->script('argon/assets/vendor/bootstrap/dist/js/bootstrap.bundle'); ?>
        <?= $this->Minify->script('argon/assets/vendor/js-cookie/js.cookie'); ?>
        <?= $this->Minify->script('argon/assets/vendor/jquery.scrollbar/jquery.scrollbar'); ?>
        <?= $this->Minify->script('argon/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock'); ?>
        <?= $this->Minify->script('argon/assets/js/argon'); ?>
        <?= $this->Minify->script('backend/js/async_request', true); ?>
        <?= $this->Minify->script('backend/js/common', true); ?>
        <?= $this->Minify->fetchJs(); ?>
        <?= $this->Minify->fetchBottomJs(); ?>
    </body>
</html>