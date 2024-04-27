<div class="other_member_row_<?= $row_count ?>" rowclass="<?=$row_count?>" id="other-member-row-<?= $row_count ?>"
    style="display:block">
    <div class="col-sm-3">
        <label>9(c). Type of Membership<span class="important-field">*</span></label>
        <?php //$arr_other_type_member = ['1'=>'Institutional Member','2'=>'Individual Member','3'=>'Any Other'];//,'3'=>'Any Other (Please Specify)' ?>
        <?=$this->Form->control("dcb_scb_other_members.2.type_membership",['options'=>$arr_other_type_member,'label'=>false,'class'=>'other_type_membership select2','inc'=>$ohr2,'required'=>true, 'onchange' => 'any_other(this)'])?>
    </div>
    <div class="col-sm-3">
        <label>9(d). Class of Membership<span class="important-field">*</span></label>
        <?php $arr_class_member = ['1'=>'Ordinary/Regular/Voting','2'=>'Associate/Nominal/Non Voting']; ?>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.class_membership",['options'=>$arr_class_member,'label'=>false,'class'=>'building_type select2 ','required'=>true])?>
    </div>
    <div class="col-sm-3">
        <label>9(e). Name of Organisation<span class="important-field">*</span></label>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.org_name",['type'=>'textbox','placeholder'=>'Name of Organisation','label'=>false,'class'=>'','required'=>true, 'maxlength'=>'200'])?>
    </div>

    <div class="col-sm-3 <?= $ohr2 ?>individual_div"
        style="display:<?= ($dcb_scb_other_member->type_membership == 2 || $dcb_scb_other_member->type_membership == 3) ? 'none' : 'block'; ?>;">
        <label>9(f). Primary Activities/Sector<span class="important-field">*</span></label>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.primary_activity",['options'=>$PrimaryActivities,'readonly'=>true,'label'=>false,'class'=>$ohr2.'individual_input select2','required'=>true])?>
        <?php //$this->Form->control("dcb_scb_other_members.$ohr2.member_type",['type'=>'hidden','value'=>'1'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div"
        style="display:<?= ($dcb_scb_other_member->type_membership == 2 || $dcb_scb_other_member->type_membership == 3) ? 'none' : 'block'; ?>;">
        <label>9(g). District<span class="important-field">*</span></label>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.district_code",['options'=>$districts,'label'=>false,'class'=>$ohr2.'individual_input select2','type'=>'select','required'=>true])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div"
        style="display:<?= ($dcb_scb_other_member->type_membership == 2 || $dcb_scb_other_member->type_membership == 3) ? 'none' : 'block'; ?>;">
        <label>9(h). Address<span class="important-field">*</span></label>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.address",['type'=>'textbox','placeholder'=>'Address','label'=>false,'class'=>$ohr2.'individual_input','required'=>true, 'maxlength'=>'200'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div"
        style="display:<?= ($dcb_scb_other_member->type_membership == 2 || $dcb_scb_other_member->type_membership == 3) ? 'none' : 'block'; ?>;">
        <label>9(i). Phone Number<span class="important-field">*</span></label>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.phone_number",['type'=>'textbox','placeholder'=>'Phone Number','label'=>false,'class'=>$ohr2.'individual_input number','required'=>true, 'maxlength'=>'10'])?>
    </div>
    <div class="col-sm-3 <?= $ohr2 ?>individual_div"
        style="display:<?= ($dcb_scb_other_member->type_membership == 2 || $dcb_scb_other_member->type_membership == 3) ? 'none' : 'block'; ?>;">
        <label>9(j). Email<span class="important-field">*</span></label>
        <?=$this->Form->control("dcb_scb_other_members.$ohr2.email",['type'=>'email','placeholder'=>'Email','label'=>false,'class'=>$ohr2.'individual_input','required'=>true, 'maxlength'=>'200'])?>
    </div>
    <!-- <div class="col-md-3 <?= $ohr2 ?>individual_upload_div"
        style="display:<?= ($dcb_scb_other_member->type_membership == 2 || $dcb_scb_other_member->type_membership == 3) ? 'block' : 'none'; ?>;">

        <label>9(f).Document</label>&nbsp;&nbsp;<a class="<?= $ohr2 ?>_file"
            href="<?=$this->request->webroot?>files/member_document/<?= $dcb_scb_other_member->member_document ?>"
            target="_blank" download><i class="fa fa-download"></i></a>
        <?php 
                                            $accept = '';
                                            if($dcb_scb_other_member->type_membership == 2)
                                                {
                                                        $accept = 'application/pdf';
                                                }

                                            if($dcb_scb_other_member->type_membership == 3)
                                                {
                                                        $accept = '.csv,text/csv';
                                                }
                    
                                            echo $this->Form->control("dcb_scb_other_members.$ohr2.member_document",['label' => false,'type' =>'file','class'=>$ohr2.'individual_upload_input','required'=>false,'accept'=>$accept,'value'=>$dcb_scb_other_member->member_document]);?>

    </div> -->
    <!-- <div class="col-md-3 <?= $ohr2 ?>individual_upload_div"
        style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'block' : 'none'; ?>;">

        <label>9(g). Member Count</label>
        <?php echo $this->Form->control("dcb_scb_other_members.$ohr2.member_count",['label' => false,'type' =>'text','class'=>$ohr2.'individual_upload_input docu number','maxlength'=>'7']);?>

    </div> -->
    <!-- <div class="col-sm-12">
        <button type="button" inc="<?= $ohr2 ?>" class="pull-right btn btn-primary btn-xs save_row_other_member"
            data_id="<?= $dcb_scb_other_member->id ?>">Save other member</button>
    </div> -->
    <div class="col-sm-12">
        <!-- <button type="button" class="pull-right btn btn-primary btn-xs add_other_member_row"
            onclick="add_other_member_row()">
            Add other member<i class="fa fa-plus-circle"></i>
        </button> -->
        <button style="display: block;margin-bottom: 10px" type="button"
            class="pull-right btn btn-danger btn-xs remove_other_member_row" onclick="remove_other_member_row()">
            <i class="fa fa-minus-circle"></i>
        </button>
    </div>
</div>

<script>

function remove_other_member_row(){
    console.log("remove_other_member_row");
}
</script>