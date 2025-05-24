<?php

declare(strict_types=1);

use classes\{PreviewProvider, CategoryContainers};

require_once __DIR__ . '/includes/header.php';
?>

<div class="textboxContainer">
  <input type="text" class="searchInput" placeholder="Serach for something">
</div>

<div class="results"></div>
<script>
  $(function() {
    const username = '<?php echo userLoggedIn(); ?>';
    let timer;

    $(".searchInput").keyup(function() {
      clearTimeout(timer);

      timer = setTimeout(function() {
        const val = $(".searchInput").val();
        if (val != "") {
          $.post("ajax/getSearchResults.php", { term: val, username }, function(data) {
            $(".results").html(data);
          });
        } else {
          $(".results").html("");
        }
      }, 500);
    });
  });

</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>