$(document).ready(function () {
    "use strict";

    var token = $('meta[name="csrf-token"]').attr('content');

    function saveMenuItem() {
        var data = $('#menuNestable').nestable('asNestedSet');

        toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "toastClass": "animated fadeInDown"
        };

        $.ajax({
            url: window.adminPath + '/menu/menu-item/update-tree',
            type: 'PUT',
            data: {
                menu: data,
                _token: token
            },
            success: function (e) {
                if (e.success) {
                    toastr.success(e.message || 'Updated');
                } else {
                    toastr.error(e.message || 'Error');
                }
            },
            error: function (e) {
                toastr.error(e.responseJSON.message || e.statusText);
            }
        })
    }

    $('#menuNestable').nestable({
        json: menuItems,
        expandBtnHTML: '',
        collapseBtnHTML: '',
        contentCallback: function (item) {
            return item.label;
        },
        itemRenderer: function(item_attrs, content, children, options, item) {
            console.log(item, 'content: ' + content);
            var item_attrs_string = $.map(item_attrs, function(value, key) {
                return ' ' + key + '="' + value + '"';
            }).join(' ');

            var html = '<' + options.itemNodeName + item_attrs_string + '>';
            html += '<div class="dd-handle-group">';
            html += '<' + options.handleNodeName + ' class="' + options.handleClass + '">';
            html += '<' + options.contentNodeName + ' class="' + options.contentClass + '">';
            html += content;
            html += '</' + options.contentNodeName + '>';
            html += '</' + options.handleNodeName + '>';
            html += '<div class="dd-handle-tool">';
            html += `<a href="${adminPath}/menu/menu-item/create?menu_id=${window.menuId}&parent_id=${item.id}" class="add-item" title="Add Item"><i class="fas fa-plus fa-fw"></i></a>`;
            html += `<a href="${adminPath}/menu/menu-item/${item.id}/edit" class="edit-item" title="Edit Item"><i class="fas fa-pencil-alt fa-fw"></i></a>`;
            html += `<a href="${adminPath}/menu/menu-item/${item.id}" class="delete-item" title="Delete Item"><i class="fas fa-times fa-fw"></i></a>`;
            html += '</div>';
            html += '</div>';
            html += children;
            html += '</' + options.itemNodeName + '>';

            return html;
        }
    }).on('change', function () {
        saveMenuItem();
    }).on('click', '.delete-item', function (e) {
        e.preventDefault();

        let urlDelete = $(this).attr('href');

        swal({
            title: 'Cảnh báo',
            text: 'Bạn có chắc chắn muốn xóa mục này?',
            type: "warning",
            showCancelButton: true,
            cancelButtonText: 'Hủy',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Xóa'
        }, () => {
            $.ajax({
                url: urlDelete,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: token,
                },
                success: function (r) {
                    if (r.success) {
                        $('#menuNestable').nestable('remove', r.id);
                    } else {
                        swal({
                            title: 'Error',
                            text: e.message || 'Error',
                            type: "error"
                        });
                    }
                },
                error: function (e) {
                    swal({
                        title: 'Error',
                        text: e.responseJSON.message || e.statusText,
                        type: "error"
                    });
                },
            });
        });
    });
});
