<?php
/**
  * @var \App\View\AppView $this
  * @author Nilesh Kumar
  */
?>


<?php $this->Breadcrumbs->add('Users', '/users');
$this->Breadcrumbs->add('Change Password', ['controller' => 'Users', 'action' => 'changePassword']); ?>
  <div id="change-password" class="col-md-8 col-md-offset-2">
    <br>
    <div class="errorlist" style="display: none;">
      <div class="row">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <div class="panel-title">
              There are serious errors in your form submission, please see below for details.
            </div>
          </div>
          <ol></ol>
        </div>
      </div>
    </div>

    <div class="row">
      <?= $this->Flash->render(); ?>
      <?= $this->Flash->render('auth'); ?>
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title"> Change Password </div>
      </div>
      <div class="panel-body">
        <?= $this->Form->create($user,['id'=>'changePassword','class' => 'form-horizontal']); ?>
          <div class="form-group">
            <label for="old_password" class="control-label col-sm-4"> Old Password <span class="important-field">*</span></label>
            <div class="col-sm-8">
              <input type="password" title="please enter old password" required maxlength="40" class="form-control" name="old_password" id="old_password" placeholder="please enter old password">
            </div>
          </div>
          <div class="form-group">
            <label for="new_password" class="control-label col-sm-4"> New Password <span class="important-field">*</span></label>
            <div class="col-sm-8">
              <input type="password" title="please enter password" required maxlength="40" class="form-control" name="new_password" id="new_password" placeholder="please enter new password">
            </div>
          </div>
          <div class="form-group">
            <label for="confirm_password" class="control-label col-sm-4"> Confirm Password <span class="important-field">*</span></label>
            <div class="col-sm-8">
            	<input type="password" required maxlength="40" class="form-control" name="confirm_password" id="confirm_password" placeholder="confirm your password">
              <div id="divCheckPasswordMatch"></div>
            </div>
          </div>          
          <div class="form-group">
            <div class="  col-sm-offset-4 col-sm-8">
              <button id="btn-signup" type="submit" class="btn btn-success btn-green">Submit</button>
              <button id="cancel" class="btn btn-default btncancel">Cancel</button>
            </div>
          </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>

<?php $this->append('bottom-script');?>
<script type="text/javascript">
$(document).ready(function(){
    //$("#confirm_password").keyup(checkPasswordMatch);
  
  $.validator.addMethod("passwordV", function(value, element) {
    return this.optional(element) || /^(?=.*[0-9])[a-zA-Z0-9]{6,12}$/.test(value);
  }, "Password should be alpha-numeric and between 6-12 digits.");

  jQuery.validator.addMethod("pwcheck", function(value) {
    return /[\@\#\$\%\^\&\*\(\)\_\+\!\?]/.test(value) // At least one special character
      && /[a-z]/.test(value) // At least one lower case English letter
      && /[0-9]/.test(value) // At least one digit
      && /[A-Z]/.test(value) // At least one upper case English letter
  });

  function checkPasswordMatch() {
    var new_password = $("#new_password").val();
    var confirmPassword = $("#confirm_password").val();

    if (new_password != confirmPassword)
      $("#divCheckPasswordMatch").html("Passwords do not match!").css('color','red');
    else
      $("#divCheckPasswordMatch").html("Passwords match.").css('color','green');
  }

  // for form validation
  var container = $('div.errorlist');
  // validate the form when it is submitted
  $("#changePassword").validate({
    errorContainer: container,
    errorLabelContainer: $("ol", container),
    wrapper: 'li',
		// rules
		rules: {                   
			old_password : {
				required: true,
			},
      new_password : {
        required: true,
        rangelength: [8, 20],
        //pwcheck: true,
      },
			confirm_password:{
				required: true,
				equalTo: "#new_password"
			},
		},                             
		messages: {
      old_password : {
        required: " Enter old password",
      },
      new_password : {
				required: " Enter new password",
				rangelength:"Password should be minimum {8} and maximum {20} character",
        //pwcheck: "Password should be at least one uppercase letter, one lowercase letter, one number and one special character.",
			},
      confirm_password :{
        required: " Enter Confirm Password",
        equalTo:" Enter Confirm Password Same as Password"
		  },
		},
    submitHandler : function(form) {
      form.submit();
    }
  });

  $('#cancel').on('click', function () {
    var form = $("#changePassword");
    form.validate().resetForm();
    form[0].reset();
  });

});
</script>
<?= $this->Html->script('jquery.jcryption.3.1.0.js'); ?>
<script type="text/javascript">
  $(function() {
    var encryptUrl = "<?php echo $this->Url->build([
                    'controller' => 'Users',
                    'action' => 'jcryption']); ?>";
    $("#changePassword").jCryption({
      getKeysURL: encryptUrl + "?getPublicKey=true",    
      handshakeURL: encryptUrl + "?handshake=true",
      beforeEncryption: function() { 
        return $("#changePassword").valid(); 
      }
    });
  });
</script>
<?php $this->end();?>