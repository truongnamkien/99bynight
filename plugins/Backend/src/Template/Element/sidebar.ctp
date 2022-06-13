<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="navbar-inner">
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <?php foreach ($sideBarList as $section): ?>
                    <?php if (!empty($section['subModules'])): ?>
                        <?php if (!empty($section['label'])): ?>
                            <h6 class="navbar-heading p-0 text-muted">
                                <span class="docs-normal"><?php echo __($section['label']); ?></span>
                            </h6>
                        <?php endif; ?>
                        <ul class="navbar-nav">
                            <?php foreach ($section['subModules'] as $subModule): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (!empty($currentController) && $currentController == $subModule['controller'] ? 'active' : ''); ?>" href="<?php echo Router::url(['controller' => $subModule['controller'], 'action' => 'index'], true); ?>">
                                    <i class="<?php echo $subModule['iconClass']; ?>"></i>
                                    <span class="nav-link-text"><?php echo __($subModule['label']); ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <hr class="my-3">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</nav>
