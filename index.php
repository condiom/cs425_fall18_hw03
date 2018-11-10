<?php
  include("header.php");
?>



<?php
  if($_SERVER['REQUEST_METHOD']=="GET"){
    session_start();
    session_destroy();
    include("start.php");
  }
  else {
    session_start();
  }
  if($_SERVER['REQUEST_METHOD']=="POST"){
    include("game.php");
  }
?>


<?php
  include("footer.php");
?>