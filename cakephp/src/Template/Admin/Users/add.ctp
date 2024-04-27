<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:05 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title',__('Add New User'));
$this->Breadcrumbs->add(__('Users'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New User'));
?>

<!-- Main content -->
<section class="content user add form">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?= __('User') ?>
                        <small><?= __('Add New User') ?></small>
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Users'),'escape' => false]
                  );?>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                  echo $this->Form->create($user,['id' => 'user-add-frm']); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $this->Form->control('name',['label' => 'Full Name']) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->Form->control('username') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $this->Form->control('email') ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->Form->control('password') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $this->Form->control('role_id', ['options' => $roles, 'onChange'=> 'getPrimry(this)']) ?>
                        </div>
                        <div class="col-sm-6">
                            <?php 
                            if($loginUser['role_id']==8)
                            {
                                  echo  $this->Form->control('state_code1', ['options' => $states,'empty'=>'--Select State--','value'=>$loginUser['state_code'],'required' => true,'label' =>'State','disabled'=>'disabled']);
                            }
                              ?>
                        </div>
                        <div class="col-sm-6 div_state_code">

                            <?php  if($loginUser['role_id']==8)
                            {
                                echo $this->Form->control('district_code1', ['options' => $districts,'empty'=>'--Select District--','value'=>$loginUser['district_code'],'required' => true,'label' =>'Districts','disabled'=>'disabled']);
                                echo  $this->Form->control('state_code', ['type'=>'hidden','value'=>$loginUser['state_code']]) ;

                                echo  $this->Form->control('district_code', ['type'=>'hidden','value'=>$loginUser['district_code']]) ;

                            }else
                            {
                              echo $this->Form->control('state_code', ['options' => $states,'empty'=>'--Select State--','required' => true,'label' =>'State']);
                              } ?>
                                  </div>

                                  <?php  if($loginUser['role_id'] !=8)
                            { ?>
                                  <div class="col-sm-6 div_district_code">

                                      <?php    echo $this->Form->control('district_code', ['options' => '','empty'=>'--Select District--','required' => true,'label' =>'Districts']);
                              ?>
                        </div>
                        <?php } ?>
                        <div class="col-sm-6">
                            <?= $this->Form->control('designation') ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->Form->control('contact_no',['maxLength'=>10,'minLength'=>10,'type'=>'number']) ?>
                        </div>
                        <div class="col-sm-6" style="" id="pa">
                            <?= $this->Form->control('primary_act_code', ['options' => $pactivities,'empty'=>'--Select Primary Activities--','label' =>'Primary Activities']); ?>
                        </div>
                        <div class="col-md-6">
                            <div><label for="status">Joined</label></div>
                            <?= $this->Form->control('intern_join',['options'=>[1=>'Yes',2=>'No'],'id'=>'intern_join','default'=>'2','type' => 'radio','label'=>false, 'onChange'=> 'getValue(this)','required'=>true]) ?>
                        </div>

                        <div class="col-sm-6" style="display:none;" id="myDiv">
                            <?= $this->Form->control('date_join',['type'=>'text','id'=>'','label'=>'Date of Joining','class'=>'before_date','required'=>true]) ?>
                        </div>
                        
                        
                        
                        <div class="box-block-m bank_details" style="display:<?= $loginUser['role_id'] == 8 ? 'block' : 'none'; ?>">
                            <div class="col-sm-12">
                                <h4><strong>Bank Details</strong></h4>
                            </div>
                            <div class="col-sm-6">
                                <?php 
                                $row = 0;
                                $user_types = ['1'=>'DEO','2'=>'Intern']; ?>
                                <?= $this->Form->control("user_payment.$row.user_type", ['options' => $user_types,'empty'=>'--Select DEO/Intern--','default'=>0,'label' =>'User Type']) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control("user_payment.$row.account_holder_name",['maxlength'=>200]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control("user_payment.$row.account_number",['class'=>'number','maxlength'=>15]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control("user_payment.$row.name_of_bank_and_branch",['maxlength'=>200]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control("user_payment.$row.ifsc_code",['maxlength'=>20]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control("user_payment.$row.remarks",['rows'=>2,'type'=>'textarea']) ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <?= $this->Form->control('status') ?>
                        </div>
                        <div class="col-sm-12 box-footer">
                            <?php 
                                echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
                                echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
                        </div>
                        <?php echo $this->Form->end();?>
                    </div>
                </div>
            </div>
</section>
<?php $this->append('bottom-script');?>
<?php echo $this->Html->script('jquery.jcryption.3.1.0.js'); ?>
<script type="text/javascript">
$(function() {


  $('body').on('keyup', '.number', function() {
            var value = $(this).val();
            if (/^[0-9.]*$/i.test(value)) {
                return false;
            } else if (/\D/g.test(value)) {
                // Filter non-digits from input value.
                value = this.value.replace(/\D/g, '');
                $(this).val(value);
            }

        });
    var encryptUrl = "<?php echo $this->Url->build([
                    'controller' => 'Users',
                    'action' => 'jcryption']); ?>";
    $("#user-add-frm").jCryption({
        getKeysURL: encryptUrl + "?getPublicKey=true",
        handshakeURL: encryptUrl + "?handshake=true",
        beforeEncryption: function() {
            return $("#user-add-frm").valid();
        }
    });
});

$(document).ajaxSend(function(e, xhr, settings) {
    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
});

(function($) {
    $(document).ready(function() {
        if (typeof $.validator !== "undefined") {
            $("#user-add-frm").validate();
        }
    });

    /*  get district */


    $('#state-code').on('change', function(e) {
        e.preventDefault();
        var base_urla = "<?=BASE_URL?>";

        var base_url = "<?php echo $this->Url->build('/admin/en/users/getDistricts', false);?>";

        $.ajax({
            type: 'POST',
            cache: false,
            url: base_url,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            data: {
                state_code: $(this).val()
            },
            success: function(response) {
                $("#district-code").html(response);

            },
        });
    });

    $('body').on('change', '#role-id', function() {
        $('.div_state_code').show();
        if ($(this).val() == 11) {
            $('.div_district_code').hide();
        } else {
            $('.div_district_code').show();
            if ($(this).val() == '13') {
                $('.div_state_code').hide();
                $('.div_district_code').hide();
            }

        }
    });

})($);

$(".before_date").datepicker({
    dateFormat: 'yy/mm/dd',
    changeMonth: true,
    changeYear: true,
    maxDate: new Date(),
});

function getValue(x) {
    if (document.getElementById('intern-join-1').checked == true) {
        document.getElementById("myDiv").style.display = 'block';
    } else {
        document.getElementById("myDiv").style.display = 'none';
    }
}

function getPrimry(x) {
    var val = document.getElementById('role-id').value;
    if(val == '13'){
        $('.div_state_code').hide();
        $('.div_district_code').hide();
    } else{
        $('.div_state_code').show();
        $('.div_district_code').show();
    }
    if (val != '8' && val != '11') {
        document.getElementById("pa").style.display = 'none';
        
    } else {
        document.getElementById("pa").style.display = 'block';
    }
}
</script>
<?php $this->end(); ?>