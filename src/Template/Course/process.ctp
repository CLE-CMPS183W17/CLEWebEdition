<?php 
	if ($myTermIndex <= 0) { ?>
		<h3>Please go back and add course.</h3>
	<?php }else{ ?>
<h3>You can graduate in <?php echo "$myTermIndex"; ?> <?php echo ($myTermIndex > 1)?  'quarters' :  'quarter'; ?> </h3>
<div class='container'>
<?php
$count = 1;
foreach($myTerms as $myCurrentTerm) {
    if (empty($myCurrentTerm)) {
        $count++;
        continue;
    }
    if ($count % 4 == 1) $quar = 'Fall';
    elseif ($count % 4 == 2) $quar = 'Winter';
    elseif ($count % 4 == 3) $quar = 'Spring';
    else $quar = 'Summer';
?>
	
	<p class="well">

    <b><?php echo "$quar, Year ".(floor(($count - 1)/4) + 1).":<br>"; ?></b>
    <?php foreach($myCurrentTerm as $myCourse) {
        echo "$myCourse->name - $myCourse->units units <br>";
    } ?>
    <?php $count++; ?>
<?php }?>

	</p>


</div>
<?php } ?>
