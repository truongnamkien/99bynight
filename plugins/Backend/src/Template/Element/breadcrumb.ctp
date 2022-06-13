<?php use Cake\Core\Configure; ?>
<?php use Cake\Routing\Router; ?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $headerTitle; ?></h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item">
                                <a  class="fake" href="#"><i class="fas fa-home"></i></a>
                            </li>
                            <?php foreach ($breadcrumb as $index => $breadItem): ?>
                                <li class="breadcrumb-item <?php echo ($index == count($breadcrumb) - 1 ? 'active' : ''); ?>">
                                    <a class="<?php echo $breadItem['class']; ?> <?php echo ($index == count($breadcrumb) - 1 ? 'fake' : ''); ?>" href="<?php echo $breadItem['href']; ?>"><?php echo $breadItem['title']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php if (!empty($searchingFields) || !empty($filterFields)): ?>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-list-filter">
                            <i class="fas fa-search"></i>
                            <span class="d-xl-inline d-none">
                                <?php echo __('Filter'); ?>
                            </span>
                        </button>
                    <?php endif; ?>
                    <?php if (!empty($mainNav)): ?>
                        <?php foreach ($mainNav as $navInfo): ?>
                            <a href="<?php echo $navInfo['url']; ?>" class="btn btn-<?php echo $navInfo['button']; ?>">
                                <i class="fas fa-<?php echo $navInfo['icon']; ?>"></i>
                                <span class="d-xl-inline d-none">
                                    <?php echo __($navInfo['label']); ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-12 col-12 mt-4">
                    <?= $this->Flash->render(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
