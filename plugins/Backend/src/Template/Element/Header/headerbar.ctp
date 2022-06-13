<?php use Cake\Core\Configure; ?>
<nav class="navbar navbar-top navbar-expand navbar-dark bg-default border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center ml-auto ml-md-0 ">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <?= $this->Html->image('/argon/assets/img/theme/team-1.jpg'); ?>
                            </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm font-weight-bold"><?php echo $authUser->email; ?></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>
                        <a href="<?php echo $this->Url->build('backend/profile', true); ?>" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span><?php echo __('Profile'); ?></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo $this->Url->build('backend/logout', true); ?>" class="dropdown-item">
                            <i class="ni ni-button-power"></i>
                            <span><?php echo __('Logout'); ?></span>
                        </a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center ml-4">
                <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</nav>
