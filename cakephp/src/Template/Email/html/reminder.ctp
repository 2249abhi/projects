<?php $encodeEmail = base64_encode($email); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Your Subscription</title>
</head>
<body>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="650px" align="center" style="border:1px solid #ddd;background: #f1f7f9;">
				<tr>
					<td style="border-bottom: 1px solid #00b6f0;background: #fff;">
						<table>
							<tr>
								<td style="padding:10px;">
									<a href="<?php echo $base_url ?>" style=" width:28%;">
										<img src="https://www.sidbi.in/assets/images/logo.png" alt="logo">
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding:20px;">
						<table cellpadding="0" cellspacing="0" width="650px" align="left">
							<tr>
								<td>
									<p style="font-size:20px; line-height:23px;text-align:justify; color:#080C0E; font-family:arial;">You're receiving this email beacuse you subscribed with us.</p>
								</td>
							</tr>
							<tr>
								<td style="padding-top:40px;">
									<p><a href="<?php echo $base_url.'home/unsubscribe?q1='.$encodeEmail;?>" style="font-size:18px; color:#fff; line-height:20px; background:#00b6f0; padding:10px;">To stop receiving emails click here.</a></p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding:20px;"><p>If you received this email by mistake, simply delete it. You would not be subscribed if you don't click the confirmation link above.</p></td>
				</tr>
				<tr>
					<td style="padding:20px;"><p>For question about this list, please contact : <a href="mailto:admin@sidbi.in" target="_top">admin@sidbi.in</a> </p></td>
				</tr>
				<tr>
					<td style="padding-top:20px;">
						<div class="footer-bottom-cont" style="text-align: center;">
							<p>
								<a href="https://www.facebook.com" style="padding-right:7px;"><?= $this->Html->image("../assets/images/facebook-icon.png",["alt" => "facebook",'fullBase' => true]) ?></a>
								<a href="https://twitter.com" style="padding-right:7px;"><?= $this->Html->image("../assets/images/twitter-icon.png",["alt" => "twitter",'fullBase' => true]) ?></a>
								<a href="https://plus.google.com" ><?= $this->Html->image("../assets/images/google-plus.png",["alt" => "google",'fullBase' => true]) ?></a>
							</p>
							<br>
							<p style=" font-size:14px; line-height:14px; color:#4c4d4f;font-family:arial;">Copyright  ©<?= date('Y');?> Small Industries Development Bank of India(SIDBI)</p>
						</div>
					</td>
				</tr>
				<tr>
					<td style="padding: 10px;"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>