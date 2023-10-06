<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration $CooperativeRegistration
 */
$this->assign('title',__('Add New Cooperative Registration'));
$this->assign('content_header',__(' Cooperative Society Registration'));
$this->Breadcrumbs->add(__('Cooperative Registrations'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Cooperative Registration'));
//$this->assign('top_head',__('hide'));
?>

<!-- Main content -->
<section class="content Cooperative Registration add form add_cop_res design_row-sect">
    <div class="row">
        <!-- heading -->
        <div class="col-md-12 top-heading_div">
            <h3>Data Collection form for State/District Federation</h3>
        </div>
        <!-- heading ends -->
        <!-- left column -->
        <div>
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bold-b">
                        <?= __('State/District Federation Identification Module') ?>
                        <!--<small><?= __('Add New Cooperative Registration') ?></small>-->
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'list'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Cooperative Registrations'),'escape' => false]
                  );?>
                    </div>
                </div>
                <!-- /.box-header -->

                <!-- <h3>Data Collection form for Primary Cooperatives</h3> -->

                <!-- form start -->
                <div class="contentbox dak">
                    <?php //if(!empty($StateDistrictFederation->errors())) { ?>
                    <div>
                        <?php
                                // echo '<pre>';
                                // print_r($CooperativeRegistration->errors());
                            ?>
                    </div>
                    <span id="lblMessage" class="success">
                        <?php //echo $this->Flash->render();
							//echo "<p>".$message."</p>"; ?>
                    </span> <?php  //}  ?>
                </div>

                <?php
              echo $this->Form->create(null,['id' => 'RevisedForm','type'=>'file']);?>
                <div class="box-body">
                    <div class="boxk">
                        <div class="row">
                            <div class="col-sm-4">
                                <?php
                                // if(!empty($CooperativeRegistration->errors()))
                                // {
                                //    // echo '<pre>';
                                //    // print_r($CooperativeRegistration->errors());
                                //     //echo '<span style="color:red;">Please Fill Required Fields.</span>';
                                // } 
                            ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>1(a). Name of Cooperative Federation<span
                                        class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_federation_name',['type'=>'text','placeholder'=>'Name of Cooperative Federation','label'=>false,'required'=>true])?>
                                <input type="hidden" name="curr_id" id="curr_id" value="" />
                            </div>

                            <!-- ****down -->
                            <div class="col-sm-4">
                                <label>1(b). Sector of Operation<span class="important-field">*</span></label>
                                <?=$this->Form->control('sector_of_operation_type',['options'=>$sector_operations,'id'=>'sector_operation','empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                            </div>
                            <!-- <div class="clearfix"></div> -->

                            <div class="col-sm-4">
                                <label>1(c). Primary Activity<span class="important-field">*</span></label>
                                <?=$this->Form->control('sector_of_operation',['options'=>$PrimaryActivities,'id'=>'primary_activity','empty'=>'Select','label'=>false,'class'=>'select2 sector_of_operation','required'=>true])?>
                            </div>
                            <!-- <div class="col-sm-4 sector_of_operation_other"
                                style="display: <?=$CooperativeRegistration->sector_of_operation_type==1 && $CooperativeRegistration->sector_of_operation==8?'block':'none';?>;">
                                <label>1(c)(i). Primary Activity - Other<span class="important-field">*</span></label>
                                <?=$this->Form->control('sector_of_operation_other',['label'=>false,'placeholder'=>'Primary Activity - Other','required'=>true])?>
                            </div> -->

                            <!-- ****down -->
                            <div class="col-sm-4">
                                <label>1(d).Registration Authority<span class="important-field">*</span></label>
                                <?=$this->Form->control('registration_authority_id',['options'=>$register_authorities,'empty'=>'Select','class'=>'select2','label'=>false,'required'=>true])?>
                            </div>

                            <!-- <div class="clearfix"></div> -->
                            <div class="col-sm-4">
                                <label>1(e). Registration Number<span class="important-field">*</span></label>
                                <?=$this->Form->control('registration_number',['maxlength'=>40,'class'=>'','type'=>'text','placeholder'=>'Registration Number','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-4">
                                <label>1(f). Date of Registration<span class="important-field">*</span></label>
                                <?=$this->Form->control('date_registration',['type'=>'text','placeholder'=>'Date of Registration','label'=>false,'class'=>'before_date','required'=>true,'readonly'=>true])?>
                            </div>

                            <!-- <div class="col-sm-4 typemis"
                                style="display:<?= $CooperativeRegistration->sector_of_operation!=29?'none':'block';?>;">
                                <label>1(g). Type of Miscellaneous Cooperatives<span
                                        class="important-field">*</span></label> -->
                            <?php // $CooperativeRegistration->cooperative_registrations_social->type_social_culture_activity = explode(',',$CooperativeRegistration->cooperative_registrations_social->type_social_culture_activity);  ?>
                            <?php //$this->Form->control("cooperative_registrations_miscellaneous.type_miscellaneous",['options'=>$miscellaneoustypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                            <!-- </div> -->
                            <div class="col-sm-4">
                                <label class="prevlabel">1(g). Present Functional Status<span
                                        class="important-field">*</span></label>
                                <?=$this->Form->control('functional_status',['options'=>$PresentFunctionalStatus,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                            </div>


                            <!-- ****down -->
                            <div class="col-sm-4">
                                <label>1(h). Level of Federation:<span class="important-field">*</span></label>
                                <?php $form_filling_for = ['2'=>'State Federation', '3'=>'District Federation', '4'=>'Block/Taluka/Mandal Federation', '5'=>'Regional Federation'] ?>
                                <?=$this->Form->control('federation_type',['options'=>$form_filling_for,'id'=>'form-filling-for','empty'=>'Select','label'=>false,'class'=>'select2','required'=>true, 'onchange'=>'is_affiliated(this)'])?>
                            </div>
                            <!-- <div class="col-sm-4 industry_type_div" style="display:none;">
                                <label class="prevlabel">1(h). Type of Industry:<span class="important-field">*</span></label>
                                <?php //$this->Form->control('industry_type',['options'=>'','label'=>false,'class'=>'select2','required'=>true])?>
                            </div> -->
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
                            <?=$this->Form->control('location_of_head_quarter',['options'=>[1=>'Urban',2=>'Rural'],'id'=>'location_of_head_quarter','class'=>'location_of_head_quarter','default' => 2,'type' => 'radio','label'=>false,'required'=>true])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(b). State / UT <span class="important-field">*</span></label>
                            <?=$this->Form->control('state_code',['options'=>$states,'label'=>false,'empty'=>'Select','type'=>'select','class'=>'select2','required'=>true,'value'=>$state_code])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(c). District <span class="important-field">*</span></label>
                            <?=$this->Form->control('district_code',['options'=>$districts,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select','required'=>true, 'onchange'=>'get_fed(this)'])?>
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
                            <label>2(f). Village <span class="important-field">*</span></label>
                            <?=$this->Form->control('village_code',['options'=>$villages,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select','required'=>true])?>
                        </div>
                        <div class="col-sm-4 urban_location_section" style="display:none;">
                            <label>2(d). Category of Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control('urban_local_body_type_code',['options'=>$localbody_types,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4 urban_location_section" style="display:none;">
                            <label>2(e). Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control('urban_local_body_code',['options'=>$localbodies,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4 urban_location_section" style="display:none;">
                            <label>2(f). Locality or Ward <span class="important-field"></span></label>
                            <?=$this->Form->control('locality_ward_code',['options'=>$localbodieswards,'label'=>false,'class'=>'select2','required'=>true])?>
                        </div>
                        <div class="col-sm-4">
                            <label>2(g). Pin Code <span class="important-field">*</span></label>
                            <?=$this->Form->control('pincode',['class'=>'pincode pincode2','label'=>false,'placeholder'=>'Pin Code','required'=>true,'minlength'=>6,'maxlength'=>6, 'onblur'=>'fill_address()'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(h). Full Address<span class="important-field">*</span></label>
                            <?=$this->Form->control("full_address",['type'=>'textarea','placeholder'=>'Full Address','label'=>false,'required'=>true,'rows'=>"2"])?>
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
                            <?=$this->Form->control('contact_person',['type'=>'textbox','placeholder'=>'Name','label'=>false,'required'=>true, 'maxlength'=>'200','onkeyup'=>'onlyChar(this)'])?>
                        </div>
                        <div class="col-sm-4">
                            <label>3(b) Designation<span class="important-field">*</span></label>
                            <?=$this->Form->control('designation',['options'=>$designations,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>
                        <div class="col-sm-4 designation_other" style="display:none;">
                            <label>3(b)(i) Designation Other<span class="important-field">*</span></label>
                            <?=$this->Form->control('designation_other',['type'=>'textbox','class'=>'','placeholder'=>'Other Desigantion','label'=>false,'required'=>true, 'maxlength'=>'30'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>3(c). Mobile Number<span class="important-field">*</span></label>
                            <?=$this->Form->control('mobile',['type'=>'textbox','class'=>'number','placeholder'=>'Mobile Number','label'=>false,'required'=>true, 'maxlength'=>'10'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>3(d). Landline Number <sup class="blue">#</sup></label>
                            <div class="row">
                                <div class="col-sm-3 pd-lr_0">
                                    <?=$this->Form->control('std',['type'=>'textbox', 'class'=>'number','placeholder'=>'STD Code','maxlength'=>'4','label'=>false,'style'=>'width:82px;'])?>
                                </div>
                                <div class="col-sm-9 pd-lr_0">
                                    <?=$this->Form->control('landline',['type'=>'textbox','class'=>'number','placeholder'=>'Landline Number', 'maxlength'=>'8','label'=>false])?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>3(e). Email ID <sup class="blue">#</sup></label>
                            <?=$this->Form->control('email',['type'=>'email','label'=>false,'placeholder'=>'Email ID', 'maxlength'=>'50'])?>
                        </div>
                        <div class="col-sm-4">
                            <label style="font-size: 14px;margin-top: 40px;"><span style="color:black;">Note :</span>
                                <sup class="blue">#</sup> Fields are optional</label>
                        </div>
                    </div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Sector Details</strong></h4>
                        </div>



                        <!-- <div class="col-sm-4">
                            <label>4(f). Secondary Activity<span class="important-field">*</span></label>
                            <?php //$this->Form->control('secondary_activity',['options'=>$SecondaryActivities,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="col-sm-4 secondary_activity_other"
                            style="display: <?=$CooperativeRegistration->secondary_activity==5?'block':'none';?>;">
                            <label>4(g). If Other Activity <span class="important-field">*</span></label>
                            <?=$this->Form->control('secondary_activity_other',['label'=>false,'placeholder'=>'Other Activity','required'=>true])?>
                        </div> -->

                        <!-- <div class="clearfix"></div>
                        <div class="col-sm-4 tierall1" style="display:none;">
                            <label>4. Tier of the Cooperative Society<span class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_type_id',['options'=>$CooperativeSocietyTypes,'label'=>false,'class'=>'select2','required'=>true])?>
                        </div> -->

                        <!-- <div class="col-sm-4 b2" style="display:none;">
                             <label>5(a). States in Case of Multi-State Cooperative Society<span
                                    class="important-field"></span></label>
                            <?php //$this->Form->control('multi_states',['options'=>$states,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false])?>
                        </div> -->

                        <div class="clearfix"></div>
                        <div class="col-sm-4 set-form-check">
                            <label>4(a). Operation Area Location<span class="important-field">*</span></label>
                            <!--,3=>'Both'-->
                            <?=$this->Form->control('operation_area_location',['options'=>[1=>'Urban',2=>'Rural',3=>'Both'],'id'=>'operation_area_location','class'=>'operation_area_location','default'=>'2','type' => 'radio','label'=>false,'required'=>true])?>
                        </div>


                        <!-- start urban_ward_box -->
                        <div class="clearfix"></div>
                        <div class="sector_urban_location_section urban_ward_box" style="display:none;">
                            <b style="margin-left:15px;text-decoration: underline;">Urban</b>
                            <br>

                            <div class="clearfix"></div>
                            <?php $uw2=1;?>
                            <div class="urban_ward_rows" rowclass="<?=$uw2?>">
                                <div class="col-sm-3 urban_location_section1">
                                    <label>4(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'sector_urban_district_code select2','type'=>'select','required'=>true])?>
                                </div>

                                <div class="col-sm-3 urban_location_section1" style="display: block;">
                                    <label>4(c)(ii). Category of Urban Local Body <span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.local_body_type_code",['options'=>$localbody_types,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'select2 sector_urban_local_body_type_code','required'=>true])?>
                                </div>

                                <div class="col-sm-3 urban_location_section1" style="display: block;">
                                    <label>4(c)(iii). Urban Local Body <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.local_body_code",['options'=>'','empty'=>'Select','label'=>false,'increment'=>$uw2,'class'=>'select2 sector_urban_local_body_code','required'=>true])?>
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
                        </div>
                        <!-- end urban_ward_box -->

                        <div class="clearfix"></div>
                        <!-- start rural_village_box -->

                        <div class="sector_rural_location_section rural_village_box">
                            <b style="margin-left:15px;text-decoration: underline;">Rural</b>

                            <div class="clearfix"></div>
                            <?php $hr2=1;?>

                            <div class="rural_village_rows extra_rural_rows" rowclass="<?=$hr2?>">

                                <div class="col-sm-3 rural_location_section1">
                                    <label>4(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_district_code select2','type'=>'select','required'=>true])?>
                                </div>

                                <div class="col-sm-3 rural_location_section1">
                                    <label>4(c)(ii). Block <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector.$hr2.block_code",['options'=>$blocks,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_block_code select2','type'=>'select','required'=>true])?>
                                </div>


                                <div class="col-sm-12 rural_location_section1" style="display:block;">

                                    <button type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_rural_village"><i
                                            class="fa fa-plus-circle"></i></button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button style="display: none;" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_rural_village"><i
                                            class="fa fa-minus-circle"></i></button>

                                </div>
                            </div>

                        </div>
                        <!-- end rural_village_box -->

                        <div class="col-sm-3 area_operation_change2 ex-div" style="display:block;">
                            <label>4(f). Whether the Federation is affiliated to any other Union/Federation?<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_affiliated_union_federation',['options'=>['1'=>'Yes','0'=>'No'],'class'=>'is_affiliated','default'=>0,'type'=>'radio','label'=>false,'required'=>true])?>
                        </div>



                        <!-- <div> -->
                        <div class="col-sm-3 is_affiliated_change" id="affiliated_union_federation_level_div"
                            style="display:none;">
                            <label>4(g). Level of Affiliated Union/Federation<span
                                    class="important-field">*</span></label>
                            <?php //$levelOfUnions = ['1'=>'Block/Mandal/Taluka Cooperative Union','2'=>'District Cooperative Union','3'=>'Regional Cooperative Union', '4'=> 'State Cooperative Federation','5'=>'National Federation'];
                                $levelOfUnions = ['1'=>'DCCB','2'=>'STCB','3'=>'Other']; ?>
                            <?= $this->Form->control('affiliated_union_federation_level',['options'=>$levelOfUnions,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>


                        <div class="col-sm-3 is_affiliated_change 5g" id="affiliated_union_federation_name_div"
                            style="display:none;">
                            <label>4(h). Name of Affiliated Union/Federation<span
                                    class="important-field">*</span></label>
                            <?php //$nameOfUnions = ['1'=>'First Name','2'=>'Second Name','3'=>'Third Name', '4'=> 'Fourth Name','5'=>'Fifth Name']; ?>
                            <?= $this->Form->control('affiliated_union_federation_name',['options'=>'','empty'=>'Select','label'=>false,'class'=>'select2 affiliated_union_federation_name','required'=>true,'onchange'=>'for_other_affiliated(this)'])?>
                        </div>

                        <!-- <div class="col-sm-4 affiliated_union_federation_name_change"
                                style="display:<?=$CooperativeRegistration->affiliated_union_federation_name == '-1' ?'block':'none';?>;">
                                <label>4(h)(i). Other Name of Affiliated Union/Federation<span
                                        class="important-field">*</span></label>
                                <?=$this->Form->control('affiliated_union_federation_name_for_other',['type'=>'textbox','class'=>'','placeholder'=>'Other Name of Affiliated Union/Federation','label'=>false,'required'=>true, 'maxlength'=>'200'])?>
                            </div> -->

                        <div class="col-sm-3 is_affiliated_change_other 5g_other" id="5g_other_div"
                            style="display:none;">
                            <label>4(i). Name of Other Affiliated Union/Federation<span
                                    class="important-field">*</span></label>
                            <?= $this->Form->control('affiliated_union_federation_other',['type'=>'text','label'=>false,'required'=>true])?>
                        </div>
                        <!-- </div> -->
                        <div class="clearfix"></div>
                        <div class="col-sm-3 num_mem " style="display:block;">
                            <label>5. Total Number of Members of the Cooperative Federation<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('members_of_federation',['type'=>'textbox','class'=>'num_mem number not_functional','placeholder'=>'Number of Members','label'=>false,'required'=>true, 'maxlength'=>'12','onkeyup'=>'onlyNumbers(this)'])?>
                        </div>

                        <!-- <div class="col-sm-4 membership_type" style="display:block;">
                            <label>5(i). Type of Membership<span class="important-field">*</span></label>
                            <?php $categoryAudit = ['1'=>'Primary Cooperative','2'=>'Individual Member','3'=>'Institutional Member', '4'=> 'Others Membership']; ?>
                            <?=$this->Form->control('category_audit',['options'=>$categoryAudit,'empty'=>'Select','label'=>false,'class'=>'select2 not_functional','required'=>true, 'onchange'=>'select_membership_type(this)'])?>
                        </div> -->







                    </div>


                    <div class="box-block-m" id="primary_cooperative_div"
                        style="display:<?= $StateCooperativeBank->other_member == 1 ? 'block' : 'block'; ?>;">
                        <div class="col-sm-12">
                            <h4><strong>Whether the Primary Cooperative is the member of the Federation?</strong></h4>
                        </div>
                        <div class="col-sm-3">
                            <label>6(a). Whether the Primary Cooperative is the member of the Federation?<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("is_federation_member",['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'class'=>'is_society_member','type'=>'radio','label'=>false,'required'=>true, 'onclick'=>'is_coop_society_member(this)'])?>
                        </div>
                        <!-- <div class="clearfix"></div> -->
                        <?php if($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8){ ?>
                            <div class="col-sm-3 rural_location_section1">
                                    <label>6(b). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("all_district",['options'=>$districts,'label'=>false,'class'=>'all_district select2','type'=>'select','required'=>true])?>
                                </div>
                        <?php } ?>
                        <div class="col-sm-3">
                            <label>6(a)(i). Primary Activity<span class="important-field">*</span></label>
                            <?=$this->Form->control('all_sector_of_operation',['options'=>$PrimaryActivitiesTotal,'id'=>'all_primary_activity','empty'=>'Select','label'=>false,'class'=>'select2 all_sector_of_operation','required'=>true])?>
                        </div>
                        
                        <div class="col-sm-3" id="federation-member-count-div" style="display:block;">
                            <label>6(b). Number of Members<span class="important-field">*</span></label>
                            <?=$this->Form->control('primary_cooperative_member_count',['type'=>'textbox','class'=>'number','placeholder'=>'Total number of Members','label'=>false,'required'=>true, 'value'=>0])?>
                        </div>
                        <div class="member_report_div" id="member-report-div">

                            <!-- <p class="col-sm-12 row pacs_member_count_row"
                                style="font-size:18px;text-decoration: underline;">
                                Select the Primary Cooperative if they are member of the federation</p> -->

                            <div class="col-sm-12 pacs_member_table">
                                <div class="table-responisve tablenew tablenew1">
                                    <table class="table table-striped table-white table-bordered" id="tab_logic2">
                                        <thead class="table-sticky-header">
                                            <tr style="background-color: #599ec6; color: #fff;">
                                                <th style="width: 2%">
                                                    S.No.
                                                </th>
                                                <th style="width: 15%">
                                                    Primary Activity
                                                </th>
                                                <th style="width: 23%">
                                                    Name of Primary Cooperative
                                                </th>
                                                <th style="width: 15%">
                                                    District Name
                                                </th>
                                                <th style="width: 10%">
                                                    Registration Number
                                                </th>
                                                <th style="width: 10%">
                                                    Registration Date
                                                </th>
                                                <th style="width: 10%">
                                                    Mark Tick ( &#10004 ) <br/>If A Member
                                                </th>
                                            </tr>

                                        </thead>
                                        <tbody class="pacs_members">
                                            <?php
                            
                           
                            

                                        //    $affiliated_check = 'checked';
                                        //    $affiliated_flag = '';
                                           // if($StateCooperativeBank->status == 1)
                                            // {
                                           //     $affiliated_flag = 'edit';
                                           // } else {
                                          //     $affiliated_flag = 'add';
                                         // }

                                        //    if(count($society)>0){
                                        //    foreach($society as $key=>$value){


                                    
                                           // if($affiliated_flag == 'edit')
                                           // {
                                           //     if(in_array($value['id'],$curr_pacs_member_affiliated))
                                           //     {
                                           //         $affiliated_check = 'checked';
                                           //     } else {
                                           //         $affiliated_check = '';
                                          //     }
                                           // }

                                            //  echo '<tr><td>'.($key+1).'</td><td>'.$value['cooperative_society_name'].'</td><td>'.$all_districts[$value['operational_district_code']].'</td><td>'.$value['registration_number'].'</td><td>'.$value['date_registration'].'</td><td style="min-width: 140px; display:none" class="shouldbehide2"><input class="pacs_is_member pacs_is_member_affilated" type="checkbox" name="pacs_is_member_affiliated[]" '.$affiliated_check.' selected value="'.$value['id'].'"></td></tr>';

                                            //   }
                                            //  }
                                          echo  '<tr><td colspan="9">'. __('Record not found!').'</td></tr>';
                                          ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!--===========================================================================================================-->
                                <!--===========================================================================================================-->
                            </div>
                        </div>
                    </div>


                    <div class="box-block-m" id="other_membership_div" style="display:block;">
                        <div class="col-sm-12">
                            <h4><strong>Other Membership details</strong></h4>
                        </div>
                        <div class="col-sm-3">
                            <label>7(a) Other members details ?<span class="important-field">*</span></label>
                            <?=$this->Form->control("other_member",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'other_member','type'=>'radio','label'=>false,'required'=>true ])?>
                        </div>
                        <div class="col-sm-3 other_section" style="display:none;">
                            <label>7(b) Number of Members<span class="important-field">*</span></label>
                            <?=$this->Form->control('other_member_count',['type'=>'textbox','readonly'=>true,'class'=>'number','placeholder'=>'Number of Members','label'=>false,'required'=>true, 'default'=>0])?>
                        </div>

                        <div class="col-sm-12 other_section other_section_box"
                            style="display:<?= $StateCooperativeBank->other_member == 1 ? 'block' : 'none'; ?>;">

                            <?php //if(!empty($StateCooperativeBank->dcb_scb_other_members)):
                       // foreach ($StateCooperativeBank->dcb_scb_other_members as $ohr2 => $dcb_scb_other_member):
                       ?>
                            <!-- <div class="other_member_rows" rowclass="<?=$ohr2?>">
                                <div class="col-sm-3">
                                    <label>17(c). Type of Membership<span class="important-field">*</span></label>
                                    <?php //$arr_other_type_member = ['1'=>'Institutional Member','2'=>'Individual Member','3'=>'Any Other'];//,'3'=>'Any Other (Please Specify)' ?>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.type_membership",['options'=>$arr_other_type_member,'empty'=>'Select','label'=>false,'class'=>'other_type_membership select2','inc'=>$ohr2,'required'=>true])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>17(d). Class of Membership<span class="important-field">*</span></label>
                                    <?php $arr_class_member = ['1'=>'Ordinary/Regular/Voting','2'=>'Associate/Nominal/Voting']; ?>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.class_membership",['options'=>$arr_class_member,'empty'=>'Select','label'=>false,'class'=>'building_type select2','required'=>true])?>
                                </div>
                                <div class="col-sm-3" id="org_name_div">
                                    <label>17(e). Name of Organisation<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.org_name",['type'=>'textbox','placeholder'=>'Name of Organisation','label'=>false,'class'=>'org_name','required'=>true, 'maxlength'=>'200'])?>
                                </div>

                                <div class="col-sm-3 <?= $ohr2 ?>individual_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'none' : 'block'; ?>;">
                                    <label>17(f). Primary Activities/Sector<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.primary_activity",['options'=>$PrimaryActivitiesTotal,'empty'=>'Select','readonly'=>true,'label'=>false,'class'=>$ohr2.'individual_input select2','required'=>true])?>
                                    <?php //$this->Form->control("dcb_scb_other_members.$ohr2.member_type",['type'=>'hidden','value'=>'1'])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'none' : 'block'; ?>;">
                                    <label>17(g). District<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.district_code",['options'=>$districts,'empty'=>'Select','label'=>false,'class'=>$ohr2.'individual_input select2','type'=>'select','required'=>true])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'none' : 'block'; ?>;">
                                    <label>17(h). Address<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.address",['type'=>'textbox','placeholder'=>'Address','label'=>false,'class'=>$ohr2.'individual_input','required'=>true, 'maxlength'=>'200'])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'none' : 'block'; ?>;">
                                    <label>17(i). Phone Number<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.phone_number",['type'=>'textbox','placeholder'=>'Phone Number','label'=>false,'class'=>$ohr2.'individual_input number','required'=>true, 'maxlength'=>'10'])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'none' : 'block'; ?>;">
                                    <label>17(j). Email<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.email",['type'=>'email','placeholder'=>'Email','label'=>false,'class'=>$ohr2.'individual_input','required'=>true, 'maxlength'=>'200'])?>
                                </div>
                                <div class="col-md-3 <?= $ohr2 ?>individual_upload_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'block' : 'none'; ?>;">
                                    <div class="">
                                        <div><label>17(f).Document </label>&nbsp;&nbsp;<a
                                                href="<?=$this->request->webroot?>files/member_document/<?= $dcb_scb_other_member->member_document ?>"
                                                target="_blank" download><i class="fa fa-download"></i></a></div>
                                        <?php echo $this->Form->control("sd_federation_other_members.$ohr2.member_document",['label' => false,'type' =>'file','class'=>$ohr2.'individual_upload_input','required'=>false,'accept'=>'application/pdf','value'=>$dcb_scb_other_member->member_document]);?>
                                    </div>
                                </div>
                                <div class="col-md-3 <?= $ohr2 ?>individual_upload_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'block' : 'none'; ?>;">
                                    <div class="">
                                        <div><label>17(g). Member Count</label></div>
                                        <?php echo $this->Form->control("sd_federation_other_members.$ohr2.member_count",['label' => false,'type' =>'text','class'=>$ohr2.'individual_upload_input docu number','maxlength'=>'7','required'=>true]);?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_other_member">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                    <button style="display: none;margin-bottom: 10px" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_other_member">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div> -->
                            <?php //endforeach; else:?>
                            <?php $ohr2=0;?>
                            <div class="other_member_rows" rowclass="<?=$ohr2?>">
                                <div class="col-sm-3">
                                    <label>Type of Membership<span class="important-field">*</span></label>
                                    <?php //$arr_other_type_member = ['1'=>'Institutional Member','2'=>'Individual Member','3'=>'Any Other'];//,'3'=>'Any Other (Please Specify)' ?>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.type_membership",['options'=>$arr_other_type_member,'empty'=>'Select','label'=>false,'class'=>'other_type_membership select2','inc'=>$ohr2,'required'=>true])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Class of Membership<span class="important-field">*</span></label>
                                    <?php $arr_class_member = ['1'=>'Ordinary/Regular/Voting','2'=>'Associate/Nominal/Voting']; ?>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.class_membership",['options'=>$arr_class_member,'empty'=>'Select','label'=>false,'class'=>'building_type select2','required'=>true])?>
                                </div>
                                <div class="col-sm-3" id="org_name_div">
                                    <label>Name of Organisation<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.org_name",['type'=>'textbox','placeholder'=>'Name of Organisation','label'=>false,'class'=>'org_name','required'=>true, 'maxlength'=>'200'])?>
                                </div>

                                <div class="col-sm-3 <?= $ohr2 ?>individual_div">
                                    <label>Primary Activities/Sector<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.primary_activity",['options'=>$PrimaryActivitiesTotal,'empty'=>'Select','readonly'=>true,'label'=>false,'class'=>$ohr2.'individual_input select2','required'=>true])?>
                                    <?php //$this->Form->control("dcb_scb_other_members.$ohr2.member_type",['type'=>'hidden','value'=>'1'])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div">
                                    <label>District<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.district_code",['options'=>$districts,'empty'=>'Select','label'=>false,'class'=>$ohr2.'individual_input select2','type'=>'select','required'=>true])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div">
                                    <label>Address<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.address",['type'=>'textbox','placeholder'=>'Address','label'=>false,'class'=>$ohr2.'individual_input','required'=>true, 'maxlength'=>'200'])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div">
                                    <label>Phone Number<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.phone_number",['type'=>'textbox','placeholder'=>'Phone Number','label'=>false,'class'=>$ohr2.'individual_input number phone','required'=>true, 'maxlength'=>'10'])?>
                                </div>
                                <div class="col-sm-3 <?= $ohr2 ?>individual_div">
                                    <label>Email<span class="important-field">*</span></label>
                                    <?=$this->Form->control("sd_federation_other_members.$ohr2.email",['type'=>'email','placeholder'=>'Email','label'=>false,'class'=>$ohr2.'individual_input','required'=>true, 'maxlength'=>'200'])?>
                                </div>
                                <div class="col-md-3 <?= $ohr2 ?>individual_upload_div" style="display:none;">
                                    <div class="">
                                        <div><label>Document <span style="color:red;" class="dtype"></span></label>
                                        </div>
                                        <?php echo $this->Form->control("sd_federation_other_members.$ohr2.member_document",['label' => false,'type' =>'file','class'=>$ohr2.'individual_upload_input','required'=>true,'accept'=>'application/pdf']);?>
                                    </div>
                                </div>
                                <div class="col-md-3 <?= $ohr2 ?>individual_upload_div"
                                    style="display:<?= $dcb_scb_other_member->type_membership == 2 ? 'block' : 'none'; ?>;">
                                    <div class="">
                                        <div><label>Number of Member</label></div>
                                        <?php echo $this->Form->control("sd_federation_other_members.$ohr2.member_count",['label' => false,'type' =>'text','class'=>$ohr2.'individual_upload_input docu number','maxlength'=>'7','required'=>true]);?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="button" class="pull-right btn btn-primary btn-xs add_row_other_member"
                                        onclick="add_other_member_row()">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                    <button style="display: none;margin-bottom: 10px" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_other_member">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            <?php //endif;?>
                        </div>
                    </div>


                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Financial Details</strong></h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-4 ">
                            <label>8(a). Whether Financial Audit has been done? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("financial_audit",['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'class'=>'f_audit not_functional','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-4 f_audit_change" style="display:block;">
                            <label>8(b). Year of Latest Audit Completed <span class="important-field">*</span></label>
                            <?=$this->Form->control('audit_complete_year',['options'=>$years,'empty'=>'Select','label'=>false,'class'=>'select2 not_functional','required'=>true])?>
                            <!-- <span class="cop-add_span">Note: - Data after 8(b) will be based on Latest Audit Year</span> -->
                        </div>
                        <div class="col-sm-4 f_audit_change" style="display:block;">
                            <label>8(c). Category of Audit<span class="important-field">*</span></label>
                            <?php $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score']; ?>
                            <?=$this->Form->control('category_audit',['options'=>$categoryAudit,'empty'=>'Select','label'=>false,'class'=>'select2 not_functional','required'=>true])?>
                        </div>

                        <!-- <div class="clearfix"></div> -->
                        <!-- <div class="col-sm-4 rdb ">
                            <label>7(a). Whether the Cooperative Society is profit making? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_profit_making',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'not_functional','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-4 total_loss" style="display:block;">
                            <label>7(b). Net Loss of the Cooperative Society<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('annual_loss',['placeholder'=>'Net Loss of the Cooperative Society','label'=>false,'required'=>true, 'maxlength'=>'12','class'=>'numberadndesimal1 not_functional'])?>
                        </div>
                        <div class="col-sm-4 total_revenue" style="display:none;">
                            <label>7(b). Net Profit of the Cooperative Society<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('annual_turnover',['placeholder'=>'Net Profit of the Cooperative Society','label'=>false,'required'=>true, 'maxlength'=>'12','class'=>'numberadndesimal1 not_functional'])?>
                        </div>

                        <div class="col-sm-4 rdb ">
                            <label>8(a). Whether the dividend is paid by the Cooperative Society? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_dividend_paid',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'dividend not_functional','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-4 dividend_change"
                            style="display:<?=$CooperativeRegistration->is_dividend_paid == 1 ?'block' : 'none' ;?>;">
                            <label>8(b). Dividend Rate Paid by the Cooperative Society (in %)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('dividend_rate',['turn_over'=>'textbox','placeholder'=>'Dividend Rate Paid','label'=>false,'required'=>true,'class'=>'numberadndesimal not_functional', 'maxlength'=>'6'])?>
                        </div> -->
                        <div class="clearfix"></div>
                        <div class="col-sm-4 none-selected-div--1 " style="display:block;">
                            <label>9(a). Type of bank where the cooperative federation have a bank account?<span
                                    class="important-field">*</span></label>
                            <?php $bankType = ['1'=>'Cooperative Bank','2'=>'Commercial Bank']; ?>
                            <?=$this->Form->control('bank_type',['options'=>$bankType,'label'=>false,'class'=>'multiselect bank_type not_functional','required'=>true,'multiple'=>true])?>
                        </div>
                        <div class="col-sm-4 list_of_bank none-selected-div--2 " style="display:block;">
                            <label>9(b). Bank name where the cooperative federation has its bank account: -<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_bank_id',['options'=>'','placeholder'=>'Select Bank Type','label'=>false,'class'=>'multiselect bank_id not_functional','required'=>true,'multiple'=>true])?>
                        </div>
                        <div class="col-sm-4 bank_other" style="display:none;">
                            <label>9(c). Other bank name (Please Specify) <span class="important-field">*</span></label>
                            <?=$this->Form->control('other_bank',['type'=>'textbox','class'=>'not_functional','placeholder'=>'Other bank name','label'=>false,'required'=>true, 'maxlength'=>'250'])?>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-sm-4">
                            <label>10(a). Whether the cooperative federation has an office building ? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'housing_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-4 housing_has_building_change" style="display:none;">
                            <!-- style="display:none;"> -->
                            <label>10(b). Type of Office Building<span class="important-field">*</span></label>
                            <?=$this->Form->control("building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-3 rdb">
                            <label>11. Is the Federation doing a business activity? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('federation_doing_business',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'business','type'=>'radio','label'=>false,'required'=>true, 'onchange'=>'business_form(this)'])?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-3 rdb" id="is_profit_making_div" style="display:none;">
                            <label>11(a). Whether the Cooperative Federation is profit making? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_profit_making',['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'class'=>'not_functional','type'=>'radio','label'=>false,'required'=>true,'onchange'=>'is_making_profit(this)'])?>
                        </div>
                        <div class="col-sm-3 total_loss" style="display:none;" id="annual_loss_div">
                            <label>11(b). Net Loss of the Cooperative Federation (Rs. in Lakhs)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('net_loss',['placeholder'=>'Net Loss of the Cooperative Society','label'=>false,'required'=>true, 'maxlength'=>'12','class'=>'numberadndesimal1 not_functional'])?>
                        </div>
                        <div class="col-sm-3 total_revenue" style="display:none;" id="annual_turnover_div">
                            <label>11(b). Net Profit of the Cooperative Federation (Rs. in Lakhs)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('net_profit',['placeholder'=>'Net Profit of the Cooperative Society','label'=>false,'required'=>true, 'maxlength'=>'12','class'=>'numberadndesimal1 not_functional'])?>
                        </div>


                        <div class="col-sm-3 rdb" id="is_dividend_paid_div" style="display:none;">
                            <label>11(c). Whether the dividend is paid by the Federation? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_dividend_paid',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'dividend not_functional','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>

                        <div class="col-sm-3 dividend_change" style="display:none;" id="dividend_rate_div">
                            <label>11(d). Dividend Rate Paid by the Federation (in %)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('dividend_rate',['turn_over'=>'textbox','placeholder'=>'Dividend Rate Paid','label'=>false,'required'=>true,'class'=>'numberadndesimal not_functional', 'maxlength'=>'6'])?>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-3" id="auth_share_div" style="display:none;">
                            <label>11(e). Authorised Share Capital (Rs in Lakh):<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                        </div>

                        <div class="col-sm-3" id="turnover_div" style="display:none;">
                            <label>11(f). Annual Turn Over of the Federation (Rs in Lakh)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("annual_turnover",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-3 rdb">
                            <label>12. Is the Federation doing marketing?
                                <span class="important-field">*</span></label>
                            <?=$this->Form->control('federation_doing_marketing',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'marketing','type'=>'radio','label'=>false,'required'=>true,'onclick'=>'marketing_form()'])?>
                        </div>

                        <div class="col-sm-3 marketing_change" style="display: none;">
                            <label>12(a). Value of marketed product (Rs. in Lakhs)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('marketed_product_value',['type'=>'text','placeholder'=>'Dividend Rate Paid','label'=>false,'required'=>true,'class'=>'numbers', 'maxlength'=>'12'])?>

                        </div>

                    </div>










                    <!-----------------------pacs change------------------------------>


                    <!-----------------------dairy change start------------------------------>
                    <div class="dairy_change change"
                        style="display:<?=$StateDistrictFederation->sector_of_operation==9 ?'block':'none';?>;">
                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Dairy Details</strong></h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>13. Annual Total Milk Collection (In Liters) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[milk_collection]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10', 'value'=>$sd_fed_dairy->milk_collection])?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span class="loan_warning"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2 class="servce-hading">Services/Facilities Provided to the Members</h2>
                                    </div>
                                </div>

                                <div class="row sv-handling">
                                    <div class="col-sm-4">
                                        <label>14(a). Credit Facility <span class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[credit_facility]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_credit_facility','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->credit_facility])?>
                                    </div>
                                    <div class="col-sm-4 d_credit_facility_change"
                                        style="display:<?=$sd_fed_dairy->credit_facility==1 ?'block':'none';?>;">
                                        <label>14(b). Total Credit Provided in the Financial Year (Rs. In Lakhs) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[credit_provided]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10', 'value'=>$sd_fed_dairy->credit_provided])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(c). Milk Collection Unit Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[milk_collection_unit]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'milk_unit','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->milk_collection_unit])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4 milk_unit_change"
                                        style="display:<?=$sd_fed_dairy->milk_collection_unit==1 ?'block':'none';?>;">
                                        <label>14(d). Capacity of Milk Collection Unit (In Liters) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[milk_collection_capicity]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10', 'value'=>$sd_fed_dairy->milk_collection_capicity])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(e). Transportation of Milk <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[transport_milk]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'p_transport_milk','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->transport_milk])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(f). Bulk Milk Cooling (BMC) Unit Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[bulk_milk_unit]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_bulk_milk','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->bulk_milk_unit])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>14(g). Milk Testing Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[milk_testing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_milk_testing','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->milk_testing])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(h). Pasteurization and Processing Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[processing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'d_processing','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->processing])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(i). Other Facilities Provided (Please Specify) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_dairy[other_facility]",['label'=>false,'required'=>true, 'value'=>$sd_fed_dairy->other_facility])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----------------------dairy change close------------------------------>



                    <!-----------------------fishery change start------------------------------>
                    <div class="fishery_change change"
                        style="display:<?=$StateDistrictFederation->sector_of_operation==10 ?'block':'none';?>;">
                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Fishery Details</strong></h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>13. Annual Total Fish Catch (in Tonne)<span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[annual_fish_catch]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10'])?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span class="loan_warning"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h2 class="servce-hading">Services/Facilities Provided to the Members</h2>
                                    </div>
                                </div>
                                <div class="row sv-handling">
                                    <div class="col-sm-4">
                                        <label>14(a). Credit Facility <span class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[credit_facility]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_credit_facility','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 f_credit_facility_change" style="display:none;">
                                        <label>14(b). Total Credit Provided in the Financial Year (In Rs.) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[total_credit_provided]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10'])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(c). Distribution of Fuel <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[fuel_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_distribution','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>14(d). Marketing <span class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[marketing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_marketing','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(e). Cold Storage <span class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[cold_storage]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_cold_storage','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(f). Transportation <span class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[transportation]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'f_transportation','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>14(g). Other Facilities Provided (Please Specify) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("sd_federation_fishery[other_facility]",['label'=>false,'required'=>true])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----------------------fishery change close------------------------------>

                    <div class="activity_change_box">

                    </div>








                </div>
                <div class="box-footer text-center">
                    <?php
                    // if($this->request->session()->read('Auth.User.role_id') == 14)
                    // {
                        echo $this->Form->button(__('Save As Draft'),['class' => 'btn btn-warning submit mx-1','formnovalidate'=>'formnovalidate','name'=>'is_draft','value'=>'1']);
                    
                        echo $this->Form->button(__('Submit'),['class' => 'btn btn-success submit mx-1','name'=>'is_draft','value'=>'0']);
                        echo $this->Html->link(__('Cancel'),['action' => 'list'],['class' => 'btn btn-secondary mx-1','id' => 'cancel']); 

                    // }
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

<script src="<?=$this->request->webroot?>js/bootstrap-multiselect.js"></script>
<?= $this->Html->css(['bootstrap-multiselect.css']); ?>
<!-- start rural_village_box -->
<script type="text/javascript">
$(document).ready(function() {

    $('.select2').select2();


        $('body').on('click', '#save_members', function(e) {
            
            var curr_id = "";

            if($('#curr_id').val())
            {
                curr_id = $('#curr_id').val();
            } else {
                curr_id = 0;
            }

            var district = '';
            <?php if($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8){ ?>
                
                district = $('#all-district').val();
            <?php } ?>
            

            var members = [];
            $('input.member-checked:checkbox:checked').each(function () {
                members.push($(this).val());
            });

            return false;
            
            var curr_primary_activity = $('#all_primary_activity').val();
            if($('input.member-checked:checked').length > 0)
            {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'add-member'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    data: {
                        members: members,
                        sector: curr_primary_activity,
                        curr_id: curr_id,
                        district_code: district
                    },
                    success: function(response) {
                        var data = $.parseJSON(JSON.stringify(response));
                            $('#curr_id').val(data.curr_id);
                            $('#primary-cooperative-member-count').val(data.count);
                            $('#save_members').hide();
                            alert('updated successfully');
                        
                    },
                });

            } else {
                alert('Please select atleast one.');
            }

        });


        $('body').on('change', '#all-district', function(e) {

            $("#all_primary_activity").select2('destroy').val("").select2();
            $('.pacs_members').html('');
        });

    $('body').on('change', '#all_primary_activity', function(e) {


        $(".pacs_members").empty();
        var cur_sector = $(this).val();
        var state_code = $('#state-code').val();
        var curr_id = "";

        $('#save_members').show();
        if($('#curr_id').val())
        {
            curr_id = $('#curr_id').val();
        } else {
            curr_id = 0;
        }

        var district = '';
            <?php if($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8){ ?>
                
                district = $('#all-district').val();
            <?php } ?>

        $.ajax({
            type: 'POST',
            cache: false,
            url: '<?=$this->Url->build(['action'=>'get-fed'])?>',
            dataType: 'JSON',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            data: {
                primary_activity_selected_val: cur_sector,
                state_code: state_code,
                curr_id: curr_id,
                district_code: district
            },
            success: function(response) {
                var data = $.parseJSON(JSON.stringify(response));
                //console.log(data.table);
                $('#primary-cooperative-member-count').val(data.count);
                $(".pacs_members").append(data.table);
                $('.select2').select2();
                console.log($('input.member-checked:checked').length);
            },
        });

    });
    //     $('#RevisedForm').submit(function(e) {
    //         //e.preventDefault();

    //         if ($('.check:checked').length > 0) { //check is class name
    //             //$('form').submit();
    //             return true;
    //         } else {
    //             //checkboxes.attr('required', 'required');
    //             alert('Please select atleast 1 record');
    //             return false;
    //         }
    //     });

});

function for_other_affiliated(element) {
    console.log(element.value);
    if (element.value == -1) {
        document.getElementById('5g_other_div').style.display = "block";
    } else {
        document.getElementById('5g_other_div').style.display = "none";
    }
}

function select_membership_type(element) {
    console.log(element.value);

    if (element.value == 1) {
        document.getElementById('primary_cooperative_div').style.display = "block";
    } else {
        document.getElementById('primary_cooperative_div').style.display = "none";
    }

    if (element.value == 4) {
        document.getElementById('other_membership_div').style.display = "block";
    } else {
        document.getElementById('other_membership_div').style.display = "none";
    }
}



function add_other_member_row() {


    let row_count = Number(document.getElementById('other-member-count').value);
    document.getElementById('other-member-count').value = row_count + 1;



}

function is_affiliated(element) {

    $('.5g,5g_other').hide();

    console.log(element.value)
    document.getElementById('is-affiliated-union-federation-0').checked = true;
    document.getElementById('affiliated_union_federation_level_div').style.display = "none";
    document.getElementById('affiliated_union_federation_name_div').style.display = "none";

    document.getElementById('auth_share_div').style.display = "none";
    document.getElementById('turnover_div').style.display = "none";

    //id="affiliated_union_federation_name_div"
}

function is_making_profit(element) {
    console.log(element.value);
    if (element.value == 1) {

        document.getElementById('annual_turnover_div').style.display = "block";
        document.getElementById('annual_loss_div').style.display = "none";

    } else if (element.value == 0) {
        document.getElementById('annual_turnover_div').style.display = "none";
        document.getElementById('annual_loss_div').style.display = "block";
    }

}

function is_coop_society_member(element) {


    if (element.value == 1) {

        document.getElementById('federation-member-count-div').style.display = "block";
        document.getElementById('member-report-div').style.display = "block";

    } else if (element.value == 0) {
        document.getElementById('federation-member-count-div').style.display = "none";
        document.getElementById('member-report-div').style.display = "none";
    }
}
/*
function society_checked(element) {
    let federation_member_count = Number(document.getElementById('federation-member-count').value);
    console.log(element.checked)
    if (element.checked == true) {
        document.getElementById('federation-member-count').value = federation_member_count + 1;
    } else if (element.checked == false) {
        document.getElementById('federation-member-count').value = federation_member_count - 1;
    }





}*/


//============================================================================

function checkPlusMinusButtonOther() {

    var tr_row_main = $('.other_member_rows');
    var m = 1;
    $('.add_row_other_member').each(function() {
        if (m == 1) {
            $(this).show();
            $(this).parent('div').find('button.remove_row_other_member').hide();
        } else {
            $(this).hide();
            $(this).parent('div').find('button.remove_row_other_member').show();
        }
        m++;
    });
}
$(function() {

    checkPlusMinusButtonOther();
    //Component add row section
    var max_rural_village = 1000;
    var count_div_rural_village = parseFloat($(".other_section_box").find(".other_member_rows")
        .length);
    $('.other_section_box').on('click', '.add_row_other_member', function(e) {
        // if(count_div_rural_village == $('#other-member-count').val() || count_div_rural_village > $('#other-member-count').val())
        // {
        //     alert('Please Increase Is Member count for Other Members');
        //     return false;
        // }

        //console.log($('#other-member-count').val());
        // console.log(count_div_rural_village);
        e.preventDefault();
        if (count_div_rural_village < max_rural_village) {
            count_div_rural_village++;
            var ohr2 = parseFloat($(".other_section_box .other_member_rows:last").attr(
                "rowclass")) + 1;
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'other-member-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $(
                        '[name="_csrfToken"]').val());
                },
                data: {
                    ohr2: ohr2,
                    state_code: $('#state-code').val()
                },
                success: function(response) {
                    $(".other_section_box").append(response);
                    //$('.select2').select2();
                    checkPlusMinusButtonOther();
                },
            });
        }

        var t_pack_count = $('.pacs_is_member:checked').length;

        if ($('.pacs_member:checked').val() == 0) {
            t_pack_count = 0;
        }

        var total_count = $('.dccb_bank_is_member:checked').length + t_pack_count +
            count_div_rural_village;

        // $('#other-member-count').val(count_div_rural_village);


        $('#number-of-member').val(total_count);

        //console.log(total_count);

    });

    $(".other_section_box").on('click', '.remove_row_other_member', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_rural_village--;
        checkPlusMinusButtonOther();


        var count_div_rural_village = parseFloat($(".other_section_box").find(".other_member_rows")
            .length);

        var t_pack_count = $('.pacs_is_member:checked').length;

        if ($('.pacs_member:checked').val() == 0) {
            t_pack_count = 0;
        }


        var total_count = $('.dccb_bank_is_member:checked').length + t_pack_count +
            count_div_rural_village;

        $('#other-member-count').val(count_div_rural_village);

        $('#number-of-member').val(total_count);

        //console.log(total_count);



    });

    //Component add row section

});


//==============================================================================

function business_form(element) {

    document.getElementById("dividend_rate_div").style.display = "none";
    document.getElementById("annual_loss_div").style.display = "none";
    document.getElementById("is-dividend-paid-0").checked = true;
    document.getElementById("is-profit-making-1").checked = true;


    let primary_activity_val = document.getElementById("primary_activity").value;

    console.log('primary_activity_val', primary_activity_val)


    let change_form;
    if (primary_activity_val == 9) {
        change_form = document.getElementsByClassName("dairy_change")[0];
    } else if (primary_activity_val == 10) {
        change_form = document.getElementsByClassName("fishery_change")[0];
    } else if (primary_activity_val == 13) {
        change_form = document.getElementsByClassName("handloom_change")[0];
    } else if (primary_activity_val == 1 || primary_activity_val == 22) {
        change_form = document.getElementsByClassName("pacs_change")[0];
    } else if (primary_activity_val == 77) {
        change_form = document.getElementsByClassName("agriculture_change")[0];
    } else if (primary_activity_val == 79) {
        change_form = document.getElementsByClassName("bee_change")[0];
    } else if (primary_activity_val == 80) {
        change_form = document.getElementsByClassName("consumer_change")[0];
    } else if (primary_activity_val == 84) {
        change_form = document.getElementsByClassName("education_change")[0];
    } else if (primary_activity_val == 14) {
        change_form = document.getElementsByClassName("handicraft_change")[0];
    } else if (primary_activity_val == 90) {
        change_form = document.getElementsByClassName("jute_change")[0];
    } else if (primary_activity_val == 51) {
        change_form = document.getElementsByClassName("labour_change")[0];
    } else if (primary_activity_val == 54) {
        change_form = document.getElementsByClassName("livestock_change")[0];
    } else if (primary_activity_val == 29) {
        change_form = document.getElementsByClassName("miscellaneous_change")[0];
    } else if (primary_activity_val == 16) {
        change_form = document.getElementsByClassName("multi_change")[0];
    } else if (primary_activity_val == 47) {
        change_form = document.getElementsByClassName("housing_change")[0];
    } else if (primary_activity_val == 82) {
        change_form = document.getElementsByClassName("marketing_change2")[0];
    } else if (primary_activity_val == 31) {
        change_form = document.getElementsByClassName("processing_change")[0];
    } else if (primary_activity_val == 96) {
        change_form = document.getElementsByClassName("sericulture_change")[0];
    } else if (primary_activity_val == 98) {
        change_form = document.getElementsByClassName("social_change")[0];
    } else if (primary_activity_val == 11) {
        change_form = document.getElementsByClassName("sugar_change")[0];
    } else if (primary_activity_val == 99) {
        change_form = document.getElementsByClassName("tourism_change")[0];
    } else if (primary_activity_val == 68) {
        change_form = document.getElementsByClassName("transport_change")[0];
    } else if (primary_activity_val == 102) {
        change_form = document.getElementsByClassName("tribal_change")[0];
    } else if (primary_activity_val == 15) {
        change_form = document.getElementsByClassName("wocoop_change")[0];
    } else if (primary_activity_val == 18) {
        change_form = document.getElementsByClassName("credit_thrift_change")[0];
    } else if (primary_activity_val == 35) {
        change_form = document.getElementsByClassName("cmiscellaneous_change")[0];
    } else if (primary_activity_val == 7) {
        change_form = document.getElementsByClassName("ucb_change")[0];
    }

    console.log('down: change form')
    console.log(change_form)


    if (element.value == 1) {

        document.getElementById("auth_share_div").style.display = "block";
        document.getElementById("turnover_div").style.display = "block";

        document.getElementById("is_dividend_paid_div").style.display = "block";
        document.getElementById("annual_turnover_div").style.display = "block";
        document.getElementById("is_profit_making_div").style.display = "block";

        change_form.style.display = "block";
    } else if (element.value == 0) {

        document.getElementById("auth_share_div").style.display = "none";
        document.getElementById("turnover_div").style.display = "none";

        document.getElementById("is_profit_making_div").style.display = "none";
        document.getElementById("annual_turnover_div").style.display = "none";

        document.getElementById("is_dividend_paid_div").style.display = "none";
        change_form.style.display = "none";
    }

}



$('body').on('change', '.other_type_membership', function(e) {

    var inc = $(this).attr('inc');
    var t_pack_count = $('.pacs_is_member:checked').length;



    if ($(this).val() == 2 || $(this).val() == 3) {
        //if individual member



        $('.dtype').text('');

        $('#sd-federation-other-members-' + inc + '-member-document').val('');
        //if individual member
        if ($(this).val() == 3) {
            $('#sd-federation-other-members-' + inc + '-member-document').attr('accept', '.csv,text/csv');
            // $('#org_name_div').hide();
            // $('#org_name2_div').hide();


            $('.dtype').text('(Only CSV File)');
            //console.log('csv');
        }

        if ($(this).val() == 2) {

            // $('#org_name_div').hide();
            // $('#org_name2_div').hide();

            $('#sd-federation-other-members-' + inc + '-member-document').attr('accept', 'application/pdf');
            $('.dtype').text('(Only PDF File)');
            //console.log('pdf');
        }

        $('.' + inc + 'individual_div').hide();
        $('.' + inc + 'individual_input').val('');

        $('.' + inc + 'individual_upload_div').show();
    } else {
        // $('#org_name_div').show();
        // $('#org_name2_div').show();
        $('.' + inc + 'individual_div').show();
        $('.' + inc + 'individual_upload_div').hide();

    }
});


$('body').on('change', '.other_member', function(e) {

    $('#other-member-count').val('0');
    $('#society_district_code').val('');
    $('.other_is_member').prop('checked', false);
    if ($(this).val() == 1) {
        $('.other_member_count_row').show();
        $('.other_section').show();
    } else {
        $('.other_member_count_row').hide();
        $('.other_section').hide();
    }

    var other_member_count = 0;
    if ($(this).val() == 1) {
        other_member_count = $('.other_member_rows').length;
    } else {
        other_member_count = 0;
    }

    var t_pack_count = $('.pacs_is_member:checked').length;

    if ($('.pacs_member:checked').val() == 0) {
        t_pack_count = 0;
    }

    var total_count = $('.dccb_bank_is_member:checked').length + t_pack_count + other_member_count;

    $('#other-member-count').val(other_member_count);
    $('#number-of-member').val(total_count);


});


//number_of_members_div
// function other_members(element) {
//     console.log(element.value)
//     console.log(document.getElementsByClassName("other_member_rows")[0])
//     if (element.value == 1) {
//         document.getElementsByClassName("other_member_rows")[0].style.display = "block";
//         // document.getElementsByClassName("other_section_box")[0].style.display = "none";
//     } else {
//         document.getElementsByClassName("other_member_rows")[0].style.display = "none";
//         // document.getElementsByClassName("other_section_box")[0].style.display = "block";
//     }
// }

function any_other(element) {

    if (element.value == 3) {
        document.getElementById('any-other').style.display = "block";

    } else {
        document.getElementById('any-other').style.display = "none";
    }
}

function marketing_form() {

    let marketing = document.getElementsByClassName("marketing");

    let noButtonChecked = marketing[0].checked;
    let yesButtonChecked = marketing[1].checked;

    let marketing_form = document.getElementsByClassName("marketing_change")[0];

    if (noButtonChecked) {
        marketing_form.style.display = "block";
    } else if (yesButtonChecked) {
        marketing_form.style.display = "none";
    }

}

function get_fed(element) {

    // $(".pacs_members").append(response);
    $(".pacs_members").empty();
    let primary_activity_selected_val = document.getElementById("primary_activity").value;
    let district_code = element.value;
    let state_code = document.getElementById("state-code").value;

    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-fed'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            primary_activity_selected_val: primary_activity_selected_val,
            district_code: district_code,
            state_code: state_code

        },
        success: function(response) {

            $(".pacs_members").append(response);
            $('.select2').select2();
        },
    });
}


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
            $(this).show();
            $(this).parent('div').find('button.remove_row_rural_village').hide();
        } else {
            $(this).hide();
            $(this).parent('div').find('button.remove_row_rural_village').show();
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

$('body').on('change', '.housing_has_building', function() {
    if ($(this).val() == 1) {
        $('.housing_has_building_change').show();
    } else {
        $('.housing_has_building_change').hide();
    }
});

$('body').on('change', '.housing_has_store', function() {
    if ($(this).val() == 1) {
        $('.housing_has_store_change').show();
    } else {
        $('.housing_has_store_change').hide();
    }
});
</script>
<!-- end rural_village_box -->

<script>
(function($) {
    $(document).ready(function() {

        $('.select2').select2();

        $(".multiselect").multiselect({
            includeSelectAllOption: true,
            required: true,
            //maxHeight: 200 ,
            enableFiltering: true,
        });

        $('body').on('click', '.submit', function(e) {

            if ($(this).val() == '0') {
                e.preventDefault();

                if ($('#functional-status').val() == 1) {
                    $('.activity_change_box input').attr('required', 'required');
                    $('.activity_change_box select').attr('required', 'required');
                    $('.activity_change_box textarea').attr('required', 'required');
                    $('.not_functional').attr('required', 'required');

                    //pacs, dairy fishery
                    $('.pacs_change input').attr('required', 'required');
                    $('.pacs_change select').attr('required', 'required');
                    $('.pacs_change textarea').attr('required', 'required');

                    $('.dairy_change input').attr('required', 'required');
                    $('.dairy_change select').attr('required', 'required');
                    $('.dairy_change textarea').attr('required', 'required');

                    $('.fishery_change input').attr('required', 'required');
                    $('.fishery_change select').attr('required', 'required');
                    $('.fishery_change textarea').attr('required', 'required');

                } else if ($('#functional-status').val() == 2 || $('#functional-status').val() ==
                    3) {
                    $('.activity_change_box input').removeAttr('required');
                    $('.activity_change_box select').removeAttr('required');
                    $('.activity_change_box textarea').removeAttr('required');

                    $('.not_functional').removeAttr('required');

                    //pacs, dairy fishery
                    $('.pacs_change input').removeAttr('required');
                    $('.pacs_change select').removeAttr('required');
                    $('.pacs_change textarea').removeAttr('required');

                    $('.dairy_change input').removeAttr('required');
                    $('.dairy_change select').removeAttr('required');
                    $('.dairy_change textarea').removeAttr('required');

                    $('.fishery_change input').removeAttr('required');
                    $('.fishery_change select').removeAttr('required');
                    $('.fishery_change textarea').removeAttr('required');

                }

                $('.share').removeAttr('required');
                $('.select2').select2();

                if (confirm('Are you sure to submit?')) {
                    $("#RevisedForm").valid();
                    $('#RevisedForm').append(
                        '<input type="hidden" name="is_draft" value="0" />');
                    $("#RevisedForm").submit();
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
            sum = sum.toFixed(2);
            tval = 0;


            $('.land_total').val(sum);

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
            sum = sum.toFixed(2);
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


        $('#RevisedForm').on('change', '.sector_urban_local_body_code', function(e) {
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
                    $("<option value='-1'><b>Select All</b></option>").insertAfter(
                        '#sector-urban-' + increment +
                        '-locality-ward-code option:first');
                    $('#sector-urban-' + increment +
                        '-locality-ward-code option[value=""]').remove();
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

            var sector = $('#primary_activity').val();
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
                    district_code: $(this).val(),
                    sector: sector
                },
                success: function(response) {
                    console.log(response);
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

            if ($(this).val() == '-1') {
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
                        $("<option value='-1'><b>Select All</b></option>").insertAfter(
                            "#sector-" + increment + "-panchayat-code option:first");
                    },
                });
            }
        });

        $('body').on('change', '.sector_panchayat_code', function(e) {
            e.preventDefault();
            var increment = $(this).attr('increment');

            $('.sector_' + increment + '_village_code_div').show();
            $('#sector-' + increment + '-village-code').select2().attr('required');

            if ($(this).val() == '-1') {
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
                        $("<option value='-1'><b>Select All</b></option>").insertAfter(
                            "#sector-" + increment + "-village-code option:first");
                        $('#sector-' + increment + '-village-code option[value=""]')
                            .remove();
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
                if ($(this).val() == -1 && $('#sector-' + increment +
                        '-village-code > option:selected').length > 1) {
                    //console.log('#sector-' + increment + '-village-code');
                    $('#sector-' + increment + '-village-code').val(
                        '-1'); // Select the option with a value of '1'
                    $('#sector-' + increment + '-village-code ').trigger(
                        'change'); // Notify any JS components that the value chan
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
            $('.change').hide();

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
                    console.log(response)
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

        $('body').on('keypress', '.numbers', function(e) {

            //$('.numbers').on('keypress',function(event){
            // if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            //     ((event.which < 48 || event.which > 57) &&
            //         (event.which != 0 && event.which != 8))) {
            //     event.preventDefault();
            // }

            // var text = $(this).val();

            // if ((text.indexOf('.') != -1) &&
            //     (text.substring(text.indexOf('.')).length > 2) &&
            //     (event.which != 0 && event.which != 8) &&
            //     ($(this)[0].selectionStart >= text.length - 2)) {
            //     event.preventDefault();
            // }

            var keyCode = e.which ? e.which : e.keyCode

            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
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



        $(".number").change(function() {
            var inputvalues = $(this).val();
            var check = /^[0-9]{10}/;
            // var result = inputvalues.match(check);  
            if (inputvalues.match(/(\d)\1{9}/g)) {
                alert("invalid Phone no.");

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
            $("#RevisedForm").validate({
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
                        integer: true
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
            yearRange: "-122:-0",
        });

        //Location Details of Registration Office
        $('.location_of_head_quarter').on('change', function() {
            var location_of_head_quarter = $(this).val();
            document.getElementById("full-address").value = '';
            document.getElementById("pincode").value = '';
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

        $('#RevisedForm').on('change', '#primary_activity', function() {

            // $('.5g,5g_other').hide();
            // console.log('123');
            // console.log(document.getElementsByClassName('5g_other')[1]);
            // document.getElementsByClassName('5g_other')[1].style.display = "none";

            document.getElementById("form-filling-for").value = '';
            document.getElementById("district-code").value = '';
            var sector_of_operation_type = $(this).val();
            document.getElementsByClassName('business')[1].checked = true;
            document.getElementById('is-profit-making-0').checked = true;

            document.getElementById("auth_share_div").style.display = "none";
            document.getElementById("turnover_div").style.display = "none";
            document.getElementById("is_profit_making_div").style.display = "none";
            document.getElementById("annual_turnover_div").style.display = "none";
            document.getElementById("annual_loss_div").style.display = "none";
            document.getElementById("is_dividend_paid_div").style.display = "none";
            document.getElementById("dividend_rate_div").style.display = "none";


            $(".activity_change_box").html('');
            if (sector_of_operation_type == 8) {
                $('.sector_of_operation_other').show();
            } else {
                $('.sector_of_operation_other').hide();
                $('#sector-of-operation-other').val('');
            }
            /*
            $('.industry_type_div').hide();
            $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '<?=$this->Url->build(['action'=>'get-industry'])?>',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                            .val());
                    },
                    data: {
                        sector_of_operation: sector_of_operation_type
                    },
                    success: function(response) {
                        if(reponse)
                        {
                            $("#industry-type").html(response);
                            $('.industry_type_div').show();
                        }
                        

                    },
                });*/

            $(".submit").show();

            $('#functional-status').children('option[value="3"]').show();
            $('.select2').select2();

            //=======================================================
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-registration-authority'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    sector_of_operation_type: sector_of_operation_type
                },
                success: function(response) {

                    $("#registration-authoritie-id").html(response);
                },
            });
            //=======================================================

            if (sector_of_operation_type == 1 || sector_of_operation_type == 20 ||
                sector_of_operation_type == 22) {

                if (sector_of_operation_type == 20) {

                    $('#primary_activity_details').text('FSS Detail');
                }

                if (sector_of_operation_type == 22) {
                    // console.log(sector_of_operation_type)
                    $('#primary_activity_details').text('LAMPS Detail');
                }

                // if (sector_of_operation_type == 1) {

                //     $('#primary_activity_details').text('Pacs Detail');
                // }

                $('.change').hide();
                // $('.pacs_change').show();

            } else if (sector_of_operation_type == 9) {
                $('.change').hide();
                // $('.dairy_change').show();

                //Down: Mrigendra remove later 
                // $(".activity_change_box").html(
                //         '<div class="box-block-m"><h3>This form is under construction.</h3></div>'
                //     );
                //     $(".submit").hide();
                //     $("#cancel").hide();

            } else if (sector_of_operation_type == 10) {

                $('.change').hide();
                // $('.fishery_change').show();

                //Down: Mrigendra remove later 
                // $(".activity_change_box").html(
                //         '<div class="box-block-m"><h3>This form is under construction.</h3></div>'
                //     );
                //     $(".submit").hide();
                //     $("#cancel").hide();
            } else {
                $('.change').hide();

                var element = $("option:selected", this);
                var fun_name = element.attr("function");
                console.log('fun_name', fun_name);

                if (fun_name != '') {
                    // if (fun_name == 'p-agriculture'|| fun_name == 'p-consumer'|| fun_name == 'p-marketing'|| fun_name == 'p-housing'|| fun_name == 'p-sugar') {
                    $('.' + fun_name).show();

                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: baseUrl + '/state-district-federation/' + fun_name,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', $(
                                '[name="_csrfToken"]').val());
                        },
                        data: {
                            society_type: sector_of_operation_type,
                            cs: '<?= $CooperativeRegistration ?>'
                        },
                        success: function(response) {
                            console.log(response);
                            $(".activity_change_box").html(response);
                            $('.select2').select2();
                            $("#cancel").show();
                        },
                    });
                } else {
                    $(".activity_change_box").html(
                        '<div class="box-block-m"><h3>This form is under construction.</h3></div>'
                    );
                    $(".submit").hide();
                    $("#cancel").hide();

                }



            }

        });

        $('#RevisedForm').on('change', '#primary_activity', function() {
            var sector_of_operation = $(this).val();
            if (sector_of_operation == 68 || sector_of_operation == 13 || sector_of_operation ==
                77 || sector_of_operation == 99 || sector_of_operation == 35) {
                $('.area_operation_change').hide();
                $('.num_mem').hide();


            } else {
                $('.area_operation_change').show();
                $('.num_mem').show();
            }
        });

        // $('#CooperativeRegistrationForm').on('change', '#primary_activity', function() {
        //     var sector_of_operation = $(this).val();
        //     if (sector_of_operation == 102 || sector_of_operation == 99) {
        //         $('.type_of_bank').hide();
        //         $('.list_of_bank').hide();


        //     } else {
        //         $('.type_of_bank').show();
        //         $('.list_of_bank').show();
        //     }
        // });

        $('#RevisedForm').on('change', '#secondary-activity', function() {
            var secondary_activity = $(this).val();
            if (secondary_activity == 5) {
                $('.secondary_activity_other').show();
            } else {
                $('.secondary_activity_other').hide();
            }
        });


        $('#RevisedForm').on('change', '#state-code', function(e) {
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
                        state_code: state_code
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
                        $("#district-code").html(response);
                        $(".sector_district_code").html(response);
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
                        state_code: state_code
                    },
                    success: function(response) {
                        $("#district-code").html(response);
                        $(".sector_district_code").html(response);
                        $(".sector_urban_district_code").html(response);
                    },
                });
            }


        });

        $('#RevisedForm').on('change', '#urban-local-body-type-code', function(e) {
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

        $('#RevisedForm').on('change', '#urban-local-body-code', function(e) {
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



        $('#RevisedForm').on('change', '#district-code', function(e) {
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
        $('#RevisedForm').on('change', '#block-code', function(e) {
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
        $('#RevisedForm').on('change', '#gram-panchayat-code', function(e) {
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


        $('body').on('change', '.bank_type', function() {

            $('.bank_other').hide();
            if ($.inArray("1", $(this).val()) != -1) {
                $('.list_of_bank').show();
            } else {
                //console.log('fail');
                $('.list_of_bank').hide();
            }

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-bank'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    bank_type: $(this).val()
                },
                success: function(response) {
                    $(".bank_id").html(response);
                    $('.bank_id').multiselect('destroy');
                    $('.bank_id').multiselect({
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        includeSelectAllOption: true
                    });

                },
            });
        });


        $('body').on('change', '.bank_id', function() {

            var bank_type = $(this).val();

            if ($.inArray("38", bank_type) != -1) {
                $('.bank_other').show();
            } else {
                $('#other-bank').val('');
                $('.bank_other').hide();
            }
        });

    });

    $('body').on('change', '#affiliated-union-federation-level', function(e) {
        e.preventDefault();

        var union_lavel = $(this).val();
        var primary_activity = $('#primary_activity').val();

        var entity_type = $("#affiliated-union-federation-level option:selected").val();


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
    $('body').on('change', '#is-affiliated-union-federation-1,#primary_activity,#form-filling-for', function(e) {
        e.preventDefault();

        var primary_activity = $('#primary_activity').val();
        var state_code = $('#state-code').val();
        var federation_1 = $('#is-affiliated-union-federation-1').val();

        let form_filling_for = document.getElementById('form-filling-for').value;
        // console.log(form_filling_for);

        if (federation_1 == 1) {

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
                    state: state_code,
                    form_filling_for: form_filling_for
                },
                success: function(response) {
                    console.log(response)
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

    // $('body').on('change', '#is-profit-making-1, #is-profit-making-0', function(e) {
    //     e.preventDefault();
    //     if ($(this).val() == 1) {
    //         $('.total_revenue').show();
    //         $('.total_loss').hide();
    //     } else {
    //         $('.total_revenue').hide();
    //         $('.total_loss').show();
    //         $('#annual-turnover').val('');
    //     }
    // });

    $('body').on('change', '.is_socitey_has_land', function() {
        if ($(this).val() == 1) {
            $('.available_land').show();
        } else {
            $('.available_land').hide();
        }
    });

    $('body').on('change', '.land_avilabele_area', function() {
        if ($(this).val() != '') {
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




    // $('body').on('change', '#registration-authoritie-id', function() {  

    //     if ($(this).val() != 1) {
    //        $("#sector_operation option[value='1']").remove();
    //     } else {
    //         $("#sector_operation option[value='1']").remove();
    //        $("#sector_operation").append("<option value='1'>Credit</option>");
    //     }
    // });

    $('body').on('change', '.operation_area_location', function() {
        var operation_area_location = $(this).val()
        //sector_rural_location_section
        var state_code = $('#state-code').val();

        //empty urban
        // $('.sector_urban_district_code').html('');
        $('.sector_urban_local_body_code').html('');
        $('.sector_locality_ward_code').html('');

        //empty rural
        $('.sector_block_code').html('');
        $('.sector_panchayat_code').html('');
        $('.sector_village_code').html('');

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

        if (operation_area_location == 1) {
            //Urban location

            $('.area_operation_urban_div').show();
            $('.area_operation_rural_div').hide();
            $('.sector_urban_location_section').each(function() {
                $(this).show();
            });

            $('.sector_rural_location_section').each(function() {
                $(this).hide();
            });

            $('.area_operation_rural').val('');
            $('.area_operation_urban').val('');

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

            // $.ajax({
            //     type: 'POST',
            //     cache: false,
            //     url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
            //     beforeSend: function(xhr) {
            //         xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
            //             .val());
            //     },
            //     data: {
            //         state_code: state_code
            //     },
            //     success: function(response) {
            //         $(".sector_urban_district_code").html(response);

            //     },
            // });

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


            // $.ajax({
            //     type: 'POST',
            //     cache: false,
            //     url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
            //     beforeSend: function(xhr) {
            //         xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
            //             .val());
            //     },
            //     data: {
            //         state_code: state_code
            //     },
            //     success: function(response) {
            //         $(".sector_district_code").html(response);
            //         $(".sector_urban_district_code").html(response);

            //     },
            // });

        } else if (operation_area_location == 3) {
            //if urban and rural both

            $('.sector_urban_location_section').each(function() {
                $(this).show();
            });

            $('.area_operation_rural_div').show();
            $('.area_operation_urban_div').show();

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

            // $.ajax({
            //     type: 'POST',
            //     cache: false,
            //     url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
            //     beforeSend: function(xhr) {
            //         xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
            //             .val());
            //     },
            //     data: {
            //         state_code: state_code
            //     },
            //     success: function(response) {
            //         $(".sector_district_code").html(response);
            //         $(".sector_urban_district_code").html(response);

            //     },
            // });


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

    /*$('body').on('change', '#functional-status', function() {

        if ($(this).val() == 1) {
            $('.loan_warning').html('<b style="color:red">Warning!</b> It should be greater then 0 if Present Functional Status is functional.');
            $('.activity_change_box input').attr('required','required');
            $('.activity_change_box select').attr('required','required');
            $('.activity_change_box textarea').attr('required','required');
            $('.not_functional').attr('required','required');
            $('.select2').select2();
            
        } else {
            
            $('.loan_warning').html('');
            $('.activity_change_box input').removeAttr('required');
            $('.activity_change_box select').removeAttr('required');
            $('.activity_change_box textarea').removeAttr('required');

            $('.not_functional').removeAttr('required');

        }
    });*/

    $('body').on('change', '.sector_locality_ward_code', function(e) {
        e.preventDefault();
        //console.log($(this).val());
        var increment = $(this).attr('increment');

        console.log($('#sector-urban-' + increment + '-locality-ward-code > option:selected').length);

        $('#sector-urban-' + increment + '-locality-ward-code > option:selected').each(function() {
            if ($(this).val() == -1 && $('#sector-urban-' + increment +
                    '-locality-ward-code > option:selected').length > 1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sector-urban-' + increment + '-locality-ward-code').val(
                    '-1'); // Select the option with a value of '1'
                $('#sector-urban-' + increment + '-locality-ward-code').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });

})($);

//for other option in 'Name of Affiliated Union/Federation'
$('body').on('change', '.affiliated_union_federation_name', function() {
    $('#affiliated-union-federation-name-for-other').val();
    if ($(this).val() == '-1') {
        $('.affiliated_union_federation_name_change').show();
    } else {
        $('.affiliated_union_federation_name_change').hide();
    }
});

$('body').on('change', '.sector_of_operation', function() {
    $('#primary_activity').val();
    if ($(this).val() == '29') {
        $('.typemis').show();
        $('.prevlabel').hide();
    } else {
        $('.prevlabel').show();
        $('.typemis').hide();
    }
});


$('body').on('change', '.sector_of_operation', function() {
    $('#primary_activity').val();
    if ($(this).val() == '7') {
        $('input:radio[name=location_of_head_quarter][value=1]').click();
        $('.tierall').hide();
        $('.area_operation_change').hide();
        //$('.list_of_bank').hide();
        //$('.none-selected-div--1').hide();
        // $('.area_operation_change2').hide();
        // $('.num_mem').hide();





        // $("input[name=location_of_head_quarter][value='1']").prop("checked",true);
    } else {
        $('input:radio[name=location_of_head_quarter][value=2]').click();
        $('.tierall').show();
        $('.area_operation_change').show();
        $('.list_of_bank').show();
        $('.none-selected-div--1').show();
        $('.area_operation_change2').show();
        $('.num_mem').show();




    }
});
</script>
<script>
function fill_address() {
    let state = document.getElementById("full-address").value = '';
    let location_of_head_quarter = document.getElementsByClassName("location_of_head_quarter");

    let urbanChecked = location_of_head_quarter[0].checked;
    let ruralChecked = location_of_head_quarter[1].checked;
    if (urbanChecked) {

        let state = document.getElementById("state-code");
        let stateEleVal = state.selectedIndex;
        let stateEleTxt = state.options[stateEleVal].text;

        let urban_local_body_type_code = document.getElementById("urban-local-body-type-code");
        let urban_local_body_type_code_val = urban_local_body_type_code.selectedIndex;
        let urban_local_body_type_code_txt = urban_local_body_type_code.options[urban_local_body_type_code_val].text;

        let urban_local_body_code = document.getElementById("urban-local-body-code");
        let urban_local_body_code_val = urban_local_body_code.selectedIndex;
        let urban_local_body_code_txt = urban_local_body_code.options[urban_local_body_code_val].text;

        let locality_ward_code = document.getElementById("locality-ward-code");
        let locality_ward_code_val = locality_ward_code.selectedIndex;
        let locality_ward_code_txt = locality_ward_code.options[locality_ward_code_val].text;

        let pin_code = document.getElementById("pincode");
        let pin_code_val = pin_code.value;

        let fill_full_address = locality_ward_code_txt + ', ' + urban_local_body_code_txt + ', ' +
            urban_local_body_type_code_txt + ', ' + stateEleTxt + ', ' + pin_code_val;

        console.log(fill_full_address);
        document.getElementById("full-address").value = fill_full_address;

    } else if (ruralChecked) {

        let state = document.getElementById("state-code");
        let stateEleVal = state.selectedIndex;
        let stateEleTxt = state.options[stateEleVal].text;


        let district_code = document.getElementById("district-code");
        let district_code_val = district_code.selectedIndex;
        let district_code_txt = district_code.options[district_code_val].text;


        let block_code = document.getElementById("block-code");
        let block_code_val = block_code.selectedIndex;
        let block_code_txt = block_code.options[block_code_val].text;
        console.log(stateEleTxt, district_code_txt, block_code_txt);

        let gram_panchayat_code = document.getElementById("gram-panchayat-code");
        let gram_panchayat_code_val = gram_panchayat_code.selectedIndex;
        let gram_panchayat_code_val_txt = gram_panchayat_code.options[gram_panchayat_code_val].text;

        let village_code = document.getElementById("village-code");
        let village_code_val = village_code.selectedIndex;
        let village_code_val_txt = village_code.options[village_code_val].text;

        let pin_code = document.getElementById("pincode");
        let pin_code_val = pin_code.value;

        let fill_full_address = village_code_val_txt + ', ' + gram_panchayat_code_val_txt + ', ' + block_code_txt +
            ', ' + district_code_txt + ', ' + stateEleTxt + ', ' + pin_code_val;

        console.log(fill_full_address);
        document.getElementById("full-address").value = fill_full_address;
    }

}


function onlyNumbers(num) {
    if (/[^0-9]+/.test(num.value)) {
        num.value = num.value.replace(/[^0-9]*/g, "")
    }
}


function onlyChar(num) {
    if (/[^a-zA-Z ]+/.test(num.value)) {
        num.value = num.value.replace(/[^a-zA-Z ]*/g, "")
    }
}
</script>

<style>
/* .rdb .radio .form-check {
    margin-right: 21px;
    } */

.loan_warning {
    margin-left: 20px;
    /* color: #d73925!important;
    position: absolute;
    bottom: -24px;
    font-weight: normal;
    font-size: 12px; */
}

.set-form-check .form-group div {
    display: flex;
}


.set-form-check .form-check {
    margin-right: 107px;
}

.mb-n {
    margin-bottom: -12px;
}

label {
    line-height: 1;
}

.rural_village_rows.extra_rural_rows {
    background: none;
    border: none;
    margin: 0;
}

.design_row-sect button.pull-right.btn.btn-danger.btn-xs.remove_row_rural_village,
.design_row-sect .add_row_sec button,
button.pull-right.btn.btn-primary.btn-xs.add_row_rural_village {
    width: 25px !important;
    height: 25px !important;
    border-radius: 0 !important;
    font-size: 12px !important;
}
</style>
<?php $this->end(); ?>