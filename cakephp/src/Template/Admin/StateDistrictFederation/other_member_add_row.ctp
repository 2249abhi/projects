<div class="other_member_rows" rowclass="<?=$ohr2?>" id="needtohide<?=$ohr2?>">
    <div class="col-sm-3">
        <label>Type of Membership<span class="important-field">*</span></label>
        <?php //$arr_other_type_member = ['1'=>'Institutional Member','2'=>'Individual Member','3'=>'Any Other']//,'3'=>'Any Other (Please Specify)' ?>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.type_membership",['options'=>$arr_other_type_member,'label'=>false,'class'=>'other_type_membership','inc'=>$ohr2,'empty'=>'Select'])?>
    </div>
    <div class="col-sm-3">
        <label>Class of Membership<span class="important-field">*</span></label>
        <?php $arr_class_member = ['1'=>'Ordinary/Regular/Voting','2'=>'Associate/Nominal/Non Voting']; ?>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.class_membership",['options'=>$arr_class_member,'label'=>false,'class'=>'class_membership req','empty'=>'Select'])?>
    </div>
    <div class="col-sm-3" id="org_name2_div">
        <label>Name of Organisation<span class="important-field">*</span></label>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.org_name",['type'=>'textbox','placeholder'=>'Name of Organisation','label'=>false,'class'=>'org_name req', 'maxlength'=>'200'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div">
        <label>Primary Activities/Sector<span class="important-field">*</span></label>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.primary_activity",['options'=>$PrimaryActivities,'label'=>false,'class'=>$ohr2.'individual_input primaryact req','empty'=>'Select'])?>
        <?php //$this->Form->control("dcb_scb_other_members.$ohr2.member_type",['type'=>'hidden','value'=>1])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div">
        <label>District<span class="important-field">*</span></label>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.district_code",['options'=>$districts,'label'=>false,'class'=>$ohr2.'individual_input distr req','type'=>'select','empty'=>'Select'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div">
        <label>Address<span class="important-field">*</span></label>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.address",['type'=>'textbox','placeholder'=>'Address','label'=>false,'class'=>$ohr2.'individual_input addrs req', 'maxlength'=>'200'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div">
        <label>Phone Number<span class="important-field">*</span></label>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.phone_number",['type'=>'textbox','placeholder'=>'Phone Number','label'=>false,'class'=>$ohr2.'individual_input number phnum phone req', 'maxlength'=>'10'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div">
        <label>Email<span class="important-field">*</span></label>
        <?=$this->Form->control("sd_federation_other_members.$ohr2.email",['type'=>'email','placeholder'=>'Email','label'=>false,'class'=>$ohr2.'individual_input maile req', 'maxlength'=>'200'])?>
    </div>
    <div class="col-md-3 <?= $ohr2 ?>individual_upload_div" style="display:none;">
        <div class="">
            <div><label>Document <span style="color:red;" class="dtype"></span></label></div>
            <?php echo $this->Form->control("sd_federation_other_members.$ohr2.member_document",['label' => false,'type' =>'file','class'=>$ohr2.'individual_upload_input docu req','accept'=>'application/pdf']);?>
        </div>
    </div> 
    <div class="col-md-3 <?= $ohr2 ?>individual_upload_div" style="display:none;">
        <div class="">
            <div><label>Number of Member</label></div>
            <?php echo $this->Form->control("sd_federation_other_members.$ohr2.member_count",['label' => false,'type' =>'text','class'=>$ohr2.'individual_upload_input docu number']);?>
        </div>
    </div>
    <div class="col-sm-12">
        <button type="button" class="pull-right btn btn-primary btn-xs add_row_other_member">
        Add Other Member <i class="fa fa-plus-circle"></i>
        </button>
        <button style="display: none;margin-bottom: 10px" type="button"
            class="pull-right btn btn-danger btn-xs remove_row_other_member" id="bothremove<?=$ohr2?>">
            <i class="fa fa-minus-circle"></i>
        </button>
    </div>
</div>

