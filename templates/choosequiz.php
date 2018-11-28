<?php if($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <?php if((isset($current_quizzes) && count($current_quizzes) != 0) || (isset($future_quizzes) && count($future_quizzes) != 0)) { ?>
        <?php if(isset($current_quizzes) && count($current_quizzes) != 0) { ?>
            <div class="row">
                <h2 class="lead">Current quizzes</h2>    
            </div>
            <div class="row">
                <?php foreach ($current_quizzes as $quiz) { ?>
                    <a href="/participate/<?=$quiz['_id']?>"> 
                        <div class="col-md-3 text-center">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4><?= $quiz['name'] ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if(isset($future_quizzes) && count($future_quizzes) != 0) { ?>
            <div class="row">
                <h2 class="lead">Future quizzes</h2>
            </div>
            <div class="row">
                <?php foreach ($future_quizzes as $quiz) { ?>
                    <a href="/participate/<?=$quiz['_id']?>">
                        <div class="col-md-3 text-center">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4><?= $quiz['name'] ?></h4>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="panel panel-default" style="background-color: #ff333b">
            <div class="panel-body text-center">
                <h3 class="lead" style="color: #ffccc6">No quizzes have been scheduled!</h3>
            </div>
        </div>
    <?php } ?>

<?php } ?>