<div class="top-content">          
  <div class="inner-bg">
      <div class="container">          
          <div class="row">
              <div class="registration-form">                
                <div class="form-box">
                  <div class="form-top">
                    <div class="form-top-left">
                      <h3>Forgot Password?</h3>
                        <p>You can reset your password here.</p>
                    </div>
                    <div class="form-top-right">
                      <i class="fa fa-lock"></i>
                    </div>
                  </div>                  
                  <div class="form-bottom">
                    <?= $this->Flash->render(); ?>
                    <?= $this->Flash->render('auth'); ?>
                    <?= $this->Form->create('forgotPassword',['id'=>'forgotPassword','class' => 'login-form']); ?>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email address">
                      </div>
                      <div class="input-group">
                        <input type="text" name="captcha" id="captcha" autocomplete="off" class="form-control" placeholder="Enter captcha code">
                        <img src="<?php echo $this->Url->build('/admin/users/captcha');?>" class="capimg"/>
                        <a href="#" class="recaptcha"><i class="fa fa-refresh"></i></a>
                      </div>
                                     
                 <div class="row">         <div class="col-sm-4"><div class="social-login loginmx">
            <div class="social-login-buttons ">
                  <?= $this->Html->link(__('Login'), ['action' => 'login'],['class' =>'btn btn-link-2']) ?>
                  <?php //echo $this->Html->link(__('Registration'), ['controller'=>'Registration','action' => 'index'],['class' =>'btn btn-link-2']) ?>
                </div>      </div></div><div class="col-sm-5 pull-right"> <button type="submit" class="btn resetpasss">Reset Password</button> </div>
        
                    <?= $this->Form->end(); ?>
                  </div>
                </div>
              
             
            </div>
          </div>          
      </div>
    </div>  
</div>
<style type="text/css">
  input#captcha {
    width: 43%;
    float: left;
    margin-right: 2%;
  }
  img.capimg {
    float: left;
    margin-right: 2%;
    width: 45%;
  }
  a.recaptcha {
    padding: 1px;
    float: left;
  }
  a.recaptcha i.fa.fa-refresh {
    font-size: 30px;
  }
</style>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
  $(document).ready(function() {    
    $("#forgotPassword").validate({
      rules:{
        'email':{
            required:true,
            email: true,
            emailt: true
          },
      },
      messages:{
        'email':{
            required:"Email field is required",
            email: 'Enter valid mail id',
            emailt : 'This is not valid mail'
          }
       }
      });
  });

  jQuery.validator.addMethod("emailt", function(value, element) {
    return this.optional(element) || /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i.test(value);
  }, "Your entered invalid email id");

  $(function() {
    $('.recaptcha').click (function(e){
      e.preventDefault();
      $('.capimg').attr("src",$('.capimg').attr("src")+'?id='+Math.random());
    });
  });
</script>
<?php $this->end();?>