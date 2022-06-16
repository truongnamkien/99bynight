<?php use Cake\Core\Configure; ?>
<?php use App\Utility\Utils; ?>
<?php $this->loadHelper('Link'); ?>
<?php $this->loadHelper('Content'); ?>
<footer id="footer">
    <div id="footer-top">
        <div class="container">
            <!-- Social icons -->
            <ul class="social-footer">
                <?php if (!empty($siteFacebook)): ?>
                    <li>
                        <a href="<?php echo $siteFacebook; ?>" target="blank" class="btn-default btn-wapasha" data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
            <p class="footer-quote">
                <?php echo PAGE_TITLE; ?>
                <?php if(!empty($siteDescription)): ?>
                    <br>
                    <?php echo $siteDescription; ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div id="footer-bottom">
        <div class="container">
            <p class="footer-bottom-text1">
                <?php if (!empty($siteAddress)): ?>
                    <?php echo $siteAddress; ?>
                    <span class="padding">·</span>
                <?php endif; ?>
                <?php if (!empty($sitePhone)): ?>
                    <?php echo $sitePhone; ?>
                    <span class="padding">·</span>
                <?php endif; ?>
                <?php if (!empty($siteEmail)): ?>
                    <a href="mailto:<?php echo $siteEmail; ?>" data-toggle="tooltip" data-placement="top"><?php echo $siteEmail; ?></a>
                <?php endif; ?>
            </p>
            <p class="footer-bottom-text2"><span><?php echo sprintf(__('Copyright © %s %s.'), date('Y'), PAGE_TITLE); ?></span></p>
        </div>
    </div>
</footer>
