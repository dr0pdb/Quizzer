<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <div class="panel panel-default"><br>
        <div class="panel-body">
            <div class="row text-center">
               <h3> <?php echo $quiz['name']; ?></h3>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <form class="form-horizontal" method="post" action="" id="answer">
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
                                    <label class="col-lg-2 control-label"><h6><?php echo "$index"; ?>.</h6></label>
                                    <div class="col-lg-10">
                                        <h5>
                                            <?= $question['statement'] ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-lg-offset-2">
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' id='" . $index . "_A' value='A' >" ?>
                                            <?= $question['option_one'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' id='" . $index . "_B' value='B' >" ?>
                                            <?= $question['option_two'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' id='" . $index . "_C' value='C' >" ?>
                                            <?= $question['option_three'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' id='" . $index . "_D' value='D' >" ?>
                                            <?= $question['option_four'] ?> 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <?php echo "<input type='radio' name='" . $index . " ' id='" . $index . "_E' value='E' >" ?>
                                            Do not attempt 
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php $index++; } ?>
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
        // document.getElementById("submit_button").submit();
      }
    }, 1000);
</script>

<script>
$(document).ready(function(){
    var responses = <?php echo json_encode($responses); ?>;
    var _POST = new Object();
    _POST['auto'] = 'true';
    
    Object.keys(responses).forEach(function(key) {
        var value = responses[key];
        _POST[key] = responses[key];
        var id = "" + key;
        switch(value) {
            case 'A':
                id = id + "_A";
                break;
            case 'B':
                id = id + "_B";
                break;
            case 'C':
                id = id + "_C";
                break;
            case 'D':
                id = id + "_D";
                break;
            default:
                id = id + "_E";
        }

        document.getElementById(id).checked = true;
    });

    Object.keys(responses).forEach(function(key) {
        var radios = [];
        var radio_a = document.getElementById(key + "_A");
        var radio_b = document.getElementById(key + "_B");
        var radio_c = document.getElementById(key + "_C");
        var radio_d = document.getElementById(key + "_D");
        var radio_e = document.getElementById(key + "_E");
        radios.push(radio_a, radio_b, radio_c, radio_d, radio_a);

        function changeHandler(event) {
            _POST[key] = $(this).val();

            $.ajax({
                url: "",
                type: "post",
                data: _POST ,
                success: function (response) {
                   // nothing here.
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            });
        }

        Array.prototype.forEach.call(radios, function(radio) {
            radio.addEventListener('change', changeHandler);
        });
    });
});
</script>