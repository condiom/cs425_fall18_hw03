<?php
  $maxQuestions = 5;
  if(isset($_SESSION["CurrentQuestion"]) && !isset($_POST["playagain"])){
    $_SESSION["CurrentQuestion"] = $_SESSION["CurrentQuestion"] + 1;
  }
  else {
    $_SESSION["CurrentQuestion"] = 1;
    $_SESSION["Score"] = 0;
    $_SESSION["currentDificulty"] = "M";
    $_SESSION["playedId"] = array();
    $_SESSION["correctOrNot"] = array();
    $_SESSION["difficulties"] = array();
  }


  if(isset($_POST["save"])){
      $score = $_SESSION["Score"];
      $nickname = $_POST["nickname"];
      $error = "";
      if(strcmp($nickname,"")==0){
        $error = "empty nickname";
      }
      else{
        file_put_contents('data/highScores.txt', "\n".$nickname." ".$score , FILE_APPEND | LOCK_EX);
      }
  }

?>

<div class="mainwidth <?php if($_SESSION["CurrentQuestion"]<=$maxQuestions){ echo "vertical-center";} ?>">

<?php
  if($_SESSION["CurrentQuestion"]<=$maxQuestions+1 and $_SESSION["CurrentQuestion"]>1){
    array_push($_SESSION["difficulties"],trim($_SESSION["previousDificulty"]));
    if(trim($_POST["answer"])==trim($_SESSION["previousCorrect"])){
      array_push($_SESSION["correctOrNot"],'1');
      if($_SESSION["previousDificulty"]=="E"){
        $_SESSION["Score"] = $_SESSION["Score"] + 5;
        $_SESSION["currentDificulty"] = "M";
      }
      else if($_SESSION["previousDificulty"]=="M"){
        $_SESSION["Score"] = $_SESSION["Score"] + 10;
        $_SESSION["currentDificulty"] = "H";
      }
      else {
        $_SESSION["Score"] = $_SESSION["Score"] + 20;
      }
    }
    else{
      array_push($_SESSION["correctOrNot"],'0');
      if(trim($_SESSION["previousDificulty"])=="H"){
        $_SESSION["currentDificulty"] = "M";
      }
      else if(trim($_SESSION["previousDificulty"])=="M"){
        $_SESSION["currentDificulty"] = "E";
      }
    }
  }

  if($_SESSION["CurrentQuestion"]<=$maxQuestions){
    $questions = simplexml_load_file ("data/question.xml");

    if($_SESSION["currentDificulty"]=="E"){
      $questionIndex = rand(0,24);
      while (in_array($questionIndex,$_SESSION["playedId"])) {
        $questionIndex = rand(0,24);
      }
    }
    else if($_SESSION["currentDificulty"]=="M"){
      $questionIndex = rand(25,49);
      while (in_array($questionIndex,$_SESSION["playedId"])) {
        $questionIndex = rand(25,49);
      }
    }
    else{
      $questionIndex = rand(50,74);
      while (in_array($questionIndex,$_SESSION["playedId"])) {
        $questionIndex = rand(50,74);
      }
    }
    array_push($_SESSION["playedId"],$questionIndex);
    $_SESSION["previousDificulty"] = $_SESSION["currentDificulty"];
    $_SESSION["previousCorrect"] = (string)$questions->question[$questionIndex]->correct;

?>
<div class="gameTop">
  <label class"counter"><?=$_SESSION["CurrentQuestion"]."/".$maxQuestions?></label>
  <label class="close"><a href="index.php">close</a></label>
</div>
<h3 class="question"> <?=$questions->question[$questionIndex]->q?> </h3>
<form action="" method="post" class="gameForm">
  <div class="row">
    <div class="answerDiv">
      <input id="answerA" name="answer" class="answerBtn" type="radio" value="A" checked/>
      <label class="answerLabel" for="answerA"><?=$questions->question[$questionIndex]->a?></label>
    </div>
    <div class="answerDiv">
      <input id="answerB" name="answer" class="answerBtn" type="radio" value="B"/>
      <label class="answerLabel" for="answerB"><?=$questions->question[$questionIndex]->b?></label>
    </div>
  </div>
  <div class="row">
    <div class="answerDiv">
      <input id="answerC" name="answer" class="answerBtn" type="radio" value="C"/>
      <label class="answerLabel" for="answerC"><?=$questions->question[$questionIndex]->c?></label>
    </div>
    <div class="answerDiv">
      <input id="answerD" name="answer" class="answerBtn" type="radio" value="D"/>
      <label class="answerLabel" for="answerD"><?=$questions->question[$questionIndex]->d?></label>
    </div>
    <input type="hidden" name="status" value="play">
  </div>
  <?php if($_SESSION["CurrentQuestion"]==$maxQuestions){ ?>
    <button type="submit" class="btn btn-success nextBtn">FINISH</button>
  <?php }else{ ?>
    <button type="submit" class="btn btn-success nextBtn">NEXT</button>
  <?php } ?>

</form>

<?php
  }else{
?>
<h3 id="highscore">Questions</h3>
<div class="highscoreContainer">
  <table class="table">
    <thead>
      <td>No.</td>
      <td>Correct</td>
      <td>Difficulty(points)</td>
    </thead>
<?php
    $j = 0;
    while ($j < $maxQuestions) {
?>
    <div>
      <tr class="<?php if($_SESSION["correctOrNot"][$j]==1) {echo "correctAns";}else {echo "falseAns";}?>">
        <td><?=($j+1)?></td>
        <td>
          <?php if($_SESSION["correctOrNot"][$j]==1) {
              echo "Yes";
            }
            else {
              echo "No";
            }
          ?>
          </td>
        <td>
          <?php if($_SESSION["difficulties"][$j]=='E') {
              echo "Easy";
              if($_SESSION["correctOrNot"][$j]==1) {
                  echo "(+5)";
                }
                else {
                  echo "(+0)";
                }
            }
            else if ($_SESSION["difficulties"][$j]=='M'){
              echo "Medium";
              if($_SESSION["correctOrNot"][$j]==1) {
                  echo "(+10)";
                }
                else {
                  echo "(+0)";
                }
            }
            else{
              echo "Hard";
              if($_SESSION["correctOrNot"][$j]==1) {
                  echo "(+20)";
                }
                else {
                  echo "(+0)";
                }
            }
          ?>
        </td>
      </tr>
    </div>
    <?php
      $j = $j + 1;
    }

?>

</table>
</div>
<form action="" method="post" class="saveForm">
    <div class="row col-12">
      <label class="col-6"> Score: </label>
      <label id="score" class="col-6"> <?=$_SESSION["Score"]?> </label>
    </div>
      <div class="row col-12">
        <?php if(!isset($_POST["save"])){ ?>
          <label class="col-6 nickname">Nicname: </label>
          <input type="text" name="nickname" class="col-6"/>
          </div>
        <?php } else if(!(isset($error) && strcmp($error,"empty nickname")==0)){ ?>
            <label class="col-12">Your score is saved</label>
            <meta http-equiv="refresh" content="2;url=index.php" />
            </div>
        <?php } else{ ?>
            <label class="col-6 nickname">Nicname: </label>
            <input type="text" name="nickname" class="col-6"/>
            </div>
            <div class="row col-12 PlaySaveButtons">
              <label class="nickname col-12" style="text-align:center">Nickname can not be empty</label>
            </div>
        <?php } ?>


  <div class="row col-12 PlaySaveButtons">
    <button type="submit" id="playAgain" name="playagain" class="btn btn-success   <?php if(!isset($_POST["save"]) || (isset($error) && strcmp($error,"empty nickname")==0)){ ?> col-sm-12 col-md-6<?php } else {?> col-12 <?php } ?>">PLAY AGAIN</button>
    <?php if(!isset($_POST["save"]) || (isset($error) && strcmp($error,"empty nickname")==0)){ ?>
      <button type="submit" id="saveButton" name="save" class="btn btn-success col-sm-12 col-md-6">SAVE</button>
    <?php } ?>
  </div>
</form>
<?php
  }
?>
</div>
