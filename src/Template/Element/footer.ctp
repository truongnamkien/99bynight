<?php use Cake\Core\Configure; ?>
<?php use App\Utility\Utils; ?>
<?php $this->loadHelper('Link'); ?>
<?php $this->loadHelper('Content'); ?>
<div class="footer">
    <div class="container">
        <div class="row py-1 py-md-2 px-lg-0">
            <div class="col-lg-4 footer-col1">
                <div class="row flex-column flex-md-row flex-lg-column">
                    <div class="col-md col-lg-auto text-center text-md-center">
                        <div class="footer-logo">
                            <?php echo $this->Html->image('logo.png', ['class' => 'img-fluid']); ?>
                        </div>
                        <p>
                            <?php echo PAGE_TITLE; ?>
                            <?php if(!empty($siteDescription)): ?>
                                <br>
                                <?php echo $siteDescription; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <p>To receive email releases, simply provide
                    <br>us with your email below</p>
                <form action="#" class="footer-subscribe">
                    <div class="input-group">
                        <input name="subscribe_mail" type="text" class="form-control" placeholder="Your Email" />
                        <span><i class="icon-black-envelope"></i></span>
                    </div>
                </form>
            </div>
            <div class="col-sm-6 col-lg-4">
                <h3><?php echo __('Contact'); ?></h3>
                <div class="h-decor"></div>
                <ul class="icn-list">
                    <?php if (!empty($siteAddress)): ?>
                        <li>
                            <i class="icon-placeholder2"></i>
                            <?php echo $siteAddress; ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($sitePhone)): ?>
                        <li>
                            <i class="icon-telephone"></i>
                            <b><span class="phone"><span class="text-nowrap"><?php echo $sitePhone; ?></span></span></b>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($siteEmail)): ?>
                        <li>
                            <i class="icon-black-envelope"></i>
                            <a href="mailto:<?php echo $siteEmail; ?>"><?php echo $siteEmail; ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="footer-social">
                    <?php if (!empty($siteFacebook)): ?>
                        <a href="<?php echo $siteFacebook; ?>" target="blank" class="hovicon"><i class="icon-facebook-logo"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row text-center text-md-center">
                <div class="col-sm">
                    <?php echo sprintf(__('Copyright © %s %s.'), date('Y'), PAGE_TITLE); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--//footer-->
<div class="backToTop js-backToTop">
    <i class="icon icon-up-arrow"></i>
</div>