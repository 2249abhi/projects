<p><?= __('Dear').' '.$user->name; ?>,</p>
<p><?=__('We heard that you lost your password, Sorry about that!');?></p>
<p><?=__('But don\'t worry! You can use the following link within the next day to reset your password:');?></p>
<p><?=$this->Html->link(
    $this->Url->build(['controller' => 'Users', 'action' => 'resetPassword', $user->fp_token, '_full' => true]),
    ['controller' => 'Users', 'action' => 'resetPassword', $user->fp_token, '_full' => true],
    ['escape' => false]
);?></p>
<p><?=__('If you don\'t use this link within 24 hours, it will expire. To get a new password reset link,');?></p>
<p><?=__('Visit : ');?>
	<?=$this->Html->link(
    $this->Url->build(['controller' => 'Users', 'action' => 'forgotPassword', '_full' => true]),
    ['controller' => 'Users', 'action' => 'forgotPassword', '_full' => true],
    ['escape' => false]
);?></p>
<p><?=__('Thanks and have a nice day!');?></p>