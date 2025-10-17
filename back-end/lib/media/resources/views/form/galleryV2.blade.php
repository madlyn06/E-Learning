@push('styles')
    <style>
        .img-upload-popup {
            display: inline-block;
            width: 100%;
            height: 300px;
            border: 1px dashed #222f3e;
            background: #fafafa;
            text-align: center;
            vertical-align: middle;
            line-height: 300px;
            text-transform: uppercase;
            font-weight: 700;
            cursor: pointer;
            margin-top: 50px;
        }

        .editImageSelected.actived-img img {
            border: 4px solid deepskyblue;
        }
    </style>
@endpush
@if (isset($media_type) && $media_type == 'gallery')
    @include('media::form.gallery')
@else
    @include('media::form.file')
@endif

<div class="modal fade modal-media-file-{{ $name }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Quản lý file</h4>
                <span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkMultiple">
                        <label class="form-check-label" for="checkMultiple">
                            Chọn nhiều
                        </label>
                    </div>
                </span>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab-{{ $name }}" data-toggle="tab"
                            href="#nav-home-{{ $name }}" role="tab"
                            aria-controls="nav-home-{{ $name }}" aria-selected="true">
                            Library</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                            href="#nav-profile-{{ $name }}" role="tab"
                            aria-controls="nav-profile-{{ $name }}" aria-selected="false">
                            Upload file</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div style="height: 500px; overflow-y: auto; overflow-x: hidden" class="tab-pane fade show active"
                        id="nav-home-{{ $name }}" role="tabpanel"
                        aria-labelledby="nav-home-tab-{{ $name }}">
                        <div class="row js-height-popup-{{ $name }} js-modal-html-{{ $name }}"
                            style="height: 500px">
                        </div>

                    </div>
                    <div style="min-height: 500px" class="tab-pane fade" id="nav-profile-{{ $name }}"
                        role="tabpanel" aria-labelledby="nav-profile-tab">
                        <a class="img-upload-popup"
                            onclick="document.getElementById('image-upload-{{ $name }}').click()">
                            <span>Upload image</span>
                            <input type="file" id="image-upload-{{ $name }}" name="image-upload[]"
                                style="display: none">
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if (isset($media_type) && $media_type == 'gallery')
                    <button class="btn btn-success js-save-gallery-media-{{ $name }}"
                        type="button">Save</button>
                @else
                    <button class="btn btn-success js-save-img-media-{{ $name }}" type="button">Save</button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            var paginate = 1;
            let dataId = null;
            let dataImg = null;
            let dataName = null;
            let dataUrl = null;
            let dataMimetype = null;
            let findInput = null
            let findImg = null
            let checkClose = null;
            let isMultiple = false; // Biến trạng thái để theo dõi checkbox "Chọn nhiều"
            const arrayImageType = [
                'image/png',
                'image/jpeg',
                'image/gif',
                'image/webp',
                'image/x-icon',
                'image/svg+xml',
                'image/bmp'
            ];

            function reloadImg() {
                $.ajax({
                    url: '{{ route('media.admin.media.ajaxMedia') }}',
                    type: 'GET',
                    success: function (res) {
                        $('.js-modal-html-{{$name}}').html(res.result)
                        paginate = 1
                    },
                    error: function (err) {

                    }
                })
            }

            function appendImg(page) {
                $.ajax({
                    url: '{{ route('media.admin.media.ajaxMedia') }}',
                    type: 'GET',
                    data: {page: page},
                    success: function (res) {
                        $('.js-modal-html-{{$name}}').append(res.result)
                    },
                    error: function (err) {

                    }
                })
            }

            $('.js-click-modal-media-{{$name}}').click(function () {
                if (paginate == 1 && !$('.js-modal-html-{{$name}}').html()) {
                    $.ajax({
                        url: '{{ route('media.admin.media.ajaxMedia') }}',
                        type: 'GET',
                        success: function (res) {
                            $('.js-modal-html-{{$name}}').append(res.result)
                        },
                        error: function (err) {

                        }
                    })
                }
            })

            $('#image-upload-{{$name}}').change(function () {

                var image = $(this)[0].files[0]
                var formData = new FormData()
                formData.append('image-upload[]', image)

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route('media.admin.media.storeAjax') }}',
                    type: 'post',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $('#nav-home-tab-{{$name}}').trigger('click')
                        reloadImg()
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            })

            $('#nav-home-{{$name}}').scroll(function () {
                let heightfirst = $('#nav-home-{{$name}}').scrollTop() + $('#nav-home-{{$name}}').height()
                let heightSecond = $("#nav-home-{{$name}}").get(0).scrollHeight
                if (Math.round(heightfirst) == Math.round(heightSecond)) {
                    paginate = paginate + 1;
                    appendImg(paginate)
                }
                // appendImg(2)
            })

            // Theo dõi trạng thái của checkbox "Chọn nhiều"
            $('#checkMultiple').change(function () {
                isMultiple = $(this).is(':checked');
            });

            $(document).on('click', '.editImageSelected', function () {
                if (isMultiple) {
                    $(this).toggleClass('actived-img');
                } else {
                    let checkClassImg = $('.editImageSelected').hasClass('actived-img')
                    if (checkClassImg) {
                        for (let i = 0; i < $('.editImageSelected').length; i++) {
                            $($('.editImageSelected')[i]).removeClass('actived-img')
                        }
                        $(this).addClass('actived-img')
                    } else {
                        $(this).addClass('actived-img')
                    }
                }
                dataId = $(this).attr('data-id');
                dataImg = $(this).attr('data-src')
                dataUrl = $(this).attr('data-url')
                dataName = $(this).attr('data-name')
                dataMimetype = $(this).attr('data-filetype')
                findInput = $('.media-preview-{{$name}}').find('input')
                findImg = $('.media-preview-{{$name}}').find('img')
                checkClose = $('.media-preview-{{$name}}').hasClass('.remove-media')
            })

            $('.js-save-img-media-{{$name}}').click(function () {
                if (isMultiple) {
                    $('.editImageSelected.actived-img').each(function () {
                        let imgId = $(this).attr('data-id');
                        let imgSrc = $(this).attr('data-src');
                        let imgUrl = $(this).attr('data-url');
                        let imgName = $(this).attr('data-name');
                        let imgMimetype = $(this).attr('data-filetype');

                        if (!arrayImageType.includes(imgMimetype)) {
                            $('.media-preview-{{$name}}').append(`
                            <a href="${imgUrl}" target="_blank">${imgName}</a>
                            `)
                        } else {
                            $('.media-preview-{{$name}}').append(`
                                <img src="${imgSrc}" alt="Image" class="img-thumbnail">
                            `)
                        }
                        $('.media-preview-{{$name}}').append(`
                            <input type="hidden" name="{{ $name }}[]" value="${imgId}">
                        `)
                    });
                } else {
                    $(findInput).remove()
                    $(findImg).remove()
                    if (!arrayImageType.includes(dataMimetype)) {
                        $('.media-preview-{{$name}}').append(`
                        <a href="${dataUrl}" target="_blank">${dataName}</a>
                        `)
                    } else {
                        $('.media-preview-{{$name}}').append(`
                            <img src="${dataImg}" alt="Image" class="img-thumbnail">
                        `)
                    }
                    $('.media-preview-{{$name}}').append(`
                        <input type="hidden" name="{{ $name }}" value="${dataId}">
                    `)
                    if (!checkClose) {
                        $('.media-preview-{{$name}}').append(`
                        <a href="#" class="remove-media">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    `)
                    }
                }

                $('.modal-media-file-{{$name}}').modal('hide');
            })

            $('.js-save-gallery-media-{{$name}}').click(function () {
                $('.editImageSelected.actived-img').each(function () {
                    let imgId = $(this).attr('data-id');
                    let imgSrc = $(this).attr('data-src');
                    let imgUrl = $(this).attr('data-url');
                    let imgName = $(this).attr('data-name');
                    let imgMimetype = $(this).attr('data-filetype');

                    $('.gallery-list-{{$name}}').append(`
                    <div class="gallery-item ui-sortable-handle">
                            <img
                                src="${imgSrc}"
                                alt="Image">
                                <input type="hidden" name="{{ $name }}[]" value="${imgId}">
                                    <a href="#" title="Delete Image" class="remove-media">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                        </div>
                    `)
                });
                $('.modal-media-file-{{$name}}').modal('hide');
            })
        })
    </script>
@endpush
