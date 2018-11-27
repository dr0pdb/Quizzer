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
        <div class="col-md-3 text-center">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4><?= $quiz['name'] ?></h4>
                    <!-- <img src="<?= $car['pic'] ?>" class="img-responsive" style="height: 160px;"/>

                    <?php
                    $stock = $quiz['stock'];
                    $color = getColor($stock);
                    ?>

                    <?= "<h5 class=\"text-$color\" >Stock : $stock </h5>"; ?>
                    <a href="/car/<?= $car['_id'] ?>"><button class="btn btn-primary">Details</button></a>
                    <?php if ($loginInfo != 0) { ?>
                        <?php if ($stock == 0) $disable = "disabled"; else $disable = ""; ?>

                        <a href="/rent/<?= $car['_id'] ?>" class="btn btn-<?=$color . " " . $disable?>" style="margin-left: 10px;">Rent</a>
                    <?php } ?> -->
                </div>
            </div>
        </div>
    <?php } ?>
</div>