function MediaCustom() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#addFile').click(function (e) {
        e.preventDefault();
        $('.uploadFile').toggle();
    });

    $('body').on('change', '#image-upload', function (e) {
        $('#upload-form').submit();
    });

    let idImage = null
    $('body').on('click', '.editImage', function (e) {
        e.preventDefault();
        $('.editImageSelected').removeClass('selectedImage');
        $(this).closest('.editImageSelected').addClass('selectedImage');
        let id = $(this).data('id');
        idImage = id
        $.ajax({
            url: adminPath + '/media/' + id + '/edit',
            method: 'GET',
            success: function (response) {
                $('.imageSelected').attr('src', response.src);
                $('#nameImage').val(response.file.name);
                $('#altImage').val(response.file.alt);
                $('#descImage').val(response.file.description);
                $('#dateUpload').text(response.file.created_at);
                $('#sizeImage').text((response.file.size / 1024 / 1024).toFixed(2) + ' ');
                $('#modelAttached').text(typeof response.modelAttached[0] != 'undefined' ? response.modelAttached[0].mediable_type : 'No model attached!');
                $('#btnDeleteImage').attr('data-id', response.file.id);
                $('#srcImage').val(response.src)
            },
            error: function (e) {
                console.log(e)
            }
        });
    });

    $('body').on('click', '.deleteImageSelect', function (e) {
        let id = $(this).data('id');
        $('.deletedImage').val(id);
    });

    $('body').on('click', '.deletedImage', function (e) {
        $.ajax({
            url: adminPath + '/media/delete',
            type: 'POST',
            data: {
                _method: 'DELETE',
                ids: [$('#btnDeleteImage').data('id')]
            },
            success: function (response) {
                location.reload();
            },
            error: function (e) {
                console.log(e)
            }
        });
    });

    $('body').on('keyup', '#search', function (e) {
        let value = $(this).val();
        let field = $(this).data('type');
        if (value.length > 1) {
            $.ajax({
                url: adminPath + '/media/search',
                data: {
                    mode: $('#mode').val(),
                    field: field,
                    value: value
                },
                success: function (response) {
                    handleResponse(response);
                },
                error: function (e) {
                    console.log(e)
                }
            });
        }
        if (value.length == 0) {
            $.ajax({
                url: adminPath + '/media',
                data: {
                    mode: $('#mode').val()
                },
                success: function (response) {
                    handleResponse(response);
                },
                error: function (e) {
                    console.log(e)
                }
            });
        }
    });

    $('body').on('change', '#search_by_day, #search_by_attach_model, #search_by_type', function (e) {
        let value = $(this).val();
        let field = $(this).data('type');
        let url = adminPath + '/media/search';
        if (value == 'all') {
            url = adminPath + '/media'
        }
        $.ajax({
            url: url,
            data: {
                mode: $('#mode').val(),
                value: value,
                field: field
            },
            success: function (response) {
                handleResponse(response);
            },
            error: function (e) {
                console.log(e)
            }
        });
    });

    $('body').on('change', '#sort_by', function (e) {
        let value = $(this).val();
        if (value != 0) {
            $.ajax({
                url: adminPath + '/media/sort',
                data: {
                    mode: $('#mode').val(),
                    value: value
                },
                success: function (response) {
                    handleResponse(response);
                },
                error: function (e) {
                    console.log(e)
                }
            });
        }
    });

    var url = location.href;
    $('body').on('click', '#modeList', function (e) {
        if (url.indexOf('mode=list') > -1) {
            e.preventDefault();
        }
    });
    $('body').on('click', '#modeGrid', function (e) {
        if (url.indexOf('mode=grid') > -1) {
            e.preventDefault();
        }
    });

    if (url.indexOf('mode=list') > -1) {
        $('#modeGrid').removeClass('selectedView');
        $('#modeList').addClass('selectedView');
    } else {
        $('#modeGrid').addClass('selectedView');
        $('#modeList').removeClass('selectedView');
    }

    if (url.indexOf('media') > 0) {
        $('#media').addClass('mm-active');
    }

    function handleResponse(response) {
        if (response.status == 'C200') {
            $('.viewImage').html(response.result);
        } else {
            $('.viewImage').html('<h3>Not found data!</h3>')
        }
    }

    $('body').on('focus', '#srcImage', function (e) {
        copySrc()
    })

    $('body').on('click', '#copySrc', function (e) {
        copySrc()
        swal({
            title: "Successully!",
            text: 'Copied image URL to clipboard',
            icon: "success",
            button: "OK",
        })
    })

    function copySrc() {
        var copyText = document.getElementById("srcImage");
        copyText.select();
        document.execCommand("copy");
        // alert("Copied the text: " + copyText.value);
    }

    $('body').on('click', '#btnUpdateImage', function () {
        $.ajax({
            url: adminPath + '/media/' + idImage,
            method: 'PUT',
            data: {
                name: $('#nameImage').val(),
                alt: $('#altImage').val(),
                description: $('#descImage').val(),
            },
            success: function (res) {
                swal({
                    title: "Successully!",
                    text: res.message,
                    icon: "success",
                    button: "OK",
                })
            },
            error: function (err) {
                swal({
                    title: "Opps!",
                    text: err?.responseJSON.message,
                    icon: "error",
                    button: "OK",
                })
                console.log({ err });
            }
        })
    });
}

$(document).ready(function () {
    new MediaCustom();
});
