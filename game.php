<?php
  $maxQuestions = 2;
  if(isset($_SESSION["CurrentQuestion"]) && !isset($_POST["playagain"])){
    $_SESSION["CurrentQuestion"] = $_SESSION["CurrentQuestion"] + 1;
  }
  else {
    $_SESSION["CurrentQuestion"] = 1;
    $_SESSION["Score"] = 0;
  }


  if(isset($_POST["save"])){
      $score = $_SESSION["Score"];
      $nickname = $_POST["nickname"];
      file_put_contents('data/highScores.txt', "\n".$nickname." ".$score , FILE_APPEND | LOCK_EX);
  }




?>

<div class="mainwidth">

<?php
  if($_SESSION["CurrentQuestion"]<=$maxQuestions){
?>
<div class="gameTop">
  <label class"counter"><?=$_SESSION["CurrentQuestion"]."/".$maxQuestions?></label>
  <label class="close"><a href="index.php">close</a></label>
</div>
<h3 class="question"> QUESTION</h3>
<form action="" method="post" class="gameForm">
  <div class="row">
    <div class="answerDiv">
      <input id="answerA" name="answer" class="answerBtn" type="radio" value="A" checked/>
      <label class="answerLabel" for="answerA">answer A</label>
    </div>
    <div class="answerDiv">
      <input id="answerB" name="answer" class="answerBtn" type="radio" value="B"/>
      <label class="answerLabel" for="answerB">answer B</label>
    </div>
  </div>
  <div class="row">
    <div class="answerDiv">
      <input id="answerC" name="answer" class="answerBtn" type="radio" value="C"/>
      <label class="answerLabel" for="answerC">answer C</label>
    </div>
    <div class="answerDiv">
      <input id="answerD" name="answer" class="answerBtn" type="radio" value="D"/>
      <label class="answerLabel" for="answerD">answer D</label>
    </div>
  </div>
  <button type="submit" class="btn btn-success">NEXT</button>
</form>

<?php
  }else{
?>
<div class="vertical-center">
<form action="" method="post" class="saveForm">
    <div class="row col-12">
      <label class="col-6"> Score: </label>
      <label id="score" class="col-6"> <?=$_SESSION["Score"]?> </label>
    </div>
      <div class="row col-12">
        <?php if(!isset($_POST["save"])){ ?>
          <label class="col-6 nickname">Nick Name: </label>
          <input type="text" name="nickname" class="col-6"/>
        <?php } else { ?>
            <label class="col-12">Your score saved</label>
        <?php } ?>
      </div>

  <div class="row col-12 PlaySaveButtons">
    <button type="submit" id="playAgain" name="playagain" class="btn btn-success   <?php if(!isset($_POST["save"])){ ?> col-sm-12 col-md-6<?php } else {?> col-12 <?php } ?>">PLAY AGAIN</button>
    <?php if(!isset($_POST["save"])){ ?>
      <button type="submit" id="saveButton" name="save" class="btn btn-success col-sm-12 col-md-6">SAVE</button>
    <?php } ?>
  </div>
</form>
</div>
<?php
  }
?>
</div>
