<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:05 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title',__('Edit User ( ' . $user->name .' ) '));
$this->Breadcrumbs->add(__('Users'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit User ( ' . $user->name .' ) '));
?>

<!-- Main content -->
<section class="content user edit form">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?= __('User') ?>
                        <small><?= __('Edit User  ( ' . $user->name .' ) ') ?></small>
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Users'),'escape' => false]
                  );?>
                        <?php //echo $this->Form->postLink('<i class="fa fa-trash-o"></i>',['action' => 'delete', $user->id],['confirm' => __('Are you sure you want to delete this User?', $user->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]) ?>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php
              echo $this->Form->create($user,['id' => 'user-edit-frm']); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $this->Form->control('name') ?>
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
                        <?= $this->Form->control('password',['value'=>'','required' => true,]) ?>
                        </div>
                        <div class="col-sm-6">
                        <?= $this->Form->control('role_id', ['options' => $roles,'value'=>$user->role_id, 'onChange'=> 'getPrimry(this)']) ?>
                        </div>

                        <div class="col-sm-6 div_state_code">
                        <?php     
                            $disabled ="";
                            $required = true;

                            //national federation
                            

                          if($loginUser['role_id'] == 8)
                            {
                                 $disabled="disabled";
                            }             
                        echo  $this->Form->control('state_code', ['options' => $states,'empty'=>'--Select State--','value'=>$user->state_code, 'disabled'=>$disabled,'required' => true,'label' =>'State']);
                                                  ?>
                    
                        </div>

                    </div>
                    <div class="row">

                    <div class="col-sm-6 div_district_code">

                     <?php    echo $this->Form->control('district_code', ['options' =>$districts,'empty'=>'--Select District--', 'value'=>$user->district_code,'required' => true,'label' =>'Districts','disabled'=>$disabled]);
                    ?>
                   </div> 
                    <div class="col-sm-6">
                    <?= $this->Form->control('designation') ?>
                  </div>
                   <div class="col-sm-6">
                    <?= $this->Form->control('contact_no',['maxLength'=>10,'minLength'=>10,'type'=>'number']) ?>
                  </div>
                  <?php  if($user->role_id == 8): ?>
                        <div class="col-sm-6" style="" id="pa">
                         <?= $this->Form->control('primary_act_code', ['options' => $pactivities,'empty'=>'--Select Primary Activities--','value'=>$user->primary_act_code,'label' =>'Primary Activities']); ?>
                        </div>
                        <?php elseif($user->role_id == 11): ?>
                        <div class="col-sm-6" style="" id="pa">
                         <?= $this->Form->control('primary_act_code', ['options' => $pactivities,'empty'=>'--Select Primary Activities--','value'=>$user->primary_act_code,'label' =>'Primary Activities']); ?>
                        </div>
                        <?php else: ?>
                            <div class="col-sm-6" style="display:none;" id="pa">
                         <?= $this->Form->control('primary_act_code', ['options' => $pactivities,'empty'=>'--Select Primary Activities--','value'=>$user->primary_act_code,'label' =>'Primary Activities']); ?>
                        </div>
                    <?php endif; ?>            
                    <?php  if($user->status == 0): ?> 
                        <div class="col-sm-6" id="myDivv" style="">
                        <?= $this->Form->control('deactivation_date',['type'=>'text','label'=>'Deactivation Date','class'=>'before_date','required'=>true]) ?>
                        </div> 
                        
                        <?php else: ?>    
                    <div class="col-sm-6" id="myDivv" style="display:none;">
                        <?= $this->Form->control('deactivation_date',['type'=>'text','label'=>'Deactivation Date','class'=>'before_date','required'=>true]) ?>
                    </div>  
                    <?php endif; ?> 
                    </div>
					
					<div class="row">  
						<div class="col-sm-6">
                        <div><label for="status">Joined</label></div>
						<?= $this->Form->control('intern_join',['options'=>[1=>'Yes',2=>'No'],'id'=>'intern_join','default'=>'2','type' => 'radio','label'=>false, 'onChange'=> 'getValue(this)','required'=>true]) ?>
						</div>
						<?php  if($user->intern_join == 1): ?>
                        <div class="col-sm-6" id="myDiv" style="">
                            <?= $this->Form->control('date_join',['type'=>'text','label'=>'Date of Joining','class'=>'before_date','required'=>true]) ?>
                        </div>
                       <?php else: ?>
                       <div class="col-sm-6" id="myDiv" style="display:none;">
                            <?= $this->Form->control('date_join',['type'=>'text','label'=>'Date of Joining','class'=>'before_date','required'=>true]) ?>
                        </div>
                     <?php endif; ?>
                    
                        <div class="col-sm-6">
                        <?= $this->Form->control('status',['onChange'=> 'getStatus(this)']) ?>
                        </div>
					</div>

                    

                        <div class="box-block-m bank_details" style="display:<?php echo $user->role_id == 7 ? 'block' : 'none'; ?>">
                            <div class="col-sm-12">
                                <h4><strong>Bank Details</strong></h4>
                            </div>
                            <div class="col-sm-6">
                              <?php $user_types = ['1'=>'DEO','2'=>'Intern']; ?>
                            <?= $this->Form->control('user_type', ['options' => $user_types,'empty'=>'--Select DEO/Intern--','default'=>0,'label' =>'User Type']) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control('account_holder_name',['maxlength'=>200]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control('account_number',['class'=>'number','maxlength'=>15]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control('name_of_bank_and_branch',['maxlength'=>200]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control('ifsc_code',['maxlength'=>20]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $this->Form->control('remarks',['rows'=>2,'type'=>'textarea']) ?>
                            </div>
                        </div>
                        
                        <div class="row col-sm-12">
                            <div class="box-footer">
                                <?php 
                                    echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
                                    echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
                            </div>
                       </div>
                    
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
</section>
<?php $this->append('bottom-script');?>
<script>
(function($) {
    $(document).ready(function() {
        if (typeof $.validator !== "undefined") {
            $("#user-edit-frm").validate();
        }
    });



$('#state-code').on('change',function(e){
    e.preventDefault();
   var base_urla= "<?=BASE_URL?>"; 

    var base_url= "<?php echo $this->Url->build('/admin/en/users/getDistricts', false);?>"; 
   
    $.ajax({
            type:'POST',
            cache: false,
            url: base_url,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            data: {state_code : $(this).val()},
            success: function(response){
                $("#district-code").html(response);
               
            },
        }); 
});





if($('#role-id').val()==11)
 {
    $('.div_district_code').hide();
 } else {

    $('.div_district_code').show();

    if($('#role-id').val()==13)
    {
        $('.div_state_code').hide();
        $('.div_district_code').hide();
    }

}

})($);

$(".before_date").datepicker({
	dateFormat: 'yy/mm/dd',
	changeMonth: true,
	changeYear: true,
	maxDate: new Date(),
});

</script>
<script>
  function getValue(x){
    if(document.getElementById('intern-join-1').checked == true) {   
    document.getElementById("myDiv").style.display = 'block';   
    }else{
        document.getElementById("myDiv").style.display = 'none';  
    }
  }

</script>
<script>
  function getStatus(y){
  if(document.getElementById('status').checked != true) {   
    document.getElementById("myDivv").style.display = 'block';   
  }else{
        document.getElementById("myDivv").style.display = 'none';  
    } 
  }

  function getPrimry(x){
   var val = document.getElementById('role-id').value;
    if(val == '13'){
        $('.div_state_code').hide();
        $('.div_district_code').hide();
    } else{
        $('.div_state_code').show();
        $('.div_district_code').show();
    }

    if(val == 7)
    {
        $('.bank_details').show();
    } else {
        $('.bank_details').hide();
    }
    
   if(val =='8') {
    document.getElementById("pa").style.display = 'block';  
    }
    else if(val == '11'){
        document.getElementById("pa").style.display = 'block';
    }
   else{
     document.getElementById("pa").style.display = 'none';   
     }
    }
  
</script>
<?php $this->end(); ?>