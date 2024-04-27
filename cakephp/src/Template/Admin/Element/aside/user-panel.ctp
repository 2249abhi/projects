<?php
/**
 * Element : User panel
 * User panel controll all list of navigation.
 * @author : Nilesh Kushvaha
 * @version : 1.1
 * @since : 24 October 2018
 */
?>
<?php $loginUser = $this->request->getSession()->read('Auth.User'); ?>
<div class="user-panel">
    <div class="pull-left image">
        <?php echo $this->Html->image('user2-160x160.jpg', array('class' => 'img-circle', 'alt' => 'User Image')); ?>
    </div>
    <div class="pull-left info">
        <p><?=$loginUser['name']?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>