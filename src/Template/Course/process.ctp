<h3>You can graduate in <?php echo "$myTermIndex"; ?> quarters!</h3>
<div class='container'>
<?php
$count = 1;
foreach($myTerms as $myCurrentTerm) {?>
	
	<p class="well">

    <b><?php echo "Quarter: $count<br>"; ?></b>
    <?php foreach($myCurrentTerm as $myCourse) {
        echo "$myCourse->name - $myCourse->units units <br>";
    } ?>
    <?php $count++; ?>
<?php }?>

	</p>


</div>