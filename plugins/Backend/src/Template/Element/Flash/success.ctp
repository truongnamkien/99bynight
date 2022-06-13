<?php use Cake\Core\Configure; ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
    <span class="alert-text"><?= h($message) ?></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
