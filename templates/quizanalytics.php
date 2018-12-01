<?php if ($loginInfo == 0) {
    include_once('../templates/logout.php');
} else { ?>
    <?php if ($isInstructor != 0 || $quiz['share_ranklist'] == 'Y') { ?> 
        <legend class="white-text">Rank List</legend>
        <div class="panel panel-default">
            <table style="width:100%;float:center;">
                <tr>
                    <th align="center" style="padding:10px">First Name</th>
                    <th>Last Name</th> 
                    <th>Roll Number</th>
                    <th>Score</th>
                </tr>
                <?php foreach ($score_card as $entry) { ?>
                    <tr>
                        <td style="padding:10px"><?php echo $entry['first_name'] ?></td>
                        <td><?php echo $entry['last_name'] ?></td>
                        <td><?php echo $entry['roll_number'] ?></td>
                        <td><?php echo $entry['score'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>    
    <?php } ?> 
<?php } ?>