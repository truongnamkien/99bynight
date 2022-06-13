<?php use App\Utility\Utils; ?>
<?php $this->loadHelper('Form'); ?>
<?php echo $this->element('breadcrumb'); ?>

<?php echo $this->Form->create(null, [
    'id' => 'blog-list-form',
    'class' => 'list-form-ajax',
    'rel' => 'async',
    'action' => 'loadBlogs',
    'method' => 'POST',
    'accept-charset' => 'utf-8',
    'enctype' => 'multipart/form-data',
]); ?>
<input type="hidden" class="categoryId" name="categoryId" value="<?php echo !empty($currentCategory) ? $currentCategory->id : false; ?>" />
<input type="hidden" class="page" name="page" value="1" />
<?php echo $this->Form->end(); ?>
<div class="section page-content-first">
    <div class="container">
        <div class="text-center mb-2  mb-md-3 mb-lg-4">
            <h1><?php echo (!empty($currentCategory) ? $currentCategory->getTitle() : __('Blogs')); ?></h1>
            <div class="h-decor"></div>
        </div>
    </div>
    <div class="container">
        <div id="blog-list"></div>
        <div class="clearfix"></div>
        <ul class="pagination justify-content-center navigation-ajax" data-form="blog-list-form" id="blog-pagination"></ul>
    </div>
</div>
