<div class="top-content">          
  <div class="inner-bg">
      <div class="container">          
          <div class="row">
              <div class="registration-form">                
                <div class="form-box" style="margin-top: 30px">
                  <div class="form-top">
                    <div class="form-top-left">
                      <h3>Reset Password</h3>
                        <p>You can reset your password here.</p>
                    </div>
                    <div class="form-top-right">
                      <i class="fa fa-unlock"></i>
                    </div>
                  </div>                  
                  <div class="form-bottom">
                    <?= $this->Flash->render(); ?>
                    <?= $this->Flash->render('auth'); ?>
                    <?= $this->Form->create('forgotPassword',['id'=>'forgotPassword','class' => 'login-form']); ?>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="password" title="Enter new password" required  name="password" id="new_password" placeholder="Enter new password" class="form-control" />
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="password" required title="Enter confirm password" name="confirm_password" id="confirm-password" placeholder="Confirm password" class="form-control">
                      </div>
                      <button type="submit" class="btn">Reset Password</button>
                    <?= $this->Form->end(); ?>
                  </div>
                </div>
              
              <div class="social-login">
                <div class="social-login-buttons">
                  <?= $this->Html->link(__('Login'), ['action' => 'login'],['class' =>'btn btn-link-2']) ?>
                  <?php //echo $this->Html->link(__('Registration'), ['controller'=>'Registration','action' => 'index'],['class' =>'btn btn-link-2']) ?>
                </div>
              </div>
            </div>
          </div>          
      </div>
    </div>  
</div>
<style type="text/css">
  .form-bottom label {
    color: #fff;
  }
</style>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
  $(document).ready(function() {    
    $("#forgotPassword").validate();
  });
</script>
<?php $this->end();?>