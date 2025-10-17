function MediaSetting() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#media_is_cloud').click(function () {
    if ($(this).is(':checked')) {
      $('#cloud-provider').css('display', 'block');
    } else {
      $('#cloud-provider').css('display', 'none');
    }
  });
}

$(document).ready(function () {
  new MediaSetting();

  if ($('#media_is_cloud').is(':checked')) {
    $('#cloud-provider').css('display', 'block');
  }
});
