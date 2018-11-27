<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-4">
                <h4>Hello, <?=$user['first_name']?> </h4>
            </div>
        </div>
    </div>
    <?php if(!isset($quizzes) || count($quizzes) == 0) { ?>
        <div class="panel panel-default" style="background-color: #ff333b">
            <div class="panel-body text-center">
                <?php if($isStudent != 0) { ?>
                    <h3 class="lead" style="color: #ffccc6">You have not participated in any quizzes!</h3>
                <?php } else if($isInstructor != 0) { ?>
                    <h3 class="lead" style="color: #ffccc6">You have not posted any quizzes!</h3>    
                <?php } ?>
            </div>
        </div>
    <?php } else {
        include_once('../templates/quiz_item.php');
    } ?>

<?php } ?>