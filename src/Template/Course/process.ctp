<?php 
	if ($myTermIndex <= 0) { ?>
		<h3>Please go back and add course.</h3>
	<?php }else{ ?>
<h3>You can graduate in <?php echo "$myTermIndex"; ?> <?php echo ($myTermIndex > 1)?  'quarters' :  'quarter'; ?> </h3>
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
<?php } ?>