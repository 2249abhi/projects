<h2><p>Dear  <?=$name?>,</p></h2>
<?php 
	$encodeUid 		= base64_encode($uid);
	$current_time 	=  base64_encode(time());
 ?>

<p>Please click on following link to reset your password : 
	<a href="<?php echo $base_url.'users/reset_password?q1='.$encodeUid.'&q2='.$current_time;?>">Reset</a>
</p>
<p>If you did not get your password, please contact us immediately.</p>

<p>Thanks and have a nice day!</p>
