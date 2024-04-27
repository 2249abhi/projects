<p>Dear <?= $name ?>,</p>
<p>Greetings from Startup Haryana.</p>
<p>We would like to thank you for registering with us.</p>
<p>To complete the registration process, please activate and verify your account by clicking on the link below. Alternatively, you may copy and paste the link in your web browser.</p>
<p><a href="<?= $link ?>"><?= $link ?></a></p>
<br/><br/>
<p>Kind regards,</p>
<p>Startup Haryana</p>
<p><a href="<?= $this->Url->build('/', true);?>"><?= $this->Url->build('/', true);?></a></p>