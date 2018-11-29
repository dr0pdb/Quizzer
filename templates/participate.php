<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <div class="panel panel-default"><br>
        <div class="panel-body">
            <div class="row text-center">
               <h3> <?php echo $quiz['name']; ?></h3>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <form class="form-horizontal" method="post" action="">
                    <fieldset>
                        <div class="row">
                            <div class="col-lg-3">
                                <legend>Questions</legend>
                            </div>
                            <div class="col-lg-offset-9" >
                                <legend id="time_left"></legend>
                            </div>
                        </div>
                        <?php $index = 1; ?>
                        <?php foreach ($questions as $question) { ?>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-lg-2 control-label"><h6><?php echo "$index"; $index++; ?>.</h6></label>
                                    <div class="col-lg-10">
                                        <h5>
                                            <?= $question['statement'] ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-lg-offset-2">
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' value='A' >" ?>
                                            <?= $question['option_one'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' value='B' >" ?>
                                            <?= $question['option_two'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' value='C' >" ?>
                                            <?= $question['option_three'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' value='D' >" ?>
                                            <?= $question['option_four'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' value='E' checked='' >" ?>
                                            Do not attempt 
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <br>
                        <div class="form-group">
                            <div class="col-lg-3 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary" id="submit_button">Submit Answers</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <br>
    <?php
        if(isset($errors)) {
            foreach ($errors as $error) {
                echo "<div class=\"alert alert-dismissible alert-danger fade in\">\n" .
                "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" .
                "$error\n" .
                "</div>\n";
            }
        }

        if(isset($success) && strlen($success) > 0) {
            echo "<div class=\"alert alert-dismissible alert-success fade in\">\n" .
                "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" .
                "$success\n" .
            "</div>\n";
        }
    ?>
<?php } ?>
<script type="text/javascript">
    var countDownDate = new Date("<?php echo $end_time ?>").getTime();

    var x = setInterval(function() {

      var now = new Date().getTime();

      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      document.getElementById("time_left").innerHTML = "Time left: " + hours + "h " + minutes + "m " + seconds + "s ";

      // If the count down is finished.
      if (distance < 0) {
        document.getElementById("time_left").innerHTML = "Time up!";

        // Automatically submit the responses.
        document.getElementById("submit_button").submit();
      }
    }, 1000);
</script>
