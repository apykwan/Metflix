function volumeToggle() {
  const $video = $('.previewVideo'); 
  const muted = $video.prop('muted'); 

  $video.prop('muted', !muted);

  $('#volBtn').find("i").toggleClass("fa-volume-mute");
  $('#volBtn').find("i").toggleClass("fa-volume-up");
}

$('#volBtn').click(volumeToggle); 

function previewEnded() {
  $('.previewVideo').toggle();
  $('.previewImage').toggle();
}

$('.previewVideo').on('ended', previewEnded);
