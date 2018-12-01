<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <div class="panel panel-default">
    <div class="panel-body">
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
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
            <fieldset>
                <legend>Organize a Quiz</legend>
                <div class="form-group">
                    <label for="inputName" class="col-lg-2 control-label">Title</label>
                    <div class="col-lg-10">
                        <input type='text' class='form-control' name='name' value='<?=@$values["name"]?>' placeholder='<?=$defaults["name"]?>' >
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputStartDateTime" class="col-lg-2 control-label">Start Date & Time</label>
                    <div class="col-lg-10">
                        <?php echo "<input type='datetime-local' class='form-control' name='start_time' value='" . @$values["start_time"] . "' placeholder='" . $defaults["start_time"] . "' >" ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputDuration" class="col-lg-2 control-label">Duration(Minutes)</label>
                    <div class="col-lg-10">
                        <?php echo "<input type='number' class='form-control' name='duration_minutes' min='0' max='300' value='" . @$values["duration_minutes"] . "' placeholder='" . $defaults["duration_minutes"] . "' >" ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputStartDateTime" class="col-lg-2 control-label">Wrong Answer Penalty</label>
                    <div class="col-lg-10">
                        <?php echo "<input type='number' class='form-control' name='negative_mark' min='0' step='0.01' value='" . @$values["negative_mark"] . "' placeholder='" . $defaults["negative_mark"] . "' >" ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputDuration" class="col-lg-2 control-label">Questions(CSV)</label>
                    <div class="col-lg-5">
                        <input type="file" name="questions" id="questions" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Public Ranklist?</label>
                    <div class="col-lg-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="share_ranklist" id="share_ranklist" value="Y">
                                Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="share_ranklist" id="share_ranklist" value="N" checked="">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-1 col-lg-offset-2">
                        <button type="reset" class="btn btn-default">Cancel</button>
                    </div>
                    <div class="col-lg-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </fieldset>
        </form>
        <br>
    </div>
</div>
<?php } ?>