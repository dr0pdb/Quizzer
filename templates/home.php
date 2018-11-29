<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-lg-6">
            <?php if($loginInfo == 0) { ?>
                <h4>You need to Sign In/Register first</h4>
            <?php } else { ?>
                <h4>Hello, <?=$user['first_name']?> </h4>
            <?php } ?>
        </div>
    </div>
</div>

<?php function getColor($stock) {
    $color = 'danger';

    if($stock > 50){
        $color = 'success';
    } else if($stock > 20) {
        $color = 'warning';
    }

    return $color;
}
?>

<div class="row">
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