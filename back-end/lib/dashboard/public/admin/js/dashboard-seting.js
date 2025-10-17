$(document).ready(function () {
    "use strict";

    var token = $('meta[name="csrf-token"]').attr('content');

    function saveMyDashboard(e) {
        var data = $(e.target).nestable('serialize');

        console.log(data);

        toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "toastClass": "animated fadeInDown"
        };

        $.ajax({
            url: window.adminPath + '/dashboard/save',
            type: 'POST',
            data: {
                items: data,
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

    $('#myDashboard')
        .nestable({
            group: 1,
            json: Object.keys(myDashboard).length ? myDashboard : null,
            maxDepth: 1,
            contentCallback: function (item) {
                return item.name;
            },
        })
        .on('change', saveMyDashboard)
        .on('lostItem', saveMyDashboard)
        .on('gainedItem', saveMyDashboard);

    $('#allDashboard').nestable({
        group: 1,
        json: Object.keys(allDashboard).length ? allDashboard : null,
        maxDepth: 1,
        contentCallback: function (item) {
            return item.name;
        },
    });
});
