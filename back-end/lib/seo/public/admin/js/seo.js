$(document).ready(function () {
    'use strict';

    $('[name="seourl[request_path]"]').on('change', function () {
        let slug = $(this).val();
        if (slug) {
            let url = ($('#front-end-url').val() ?? window.location.origin) + '/' + slug;
            let html = `<span class="helper-block seourl-preview"><a href="${url}" target="_blank">${url} <i class="fas fa-external-link-alt"></i></a></span>`;

            if ($(this).next('.seourl-preview').length) {
                $(this).next('.seourl-preview').html(html);
            } else {
                $(this).after($(html));
            }
        } else {
            $(this).next('.seourl-preview').remove();
        }
    }).trigger('change');
});
