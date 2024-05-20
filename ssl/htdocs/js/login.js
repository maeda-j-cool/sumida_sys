$(function() {
    const $modal = $('#modal');

    const showLoading = function(disp) {
        if (disp) {
            $('#modal-contents').hide();
            $('#modal-loading' ).show();
        } else {
            $('#modal-loading' ).hide();
            $('#modal-contents').show();
        }
    };

    const modalTypes = [
        'register',
        'inputmail',
        'resend',
        'inputcode',
        'login',
        'reissue',
        'error'
    ];

    const dlgContents = {};
    for (let i = 0; i < modalTypes.length; i++) {
        let dlgId = '#d-' + modalTypes[i];
        dlgContents[modalTypes[i]] = $(dlgId).html();
        $(dlgId).remove();
    }
    Object.freeze(dlgContents);

    let token = $('#token').val();

    const doPost = (postData) => {
        showLoading(true);
        $.ajax({
            type: 'POST',
            url: $('form#login-form').attr('action'),
            data: postData,
            dataType: 'json',
            cache: false
        }).done((data, textStatus, jqXHR) => {
            if ((data.errors === undefined) || (data.token === undefined)) {
                location.reload();
            } else {
                token = data.token;
                if (data.location !== undefined) {
                    location.href = data.location;
                } else if (data.errors.length) {
                    $errorBlock = $('.errors', $modal);
                    $errorBlock.html($('#error-message-area').html());
                    let $ul = $('.ul-error', $errorBlock);
                    for (let i = 0; i < data.errors.length; i++) {
                        $ul.append($('<li/>').html(data.errors[i].replace(/\n/g, '<br>')));
                    }
                    $errorBlock.show();
                    showLoading(false);
                } else {
                    showModal(data.op);
                    if (data.op  === 'error') {
                        $('.error-message', $modal).html(data.message).show();
                    } else if (data.op  === 'inputcode') {
                        $('.mailmessage', $modal).html(data.message).show();
                    }
                    showLoading(false);
                }
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            $modal.removeClass('active');
            console.log([jqXHR.status, textStatus, errorThrown].join(' '));
        });
    };

    const showModal = function(modalType) {
        if (modalTypes.indexOf(modalType) >= 0) {
            $('#modal-contents').html(dlgContents[modalType]);
            const toggleSubmit = () => {
                let invalid = true;
                $('input', $modal).each(function() {
                    if (!$(this).val().length) {
                        invalid = true;
                        return false;
                    }
                    invalid = false;
                });
                $('[type="submit"]', $modal).prop('disabled', invalid);
            };
            showLoading(false);
            $modal.unbind();
            setTimeout(toggleSubmit, 500);
            $modal.on('keyup click blur', 'input', toggleSubmit);
            if (modalType === 'inputcode') {
                $modal.on('click', '#act-resendmail', () => {
                    doPost({
                        'op': 'resendmail',
                        'token': token
                    });
                    return false;
                });
            }
            if (modalType === 'login') {
                $modal.on('click', '#act-reissue', function() {
                    if (showModal($(this).attr('id').replace(/^act-/, ''))) {
                        $modal.addClass('active');
                    }
                    return false;
                });
            }
            $modal.on('click', '.normal-link', (ev) => {
                ev.stopPropagation();
                return true;
            });
            $modal.on('click', '.modal-content', (ev) => {
                ev.stopPropagation();
                return false;
            });
            $modal.on('click', () => {
                $modal.removeClass('active');
                return false;
            });
            $modal.on('click', '[type="submit"]', () => {
                let data = {
                    'op': modalType,
                    'token': token
                };
                $('input,select', $modal).each(function() {
                    data[$(this).attr('name')] = $(this).val();
                });
                //console.log(data);
                doPost(data);
                return false;
            });
            return true;
        }
        $modal.removeClass('active');
        showLoading(true);
        return false;
    };

    $('.modal-close', $modal).on('click', () => {
        $modal.removeClass('active');
        return false;
    });

    $('.act--register,.act--login').click(function() {
        if (showModal($(this).hasClass('act--register') ? 'register' : 'login')) {
            $modal.addClass('active');
        }
        return false;
    });

    $('#act-preview').click(() => {
        location.href = '/sb3//type/trial/';
        return false;
    });

    const initAction = $('#init-action').val();
    if (((initAction === 'l') && showModal('login')) || ((initAction === 'r') && showModal('register'))) {
        if (initAction === 'r') {
            const qrGcno = $('#qr-gcno').val();
            if (qrGcno.length) {
                $('input[name="register_id"]').val(qrGcno);
                $('input[name="register_password"]').val($('#qr-pin').val());
                $('#qr-gcno,#qr-pin').val('');
                $('[type="submit"]', $modal).prop('disabled', false);
            }
        }
        $modal.addClass('active');
    }
});
