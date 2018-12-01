<div class="row">
    <img class="bg" src="/images/quiz.png">
</div>
<div class="row">
    <br>
    <?php if(isset($quizzes) && count($quizzes) != 0) { ?>
        <legend class="white-text">Featured Quizzes</legend>
    <?php } ?>
    <br>
    <?php foreach ($quizzes as $quiz) { ?>
        <div class="col-md-4 text-center">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3><?= $quiz['name'] ?></h3>
                    <h5 class="text" >Start: <?= $quiz['start_time'] ?> </h5>
                    <h5>Duration: <?= $quiz['duration_minutes']?> minutes</h5>
                </div>
            </div>
        </div>
    <?php } ?>
</div>