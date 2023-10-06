<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration $CooperativeRegistration
 */
$this->assign('title',__('Edit Cooperative Registration'));
$this->Breadcrumbs->add(__('Cooperative Registrations'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Cooperative Registration'));

$link = '';
if($this->request->session()->read('Auth.User.role_id') == 7)
{
    if($CooperativeRegistration->is_draft == 0)
    {
        $link = '/data-entry-pending';
    } else {
        $link = '/draft';
    }
    
} elseif($this->request->session()->read('Auth.User.role_id') == 8) {

    if($CooperativeRegistration->is_approved == 0)
    {
     $link = '/pending';
    } else if($CooperativeRegistration->is_approved == 1)
    {
     $link = '/accepted';
    } else {
     $link = '/rejected';
    }
 
} else {
    $link = '/index';
}
?>

<!-- Main content -->
<section class="content Cooperative Registration add form">

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?= __('Cooperative Registration') ?>
                        <small><?= __('Edit Cooperative Registration') ?></small>
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => $link],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Cooperative Registrations'),'escape' => false]
                  );?>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php
              echo $this->Form->create($CooperativeRegistration,['id' => 'CooperativeRegistrationForm']);?>
                <div class="box-body">
                    <div class="boxk">
                        <div class="row">
                            <div class="col-sm-4">
                                <?php
                                if(!empty($CooperativeRegistration->errors()))
                                {
                                   // echo '<pre>';
                                   // print_r($CooperativeRegistration->errors());
                                    //echo '<span style="color:red;">Please Fill Required Fields.</span>';
                                } 
                            ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>1(a). Name of Cooperative Society<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name',['type'=>'text','placeholder'=>'Name of Cooperative Society','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-4">
                                <label>1(b). Registration Authority<span class="important-field">*</span></label>
                                <?=$this->Form->control('registration_authoritie_id',['options'=>$register_authorities,'empty'=>'Select','label'=>false,'required'=>true])?>
                            </div>
                            <!-- <div class="clearfix"></div> -->
                            <div class="col-sm-4">
                                <label>1(c). Registration Number<span class="important-field">*</span></label>
                                <?=$this->Form->control('registration_number',['maxlength'=>30,'class'=>'','type'=>'text','placeholder'=>'Registration Number','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-4">
                                <label>1(d). Date of Registration<span class="important-field">*</span></label>
                                <?=$this->Form->control('date_registration',['type'=>'text','placeholder'=>'Date of Registration','label'=>false,'class'=>'before_date','required'=>true,'readonly'=>true])?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-------------------------------------------->


                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Location Details of Registered Office</strong></h4>
                        </div>
                        <br>
                        <div class="col-sm-4">
                            <label>2(a). Location of Registered Office<span class="important-field">*</span></label>
                            <?=$this->Form->control('location_of_head_quarter',['options'=>[1=>'Urban',2=>'Rural'],'id'=>'location_of_head_quarter','class'=>'location_of_head_quarter','default'=>'2','type' => 'radio','label'=>false,'required'=>true])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(b). State / UT <span class="important-field">*</span></label>
                            <?=$this->Form->control('state_code',['options'=>$states,'label'=>false,'type'=>'select','class'=>'select2','required'=>true])?>
                        </div>


                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(c). District <span class="important-field">*</span></label>
                            <?=$this->Form->control('district_code',['options'=>$districts,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select','required'=>true])?>
                        </div>

                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(d). Block <span class="important-field">*</span></label>
                            <?=$this->Form->control('block_code',['options'=>$blocks,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select','required'=>true])?>
                        </div>

                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(e). Gram Panchayat <span class="important-field">*</span></label>
                            <?=$this->Form->control('gram_panchayat_code',['options'=>$gps,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select','required'=>true])?>
                        </div>

                        <!--<div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(f). Gram Panchayat is Coastal  <span class="important-field">*</span></label>
                            <?=$this->Form->control('is_coastal',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','label'=>false,'required'=>true])?>
                        </div>-->

                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(g). Village <span class="important-field">*</span></label>
                            <?=$this->Form->control('village_code',['options'=>$villages,'label'=>false,'class'=>'select2','type'=>'select','required'=>true])?>
                        </div>


                        <div class="col-sm-4 urban_location_section"
                            style="display: <?=$CooperativeRegistration->location_of_head_quarter==1?'block':'none';?>;">
                            <label>2(c). Category of Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control('urban_local_body_type_code',['options'=>$localbody_types,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4 urban_location_section"
                            style="display: <?=$CooperativeRegistration->location_of_head_quarter==1?'block':'none';?>;">
                            <label>2(d). Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control('urban_local_body_code',['options'=>$localbodies,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4 urban_location_section"
                            style="display: <?=$CooperativeRegistration->location_of_head_quarter==1?'block':'none';?>;">
                            <label>2(e). Locality or Ward <span class="important-field"></span></label>
                            <?=$this->Form->control('locality_ward_code',['options'=>$localbodieswards,'label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(f). Pin Code <span class="important-field">*</span></label>
                            <?=$this->Form->control('pincode',['class'=>'pincode','label'=>false,'placeholder'=>'Pin Code','required'=>true,'minlength'=>6,'maxlength'=>6])?>
                        </div>
                    </div>
                    <!-------------------------------------------->
                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Contact Details</strong></h4>
                        </div>
                        <div class="col-sm-4">
                            <label>3(a) Name<span class="important-field">*</span></label>
                            <?=$this->Form->control('contact_person',['type'=>'textbox','placeholder'=>'Name','label'=>false,'required'=>true, 'maxlength'=>'200'])?>
                        </div>
                        <div class="col-sm-4">
                            <label>3(b) Designation<span class="important-field">*</span></label>
                            <?=$this->Form->control('designation',['options'=>$designations,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4 designation_other"
                            style="display:<?php if($CooperativeRegistration->designation == 6){ ?>block <?php } else{ ?> none <?php }; ?>">
                            <label>3(b)(i) Designationv Other<span class="important-field">*</span></label>
                            <?=$this->Form->control('designation_other',['type'=>'textbox','class'=>'','placeholder'=>'Other Desigantion','label'=>false,'required'=>true, 'maxlength'=>'30'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>3(c). Mobile Number<span class="important-field">*</span></label>
                            <?=$this->Form->control('mobile',['type'=>'textbox','class'=>'number','placeholder'=>'Mobile Number','label'=>false,'required'=>true, 'maxlength'=>'10'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>3(d). Landline Number <sup class="blue">*</sup></label>
                            <?php
                            $landline_data = explode('-',$CooperativeRegistration->landline);
                            if(!empty($landline_data))
                            {
                                $std = $landline_data[0];
                                $landline = $landline_data[1];
                            }
                           
                            ;
                            ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <?=$this->Form->control('std',['type'=>'textbox', 'class'=>'number','placeholder'=>'STD Code','maxlength'=>'4','label'=>false,'style'=>'width:82px;','value'=>$std])?>
                                </div>
                                <div class="col-sm-9">
                                    <?=$this->Form->control('landline',['type'=>'textbox','class'=>'number','placeholder'=>'Landline Number', 'maxlength'=>'8','label'=>false,'value'=>$landline])?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>3(e). Email ID <sup class="blue">*</sup></label>
                            <?=$this->Form->control('email',['type'=>'textbox','label'=>false,'placeholder'=>'Email ID', 'maxlength'=>'50'])?>
                        </div>
                        <div class="col-sm-4">
                            <label style="margin-top:45px;font-size: 17px;"><span style="color:black;">Note :</span>
                                <sup class="blue">*</sup> Fields are Non Mandatory</label>
                        </div>
                    </div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Sector Details</strong></h4>
                        </div>

                        <div class="col-sm-3">
                            <label>4(a). Sector of Operation<span class="important-field">*</span></label>
                            <?=$this->Form->control('sector_of_operation_type',['options'=>$sector_operations,'id'=>'sector_operation','empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>
                        <!-- <div class="clearfix"></div> -->

                        <div class="col-sm-3">
                            <label>4(b). Primary Activity<span class="important-field">*</span></label>
                            <?=$this->Form->control('sector_of_operation',['options'=>$PrimaryActivities,'id'=>'primary_activity','value'=>'','empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$CooperativeRegistration->sector_of_operation])?>
                        </div>
                        <div class="col-sm-3 sector_of_operation_other"
                            style="display: <?=$CooperativeRegistration->sector_of_operation_type==1 && $CooperativeRegistration->sector_of_operation==8?'block':'none';?>;">
                            <label>4(b). Primary Activity - Other<span class="important-field">*</span></label>
                            <?=$this->Form->control('sector_of_operation_other',['label'=>false,'placeholder'=>'Primary Activity - Other','required'=>true])?>
                        </div>

                        <!-- <div class="col-sm-3">
                            <label>4(f). Secondary Activity<span class="important-field">*</span></label>
                            <?=$this->Form->control('secondary_activity',['options'=>$SecondaryActivities,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-3 secondary_activity_other"
                            style="display: <?=$CooperativeRegistration->secondary_activity==5?'block':'none';?>;">
                            <label>4(g). If Other Activity <span class="important-field">*</span></label>
                            <?=$this->Form->control('secondary_activity_other',['label'=>false,'placeholder'=>'Other Activity','required'=>true])?>
                        </div> -->
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <label>5(a). Tier of the Cooperative Society<span class="important-field">*</span><i
                                    class="fa fa-info-circle"
                                    title="For multiple selection in area operation"></i></label>
                            <?=$this->Form->control('cooperative_society_type_id',['options'=>$CooperativeSocietyTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>
                        <div class="col-sm-3">
                            <label>5(b). Operation Area Location<span class="important-field">*</span></label>
                            <!--,3=>'Both'-->
                            <?=$this->Form->control('operation_area_location',['options'=>[1=>'Urban',2=>'Rural',3=>'Both'],'id'=>'operation_area_location','class'=>'operation_area_location','type' => 'radio','label'=>false,'required'=>true])?>
                        </div>

                        <div class="clearfix"></div>
                        <!-- start urban_ward_box -->
                        <div class="sector_urban_location_section urban_ward_box">
                            <b class="area_operation_urban_div"
                                style="margin-left:15px;text-decoration: underline;display:<?= ($CooperativeRegistration->operation_area_location == 1 || $CooperativeRegistration->operation_area_location == 3) ? 'block' : 'none' ?>;">Urban</b>
                            <div class="clearfix"></div>
                            <div class="col-sm-3 area_operation_urban_div"
                                style="display:<?php echo ($CooperativeRegistration->operation_area_location == 1 || $CooperativeRegistration->operation_area_location == 3) ? 'block' : 'none;' ?>">
                                <label>5(b)(i). Area of Operation Urban <span class="important-field">*</span><i
                                        class="fa fa-info-circle"
                                        title="For multiple selection in area operation"></i></label>
                                <?php echo $this->Form->control('area_of_operation_id_urban',['options'=>$areaOfOperationsUrban,'empty'=>'Select','label'=>false,'class'=>'select2 area_operation_urban','required'=>true,'value'=>$area_of_operation_id_urban])?>
                            </div> 
                            <div class="clearfix"></div>
                            <?php $uw2=1;
                            if(!empty($sectorUrbans))
                            {
                                foreach($sectorUrbans as $key => $sectorurban)
                                {
                                    $uw2=$key+1;
                                    
                            ?>
                            <div class="urban_ward_rows remove" rowclass="<?=$uw2?>">
                                <div class="col-sm-3 urban_location_section1">
                                    <label>5(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'sector_urban_district_code select2','type'=>'select','required'=>true,'value'=>$sectorurban['district_code']])?>
                                </div>
                                <div class="col-sm-3 urban_location_section">
                                    <label>5(c)(i). Category of Urban Local Body <span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.local_body_type_code",['options'=>$sectorurban['localbody_types'],'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'select2 sector_urban_local_body_type_code','required'=>true,'value'=>$sectorurban['local_body_type_code']])?>
                                </div>
                                <div class="col-sm-3 urban_location_section">
                                    <label>5(c)(ii). Urban Local Body <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.local_body_code",['options'=>$sectorurban['localbodies'],'empty'=>'Select','label'=>false,'increment'=>$uw2,'class'=>'select2 sector_urban_local_body_code','required'=>true,'value'=>$sectorurban['local_body_code']])?>
                                </div>

                                <div class="col-sm-3 urban_location_section">
                                    <label>5(c)(iii). Locality or Ward <span class="important-field"></span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.locality_ward_code",['options'=>$sectorurban['sector_wards'],'multiple'=>true,'increment'=>$uw2,'label'=>false,'class'=>'select2 sector_locality_ward_code','required'=>true,'value'=>$sectorurban['locality_ward_code']])?>
                                </div>
                                <div class="col-sm-12 urban_location_section1">
                                    <button type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_urban_ward"><i
                                            class="fa fa-plus-circle"></i></button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_urban_ward"><i
                                            class="fa fa-minus-circle"></i></button>

                                </div>
                            </div>
                            <?php 
                                } 
                            } else {

                            //1->urban,2->Rural
                            // if($CooperativeRegistration->operation_area_location == 2 || $CooperativeRegistration->operation_area_location == 3)
                            // {
                                $uw2 = $uw2+1;
                                $arr_urban = [1,3];
                                // create empty div for urban ?>
                            <div class="urban_ward_rows" rowclass="<?=$uw2?>"
                                style="display:<?php echo (empty($sectors) && in_array($CooperativeRegistration->operation_area_location,$arr_urban))  ? 'block' : 'none';  ?>;">
                                <div class="col-sm-3 urban_location_section1">
                                    <label>5(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'sector_urban_district_code select2','type'=>'select','required'=>true])?>
                                </div>

                                <div class="col-sm-3 urban_location_section1" style="display: block;">
                                    <label>5(c)(ii). Category of Urban Local Body <span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.local_body_type_code",['options'=>$localbody_types,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'select2 sector_urban_local_body_type_code','required'=>true])?>
                                </div>

                                <div class="col-sm-3 urban_location_section1" style="display: block;">
                                    <label>5(c)(iii). Urban Local Body <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.local_body_code",['options'=>'','empty'=>'Select','label'=>false,'increment'=>$uw2,'class'=>'select2 sector_urban_local_body_code','required'=>true])?>
                                </div>
                                <div class="col-sm-3 urban_location_section1" style="display: block;">
                                    <label>5(c)(iv). Locality or Ward <span class="important-field"></span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.locality_ward_code",['options'=>'','multiple'=>true,'increment'=>$uw2,'label'=>false,'class'=>'select2 sector_locality_ward_code','required'=>true])?>
                                </div>
                                <div class="col-sm-12 urban_location_section1" style="display:block;">

                                    <button type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_urban_ward"><i
                                            class="fa fa-plus-circle"></i></button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button style="display: none;" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_urban_ward"><i
                                            class="fa fa-minus-circle"></i></button>

                                </div>
                            </div>
                            <?php }
                            ?>
                        </div>

                        <div class="clearfix"></div>
                        <!-- end urban_ward_box -->
                        <!-- start rural_village_box -->
                        <div class="rural_location_section rural_village_box">

                            <b class="area_operation_rural_div"
                                style="margin-left:15px;text-decoration:underline;display:<?= ($CooperativeRegistration->operation_area_location == 2 || $CooperativeRegistration->operation_area_location == 3) ? 'block' : 'none' ?>;">Rural</b>
                            <div class="clearfix"></div>
                            <div class="col-sm-3 area_operation_rural_div"
                                style="display:<?= ($CooperativeRegistration->operation_area_location == 2 || $CooperativeRegistration->operation_area_location == 3) ? 'block' : 'none;' ?>">
                                <label>5(b)(i). Area of Operation Rural <span class="important-field">*</span><i
                                        class="fa fa-info-circle"
                                        title="For multiple selection in area operation"></i></label>
                                <?=$this->Form->control('area_of_operation_id_rural',['options'=>$areaOfOperationsRural,'empty'=>'Select','label'=>false,'class'=>'select2 area_operation_rural','required'=>true,'value'=>$area_of_operation_id_rural])?>
                            </div>
                            <div class="clearfix"></div>
                            <?php $hr2=1;
                            if(!empty($sectors))
                            {
                            foreach($sectors as $key => $sector)
                            {
                                $hr2=$key+1;
                                // echo '<pre>';
                                // print_r($sector);die;
                            ?>
                            <div class="rural_village_rows" rowclass="<?=$hr2?>">
                                <div class="col-sm-3 rural_location_section1">
                                    <label>5(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_district_code select2','type'=>'select','required'=>true,'value'=>$sector['district_code']])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1">
                                    <label>5(c)(ii). Block <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.block_code",['options'=>$sector['sector_blocks'],'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_block_code select2','type'=>'select','required'=>true,'value'=>$sector['block_code']])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1 <?php echo "sector_".$hr2."_panchayat_code_div" ?>">
                                    <label>5(c)(iii). Gram Panchayat <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.panchayat_code",['options'=>$sector['sector_panchayats'],'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_panchayat_code select2','type'=>'select','required'=>true,'value'=>$sector['panchayat_code']])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1 <?php echo "sector_".$hr2."_village_code_div" ?>" style="display:<?= $sector['gp_village_all'] == 1 ? 'none' : 'block' ?>;">
                                    <label>5(c)(iv). Village <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.village_code",['options'=>$sector['sector_villages'],'multiple'=>true,'increment'=>$hr2,'label'=>false,'class'=>'sector_village_code select2','type'=>'select','required'=>true,'value'=>$sector['village_code']])?>
                                </div>
                                <div class="col-sm-12 rural_location_section1">
                                    <button type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_rural_village"><i
                                            class="fa fa-plus-circle"></i></button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button style="display: none;" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_rural_village"><i
                                            class="fa fa-minus-circle"></i></button>
                                </div>
                            </div>
                            <?php 
                                } 
                            } else{
                            
                            
                            //1->urban,2->Rural
                            // if($CooperativeRegistration->operation_area_location == 1 || $CooperativeRegistration->operation_area_location == 3)
                            // {
                                $hr2 = $hr2+1;
                                $arr_rural = [2,3];
                                // create empty div for rural ?>
                            <div class="rural_village_rows" rowclass="<?=$hr2?>"
                                style="display:<?php echo (empty($sectors) && in_array($CooperativeRegistration->operation_area_location,$arr_rural))  ? 'block' : 'none';  ?>;">

                                <div class="col-sm-3 rural_location_section1">
                                    <label>5(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_district_code select2','type'=>'select','required'=>true])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1">
                                    <label>5(c)(ii). Block <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.block_code",['options'=>$blocks,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_block_code select2','type'=>'select','required'=>true])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1 <?php echo "sector_".$hr2."_panchayat_code_div" ?>">
                                    <label>5(c)(iii). Gram Panchayat <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.panchayat_code",['options'=>$gps,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_panchayat_code select2','type'=>'select','required'=>true])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1 <?php echo "sector_".$hr2."_village_code_div" ?>">
                                    <label>5(c)(iv). Village <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.village_code",['options'=>$villages,'multiple'=>true,'increment'=>$hr2,'label'=>false,'class'=>'sector_village_code select2','type'=>'select','required'=>true])?>
                                </div>
                                <div class="col-sm-12 rural_location_section1">
                                    <button type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_rural_village"><i
                                            class="fa fa-plus-circle"></i></button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button style="display: none;" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_rural_village"><i
                                            class="fa fa-minus-circle"></i></button>

                                </div>
                            </div>

                            <?php }
                            ?>

                        </div>
                        <!-- end rural_village_box -->



                        <!-- <div class="col-sm-4">
                            <label>5(c). Name of Selected Area of Operation<span
                                    class="important-field">*</span></label>
                            <?php //$nameOfOperations = ['1'=>'First','2'=>'Second','3'=>'Third']; ?>
                            <?php //$this->Form->control('name_of_operation_id',['options'=>$nameOfOperations,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div> -->

                        <div class="col-sm-4 area_operation_change ex-div" style="display:block;">
                            <label>5(d). Whether Area of Operation (Village, Panchayat, Block or Mandal or Town,
                                Taluka/District) is adjacent to water body? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_coastal',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','label'=>false,'required'=>true])?>
                        </div>

                        <!-- add 5e type_of_water_body as par anayat sir  -->

                        <div class="col-sm-4 type_of_water_body ex-div"
                            style="display:<?=$CooperativeRegistration->water_body_type_id !='' ?'block':'none';?>;">
                            <label>5(e). Type of water body <span class="important-field">*</span></label>
                            <?php //$levelOfUnions = ['1'=>'Block/Mandal/Taluka Cooperative Union','2'=>'District Cooperative Union','3'=>'Regional Cooperative Union', '4'=> 'State Cooperative Federation','5'=>'National Federation'];
                            //$levelOfUnions = ['1'=>'DCCB','2'=>'STCB','3'=>'Other']; ?>
                            <?= $this->Form->control('water_body_type_id',['options'=>$water_body_type,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>


                        <div class="col-sm-4 area_operation_change2 ex-div"
                            style="display:<?= in_array($CooperativeRegistration->area_of_operation_id,[1,2,6]) ? 'block' : 'block';?>;">
                            <label>5(f). Whether the Cooperative Society is affiliated to Union/Federation <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_affiliated_union_federation',['options'=>['1'=>'Yes','0'=>'No'],'class'=>'is_affiliated','default'=>0,'type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-4 is_affiliated_change"
                            style="display:<?=$CooperativeRegistration->is_affiliated_union_federation==1?'block':'none';?>;">
                            <label>5(g). Level of Affiliated Union/Federation<span
                                    class="important-field">*</span></label>
                            <?= $this->Form->control('affiliated_union_federation_level',['options'=>$federationlevel,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>
                        <div class="col-sm-4 is_affiliated_change 5g"
                            style="display:<?=$CooperativeRegistration->is_affiliated_union_federation == 1 && $CooperativeRegistration->affiliated_union_federation_level !=3?'block':'none';?>;">
                            <label>5(h). Name of Affiliated Union/Federation<span
                                    class="important-field">*</span></label>

                            <?= $this->Form->control('affiliated_union_federation_name',['options'=>$dccb,'type'=>'select' , 'value'=>$CooperativeRegistration->affiliated_union_federation_name,'empty'=>'Select','label'=>false,'class'=>'select2 affiliated_union_federation_name','required'=>true])?>
                        </div>

                        <div class="col-sm-4 is_affiliated_change_other 5g_other"
                            style="display:<?=$CooperativeRegistration->is_affiliated_union_federation == 1 && $CooperativeRegistration->affiliated_union_federation_level == 3?'block':'none';?>;">
                            <label>5(h)(i). Name of Affiliated Union/Federation<span
                                    class="important-field">*</span></label>
                            <?= $this->Form->control('affiliated_union_federation_other',['type'=>'text','label'=>false,'required'=>true])?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-4">
                            <label>6. Present Functional Status<span class="important-field">*</span></label>
                            <?=$this->Form->control('functional_status',['options'=>$PresentFunctionalStatus,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4">
                            <label>7. Number of Members of the Cooperative Society<span class="important-field">*</span></label>
                            <?=$this->Form->control('members_of_society',['type'=>'textbox','class'=>'number','placeholder'=>'Number of Members','label'=>false,'required'=>true, 'maxlength'=>'12'])?>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-4">
                            <label>8(a). Wether Financial Audit has done? <span class="important-field">*</span></label>
                            <?=$this->Form->control("financial_audit",['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'class'=>'f_audit','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-4 f_audit_change"
                            style="display:<?=$CooperativeRegistration->financial_audit==1?'block':'none';?>;">
                            <label>8(b). Year of Latest Audit Completed <span class="important-field">*</span></label>
                            <?=$this->Form->control('audit_complete_year',['options'=>$years,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                            <span>Note: - Data after 8(b) will be based on Latest Audit Year</span>
                        </div>
                        <div class="col-sm-4 f_audit_change"
                            style="display:<?=$CooperativeRegistration->financial_audit==1?'block':'none';?>;">
                            <label>8(c). Category of Audit<span class="important-field">*</span></label>
                            <?php $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score']; ?>
                            <?=$this->Form->control('category_audit',['options'=>$categoryAudit,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-3 rdb">
                            <label>9(a). Whether the Cooperative Society is profit making? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_profit_making',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-3 total_revenue"
                            style="display:<?=$CooperativeRegistration->is_profit_making==1?'block':'none';?>;">
                            <label>9(b). Net Profit of the Cooperative Society<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('annual_turnover',['placeholder'=>'Net Profit of the Cooperative Society','label'=>false,'required'=>true, 'maxlength'=>'200','class'=>'numberadndesimal1'])?>
                        </div>
                        <div class="col-sm-3 rdb">
                            <label>9(c). Whether the dividend is paid by the Cooperative Society? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_dividend_paid',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'dividend','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-3 dividend_change "
                            style="display:<?=$CooperativeRegistration->is_dividend_paid==1?'block':'none';?>;">
                            <label>9(d). Dividend Rate Paid by the Cooperative Society (in %)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('dividend_rate',['turn_over'=>'textbox','placeholder'=>'Dividend Rate Paid','label'=>false,'required'=>true,'class'=>'numberadndesimal', 'maxlength'=>'6'])?>
                        </div>
                    </div>
                    <!-----------------------pacs change------------------------------>
                    <div class="pacs_change"
                        style="display:<?=$CooperativeRegistration->sector_of_operation==1 || $CooperativeRegistration->sector_of_operation==20 || $CooperativeRegistration->sector_of_operation==22 ?'block':'none';?>;">
                        <?php

                            if($CooperativeRegistration->sector_of_operation==1)
                            {
                                $pack_detail='PACS';
                            }
                             if($CooperativeRegistration->sector_of_operation==20)
                            {
                                $pack_detail='FSS';
                            }
                             if($CooperativeRegistration->sector_of_operation==22)
                            {
                                $pack_detail='LAMPS';
                            }
                         ?>
                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong id="primary_activity_details"><?php echo $pack_detail; ?> Details</strong>
                                </h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>10(a). Whether the co-operative society has an office building <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[has_building]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'p_has_building','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['has_building']])?>
                                    </div>
                                    <div class="col-sm-4 p_has_building_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['has_building'] == 1?'block':'none';?>;">
                                        <label>10(b). Type of Office Building<span
                                                class="important-field">*</span></label>
                                        <?php $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government']; ?>
                                        <?=$this->Form->control("pacs[building_type]",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['building_type']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>10(c). Whether the co-operative society has land <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[is_socitey_has_land]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'is_socitey_has_land','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['is_socitey_has_land']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4 available_land"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['is_socitey_has_land'] == 1?'block':'none';?>;">
                                        <label>10(d). Land Available with the Cooperative<span
                                                class="important-field">*</span></label>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                            <th scope="col" width="50%"><?= __('Type of possession') ?>
                                                            </th>
                                                            <th scope="col" width="40%"><?= __('Area (in Acre)') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Owned Land</td>
                                                            <td><?= $this->Form->control("area[land_owned]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_data','value'=>$CooperativeRegistration['cooperative_registrations_lands'][0]['land_owned']])?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Leased Land</td>
                                                            <td><?= $this->Form->control("area[land_leased]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_data','value'=>$CooperativeRegistration['cooperative_registrations_lands'][0]['land_leased']])?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3.</td>
                                                            <td>Land allotted by the Government</td>
                                                            <td><?= $this->Form->control("area[land_allotted]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_data','value'=>$CooperativeRegistration['cooperative_registrations_lands'][0]['land_allotted']])?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td colspan="2" style="float:right;"><b>Total</b></td>
                                                            <td><?= $this->Form->control("area[land_total]",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_total','value'=>$CooperativeRegistration['cooperative_registrations_lands'][0]['land_total']])?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-sm-4 text_land_area" style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['available_land'] == 1?'block':'none';?>;">
                                        <label>10(e). Area(in Acre)<span class="important-field">*</span></label>
                                        <?= $this->Form->control("pacs[land_avilabele_area]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['land_avilabele_area']])?>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </div>
                                </div>
                                <div class="row sv-handling">
                                    <!-- <div class="col-sm-4">
                                        <label>11(a). Credit Facility <span class="important-field">*</span></label>
                                        <?php //$this->Form->control("pacs[credit_facility]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'p_credit_facility','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['credit_facility']])?>
                                    </div> -->
                                    <div class="col-sm-4">
                                        <label>11(a). Total Outstanding Loan extended to Member(In Rs)<span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_total_outstanding_loan]",['placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'50','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['pack_total_outstanding_loan']])?>
                                    </div>
                                    <div class="col-sm-4 p_credit_facility_change">
                                        <label>11(b). Revenue (Other than interest) from Non-Credit Activities (in
                                            Rs)<span class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_revenue_non_credit]",['placeholder'=>'Revenue (Other than interest) from Non-Credit Activities (in Rs)','label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'50','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['pack_revenue_non_credit']])?>
                                    </div>
                                    <div class="col-sm-4 ex-div">
                                        <label>11(c). Fertilizer Distribution <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[fertilizer_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_fertilizer_distribution','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['fertilizer_distribution']])?>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <span class="loan_warning"><?php echo $CooperativeRegistration['functional_status'] == 1 ? '<b style="color:red">Warning!</b> It should be greater then 0 if Present Functional Status is functional.' : '';  ?></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(d). Pesticide Distribution <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pesticide_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_pesticide_distribution','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['pesticide_distribution']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(e). Seed Distribution <span class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[seed_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_seed_distribution','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['seed_distribution']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(f). Fair Price Shop License <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[fair_price]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_fair_price','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['fair_price']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(g). Procurement of Foodgrains <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[is_foodgrains]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_foodgrains','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['is_foodgrains']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(h). Hiring of Agricultural Implements <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[agricultural_implements]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_agricultural_implements','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['agricultural_implements']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(i). Dry Storage Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[dry_storage]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_dry_storage','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['dry_storage']])?>
                                    </div>
                                    <div class="col-sm-4 p_dry_storage_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['dry_storage'] == 1?'block':'none';?>;">
                                        <label>11(j). Capacity of Dry Storage Infrastructure (In MT) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[dry_storage_capicity]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['dry_storage_capicity']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(k). Cold Storage Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[cold_storage]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_cold_storage','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['cold_storage']])?>
                                    </div>
                                    <div class="col-sm-4 p_cold_storage_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['cold_storage'] == 1?'block':'none';?>;">
                                        <label>11(l). Capacity of Cold Storage Infrastructure (In MT) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[cold_storage_capicity]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['cold_storage_capicity']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(m). Milk Collection Unit Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[milk_unit]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_milk_unit','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['milk_unit']])?>
                                    </div>
                                    <div class="col-sm-4 p_milk_unit_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['milk_unit'] == 1?'block':'none';?>;">
                                        <label>11(n). Annual Milk Collection by Cooperative Society (In Liters) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[milk_capicity_unit]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['milk_capicity_unit']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4 ex-div">
                                        <label>11(o). Wheather Cooperative Society is involved in fish catch <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_involved_fish_catch]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate pack_involved_fish_catch','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['pack_involved_fish_catch']])?>
                                    </div>
                                    <div class="col-sm-4 pack_annual_fish_catch" style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['pack_involved_fish_catch'] == 1?'block':'none';?>;">
                                        <label>11(p). Annual Fish Catch by Cooperative Society (In kg) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_annual_fish_catch]",['label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['pack_annual_fish_catch']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4 ex-div">
                                        <label>11(q). Food Processing Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[food_processing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate food_processing','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['food_processing']])?>
                                    </div>
                                    <div class="col-sm-4 food_processing_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_pacs'][0]['food_processing'] == 1?'block':'none';?>;">
                                        <label>11(r). Type of Food Processing Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[food_processing_type]",['label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['food_processing_type']])?>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>11(s). Other Facilities Provided (Please Specify) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[other_facility]",['label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_pacs'][0]['other_facility']])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-----------------------dairy change start------------------------------>
                    <div class="dairy_change"
                        style="display:<?=$CooperativeRegistration->sector_of_operation==9?'block':'none';?>;">
                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Dairy Details</strong></h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>10. Annual Total Milk Collection (In Liters) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[milk_collection]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['milk_collection']])?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span class="loan_warning"><?php echo $CooperativeRegistration['functional_status'] == 1 ? '<b style="color:red">Warning!</b> It should be greater then 0 if Present Functional Status is functional.' : '';  ?></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2 class="servce-hading">Services/Facilities Provided to the Members</h2>
                                    </div>
                                </div>

                                <div class="row sv-handling">
                                    <div class="col-sm-4">
                                        <label>11(a). Credit Facility <span class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[credit_facility]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_credit_facility','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['credit_facility']])?>
                                    </div>
                                    <div class="col-sm-4 d_credit_facility_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_dairy'][0]['credit_facility'] == 1?'block':'none';?>;">
                                        <label>11(b). Total Credit Provided in the Financial Year (In Rs.) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[credit_provided]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['credit_provided']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(c). Milk Collection Unit Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[milk_collection_unit]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'milk_unit','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['milk_collection_unit']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4 milk_unit_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_dairy'][0]['milk_collection_unit'] == 1?'block':'none';?>;">
                                        <label>11(d). Capacity of Milk Collection Unit (In Liters) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[milk_collection_capicity]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['milk_collection_capicity']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(e). Transportation of Milk <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[transport_milk]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'p_transport_milk','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['transport_milk']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(f). Bulk Milk Cooling (BMC) Unit Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[bulk_milk_unit]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_bulk_milk','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['bulk_milk_unit']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(g). Milk Testing Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[milk_testing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_milk_testing','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['milk_testing']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(h). Pasteurization and Processing Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[processing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_processing','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['processing']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(i). Other Facilities Provided (Please Specify) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("dairy[other_facility]",['label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_dairy'][0]['other_facility']])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----------------------dairy change close------------------------------>
                    <!-----------------------fishery change start------------------------------>
                    <div class="fishery_change"
                        style="display:<?=$CooperativeRegistration->sector_of_operation==10?'block':'none';?>;">
                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Fishery Details</strong></h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>10. Annual Total Fish Catch (in Tonne)<span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[annual_fish_catch]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['annual_fish_catch']])?>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-sm-12">
                                            <span class="loan_warning"><?php echo $CooperativeRegistration['functional_status'] == 1 ? '<b style="color:red">Warning!</b> It should be greater then 0 if Present Functional Status is functional.' : '';  ?></span>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2 class="servce-hading">Services/Facilities Provided to the Members</h2>
                                    </div>
                                </div>
                                <div class="row sv-handling">
                                    <div class="col-sm-4">
                                        <label>11(a). Credit Facility <span class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[credit_facility]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_credit_facility','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['credit_facility']])?>
                                    </div>
                                    <div class="col-sm-4 f_credit_facility_change"
                                        style="display:<?=$CooperativeRegistration['cooperative_registration_fishery'][0]['credit_facility'] == 1?'block':'none';?>;">
                                        <label>11(b). Total Credit Provided in the Financial Year (In Rs.) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[total_credit_provided]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10','value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['total_credit_provided']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(c). Distribution of Fuel <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[fuel_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_distribution','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['fuel_distribution']])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(d). Marketing <span class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[marketing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_marketing','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['marketing']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(e). Cold Storage <span class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[cold_storage]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_cold_storage','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['cold_storage']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(f). Transportation <span class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[transportation]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_transportation','type'=>'radio','label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['transportation']])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(g). Other Facilities Provided (Please Specify) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("fishery[other_facility]",['label'=>false,'required'=>true,'value'=>$CooperativeRegistration['cooperative_registration_fishery'][0]['other_facility']])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----------------------fishery change close------------------------------>

                </div>
                <div class="box-footer text-center">
                    <?php
                    if($CooperativeRegistration->is_draft == 1){
                        echo $this->Form->button(__('Save As Draft'),['class' => 'btn btn-primary submit mx-1','formnovalidate'=>'formnovalidate','name'=>'is_draft','value'=>'1']);
                    } 
                        echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary submit mx-1','name'=>'is_draft','value'=>'0']);
                        
                        echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); 
                    ?>
                </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>


    </div>
    </div>
    </div>
</section>
<?php $this->append('bottom-script');?>
<!-- start rural_village_box -->
<script type="text/javascript">
function checkPlusMinusButtonUrban() {

    var tr_row_main = $('.urban_ward_rows');
    var main = 1;
    $('.add_row_urban_ward').each(function() {
        if (tr_row_main.length == main) {
            $(this).show();
            $(this).parent('div').find('button.remove_row_urban_ward').hide();
        } else {
            $(this).hide();
            $(this).parent('div').find('button.remove_row_urban_ward').show();
        }
        main++;
    });
}
$(function() {

    checkPlusMinusButtonUrban();
    //Component add row section
    var max_urban_ward = 1000;
    var count_div_urban_ward = parseFloat($(".urban_ward_box").find(".urban_ward_rows").length);
    $('.urban_ward_box').on('click', '.add_row_urban_ward', function(e) {
        e.preventDefault();
        if (count_div_urban_ward < max_urban_ward) {
            count_div_urban_ward++;
            var uw2 = parseFloat($(".urban_ward_box .urban_ward_rows:last").attr("rowclass")) + 1;
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'urban-ward-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    uw2: uw2,
                    state_code: $('#state-code').val()
                },
                success: function(response) {
                    $(".urban_ward_box").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonUrban();
                },
            });
        }
    });

    $(".urban_ward_box").on('click', '.remove_row_urban_ward', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_urban_ward--;
        checkPlusMinusButtonUrban();
    });

    //Component add row section


});

//-----------rural-----------------

function checkPlusMinusButtonRural() {

    var tr_row_main = $('.rural_village_rows');
    var m = 1;
    $('.add_row_rural_village').each(function() {
        if (tr_row_main.length == m) {
            //$(this).hide();
            $(this).parent('div').find('button.add_row_rural_village').show();
            $(this).parent('div').find('button.remove_row_rural_village').hide();
            console.log('first');
        } else {
            //$(this).show();
            $(this).parent('div').find('button.remove_row_rural_village').show();
            $(this).parent('div').find('button.add_row_rural_village').hide();

            console.log('second');
        }
        m++;
    });
}
$(function() {

    checkPlusMinusButtonRural();
    //Component add row section
    var max_rural_village = 1000;
    var count_div_rural_village = parseFloat($(".rural_village_box").find(".rural_village_rows").length);
    $('.rural_village_box').on('click', '.add_row_rural_village', function(e) {
        e.preventDefault();
        if (count_div_rural_village < max_rural_village) {
            count_div_rural_village++;
            var hr2 = parseFloat($(".rural_village_box .rural_village_rows:last").attr("rowclass")) + 1;
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'rural-village-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    hr2: hr2,
                    state_code: $('#state-code').val()
                },
                success: function(response) {
                    $(".rural_village_box").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonRural();
                },
            });
        }
    });

    $(".rural_village_box").on('click', '.remove_row_rural_village', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_rural_village--;
        checkPlusMinusButtonRural();
    });

    //Component add row section


});
</script>
<!-- end rural_village_box -->
<script>
(function($) {
    $(document).ready(function() {

        $('body').on('click', '.submit', function(e) {
            if ($(this).val() == '0') {
                e.preventDefault();

                /*
                if($('#pacs-pack-total-outstanding-loan').rules().min)
                {
                    $('#pacs-pack-total-outstanding-loan,#dairy-milk-collection,#fishery-annual-fish-catch').rules( 'remove', 'min');
                }

                if($('#dairy-milk-collection').rules().min)
                {
                    $('#dairy-milk-collection').rules( 'remove', 'min');
                }

                if($('#fishery-annual-fish-catch').rules().min)
                {
                    $('#fishery-annual-fish-catch').rules( 'remove', 'min');
                }*/

                // if($('#pacs-pack-revenue-non-credit').rules().min)
                // {
                //     $('#pacs-pack-revenue-non-credit').rules( 'remove', 'min');
                // }

                /*
                //3 point
                if($('#functional-status').val() == 1)
                {   
                    //pacs
                    if($('#primary_activity').val() == 1 || $('#primary_activity').val() == 20 || $('#primary_activity').val() == 22)
                    {
                        $("#pacs-pack-total-outstanding-loan").rules("add", {
                            min:1
                        });
                    }

                    //dairy
                    if($('#primary_activity').val() == 9)
                    {
                        $("#dairy-milk-collection").rules("add", {
                            min:1
                        });
                    }

                    //fishery
                    if($('#primary_activity').val() == 10)
                    {
                        $("#fishery-annual-fish-catch").rules("add", {
                            min:1
                        });
                    }
                    
                }*/

                //5 point
                // $(".revenue_validate:checked").each(function() {
                    
                //     var flag = $(this).val();
                //     if(flag == 1)
                //     {
                //         $("#pacs-pack-revenue-non-credit").rules("add", {
                //             min:1
                //         });
                //     }
                // });
                if (confirm('Are you sure to submit?')) {
                    $("#CooperativeRegistrationForm").valid();
                    $('#CooperativeRegistrationForm').append(
                        '<input type="hidden" name="is_draft" value="0" />');
                    $("#CooperativeRegistrationForm").submit();
                }

            } else {
                $('#loader').show();
            }
        });

        $(".land_data").bind("change paste keyup", function() {

            var first = '';
            var second = '';
            var third = '';
            var fourth = '';


            first = $('#area-land-owned').val();



            second = $('#area-land-leased').val();



            third = $('#answer_id_13').val();



            fourth = $('#area-land-allotted').val();

            var arr = [first, second, third, fourth].filter(cV => cV != "");
            console.log(arr);

            sum = 0;
            count = arr.length;
            $.each(arr, function() {
                sum += parseFloat(this) || 0;
            });
            tval = 0;


            $('.land_total').val(sum);

        });

        //------------------------------------






        $(".land_data").blur(function() {
            var total = 0;
            $(".land_data").each(function() {
                var land = $(".land_data").val();
                console.log(land);


                total += land;
            });
            $(".total").text(total); // Update the total
        });

        // $('input').removeAttr('required');
        // $('select').removeAttr('required');
        // $('textarea').removeAttr('required');

        //############## validation 3 digit and two digit number input #####


        $('body').on('keyup', '.numberadndesimal', function() {
            var beforeDecimal = 3;
            var afterDecimal = 2;

            var id = $(this).attr('id');

            $('#' + id).on('input', function() {
                this.value = this.value
                    .replace(/[^\d.]/g, '')
                    .replace(new RegExp("(^[\\d]{" + beforeDecimal + "})[\\d]", "g"), '$1')
                    .replace(/(\..*)\./g, '$1')
                    .replace(new RegExp("(\\.[\\d]{" + afterDecimal + "}).", "g"), '$1');
            });

        });

        $('body').on('keyup', '.numberadndesimal1', function() {
            var beforeDecimal = 50;
            var afterDecimal = 5;

            var id = $(this).attr('id');

            $('#' + id).on('input', function() {
                this.value = this.value
                    .replace(/[^\d.]/g, '')
                    .replace(new RegExp("(^[\\d]{" + beforeDecimal + "})[\\d]", "g"), '$1')
                    .replace(/(\..*)\./g, '$1')
                    .replace(new RegExp("(\\.[\\d]{" + afterDecimal + "}).", "g"), '$1');
            });

        });




        //######end #######################################################


        //--------------pacs-------------------------
        $('body').on('change', '.p_has_building', function() {
            if ($(this).val() == 1) {
                $('.p_has_building_change').show();
            } else {
                $('.p_has_building_change').hide();
            }
        });


        $('body').on('change', '.p_credit_facility', function() {
            if ($(this).val() == 1) {
                $('.p_credit_facility_change').show();
            } else {
                $('.p_credit_facility_change').hide();

                $('#pacs-total-credit').val('');
                $("#pacs-fertilizer-distribution-0").attr('checked', true).trigger('click');
                $("#pacs-pesticide-distribution-0").attr('checked', true).trigger('click');
                $("#pacs-seed-distribution-0").attr('checked', true).trigger('click');
                $("#pacs-fair-price-0").attr('checked', true).trigger('click');
                $("#pacs-is-foodgrains-0").attr('checked', true).trigger('click');
                $("#pacs-agricultural-implements-0").attr('checked', true).trigger('click');

                $("#pacs-dry-storage-0").attr('checked', true).trigger('click');
            }
        });


        $('body').on('change', '.p_dry_storage', function() {
            if ($(this).val() == 1) {
                $('.p_dry_storage_change').show();
            } else {
                $('.p_dry_storage_change').hide();
                $('#pacs-dry-storage-capicity').val('');
                $("#pacs-cold-storage-0").attr('checked', true).trigger('click');
            }
        });


        $('body').on('change', '.p_cold_storage', function() {
            if ($(this).val() == 1) {
                $('.p_cold_storage_change').show();
            } else {
                $('.p_cold_storage_change').hide();
                $('#pacs-cold-storage-capicity').val('');
                $("#pacs-milk-unit-0").attr('checked', true).trigger('click');
            }
        });


        $('body').on('change', '.p_milk_unit', function() {
            if ($(this).val() == 1) {
                $('.p_milk_unit_change').show();
            } else {
                $('.p_milk_unit_change').hide();
                $('#pacs-milk-capicity-unit').val('');
                $("#pacs-food-processing-0").attr('checked', true).trigger('click');
            }
        });

        $('body').on('change', '.food_processing', function() {
            if ($(this).val() == 1) {
                $('.food_processing_change').show();
            } else {
                $('.food_processing_change').hide();
                $('#pacs-food-processing-type').val('');
                //$("#milk-unit-0").attr('checked', true).trigger('click');
            }
        });

        //--------------pacs-------------------------

        //--------------dairy-------------------------

        $('body').on('change', '.d_credit_facility', function() {
            if ($(this).val() == 1) {
                $('.d_credit_facility_change').show();
            } else {
                $('.d_credit_facility_change').hide();
                $("#milk-unit-0").attr('checked', true).trigger('click');
            }
        });

        $('body').on('change', '.milk_unit', function() {
            if ($(this).val() == 1) {
                $('.milk_unit_change').show();
            } else {
                $('.milk_unit_change').hide();
            }
        });
        //--------------dairy---------------------------
        //---------------------fishrey-------------------
        $('body').on('change', '.f_credit_facility', function() {
            if ($(this).val() == 1) {
                $('.f_credit_facility_change').show();
            } else {
                $('.f_credit_facility_change').hide();
            }
        });

        //---------------------fishrey--------------------

        //---------latest changes----------------------

        //on 5(b) change
        $('body').on('change', '.area_operation', function() {
            var area_operation = $(this).val();
            //1->village,2->panchayat,6->block
            if (area_operation == 1 || area_operation == 2 || area_operation == 6 ||
                area_operation == 3) {
                $('.area_operation_change').show();
                //$('#sector-state-code').val('').trigger('change');

                $("#is-affiliated-union-federation-0").attr('checked', true).trigger('click');
                $('.5g,5g_other').hide();
                //if block level
                if (area_operation == 6) {
                    $('.sector_district').show();
                    $('#sector-district-code').val('');

                    $('.sector_block').show();
                    $('#sector-block-code').html('');

                    $('.sector_panchayat').hide();
                    $('#sector-panchayat-code').val('');

                    $('.sector_village').hide();
                    $('#sector-village-code').val('');

                    $("#sector-district-code").select2({
                        multiple: false
                    });

                    $("#sector-panchayat-code").select2({
                        multiple: false
                    });

                    $("#sector-village-code").select2({
                        multiple: false
                    });

                    $("#sector-block-code").select2({
                        multiple: true
                    });


                } else if (area_operation == 2) {
                    //if panchayat level
                    $('.sector_district').show();
                    $('#sector-district-code').val('');

                    $('.sector_block').show();
                    $('#sector-block-code').val('');


                    $('.sector_panchayat').show();
                    $('#sector-panchayat-code').val('');
                    $('#sector-panchayat-code').html('');

                    $('.sector_village').hide();
                    $('#sector-village-code').val('');

                    $("#sector-district-code").select2({
                        multiple: false
                    });

                    $("#sector-block-code").select2({
                        multiple: false
                    });

                    $("#sector-village-code").select2({
                        multiple: false
                    });


                    $("#sector-panchayat-code").select2({
                        multiple: true
                    });

                } else if (area_operation == 1) {
                    //if village level
                    $('.sector_district').show();
                    $('#sector-district-code').val('');

                    $('.sector_block').show();
                    $('#sector-block-code').val('');


                    $('.sector_panchayat').show();
                    $('#sector-panchayat-code').val('');


                    $('.sector_village').show();
                    $('#sector-village-code').val('');
                    $('#sector-village-code').html('');

                    $("#sector-district-code").select2({
                        multiple: false
                    });

                    $("#sector-block-code").select2({
                        multiple: false
                    });

                    $("#sector-panchayat-code").select2({
                        multiple: false
                    });


                    $("#sector-village-code").select2({
                        multiple: true
                    });
                } else if (area_operation == 3) {

                    $('.sector_district').show();
                    $('#sector-district-code').val('');
                    $('#sector-district-code').html('');

                    $('.sector_block').hide();
                    $('#sector-block-code').html('');

                    $('.sector_panchayat').hide();
                    $('#sector-panchayat-code').html('');

                    $('.sector_village').hide();
                    $('#sector-village-code').html('');


                    $("#sector-panchayat-code").select2({
                        multiple: false
                    });

                    $("#sector-village-code").select2({
                        multiple: false
                    });

                    $("#sector-block-code").select2({
                        multiple: false
                    });

                    $("#sector-district-code").select2({
                        multiple: true
                    });

                }

            } else {

                $('.sector_district').hide();
                $('#sector-district-code').val('');

                $('.sector_block').hide();
                $('#sector-block-code').val('');

                $('.sector_panchayat').hide();
                $('#sector-panchayat-code').val('');

                $('.sector_village').hide();
                $('#sector-village-code').val('');

                $('.area_operation_change').hide();
                $("#is-affiliated-union-federation-0").attr('checked', true).trigger('click');
            }

        });

        //---=====sector state to vilage dropdown======---------
        //on 5(b)(i) state  change
        /*$('body').on('change', '#sector-state-code', function() {

            //1->village,2->panchayat,6->block
            if ($('.area_operation').val() == 1 || $('.area_operation').val() == 2 || $(
                    '.area_operation').val() == 6 || $('.area_operation').val() == 3) {
                $('#sector-block-code').val('');
                $('#sector-panchayat-code').val('');
                $('#sector-village-code').val('');
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        state_code: $(this).val()
                    },
                    success: function(response) {
                        $("#sector-district-code").html(response);
                    },
                });
            }


        });*/

        //on change district if urban
        $('body').on('change', '.sector_urban_local_body_type_code', function(e) {
            //e.preventDefault();
            var increment = $(this).attr('increment');

            $('#sector-urban-' + increment + '-local-body-code').val('');
            $('#sector-urban-' + increment + '-locality-ward-code').val('');

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-urban-local-body'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    urban_local_body_type_code: $(this).val(),
                    state_code: $('#state-code option:selected').val()
                },
                success: function(response) {
                    $('#sector-urban-' + increment + '-local-body-code').html(response);
                },
            });

        });

        $('#CooperativeRegistrationForm').on('change', '.sector_urban_local_body_code', function(e) {
            var increment = $(this).attr('increment');
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-locality-ward'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    urban_local_body_code: $(this).val()
                },
                success: function(response) {
                    $('#sector-urban-' + increment + '-locality-ward-code').html(
                        response);
                },
            });
        });


        //on change district if rural
        $('body').on('change', '.sector_district_code', function(e) {
            var increment = $(this).attr('increment');

            $('#sector-' + increment + '-block-code').val('');
            $('#sector-' + increment + '-panchayat-code').val('');
            $('#sector-' + increment + '-village-code').val('');
            e.preventDefault();

            //1->village,2->panchayat,6->block
            //if ($('.area_operation').val() == 1 || $('.area_operation').val() == 2 || $('.area_operation').val() == 6) {
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-blocks'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    district_code: $(this).val()
                },
                success: function(response) {
                    $('#sector-' + increment + '-block-code').html(response);
                },
            });
            //}

            // if($('.area_operation').val() == 6)
            // {
            //     $("#sector-block-code").select2({
            //         multiple: true
            //     });
            // }




        });

        $('body').on('change', '.sector_block_code', function(e) {
            e.preventDefault();
            var increment = $(this).attr('increment');

            $('.sector_' + increment + '_panchayat_code_div').show();
            $('#sector-' + increment + '-panchayat-code').select2().attr('required');

            $('.sector_' + increment + '_village_code_div').show();
            $('#sector-' + increment + '-village-code').select2().attr('required');           
            $('#sector-' + increment + '-village-code').select2().html('');

            //1->village,2->panchayat,6->block
            // if ($('.area_operation').val() == 1 || $('.area_operation').val() == 2) {
            //     $('#sector-panchayat-code').val('').trigger('change');
            //     $('#sector-village-code').val('').trigger('change');

            if($(this).val() == '-1')
            {
                console.log('sector-' + increment + '-panchayat-code');
                $('.sector_' + increment + '_panchayat_code_div').hide();
                $('#sector-' + increment + '-panchayat-code').select2().removeAttr('required');

                $('.sector_' + increment + '_village_code_div').hide();
                $('#sector-' + increment + '-village-code').select2().removeAttr('required');
            } else {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-gp'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        block_code: $(this).val()
                    },
                    success: function(response) {
                        $('#sector-' + increment + '-panchayat-code').html(response);
                        $("<option value='-1'><b>Select All</b></option>").insertAfter("#sector-" + increment + "-panchayat-code option:first");
                    },
                });
            }
        });

        $('body').on('change', '.sector_panchayat_code', function(e) {
            e.preventDefault();
            var increment = $(this).attr('increment');

            $('.sector_' + increment + '_village_code_div').show();
            $('#sector-' + increment + '-village-code').select2().attr('required'); 

            if($(this).val() == '-1')
            {
                $('.sector_' + increment + '_village_code_div').hide();
                $('#sector-' + increment + '-village-code').select2().removeAttr('required');
            } else {

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-villages'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        gp_code: $(this).val()
                    },
                    success: function(response) {
                        $('#sector-' + increment + '-village-code').html(response);
                        //$("<option value='-1'><b>Select All</b></option>").insertAfter("#sector-" + increment + "-village-code option:first");
                        $("<option value='-1'><b>Select All</b></option>").insertAfter("#sector-" + increment + "-village-code option:first");
                        $('#sector-' + increment + '-village-code option[value=""]').remove();
                    },
                });
            }
        });

        $('body').on('change', '.sector_village_code', function(e) {
            e.preventDefault();
            //console.log($(this).val());
            var increment = $(this).attr('increment');

            console.log($('#sector-' + increment + '-village-code > option:selected').length);

            $('#sector-' + increment + '-village-code > option:selected').each(function() {
                if($(this).val() == -1 && $('#sector-' + increment + '-village-code > option:selected').length > 1)
                {
                    //console.log('#sector-' + increment + '-village-code');
                    $('#sector-' + increment + '-village-code').val('-1'); // Select the option with a value of '1'
                    $('#sector-' + increment + '-village-code ').trigger('change'); // Notify any JS components that the value chan
                    return false; // breaks

                }
            });
            
        });
        //---=====sector state to vilage dropdown======---------

        //on 5(e) change
        $('body').on('change', '.is_affiliated', function() {
            if ($(this).val() == 1) {
                $('.is_affiliated_change').show();
            } else {
                $('.is_affiliated_change').hide();
                $('#affiliated-union-federation-level').val('').trigger('change');
                $('#affiliated-union-federation-name').val('').trigger('change');
            }

        });

        // on 9(c) change
        $('body').on('change', '.dividend', function() {
            console.log($(this).val());
            if ($(this).val() == 1) {
                $('.dividend_change').show();
            } else {
                $('.dividend_change').hide();
            }

        });

        //-----------------------------------------

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
        //$('.select2').select2();

        $('body').on('change', '#cooperative-society-type-id', function(e) {
            var a2 = $(this).val();
            $('#multi-states').val('').trigger('change');
            if (a2 == 5) {
                $('.b2').show();
            } else {
                $('.b2').hide();
            }

        });

        $('body').on('change', '#sector_operation', function(e) {

            e.preventDefault();
            $('.pacs_change').hide();
            $('.dairy_change').hide();
            $('.fishery_change').hide();

            $('#sector-of-operation-other').val('');
            $('.sector_of_operation_other').hide();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-primary-activity'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    sector_operation: $(this).val()
                },
                success: function(response) {
                    $("#primary_activity").html(response);
                },
            });

            /*
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-secondary-activity'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    sector_operation: $(this).val()
                },
                success: function(response1) {
                    $("#secondary-activity").html(response1);
                },
            });*/


        });

        $('body').on('keypress', '.numbers', function(event) {

            //$('.numbers').on('keypress',function(event){
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                ((event.which < 48 || event.which > 57) &&
                    (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }

            var text = $(this).val();

            if ((text.indexOf('.') != -1) &&
                (text.substring(text.indexOf('.')).length > 2) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 2)) {
                event.preventDefault();
            }
        });

        $('body').on('keypress', '.pincode', function(event) {

            //$('.numbers').on('keypress',function(event){
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                ((event.which < 48 || event.which > 57) &&
                    (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }

            var text = $(this).val();

            if ((text.length == 6)) {
                event.preventDefault();
            }
        });

        //start add ajax for add row of contact number

        var counthr2 = 1;
        var max_contact_number_list = 150;
        var contact_number_list = $("#contact_number_list");
        var add_contact_number_list = $(".add_contact_number_list");
        $(add_contact_number_list).on('click', function(e) {
            e.preventDefault();
            if (counthr2 < max_contact_number_list) {
                counthr2++;
                var hr2 = parseInt($("tbody#contact_number_list tr:last").attr("rowclass")) + 1;
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(["action"=>'add-row-contact-number'])?>',
                    data: {
                        hr2: hr2,
                    },
                    success: function(response) {
                        $(contact_number_list).append(response);
                    },
                });
            }
        });
        $(contact_number_list).on('click', '.closetd', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
            counthr2--;
        });

        $(contact_number_list).on('click', '.remove_row', function(e) {
            var transaction_row_id = $(this).val();
            if (confirm("Are you sure want to remove this row.?")) {
                var parent_tr = $(this).parents('tr');
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: baseUrl + '/forms-eight/add-row-section-transactions',
                    data: {
                        transaction_row_id: transaction_row_id,
                        type: "Delete Row"
                    },
                    success: function(response) {
                        if (response == "true") {
                            $(parent_tr).remove();
                        }
                    },
                });
            }
        });
        //end add ajax for add row of contact number


        //start add ajax for add row of email

        var counthr2 = 1;
        var max_email_list = 150;
        var email_list = $("#email_list");
        var add_email_list = $(".add_email_list");
        $(add_email_list).on('click', function(e) {
            e.preventDefault();
            if (counthr2 < max_email_list) {
                counthr2++;
                var hr2 = parseInt($("tbody#email_list tr:last").attr("rowclass")) + 1;
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(["action"=>'add-row-email'])?>',
                    data: {
                        hr2: hr2,
                    },
                    success: function(response) {
                        $(email_list).append(response);
                    },
                });
            }
        });
        $(email_list).on('click', '.closetd', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
            counthr2--;
        });

        $(contact_number_list).on('click', '.remove_row', function(e) {
            var transaction_row_id = $(this).val();
            if (confirm("Are you sure want to remove this row.?")) {
                var parent_tr = $(this).parents('tr');
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: baseUrl + '/forms-eight/add-row-section-transactions',
                    data: {
                        transaction_row_id: transaction_row_id,
                        type: "Delete Row"
                    },
                    success: function(response) {
                        if (response == "true") {
                            $(parent_tr).remove();
                        }
                    },
                });
            }
        });
        //end add ajax for add row of email



        $('.select2').select2();

        if (typeof $.validator !== "undefined") {
            $("#CooperativeRegistrationForm").validate({
                rules: {
                    email: {
                        email: true,
                        //regx: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i,
                    },
                    std: {
                        minlength: 4,
                        maxlength: 4,
                        digits: true,
                    },
                    landline: {
                        minlength: 6,
                        maxlength: 8,
                        digits: true,
                    },
                    mobile: {
                        minlength: 10,
                        maxlength: 10,
                        digits: true,
                    }

                },
                messages: {
                    email: {
                        required: "Please enter email",
                    },
                    landline: {
                        required: "Please enter landline number",
                    },
                    mobile: {
                        required: "Please enter mobile number",
                    }
                },
                submitHandler: function(form) {
                    // $('#register').submit();
                    $('#loader').show();
                    return true;
                }
            });
        }

        $(".before_date").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            maxDate: new Date(),
        });

        //Location Details of Registration Office
        $('.location_of_head_quarter').on('change', function() {
            var location_of_head_quarter = $(this).val();

            $('#state-code').val('').trigger('change');


            if (location_of_head_quarter == 1) {
                //urban
                $('.urban_location_section').each(function() {
                    $(this).show();
                });
                $('.rural_location_section').each(function() {
                    $(this).hide();
                });


            } else if (location_of_head_quarter == 2) {
                //Rural
                $('.urban_location_section').each(function() {
                    $(this).hide();
                });
                $('.rural_location_section').each(function() {
                    $(this).show();
                });
            }

            console.log(location_of_head_quarter);

        });

        /*
             $('select[name="sector_of_operation_type"]').on('change',function(){

                var sector_of_operation_type=$(this).val();
                $('.sector_of_operation_noncredit').hide();
                $('.sector_of_operation_credit').hide();

                if(sector_of_operation_type==1){
                  $('.sector_of_operation_credit').show();
                  $('.sector_of_operation_noncredit').hide();
                }else if(sector_of_operation_type==2){
                  $('.sector_of_operation_noncredit').show();
                  $('.sector_of_operation_credit').hide();
                }
                
                $('.select2').select2();
             });  */

        /*$("#location-of-head-quarter-1").on('change', function(e) {
            //$('body').on('change', 'select[name="location_of_head_quarter"]', function(e) {

            var location_of_head_quarter = $(this).val();
            $('#state-code').val('').trigger('change');

            $('.urban_location_section').each(function() {
                $(this).hide();
            });
            $('.rural_location_section').each(function() {
                $(this).hide();
            });

            if (location_of_head_quarter == 1) {
                $('.urban_location_section').each(function() {
                    $(this).show();
                });
                $('.rural_location_section').each(function() {
                    $(this).hide();
                });
            } else if (location_of_head_quarter == 2) {
                $('.urban_location_section').each(function() {
                    $(this).hide();
                });
                $('.rural_location_section').each(function() {
                    $(this).show();
                });
            }
            $('#state-code').val('');

        });

        $("#location-of-head-quarter-2").on('change', function(e) {
            //$('body').on('change', 'select[name="location_of_head_quarter"]', function(e) {

            var location_of_head_quarter = $(this).val();
            $('#state-code').val('').trigger('change');

            $('.urban_location_section').each(function() {
                $(this).hide();
            });
            $('.rural_location_section').each(function() {
                $(this).hide();
            });

            if (location_of_head_quarter == 1) {
                $('.urban_location_section').each(function() {
                    $(this).show();
                });
                $('.rural_location_section').each(function() {
                    $(this).hide();
                });
            } else if (location_of_head_quarter == 2) {
                $('.urban_location_section').each(function() {
                    $(this).hide();
                });
                $('.rural_location_section').each(function() {
                    $(this).show();
                });
            }
            $('#state-code').val('');

        });*/

        $('#CooperativeRegistrationForm').on('change', '#primary_activity', function() {
            var sector_of_operation_type = $(this).val();
            if (sector_of_operation_type == 8) {
                $('.sector_of_operation_other').show();
            } else {
                $('.sector_of_operation_other').hide();
                $('#sector-of-operation-other').val('');
            }


            if (sector_of_operation_type == 1 || sector_of_operation_type == 20 ||
                sector_of_operation_type == 22) {


                if (sector_of_operation_type == 20) {

                    $('#primary_activity_details').text('FSS Detail');
                }

                if (sector_of_operation_type == 22) {

                    $('#primary_activity_details').text('LAMPS Detail');
                }

                if (sector_of_operation_type == 1) {

                    $('#primary_activity_details').text('Pacs Detail');
                }

                $('.pacs_change').show();
                $('.dairy_change').hide();
                $('.fishery_change').hide();

            } else if (sector_of_operation_type == 9) {
                $('.dairy_change').show();
                $('.pacs_change').hide();
                $('.fishery_change').hide();

            } else if (sector_of_operation_type == 10) {

                $('.fishery_change').show();
                $('.dairy_change').hide();
                $('.pacs_change').hide();
            } else {
                $('.pacs_change').hide();
                $('.dairy_change').hide();
                $('.fishery_change').hide();
            }

        });

        $('#CooperativeRegistrationForm').on('change', '#secondary-activity', function() {
            var secondary_activity = $(this).val();
            if (secondary_activity == 5) {
                $('.secondary_activity_other').show();
            } else {
                $('.secondary_activity_other').hide();
            }
        });


        $('#CooperativeRegistrationForm').on('change', '#state-code', function(e) {
            e.preventDefault();


            var location_of_head_quarter = $('input[name="location_of_head_quarter"]:checked')
                .val();

            var state_code = $('#state-code').val();
            if (location_of_head_quarter == 1) {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-urban-local-bodies'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        state_code: $(this).val()
                    },
                    success: function(response) {
                        $("#urban-local-body-type-code").html(response);
                    },
                });

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        state_code: state_code
                    },
                    success: function(response) {
                        console.log('sector_urban_district_code');
                        $(".sector_urban_district_code").html(response);
                    },
                });

            } else if (location_of_head_quarter == 2) {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        state_code: $(this).val()
                    },
                    success: function(response) {
                        $("#district-code").html(response);
                        $(".sector_district_code").html(response);
                        $(".sector_urban_district_code").html(response);
                    },
                });
            }


        });

        $('#CooperativeRegistrationForm').on('change', '#urban-local-body-type-code', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-urban-local-body'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    urban_local_body_type_code: $(this).val(),
                    state_code: $('#state-code option:selected').val()
                },
                success: function(response) {
                    $("#urban-local-body-code").html(response);
                },
            });
        });

        $('#CooperativeRegistrationForm').on('change', '#urban-local-body-code', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-locality-ward'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    urban_local_body_code: $(this).val()
                },
                success: function(response) {
                    $("#locality-ward-code").html(response);
                },
            });
        });



        $('#CooperativeRegistrationForm').on('change', '#district-code', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-blocks'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    district_code: $(this).val()
                },
                success: function(response) {
                    $("#block-code").html(response);
                },
            });
        });
        $('#CooperativeRegistrationForm').on('change', '#block-code', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-gp'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    block_code: $(this).val()
                },
                success: function(response) {
                    $("#gram-panchayat-code").html(response);
                },
            });
        });
        $('#CooperativeRegistrationForm').on('change', '#gram-panchayat-code', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-villages'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    gp_code: $(this).val()
                },
                success: function(response) {
                    $("#village-code").html(response);
                },
            });
        });


    });

    $('body').on('change', '#affiliated-union-federation-level', function(e) {
        e.preventDefault();

        var union_lavel = $(this).val();
        var primary_activity = $('#primary_activity').val();

        var entity_type = $("#affiliated-union-federation-level option:selected").text();
        //alert(entity_type);

        if (union_lavel != '') {
            if (union_lavel == '3') {

                $('.5g').hide();
                $('.5g_other').show();

                return false;

            } else {
                $('.5g').show();
                $('.5g_other').hide();
            }

            if (primary_activity == 20 || primary_activity == 22) {
                primary_activity = 1;
            }

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'getdccb'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    union_level: $(this).val(),
                    state: $('#state-code').val(),
                    primary_activity: primary_activity,
                    entity_type: entity_type,
                },
                success: function(response) {
                    // alert(response);
                    $("#affiliated-union-federation-name").html(response);
                },
            });
        }
    });

    // for dccb listing after click is-affiliated-union-federation-1
    $('body').on('change', '#is-affiliated-union-federation-1,#primary_activity', function(e) {
        e.preventDefault();

        var primary_activity = $('#primary_activity').val();
        var state_code = $('#state-code').val();
        var federation_1 = $('#is-affiliated-union-federation-1').val();



        if (federation_1 == 1 || primary_activity == 20 || primary_activity == 22) {

            if (primary_activity == 20 || primary_activity == 22) {

                primary_activity = 1;

            }
            // Federation

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'getfederationlevel'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    primary_activity: primary_activity,
                    state: state_code
                },
                success: function(response) {
                    // alert(response);
                    $("#affiliated-union-federation-level").html(response);
                },
            });
        }
    });

    $('body').on('change', '#is-coastal-1, #is-coastal-0', function(e) {
        e.preventDefault();
        if ($(this).val() == 1) {
            $('.type_of_water_body').show();
        } else {
            $('.type_of_water_body').hide();

            $("#water-body-type-id option[value='']").attr('selected', true)
        }
    });

    $('body').on('change', '#is-profit-making-1, #is-profit-making-0', function(e) {
        e.preventDefault();
        if ($(this).val() == 1) {
            $('.total_revenue').show();
        } else {
            $('.total_revenue').hide();
            $('#annual-turnover').val('');
        }
    });

    $('body').on('change', '.is_socitey_has_land', function() {
        if($(this).val() == 1)
        {
            $('.available_land').show();
        } else {
            $('.available_land').hide();
            $('.land_data').val(0);
            $('.land_total').val(0);   
        }
    });

    $('body').on('change', '.land_avilabele_area', function() {
        if ($(this).val() == 1) {
            $('.text_land_area').show();
        } else {
            $('.text_land_area').hide();
        }
    });

    $('body').on('change', '.pack_involved_fish_catch', function() {
        if ($(this).val() == 1) {
            $('.pack_annual_fish_catch').show();
        } else {
            $('.pack_annual_fish_catch').hide();
            $('#pack_annual_fish_catch').val('');
        }
    });

    $('body').on('change', '#designation', function() {
        if ($(this).val() == 6) {
            $('.designation_other').show();
        } else {
            $('.designation_other').hide();
            $('#designation-other').val('');
        }
    });

    $('body').on('change', '#registration-authoritie-id', function() {
        if ($(this).val() != 1) {
            $("#sector_operation option[value='1']").remove();
        } else {
            $("#sector_operation").append("<option value='1'>Credit</option>");
        }
    });

    var auth_registration = $('#registration-authoritie-id').val();
    if (auth_registration != 1) {
        $("#sector_operation option[value='1']").remove();
    } else {
        // $("#sector_operation").append("<option value='1'>Credit</option>");
    }

    $('body').on('change', '.operation_area_location', function() {
        var operation_area_location = $(this).val();

        //sector_rural_location_section
        var state_code = $('#state-code').val();



        //empty urban
        $('.sector_urban_local_body_code').html('');
        $('.sector_locality_ward_code').html('');

        //empty rural
        $('.sector_block_code').html('');
        $('.sector_panchayat_code').html('');
        $('.sector_village_code').html('');

        $('.area_operation_rural').val('');
        $('.area_operation_urban').val('');

        $('.rural_village_rows').not(':last').remove();
        $('.urban_ward_rows').not(':last').remove();

        $.ajax({
            type: 'POST',
            cache: false,
            url: '<?=$this->Url->build(['action'=>'get-urban-rural'])?>',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                    .val());
            },
            data: {
                operation_area_location: operation_area_location
            },
            success: function(response) {
                $(".area_operation").html(response);
            }
        });

        //$(".urban_ward_rows").slice(0).remove();

        if (operation_area_location == 1) {
            //Urban location

            $('.area_operation_urban_div').show();
            $('.area_operation_rural_div').hide();


            $('.sector_urban_location_section').each(function() {
                $(this).show();
            });

            $('.rural_village_rows').hide();
            $('.urban_ward_rows').show();

            $('.sector_rural_location_section').each(function() {
                $(this).hide();
            });


            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-urban-local-bodies'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    state_code: state_code
                },
                success: function(response) {

                    $(".sector_urban_local_body_type_code").html(response);
                }
            });

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    state_code: state_code
                },
                success: function(response) {
                    $(".sector_urban_district_code").html(response);

                },
            });


        } else if (operation_area_location == 2) {
            //Rural location

            $('.area_operation_rural_div').show();
            $('.area_operation_urban_div').hide();

            $('.sector_urban_location_section').each(function() {
                $(this).hide();
            });
            $('.sector_rural_location_section').each(function() {
                $(this).show();
            });

            $('.urban_ward_rows').hide();
            $('.rural_village_box').show();
            $('.rural_village_rows').show();


            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    state_code: state_code
                },
                success: function(response) {
                    $(".sector_district_code").html(response);

                },
            });

        } else if (operation_area_location == 3) {
            //if urban and rural both

            $('.sector_urban_location_section').each(function() {
                $(this).show();
            });

            $('.area_operation_rural_div').show();
            $('.area_operation_urban_div').show();

            $('.urban_ward_rows').show();
            $('.rural_village_rows').show();

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-urban-local-bodies'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    state_code: state_code
                },
                success: function(response) {

                    $(".sector_urban_local_body_type_code").html(response);
                }
            });

            $('.sector_rural_location_section').each(function() {
                $(this).show();
            });

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    state_code: state_code
                },
                success: function(response) {
                    $(".sector_district_code").html(response);
                    $(".sector_urban_district_code").html(response);
                },
            });

        }

        console.log(operation_area_location);
    });

    $('body').on('change', '.f_audit', function() {
        if ($(this).val() == 1) {
            $('.f_audit_change').show();
        } else {
            $('.f_audit_change').hide();
            $('#audit-complete-year').val('').trigger('change');
            $('#category-audit').val('').trigger('change');
        }
    });


    $('body').on('change', '#functional-status', function() {

        if ($(this).val() == 1) {
            $('.loan_warning').html('<b style="color:red">Warning!</b> It should be greater then 0 if Present Functional Status is functional.');
        } else {
            $('.loan_warning').html('');
        }
    });


})($);
</script>
<?php $this->end(); ?>

<style>
.rdb .radio .form-check {
    margin-right: 21px;
    ]: ;
}

.loan_warning {
    margin-left:20px;
    /* color: #d73925!important;
    position: absolute;
    bottom: -24px !important;
    font-weight: normal;
    font-size: 12px; */
}
</style>