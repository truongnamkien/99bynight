<?php use App\Utility\Utils; ?>
<div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="commonMessageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="rt-icon2-times-outline"></i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="commonMessageLabel"><?php echo __('Notice'); ?></h4>
            </div>
            <div class="modal-body" id="modalDesc"></div>
        </div>
    </div>
</div>