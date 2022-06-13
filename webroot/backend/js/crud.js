$(document).ready(function () {
    $('#ajax-list-form').submit();
    $("body").on('click', '.list-sorting', function (e) {
        e.preventDefault();
        var $field = $(this).data('field');
        $('#ajax-list-form #sortField').val($field);
        var $order = $(this).data('order');
        $order = $order == 'ASC' ? 'DESC' : 'ASC';
        $('a.list-sorting').data('order', 'ASC');
        $('a.list-sorting i.fas').removeClass('fa-angle-up');
        $('a.list-sorting i.fas').addClass('fa-angle-down');
        $(this).data('order', $order);
        $('#ajax-list-form #sortOrder').val($order);
        var $arrow = $(this).find('i.fas');
        $arrow.removeClass('fa-angle-up');
        $arrow.removeClass('fa-angle-down');
        $arrow.addClass($order == 'ASC' ? 'fa-angle-down' : 'fa-angle-up');
        $('#ajax-list-form').submit();
    });
    $("body").on('click', '.btn-danger', function (e) {
        var result = confirm('Are you sure?');
        if (!result) {
            e.preventDefault();
        }
        return result;
    });
    $("body").on('click', '.text-red', function (e) {
        var result = confirm('Are you sure?');
        if (!result) {
            e.preventDefault();
        }
        return result;
    });
    $("body").on('click', '#list-pagination .page-link', function (e) {
        e.preventDefault();
        var $page = $(this).data('page');
        $('#ajax-list-form #pageIndex').val($page);
        $('#ajax-list-form').submit();
    });
    $("body").on('change', '#pagination-size', function (e) {
        e.preventDefault();
        var $size = $(this).find('option:selected').val();
        $('#ajax-list-form #pageIndex').val(1);
        $('#ajax-list-form #pageSize').val($size);
        $('#ajax-list-form').submit();
    });
    $("body").on('change', '.filter-input', function (e) {
        e.preventDefault();
        $('#ajax-list-form #pageIndex').val(1);
    });
    $("body").on('submit', '#ajax-list-form', function (e) {
        $('#modal-list-filter').modal('hide');
    });
    $(".select2").select2();
    $(".select2-tag").select2({tags: true});
    $("body").on('click', 'a.thumbnail-link', function (e) {
        $('#modal-image').find('.modal-body img').attr('src', $(this).attr('href'));
        $('#modal-image').modal('show');
        e.preventDefault();
    });
    $('textarea.textarea-ckeditor').each(function () {
        CKEDITOR.replace($(this).attr('name'), {
            filebrowserImageBrowseUrl: $filebrowserImageBrowseUrl,
            filebrowserImageUploadUrl: $filebrowserImageUploadUrl,
            filebrowserBrowseUrl: $filebrowserBrowseUrl
        });
    });
    $(".crud-datepicker").datepicker({
        format: 'dd/mm/yyyy',
    });
//    $('.timepicker').datetimepicker({
//        format: 'dd/mm/yyyy hh:ii:ss',
//        showSecond: true,
//        timeFormat: 'hh:mm:ss'
//    });
    $("body").on('click', '.btn-upload', function (e) {
        e.preventDefault();
        var $input = $(this).data('upload');
        $('#' + $input).click();
    });
    $('.single-photo-uploader').each(function () {
        var $fieldId = $(this).data('queue');
        var $photoPanel = $fieldId + '_photo';
        var $uploader = $(this).attr('id');
        var $url = $(this).data('url');
        if ($photoPanel && $uploader && $url) {
            $.fileup({
                url: $url,
                inputID: $uploader,
                queueID: $photoPanel,
                onSuccess: function (response, file_number, file) {
                    response = JSON.parse(response);
                    if (response.message) {
                        showAlert(response.message);
                    }
                    if (response.data && response.data.photoUrl) {
                        $('#' + $fieldId).val(response.data.photoUrl);
                    }
                },
                onError: function (event, file, file_number) {
                    showAlert('Upload error!');
                }
            });
        }
    });
    $('.single-file-uploader').each(function () {
        var $fieldId = $(this).data('queue');
        var $filePanel = $fieldId + '_file';
        var $uploader = $(this).attr('id');
        var $url = $(this).data('url');
        if ($filePanel && $uploader && $url) {
            $.fileup({
                url: $url,
                inputID: $uploader,
                queueID: $filePanel,
                onSuccess: function (response, file_number, file) {
                    response = JSON.parse(response);
                    if (response.message) {
                        showAlert(response.message);
                    }
                    if (response.data && response.data.fileUrl) {
                        $('#' + $fieldId).val(response.data.fileUrl);
                    }
                },
                onError: function (event, file, file_number) {
                    console.log(event);
                    console.log(file);
                    console.log(file_number);
                    showAlert('Upload error!');
                }
            });
        }
    });

});
