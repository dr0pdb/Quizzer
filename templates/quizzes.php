<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <legend class="white-text">My Quizzes</legend>
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
    <?php } else { ?>
        <div class="row">
            <?php foreach ($quizzes as $quiz) { ?>
                <?php if($isStudent != 0) { ?>
                    <?php if($quiz['share_ranklist'] == 'Y') { ?>
                        <a href="/analytics/<?=$quiz['_id']?>">
                    <?php ?>
                    <div class="col-md-4 text-center">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h3><?= $quiz['name'] ?></h3>
                                <h5>Score: <?= $quiz['score']?></h5> 
                            </div>
                        </div>
                    </div>
                    <?php if($quiz['share_ranklist'] == 'Y') { ?>
                        </a>
                    <?php ?>
                <?php } else if($isInstructor != 0) { ?>
                    <a href="/analytics/<?=$quiz['_id']?>">
                        <div class="col-md-4 text-center">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3><?= $quiz['name'] ?></h3>
                                    <h5 class="text" >Start: <?= $quiz['start_time'] ?> </h5>
                                    <h5>Duration: <?= $quiz['duration_minutes']?> minutes</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>

<?php } ?>