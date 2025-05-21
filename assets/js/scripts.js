function volumeToggle(button) {
  var muted = $(".previewVideo").prop("muted");
  $(".previewVideo").prop("muted", !muted);

  $(button).find("i").toggleClass("fa-volume-mute");
  $(button).find("i").toggleClass("fa-volume-up");
}

function previewEnded() {
  $('.previewVideo').toggle();
  $('.previewImage').toggle();
}
$('.previewVideo').on('ended', previewEnded);

function horizontalScroll(containers) {
  containers.forEach(container => {
    let isMouseDown = false;
    let startX, scrollLeft;
  
    // Mouse down event to start dragging
    container.addEventListener('mousedown', (e) => {
      isMouseDown = true;
      startX = e.pageX - container.offsetLeft; // Get the initial mouse position
      scrollLeft = container.scrollLeft; // Get the initial scroll position
      container.style.cursor = 'grabbing'; // Change cursor to grabbing
    });
  
    // Mouse leave event to reset dragging state
    container.addEventListener('mouseleave', () => {
      isMouseDown = false;
      container.style.cursor = 'grab'; // Reset cursor when mouse leaves
    });
  
    // Mouse up event to stop dragging
    container.addEventListener('mouseup', () => {
      isMouseDown = false;
      container.style.cursor = 'grab'; // Reset cursor when mouse is released
    });
  
    // Mouse move event to perform the scroll while dragging
    container.addEventListener('mousemove', (e) => {
      if (!isMouseDown) return; // Only execute when mouse is down
      e.preventDefault();
      const x = e.pageX - container.offsetLeft; // Get the mouse position
      const walk = (x - startX) * 2; // Calculate scroll distance
      container.scrollLeft = scrollLeft - walk; // Scroll the container
    });
  });
}

const entitiesContainers = document.querySelectorAll('.entities');
const videosContainers = document.querySelectorAll('.videos');
horizontalScroll(entitiesContainers);
horizontalScroll(videosContainers);


function goBack() {
  window.history.back();
}

$('.iconButton').on('click', goBack);

function startHideTimer() {
  let timeout = null;

  $(document).on("mousemove", function() {
    clearTimeout(timeout);

    $('.watchNav').fadeIn();

    timeout = setTimeout(function() {
      $('.watchNav').fadeOut();
    }, 2000);
  });
}

function initVideo() {
  startHideTimer();
}

function restartVideo() {
  $("video")[0].currentTime = 0;
  $("video")[0].play();
  $(".upNext").fadeOut();
}

function watchVideo(video) {
  window.location.href = `watch.php?id=${video}`;
}

function showUpNext() {
  $(".upNext").fadeIn();
}