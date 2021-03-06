<?php
  include("header.php");
?>
<meta name="title" content="Scores">
<title>Scores</title>

<div class="mainwidth">

  <h3 id="highscore">HighScore</h3>
  <div class="highscoreContainer">
    <table class="table">
      <thead>
        <td>Rank</td>
        <td>Nickname</td>
        <td>Score</td>
      </thead>
<?php

  function array_sort($array, $on, $order=SORT_ASC)
  {
      $new_array = array();
      $sortable_array = array();

      if (count($array) > 0) {
          foreach ($array as $k => $v) {
              if (is_array($v)) {
                  foreach ($v as $k2 => $v2) {
                      if ($k2 == $on) {
                          $sortable_array[$k] = (int)$v2;
                      }
                  }
              } else {
                  $sortable_array[$k] = (int)$v;
              }
          }

          switch ($order) {
              case SORT_ASC:
                  asort($sortable_array);
              break;
              case SORT_DESC:
                  arsort($sortable_array);
              break;
          }

          $counter = 0;
          foreach ($sortable_array as $k => $v) {
              $new_array[$counter] = $array[$k];
              $counter = $counter +1;
          }
      }

      return $new_array;
  }
  $fn = fopen("data/highScores.txt","r");
  $highscore = array();
  $counter = 0;
  while(! feof($fn))  {
    $result = fgets($fn);
    $result = explode(" ",$result);
    $highscore[$counter]["nickname"] =  $result[0];
    $highscore[$counter]["score"] =  $result[1];
    $counter =$counter +1;
  }
  fclose($fn);

  $highscore = array_sort($highscore, "score", SORT_DESC);

  $counter2 = 0;
  while($counter2 < 10 and $counter2 < $counter){
 ?>
    <?="<tr><td>".($counter2+1)."</td><td>".$highscore[$counter2]["nickname"]."</td><td>".$highscore[$counter2]["score"]."</td></tr>"?>
 <?php
  $counter2 = $counter2 + 1;
  }
 ?>
</table>
</div>
</div>

<?php
  include("footer.php");
?>