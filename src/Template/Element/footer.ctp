<?php use Cake\Core\Configure; ?>
<?php use App\Utility\Utils; ?>
<?php $this->loadHelper('Link'); ?>
<?php $this->loadHelper('Content'); ?>


<footer>
    <div class="frame black"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="footer_wp">
                    <i class="icon_globe-2"></i>
                    <h3><?php echo PAGE_TITLE; ?></h3>
                    <p><?php echo $siteDescription; ?></p>
                </div>
            </div>
            <?php if (!empty($siteAddress)): ?>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="footer_wp">
                        <i class="icon_pin_alt"></i>
                        <h3><?php echo __('Address'); ?></h3>
                        <p><?php echo $siteAddress; ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($sitePhone)): ?>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="footer_wp">
                        <i class="icon_phone"></i>
                        <h3><?php echo __('Phone'); ?></h3>
                        <p><?php echo $sitePhone; ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($siteEmail)): ?>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="footer_wp">
                        <i class="icon_mail"></i>
                        <h3><?php echo __('Email'); ?></h3>
                        <p><a href="mailto:<?php echo $siteEmail; ?>"><?php echo $siteEmail; ?></a></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-5">
                <p class="copy"><?php echo sprintf(__('Copyright © %s %s.'), date('Y'), PAGE_TITLE); ?></p>
            </div>
            <div class="col-sm-7">
                <div class="follow_us">
                    <ul>
                        <?php if (!empty($siteFacebook)): ?>
                            <li>
                                <a href="<?php echo $siteFacebook; ?>" target="blank">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="<?php echo $this->Url->build('/html/img/facebook_icon.svg', true); ?>" class="lazy">
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <p class="text-center"></p>
    </div>
</footer>
