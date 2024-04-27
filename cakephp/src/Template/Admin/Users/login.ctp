<?php
use Cake\Core\Configure;
?>
<div class="top-content">          
  <div class="inner-bg">
      <div class="container">          
          <div class="row">
              <div class="registration-form">                
                <div class="form-box" style="margin-top: 0;">
				<!--<marquee width="60%" direction="left" height="100px" style="color:red">
				<h5>The portal will be down for maintenance between 6:30 PM to 7:00 PM . Sorry for inconvenience ! </h5>
				</marquee>-->
                  <div class="form-top">
                    <div class="form-top-left">
                      <h3>National Cooperative Database</h3>
                        <p>Ministry of Cooperation</p>
                    </div>
                    <div class="form-top-right">
                      <i class="fa fa-lock"></i>
                    </div>
                  </div>                  
                  <div class="form-bottom">
                    <?= $this->Flash->render(); ?>
                    <?= $this->Flash->render('auth'); ?>
                    <?php echo $this->Form->create('loginform',['id' => 'loginform','autocomplete' => 'off']); ?>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input type="text" name="email" autocomplete="off" class="form-control" placeholder="Enter User ID" value="<?= $this->request->data['email'] ?>">
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" autocomplete="off" class="form-control" placeholder="Enter your password">
                      </div>
                      <div class="input-group">
                        <input type="text" name="captcha" id="captcha" autocomplete="off" class="form-control" placeholder="Enter captcha code">
                        <img src="<?php echo $this->Url->build('/admin/users/captcha');?>" class="capimg"/>
                        <a href="#" class="recaptcha"><i class="fa fa-refresh"></i></a>
                      </div>
                      
                     <div class="row"> <div class="col-sm-4"><button type="submit" class="btn">Login</button></div>
					    <div class="col-sm-8"> <div class="social-login sloginx pull-right">
               <div class="social-login-buttons">
                  <?=  $this->Html->link(__('Forgot password'), ['action' => 'forgotPassword'],['class' =>'btn btn-link-2']) ?>
                  <?php //echo $this->Html->link(__('New Registration'), ['prefix'=>false,'controller'=>'Registration','action' => 'index'],['class' =>'btn btn-link-2']) ?>
                </div>
              </div></div></div>
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
    $("#loginform").validate({
      rules:{
        'captcha':{
          required:true,
        },
        'email':{
            required:true,
            email: false,
            emailt: false
          },
        'password' : {
            required: true,
          },
      },
      messages:{
        'captcha':{
          required:"Captcha field is required",
        },
        'email':{
            required:"Email field is required",
            email: 'Enter valid mail id',
            emailt : 'This is not valid mail'
          },
        'password' : {
            required: "Password field is required",
          }
       }
      });
  })

  jQuery.validator.addMethod("emailt", function(value, element) {
    return this.optional(element) || /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i.test(value);
  }, "Your entered invalid email id");

  $('#reset').on('click', function () {
    var validator = $("#loginform").validate();
    validator.resetForm();
    validator.reset();
  });
</script>
<?php echo $this->Html->script('jquery.jcryption.3.1.0.js'); ?>
<script type="text/javascript">  
  $(function() {
    var encryptUrl = "<?php echo $this->Url->build([
                    'controller' => 'Users',
                    'action' => 'jcryption']); ?>";
    $("#loginform").jCryption({
        getKeysURL: encryptUrl + "?getPublicKey=true",    
        handshakeURL: encryptUrl + "?handshake=true",
        beforeEncryption: function() { 
          return $("#loginform").valid(); 
        }
    });
  });

  $(document).ajaxSend(function(e, xhr, settings) {
    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
  });
  
  $(function() {
    $('.recaptcha').click (function(e){
      e.preventDefault();
      $('.capimg').attr("src",$('.capimg').attr("src")+'?id='+Math.random());
    });
  });
</script>
<?php $this->end();?>