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


const entitiesContainers = document.querySelectorAll('.entities');

entitiesContainers.forEach(entities => {
  let isMouseDown = false;
  let startX, scrollLeft;

  // Mouse down event to start dragging
  entities.addEventListener('mousedown', (e) => {
    isMouseDown = true;
    startX = e.pageX - entities.offsetLeft; // Get the initial mouse position
    scrollLeft = entities.scrollLeft; // Get the initial scroll position
    entities.style.cursor = 'grabbing'; // Change cursor to grabbing
  });

  // Mouse leave event to reset dragging state
  entities.addEventListener('mouseleave', () => {
    isMouseDown = false;
    entities.style.cursor = 'grab'; // Reset cursor when mouse leaves
  });

  // Mouse up event to stop dragging
  entities.addEventListener('mouseup', () => {
    isMouseDown = false;
    entities.style.cursor = 'grab'; // Reset cursor when mouse is released
  });

  // Mouse move event to perform the scroll while dragging
  entities.addEventListener('mousemove', (e) => {
    if (!isMouseDown) return; // Only execute when mouse is down
    e.preventDefault();
    const x = e.pageX - entities.offsetLeft; // Get the mouse position
    const walk = (x - startX) * 2; // Calculate scroll distance
    entities.scrollLeft = scrollLeft - walk; // Scroll the container
  });
});