<?php use Cake\Core\Configure; ?>
<?php $this->loadHelper('Link'); ?>
<?php $this->loadHelper('Content'); ?>
<header class="header">
    <div class="header-quickLinks js-header-quickLinks d-lg-none">
        <div class="quickLinks-top js-quickLinks-top"></div>
        <div class="js-quickLinks-wrap-m">
        </div>
    </div>
    <div class="header-topline d-none d-lg-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto d-flex align-items-center">
                    <?php if (!empty($sitePhone)): ?>
                        <div class="header-phone">
                            <i class="icon-telephone"></i>
                            <?php echo $sitePhone; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($siteAddress)): ?>
                        <div class="header-info">
                            <i class="icon-placeholder2"></i>
                            <?php echo $siteAddress; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($siteEmail)): ?>
                        <div class="header-info">
                            <i class="icon-black-envelope"></i>
                            <a href="mailto:<?php echo $siteEmail; ?>"><?php echo $siteEmail; ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-auto ml-auto d-flex align-items-center">
                    <span class="header-social">
                        <?php if (!empty($siteFacebook)): ?>
                            <a href="<?php echo $siteFacebook; ?>" target="blank" class="hovicon"><i class="icon-facebook-logo-circle"></i></a>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="header-content">
        <div class="container">
            <div class="row align-items-lg-center">
                <button class="navbar-toggler collapsed" data-toggle="collapse" data-target="#navbarNavDropdown">
                    <span class="icon-menu"></span>
                </button>
                <div class="col-lg-auto col-lg-2 d-flex align-items-lg-center">
                    <a href="<?php echo $this->Link->homeUrl(); ?>" class="header-logo">
                        <?php echo $this->Html->image('logo.png', ['class' => 'img-fluid']); ?>
                    </a>
                </div>
                <div class="col-lg ml-auto header-nav-wrap">
                    <div class="header-nav js-header-nav">
                        <nav class="navbar navbar-expand-lg btco-hover-menu">
                            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                                <ul class="navbar-nav">
                                    <?php echo $this->Content->pageListHeader($currentPage); ?>
                                    <?php echo $this->Content->healthcareListHeader($currentPage); ?>
                                    <?php echo $this->Content->specialistListHeader($currentPage); ?>
                                    <?php echo $this->Content->serviceListHeader($currentPage); ?>
                                    <?php echo $this->Content->blogListHeader($currentPage); ?>
                                    <li class="nav-item <?php echo (!empty($currentPage) && $currentPage == 'contact' ? 'active' : ''); ?>">
                                        <a class="nav-link" href="<?php echo $this->Link->contactUrl(); ?>"><?php echo __('Contact'); ?></a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
