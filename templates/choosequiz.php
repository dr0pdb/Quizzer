<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <?php if((isset($current_quizzes) && count($current_quizzes) != 0) || (isset($future_quizzes) && count($future_quizzes) != 0)) { ?>
        <?php if(isset($current_quizzes) && count($current_quizzes) != 0) { ?>
        <?php } ?>
        <?php if(isset($future_quizzes) && count($future_quizzes) != 0) { ?>

        <?php } ?>
    <?php } else { ?>
        <div class="panel panel-default" style="background-color: #ff333b">
            <div class="panel-body text-center">
                <h3 class="lead" style="color: #ffccc6">No quizzes have been scheduled!</h3>
            </div>
        </div>
    <?php } ?>

<?php } ?>