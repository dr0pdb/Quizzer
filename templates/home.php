<div class="row">
    <img src="https://static-news.moneycontrol.com/static-mcnews/2018/09/Answers1.jpg">
</div>
<div class="row">
    <br>
    <legend>Featured Quizzes</legend>
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