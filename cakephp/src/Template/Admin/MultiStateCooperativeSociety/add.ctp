<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration $CooperativeRegistration
 */
$this->assign('title',__('Add New Multi State Cooperative Society'));
$this->assign('content_header',__(' Multi State Cooperative Society'));
$this->Breadcrumbs->add(__('Cooperative Registrations'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Multi State Cooperative Society'));
//$this->assign('top_head',__('hide'));
?>

<!-- Main content -->
<section class="content Multi State Cooperative Society add form">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title bold-b">
                        <?= __('Multi State Cooperative Society') ?>
                        <!--<small><?= __('Add New Multi State Cooperative Society') ?></small>-->
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'add'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to MSCS'),'escape' => false]
                  );?>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="contentbox dak">
                    <?php if(!empty($CooperativeRegistration->errors())) { ?>
                    <div>
                        <?php
                                //echo '<pre>';
                                //print_r($CooperativeRegistration->errors());
                            ?>
                    </div>
                    <span id="lblMessage" class="success">
                        <?php echo $this->Flash->render();
                            echo "<p>".$message."</p>"; ?>
                    </span> <?php  }  ?>
                </div>

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
                            <div class="col-sm-3">
                                <label>1(a). Name of the MSCS Society<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name',['type'=>'text','placeholder'=>'Name of Multi State Cooperative Society','label'=>false,'required'=>true])?>
                            </div>

                            <div class="col-sm-3">
                                <label>1(b). Registration Number<span class="important-field">*</span></label>
                                <?=$this->Form->control('registration_number',['maxlength'=>30,'class'=>'','type'=>'text','placeholder'=>'Registration Number','label'=>false,'required'=>true])?>
                            </div>

                            <div class="col-sm-3">
                                <label>1(c). Date of Registration<span class="important-field">*</span></label>
                                <?=$this->Form->control('date_registration',['type'=>'text','placeholder'=>'Date of Registration','label'=>false,'class'=>'before_date','required'=>true,'readonly'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>1(d).Registration Authority<span class="important-field">*</span></label>
                                <?=$this->Form->control('registration_authoritie_id',['options'=>$register_authorities,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                            </div>

                            <div class="col-sm-3 ">
                            <label>1(e). Whether listed under Schedule-II of MSCS Act,2002 <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_under_schedule_two',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'dividend1','type'=>'radio','label'=>false,'required'=>true])?>
                        </div>
                        <div class="col-sm-3 dividend_change1"
                            style="display:<?=$CooperativeRegistration->is_under_schedule_two == 1 ?'block' : 'none' ;?>;">
                            <label>1(f). MSCS Society<span
                                    class="important-field">*</span></label>
                                  <?php  $mscsList = ['1'=>'Society test 1','2'=>'Society test 2','3'=>'Society test 3']; ?>
                            <?= $this->Form->control('mscs_name',['options'=>$mscsList,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                           
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
                            <?=$this->Form->control('location_of_head_quarter',['options'=>[1=>'Urban',2=>'Rural'],'id'=>'location_of_head_quarter','class'=>'location_of_head_quarter','default'=>'2','type' => 'radio','label'=>false])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(b). State / UT <span class="important-field">*</span></label>
                            <?=$this->Form->control('state_code',['options'=>$states,'label'=>false,'empty'=>'Select','type'=>'select','class'=>'select2','value'=>$state_code])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(c). District <span class="important-field">*</span></label>
                            <?=$this->Form->control('district_code',['options'=>$districts,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select'])?>
                        </div>

                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(d). Block <span class="important-field">*</span></label>
                            <?=$this->Form->control('block_code',['options'=>$blocks,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select'])?>
                        </div>

                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(e). Gram Panchayat <span class="important-field">*</span></label>
                            <?=$this->Form->control('gram_panchayat_code',['options'=>$gps,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select'])?>
                        </div>

                        <!--<div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(f). Gram Panchayat is Coastal  <span class="important-field">*</span></label>
                            <?=$this->Form->control('is_coastal',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','label'=>false])?>
                        </div>-->

                        <div class="col-sm-4 rural_location_section"
                            style="display:<?= $CooperativeRegistration->location_of_head_quarter==1?'none':'block';?>;">
                            <label>2(g). Village <span class="important-field">*</span></label>
                            <?=$this->Form->control('village_code',['options'=>$villages,'empty'=>'Select','label'=>false,'class'=>'select2','type'=>'select'])?>
                        </div>

                        <div class="col-sm-4 urban_location_section"
                            style="display: <?=$CooperativeRegistration->location_of_head_quarter==1?'block':'none';?>;">
                            <label>2(c). Category of Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control('urban_local_body_type_code',['options'=>$localbody_types,'empty'=>'Select','label'=>false,'class'=>'select2'])?>
                        </div>

                        <div class="col-sm-4 urban_location_section"
                            style="display: <?=$CooperativeRegistration->location_of_head_quarter==1?'block':'none';?>;">
                            <label>2(d). Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control('urban_local_body_code',['options'=>$localbodies,'empty'=>'Select','label'=>false,'class'=>'select2'])?>
                        </div>

                        <div class="col-sm-4 urban_location_section"
                            style="display: <?=$CooperativeRegistration->location_of_head_quarter==1?'block':'none';?>;">
                            <label>2(e). Locality or Ward <span class="important-field"></span></label>
                            <?=$this->Form->control('locality_ward_code',['options'=>$localbodieswards,'label'=>false,'class'=>'select2'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>2(f). Pin Code <span class="important-field">*</span></label>
                            <?=$this->Form->control('pincode',['type'=>'textbox','class'=>'pincode','label'=>false,'placeholder'=>'Pin Code','minlength'=>6,'maxlength'=>6])?>
                        </div>
                        <div class="col-sm-4">
                        <label>2(h). Full Address <span class="important-field">*</span></label>
                        <?=$this->Form->control('full_address',['type'=>'textbox','class'=>'address','label'=>false,'placeholder'=>'Full Address', 'maxlength' => '255'])?>
                    </div>
                    
                    </div>

                    <div class="clearfix"></div>
                        <div class="box-block-m">
                        <!-- <div class="col-sm-12">
                                <h4><strong>Present Functional Status of the MSCS</strong></h4>
                            </div> -->
                            <div class="col-sm-3">
                        <label>3. Present Functional Status<span class="important-field">*</span></label>
                        <?=$this->Form->control('functional_status',['options'=>$PresentFunctionalStatus,'label'=>false,'class'=>'select2'])?>
                    </div>
                        </div>

                    <!-------------------------------------------->
                     <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>4(a). Details of Contact Person's of the MSCS</strong></h4>
                        </div>
                        <div class="col-sm-3">
                            <label>Name<span class="important-field">*</span></label>
                            <?=$this->Form->control('contact_person',['type'=>'textbox','placeholder'=>'Name','label'=>false, 'maxlength'=>'200'])?>
                        </div>
                        <div class="col-sm-3">
                            <label>Designation<span class="important-field">*</span></label>
                            <?=$this->Form->control('designation',['options'=>$designations,'empty'=>'Select','label'=>false,'class'=>'select2'])?>
                        </div>

                        <div class="col-sm-3">
                            <label>Mobile Number<span class="important-field">*</span></label>
                            <?=$this->Form->control('mobile',['type'=>'textbox','class'=>'number','placeholder'=>'Mobile Number','label'=>false,'maxlength'=>'10'])?>
                        </div>

                        <div class="col-sm-3">
                            <label>Landline Number <sup class="blue">#</sup></label>
                            <div class="row">
                                <div class="col-sm-3">
                                    <?=$this->Form->control('std',['type'=>'textbox', 'class'=>'number','placeholder'=>'STD Code','maxlength'=>'4','label'=>false,'style'=>'width:82px;'])?>
                                </div>
                                <div class="col-sm-9">
                                    <?=$this->Form->control('landline',['type'=>'textbox','class'=>'number','placeholder'=>'Landline Number', 'maxlength'=>'8','label'=>false])?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Email ID </label>
                            <?=$this->Form->control('email',['type'=>'email','label'=>false,'placeholder'=>'Email ID', 'maxlength'=>'50'])?>
                        </div>
                        <div class="col-sm-3">
                            <label style="margin-top:31px;font-size: 17px;"><span style="color:black;">Note :</span>
                                <sup class="blue">#</sup> Fields are optional</label>
                        </div>
                    </div>

                    <!-------------------------------------------->
                    
    <!-- <div class="clearfix"></div>

    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong>Contact Details</strong></h4>
        </div>
        <div class="phone_number_section">
            <?php 
            if(!empty($StateCooperativeBank->scb_contact_details)):
                foreach ($StateCooperativeBank->scb_contact_details as $mhr2 => $scb_contact_detail):
                    $landline_data = explode('-',$scb_contact_detail->landline);
                    if(!empty($landline_data))
                    {
                        $std = $landline_data[0];
                        $landline = $landline_data[1];
                    }
            ?>
                        <?=$this->Form->control("scb_contact_details.$mhr2.id",['type'=>'hidden','required'=>true])?>
                        <div class="phone_number_rows" rowclass="<?=$mhr2?>">
                            <div class="col-sm-3">
                                <label>3(a) Name of the Contact Person<span class="important-field">*</span></label>
                                <?=$this->Form->control("scb_contact_details.$mhr2.contact_person",['type'=>'textbox','placeholder'=>'Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                            </div>
                            <div class="col-sm-3">
                                <label>3(b) Designation of the Contact Person<span class="important-field">*</span></label>
                                <?=$this->Form->control("scb_contact_details.$mhr2.designation",['options'=>$designations,'empty'=>'Select','label'=>false,'class'=>'select2 designation','required'=>true])?>
                            </div>
                            <div class="col-sm-3 designation_other" style="display:none;">
                                <label>3(b)(i) Designation Other<span class="important-field">*</span></label>
                                <?=$this->Form->control("scb_contact_details.$mhr2.designation_other",['type'=>'textbox','class'=>'','placeholder'=>'Other Desigantion','label'=>false,'required'=>true, 'maxlength'=>'30'])?>
                            </div>
                            <div class="col-sm-3">
                                <label>3(c). Phone Number of Contact Person<span class="important-field">*</span></label>
                                <?=$this->Form->control("scb_contact_details.$mhr2.phone_number",['type'=>'text','class'=>'number','placeholder'=>'Mobile Number','label'=>false,'required'=>true,'minlength'=>'10','maxlength'=>'10'])?>
                            </div>
                            <div class="col-sm-3">
                                <label>3(d). Landline Number <sup class="blue">#</sup></label>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <?=$this->Form->control("scb_contact_details.$mhr2.std",['type'=>'textbox', 'class'=>'number','placeholder'=>'STD Code','maxlength'=>'4','label'=>false,'style'=>'width:82px;','value'=>$std])?>
                                    </div>
                                    <div class="col-sm-9">
                                        <?=$this->Form->control("scb_contact_details.$mhr2.landline",['type'=>'textbox','class'=>'number','placeholder'=>'Landline Number', 'maxlength'=>'8','label'=>false,'value'=>$landline])?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label>3(e). E-mail ID of Contact Person<span class="important-field">*</span></label>
                                <?=$this->Form->control("scb_contact_details.$mhr2.email",['type'=>'email','placeholder'=>'E-mail ID of Contact Person','required'=>true,'label'=>false,'maxlength'=>'200'])?>
                            </div>
                            <div class="col-sm-12">
                                <button style="margin-top: -15px;" type="button"
                                    class="pull-right btn btn-primary btn-xs add_row_phone_number">
                                    <i class="fa fa-plus-circle"></i>
                                </button>
                                <button style="display: none;margin-bottom: 10px" type="button"
                                    class="pull-right btn btn-danger btn-xs remove_row_phone_number">
                                    <i class="fa fa-minus-circle"></i>
                                </button>
                            </div>
                        </div>
            <?php 
                endforeach; else:?>
                            <?php $mhr2=0;?>
            <div class="phone_number_rows" rowclass="<?=$mhr2?>">
                <div class="col-sm-3">
                    <label>3(a) Name<span class="important-field">*</span></label>
                    <?=$this->Form->control("scb_contact_details.$mhr2.contact_person",['type'=>'textbox','placeholder'=>'Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                </div>
                <div class="col-sm-3">
                    <label>3(b) Designation<span class="important-field">*</span></label>
                    <?=$this->Form->control("scb_contact_details.$mhr2.designation",['options'=>$designations,'empty'=>'Select','label'=>false,'class'=>'select2 designation','required'=>true])?>
                </div>
                <div class="col-sm-3 designation_other" style="display:none;">
                    <label>3(b)(i) Designation Other<span class="important-field">*</span></label>
                    <?=$this->Form->control("scb_contact_details.$mhr2.designation_other",['type'=>'textbox','class'=>'','placeholder'=>'Other Desigantion','label'=>false,'required'=>true, 'maxlength'=>'30'])?>
                </div>
                <div class="col-sm-3">
                    <label>3(c). Phone Number<span class="important-field">*</span></label>
                    <?=$this->Form->control("scb_contact_details.$mhr2.phone_number",['type'=>'text','class'=>'number','placeholder'=>'Mobile Number','label'=>false,'required'=>true,'minlength'=>'10','maxlength'=>'10'])?>
                </div>
                <div class="col-sm-3">
                    <label>3(d). Landline Number <sup class="blue">#</sup></label>
                    <div class="row">
                        <div class="col-sm-3">
                            <?=$this->Form->control("scb_contact_details.$mhr2.std",['type'=>'textbox', 'class'=>'number','placeholder'=>'STD Code','maxlength'=>'4','label'=>false,'style'=>'width:82px;'])?>
                        </div>
                        <div class="col-sm-9">
                            <?=$this->Form->control("scb_contact_details.$mhr2.landline",['type'=>'textbox','class'=>'number','placeholder'=>'Landline Number', 'maxlength'=>'8','label'=>false])?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label>3(e). E-mail ID </label>
                    <?=$this->Form->control("scb_contact_details.$mhr2.email",['type'=>'email','placeholder'=>'E-mail ID of Contact Person','required'=>true,'label'=>false, 'maxlength'=>'200'])?>
                </div>

                <div class="col-sm-12">
                    <button style="margin-top: -15px;" type="button"
                        class="pull-right btn btn-primary btn-xs add_row_phone_number">
                        <i class="fa fa-plus-circle"></i>
                    </button>
                    <button style="display: none;margin-bottom: 10px" type="button"
                        class="pull-right btn btn-danger btn-xs remove_row_phone_number">
                        <i class="fa fa-minus-circle"></i>
                    </button>
                </div>
            </div>
            <?php 
            endif;?>
            <div class="col-sm-4" style="display:none;">
                <label style=""><span style="color:black;">Note :</span>
                    <sup class="blue23" style="color:#2196f3;">#</sup> Fields are optional</label>
            </div>
        </div>

    </div> -->

            <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Area of Operation</strong></h4>
                        </div>
                        <div class="col-sm-4 ">
                            <label>4(b). Whether Area of Operation of MSCS is whole of Union of India?<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.is_whole_union",['options'=>['2'=>'Yes','1'=>'No'],'default'=>1,'class'=>'is_operational_area','type'=>'radio','label'=>false])?>
                        </div>

                <div class="area_of_operation_state" style="display:<?= $CooperativeRegistration->is_whole_union == 1 ? 'none' : 'block'; ?>;">
                        <div class="col-sm-4">
                        <label>4(c). Select Name of State/UTs Area of Operation of MSCS<span class="important-field">*</span></label>
                        <?php 
                            $StateCooperativeBank->area_of_operation_state_code=!empty($StateCooperativeBank->area_of_operation_state_code)?explode(',',$StateCooperativeBank->area_of_operation_state_code):[];   
                        ?>

                        <?=$this->Form->control('area_of_operation_state_code',['options'=>$states,'label'=>false,'type'=>'select','class'=>'select2','multiple'=>true,'id'=>'list'])?>
                      </div>

                      <div class="col-sm-4">
                            <label>4(d). Sector of Operation<span class="important-field">*</span></label>
                            <?=$this->Form->control('sector_of_operation_type',['options'=>$sector_operations,'id'=>'sector_operation','empty'=>'Select','label'=>false,'class'=>'select2'])?>
                        </div>

                        <div class="col-sm-4">
                            <label>4(e). Primary Activity<span class="important-field">*</span></label>
                            <?=$this->Form->control('sector_of_operation',['options'=>$PrimaryActivities,'id'=>'primary_activity','empty'=>'Select','label'=>false,'class'=>'select2'])?>
                        </div>

                        <div class="col-sm-4 ">
                            <label>4(f). Whether The Cooperative Bank is Scheduled<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.is_whole_union22",['options'=>['2'=>'Yes','1'=>'No'],'default'=>1,'type'=>'radio','label'=>false])?>
                        </div>

                        

        </div>
     
    </div>
<!--===========================================================================================================-->


            <div class="clearfix"></div>

                    <div class="box-block-m">
                    <div class="col-sm-3">
                            <label>5(a). Total Number of Members of the MSCS<span class="important-field">*</span></label>
                            <?=$this->Form->control('contact_person1',['type'=>'textbox','placeholder'=>'Name','label'=>false, 'maxlength'=>'200'])?>
                        </div>

                        <p class="col-sm-12 row pacs_member_count_row" style="font-size:18px;text-decoration: underline;">5(b). Details of Member of the MSCS</p>
                        <div class="col-sm-12 pacs_member_table">
                            <div class="table-responisve tablenew">
                                <table class="table table-striped table-white table-bordered" id="tab_logic2">
                                    <thead class="table-sticky-header">
                                        <tr style="background-color: #599ec6; color: #fff;">
                                            <th style="width: 2%">
                                                S.No.
                                            </th>
                                            <th style="width: 15%">
                                                State Name
                                            </th>
                                            <th style="width: 15%">
                                                Type of Member
                                            </th>
                                            <th style="width: 15%">
                                                State wise Number of Members
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody class="area_of_operation">
                                  
                                    </tbody>
                                </table>
                            </div>
                    
                        </div>
                    </div>


                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>5(c). Institutional Member of MSCS as per Section 25 of MSCS Act,2002</strong></h4>
                        </div>
                        <div class="col-sm-3 ">
                            <label>(i). Central Government <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('central_gov',['options'=>['1'=>'Yes','0'=>'No'],'class'=>'dividend1','type'=>'radio','label'=>false])?>
                        </div>
                        <div class="col-sm-3 ">
                            <label>(ii). NCDC <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('ncdc',['options'=>['1'=>'Yes','0'=>'No'],'class'=>'dividend1','type'=>'radio','label'=>false])?>
                        </div>

                        <div class="col-sm-3">
                            <label>(iii). State / UT <span class="important-field">*</span></label>
                            <?=$this->Form->control('state_code_jh',['options'=>$states,'label'=>false,'empty'=>'Select','type'=>'select','class'=>'select2','value'=>$state_code])?>
                        </div>

                        <div class="col-sm-3">
                                <label>(iv). Government Corporation<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name2',['type'=>'text','placeholder'=>'Government Corporation','label'=>false])?>
                            </div>
                        <div class="col-sm-3">
                            <label>(v). Government Company<span class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_name3',['type'=>'text','placeholder'=>'Government Company','label'=>false])?>
                        </div>
                        <div class="col-sm-3">
                            <label>(vi). Firm Company or Other Body<span class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_name4',['type'=>'text','placeholder'=>'Firm Company or Other Body','label'=>false])?>
                        </div>
                        <div class="col-sm-3">
                            <label>(vii). Registered Society<span class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_name5',['type'=>'text','placeholder'=>'Registered Society','label'=>false])?>
                        </div>
                        <div class="col-sm-3">
                            <label>(viii). Local Authority<span class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_name6',['type'=>'text','placeholder'=>'Local Authority','label'=>false])?>
                        </div>
                        <div class="col-sm-3">
                            <label>(ix). Public Trust<span class="important-field">*</span></label>
                            <?=$this->Form->control('cooperative_society_name7',['type'=>'text','placeholder'=>'Public Trust','label'=>false])?>
                        </div>
                        

                        
                    </div> 

                    <div class="clearfix"></div>
                     <div class="box-block-m">
                     <div class="col-sm-3 f_audit_change" style="display:block;">
                            <label>6. Year of Latest Audit <span class="important-field">*</span></label>
                            <?php $arr_audit_year = [date('Y')=>date('Y'),(date('Y')-1)=>(date('Y')-1),(date('Y')-2)=>(date('Y')-2)]; ?>
                            <?=$this->Form->control("multi_state_cooperative.latest_audit_year",['options'=>$years,'label'=>false,'class'=>'select2'])?>
                        </div>
                    </div> 

                <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>AGM Detail</strong></h4>
                        </div>
                        <div class="col-sm-3 ">
                            <label>7(a). Whether AGM has been organized<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.is_agm_organized",['options'=>['1'=>'Yes','0'=>'No'],'class'=>'is_agm_org','type'=>'radio','label'=>false])?>
                        </div>
                        
                        <div class="col-sm-3">
                                <label>7(b). Date of Last AGM<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.last_agm_date",['type'=>'text','placeholder'=>'Date of Last AGM','label'=>false,'class'=>'before_date','readonly'=>true])?>
                            </div>

                         <div class="col-sm-3">
                            <label>7(c). Number of Member who attended the last AGM<span class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.last_agm_no_members",['type'=>'text','placeholder'=>'Number of Member who attended the last AGM','label'=>false])?>
                        </div>

                        <div class="col-sm-3">
                                <label>7(d). Date of Latest Annual Report Publication<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.latest_annual_report_pub_date",['type'=>'text','placeholder'=>'Date of Latest Annual Report Publication','label'=>false,'class'=>'before_date','readonly'=>true])?>
                            </div>

                            <div class="col-sm-3">
                                <label>8(a). Date of Latest General Board Election<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.latest_gbe_date",['type'=>'text','placeholder'=>'Date of Latest General Board Election','label'=>false,'class'=>'before_date','readonly'=>true])?>
                            </div>

                            <div class="col-sm-3">
                                <label>8(b). Date of Next Due General Board Election<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.next_due_gbe_date",['type'=>'text','placeholder'=>'Date of Next Due General Board Election','label'=>false,'class'=>'before_date','readonly'=>true])?>
                            </div>

                    </div>


                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Gender Wise Detail Employee</strong></h4>
                        </div>
                        <div>
                            <label>9. Gender wise detail of number of employee<span class="important-field">*</span></label>
                            <div class="col-sm-12">
                                <div class="table-responisve">
                                    <table class="table table-striped table-white table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Type of employee</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>Transgender</th>
                                                <th>Total no of Employee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Regular</td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.regular_male_emp",['type'=>'text','placeholder'=>'Male Regular','label'=>false])?></div>
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.regular_female_emp",['type'=>'text','placeholder'=>'Female Regular','label'=>false])?></div>
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.regular_trans_emp",['type'=>'text','placeholder'=>'Transgender Regular','label'=>false])?></div>
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.regular_total_emp",['type'=>'text','placeholder'=>'Total no of Employee Regular','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Contractual</td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.contractual_male_emp",['type'=>'text','placeholder'=>'Male Contractual','label'=>false])?></div>
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.contractual_female_emp",['type'=>'text','placeholder'=>'Female Contractual','label'=>false])?></div>
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.contractual_trans_emp",['type'=>'text','placeholder'=>'Transgender Contractual','label'=>false])?></div>
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.contractual_total_emp",['type'=>'text','placeholder'=>'Total no of Employee Contractual','label'=>false])?></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    <div>
                        <div class="col-sm-12">
                            <div class="col-sm-3">
                                <label>10(a). Total Number of Board of Directors<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.total_no_board_directors",['type'=>'text','placeholder'=>'Total Number of Board of Directors','label'=>false])?>
                            </div>
                        </div>
                            <label>10(b). Gender wise number of board of directors<span class="important-field">*</span></label>
                            <div class="col-sm-12">

                                <div class="table-responisve">
                                    <table class="table table-striped table-white table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Category in board of directors</th>
                                                <th>Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    Number of female in board of directors
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.female_bds_no",['type'=>'text','placeholder'=>'Number of female in board of directors','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>
                                                    Number of male in board of directors
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.male_bds_no",['type'=>'text','placeholder'=>'Number of male in board of directors','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    Number of transgenders in board of directors
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.trans_bds_no",['type'=>'text','placeholder'=>'Number of transgenders in board of directors','label'=>false])?></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label>10(c). Social category in board of directors<span class="important-field">*</span></label>
                            <div class="col-sm-12">
                                <div class="table-responisve">
                                    <table class="table table-striped table-white table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Category in board of directors</th>
                                                <th>Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    Number of st in board of directors
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.st_bds_no",['type'=>'text','placeholder'=>'Number of st in board of directors','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>
                                                    Number of sc in board of directors
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.sc_bds_no",['type'=>'text','placeholder'=>'Number of sc in board of directors','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    Number of specially abled(divyang) in board of directors
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.divyang_bds_no",['type'=>'text','placeholder'=>'Number of specially abled(divyang) in board of directors','label'=>false])?></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                                <label>10(d). Number of Board Meetings Conducting in the Audit Year <span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.no_bmc_in_audit_year",['type'=>'text','placeholder'=>'Number of Board Meetings Conducting in the Audit Yaer','label'=>false])?>
                            </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Commericial Activity</strong></h4>
                        </div>
                        <div class="col-sm-3 ">
                            <label>11(a). Whether the MSCS is running Commericial Activity?<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.is_running_com_act",['options'=>['1'=>'Yes','0'=>'No'],'class'=>'society_is_running','type'=>'radio','label'=>false])?>
                        </div>

                        <div class="col-sm-3 society_is_profile_making" style="display:none;">
                            <label>11(b). Whether the Cooperative Society is profit making? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("is_profit_making_mscs",['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','class'=>'is_profit_making_mscs','label'=>false])?>
                        </div>
                        <div class="col-sm-3 anual_budget" style="display:none;">
                            <label>11(b). Anual Budget of the MSCS Society<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.annual_budget_society",['placeholder'=>'Anual Budget of the MSCS Society','label'=>false, 'maxlength'=>'10','class'=>'numberadndesimal1'])?>
                        </div>
                        <div class="col-sm-3 net_profit" style="display:none;">
                            <label>11(c). Net Profit of the Cooperative Society<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.net_profit",['placeholder'=>'Net Profit of the Cooperative Society','label'=>false, 'maxlength'=>'6','class'=>'numberadndesimal1'])?>
                        </div>
                        <div class="col-sm-3 net_loss" style="display:none;">
                            <label>11(c). Loss of the Cooperative Society<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative.loss",['placeholder'=>'Loss of the Cooperative Society','label'=>false, 'maxlength'=>'6','class'=>'numberadndesimal1'])?>
                        </div>

                        <div class="col-sm-3">
                                <label>11(d). Anual Turn Over of the MSCS(in Rs)  <span class="important-field">*</span></label>
                                <?=$this->Form->control('annual_turn_over',['type'=>'text','placeholder'=>'Anual Turn Over of the MSCS(in Rs)','maxlength'=>'10','label'=>false])?>
                            </div>
                            <div class="col-sm-3">
                                <label>11(e). Anual Income of the MSCS(in Rs) <span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.annual_income",['type'=>'text','placeholder'=>'Anual Income of the MSCS(in Rs)','label'=>false])?>
                            </div>
                            <div class="col-sm-3">
                                <label>11(f). Anual Expenses of the MSCS(in Rs) <span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.annual_expenses",['type'=>'text','placeholder'=>'Anual Expenses of the MSCS(in Rs)','label'=>false])?>
                            </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-3">
                            <label>12(a). Whether the dividend is paid by the MSCS? <span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('is_dividend_paid',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'dividend','type'=>'radio','label'=>false])?>
                        </div>
                        <div class="col-sm-3 dividend_change"
                            style="display:<?=$CooperativeRegistration->is_dividend_paid == 1 ?'block' : 'none' ;?>;">
                            <label>12(b). Dividend Rate Paid by the Cooperative Society (in %)<span
                                    class="important-field">*</span></label>
                            <?=$this->Form->control('dividend_rate',['turn_over'=>'textbox','placeholder'=>'Dividend Rate Paid','label'=>false,'class'=>'numberadndesimal', 'maxlength'=>'6'])?>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Share Capitals</strong></h4>
                        </div>
                        <div class="col-sm-3">
                                <label>13(a). Authorised Share Capital<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.auth_share_capital",['type'=>'text','placeholder'=>'Authorised Share Capital','label'=>false])?>
                            </div>

                            
                            <div class="col-sm-12">
                                <label>13(b). Paid up Share Capital by Different Entity<span class="important-field">*</span></label>
                                <div class="table-responisve">
                                    <table class="table table-striped table-white table-bordered">
                                        <thead>
                                            <tr>
                                                <th>s no</th>
                                                <th>Name of Entity</th>
                                                <th>Amount(in Rs)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <label>Members of other than Govt/Govt Bodies<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.paid_by_other_body",['type'=>'text','placeholder'=>'Members of other than Govt/Govt Bodies','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>
                                                    <label>Central Government<span class="important-field">*</span></label>
                                                    
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.paid_by_central_govt",['type'=>'text','placeholder'=>'Central Government','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    <label>State Government<span class="important-field">*</span></label>
                                                    
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.paid_by_state_govt",['type'=>'text','placeholder'=>'State Government','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    <label>Corporation<span class="important-field">*</span></label>
                                                    
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.paid_by_corp",['type'=>'text','placeholder'=>'Corporation','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    <label>Company<span class="important-field">*</span></label>
                                                    
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("multi_state_cooperative.paid_by_company",['type'=>'text','placeholder'=>'Company','label'=>false])?></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Assets of MSCS</strong></h4>
                        </div>
                            <div class="col-sm-3">
                                <label>14(a). Total Asset of the MSCS<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.total_asset_society",['type'=>'text','placeholder'=>'Total Asset of the MSCS','label'=>false])?>
                            </div>
                            <div class="col-sm-3">
                                <label>14(b). Total Liabilities of the MSCS<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.total_liabilities_society",['type'=>'text','placeholder'=>'Total Liabilities of the MSCS','label'=>false])?>
                            </div>
                            <div class="col-sm-3">
                                <label>14(c). Total Deposite of the MSCS Society/Bank<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.total_deposit_bank",['type'=>'text','placeholder'=>'Total Deposite of the MSCS Society/Bank','label'=>false])?>
                            </div>
                            <div class="col-sm-3">
                                <label>14(d). Total Loan Outstanding of the Society/MSCS<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.loan_outstanding",['type'=>'text','placeholder'=>'Total Loan Outstanding of the Society/MSCS','label'=>false])?>
                            </div>  

                    </div>

                    <!-- <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Brands of MSCS</strong></h4>
                        </div>
                            <div class="col-sm-3">
                                <label>14(a). Do you have any Brand or Make <span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control('brand_make',['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>14(b). Item<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Item','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>14(c). Registered Brand Name<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Registered Brand Name','label'=>false,'required'=>true])?>
                            </div>

                    </div> -->

                    <div class="clearfix"></div>

    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong>Brands of MSCS</strong></h4>
        </div>
        <div class="col-sm-3">
                <label>15(a). Do you have any Brand or Make <span
                    class="important-field">*</span></label>
                 <?=$this->Form->control("have_any_brand",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio', 'class'=>'mscs_brand','label'=>false,'required'=>true])?>
            </div>
            <div class="clearfix"></div>

        <div class="add_brand_section" style="display:<?= $CooperativeRegistration->have_any_brand == 1 ? 'block' : 'none'; ?>;">
            <?php 
            if(!empty($CooperativeRegistration->multi_state_cooperative_member_brands)):
                foreach ($CooperativeRegistration->multi_state_cooperative_member_brands as $mhr2 => $multi_state_cooperative_member_brand):

            ?>
                        <?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.id",['type'=>'hidden','required'=>true])?>
                        <div class="member_brand_rows" rowclass="<?=$mhr2?>">
                            <div class="col-sm-3">
                                <label>(a) Item Name<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.item_name",['type'=>'textbox','placeholder'=>'Item Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                            </div>

                            <div class="col-sm-3">
                                <label>(b)  Registered Brand Name<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.brand_name",['type'=>'textbox','placeholder'=>'Registered Brand Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                            </div>
 
                            
                            <div class="col-sm-12">
                                <button style="margin-top: -15px;" type="button"
                                    class="pull-right btn btn-primary btn-xs add_row_brand">
                                    <i class="fa fa-plus-circle"></i>
                                </button>
                                <button style="display: none;margin-bottom: 10px" type="button"
                                    class="pull-right btn btn-danger btn-xs remove_row_brand">
                                    <i class="fa fa-minus-circle"></i>
                                </button>
                            </div>
                        </div>
            <?php 
                endforeach; else:?>
                            <?php $mhr2=0;?>
            <div class="member_brand_rows" rowclass="<?=$mhr2?>">
                    <div class="col-sm-3">
                            <label>(a) Item Name<span class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.item_name",['type'=>'textbox','placeholder'=>'Item Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                        </div>

                        <div class="col-sm-3">
                            <label>(b)  Registered Brand Name<span class="important-field">*</span></label>
                            <?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.brand_name",['type'=>'textbox','placeholder'=>'Registered Brand Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                        </div>

                <div class="col-sm-12">
                    <button style="margin-top: -15px;" type="button"
                        class="pull-right btn btn-primary btn-xs add_row_brand
                        ">
                        <i class="fa fa-plus-circle"></i>
                    </button>
                    <button style="display: none;margin-bottom: 10px" type="button"
                        class="pull-right btn btn-danger btn-xs remove_row_brand">
                        <i class="fa fa-minus-circle"></i>
                    </button>
                </div>
            </div>
            <?php 
            endif;?>
            <div class="col-sm-4" style="display:none;">
                <label style=""><span style="color:black;">Note :</span>
                    <sup class="blue23" style="color:#2196f3;">#</sup> Fields are optional</label>
            </div>
        </div>

    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <!-- <h4><strong>Building</strong></h4> -->
                        </div>
                            <div class="col-sm-3">
                                <label>16(a). Whether the MSCS Society has an office Building? <span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control("multi_state_cooperative.has_office_building",['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','label'=>false])?>
                            </div>
                            <div class="col-sm-3">
                            <label>16(b). Type of Office Building<span
                                    class="important-field">*</span></label>
                                  <?php  $mscsBuild = ['1'=>'Own Building','2'=>'Rented/Leased Building','3'=>'Building Provided by the Government','4'=>'Others']; ?>
                            <?= $this->Form->control("multi_state_cooperative.type_office_building",['options'=>$mscsBuild,'empty'=>'Select','label'=>false,'class'=>'select2'])?>
                           
                        </div>
                            <div class="col-sm-3">
                                <label>17(a). Does the MSCS have Own Website<span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control("multi_state_cooperative.have_own_website",['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','class'=>'own_website','label'=>false])?>
                            </div>
                            <div class="col-sm-3 have_own_website_url" style="display:none;">
                                <label>17(b). URL of Official Website<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.official_website",['type'=>'text','placeholder'=>'URL of Official Website','label'=>false])?>
                            </div>

                            <div class="col-sm-3">
                                <label>17(c). Official Email of the MSCS Society<span class="important-field">*</span></label>
                                <?=$this->Form->control("multi_state_cooperative.office_email",['type'=>'text','placeholder'=>'Official Email of the MSCS Society','label'=>false])?>
                            </div>

                            <div class="col-sm-3">
                                <label>17(d). Whether the MSCS Society have own Digital Platform for e-Commerce Activity<span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control("multi_state_cooperative.have_digital_platform",['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','label'=>false])?>
                            </div>

                            

                    </div>


                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Benefits Details Central Government</strong></h4>
                        </div>
                        <div class="col-sm-3">
                                <label>18(a). Whether any Benefits Received from Central Government <span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control("multi_state_cooperative.benefits_received_central_govt",['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'type'=>'radio','class'=>'is_benefit_central_gov','label'=>false])?>
                            </div>
                    <div class="benefit_received_central_gov_data" style="display:<?= $CooperativeRegistration->is_scheme_implemented == 1 ? 'none;' : 'block'; ?>;">  
                            <div class="col-sm-12">
                                <label>18(b). Details of Benefits Received from Central Government<span class="important-field">*</span></label>
                                <div class="table-responisve">
                                    <table class="table table-striped table-white table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl. No</th>
                                                <th>Type of Benefits</th>
                                                <th>Amount of Benefits(in Rs)</th>
                                                <th>Name of Central Goernment Schemes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <label>Equity or Caoital Contribution<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Amount Equity or Caoital Contribution','label'=>false])?></div>
                                                </td>
                                                
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Name Equity or Caoital Contribution','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>
                                                    <label>Grants<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Grants Amount','label'=>false])?></div>
                                                </td>
                                                
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Grants Name','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    <label>Loan<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Loan Amount','label'=>false])?></div>
                                                </td>
                                                
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Loan Name','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>
                                                    <label>Tax Benefits<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Tax Benefits Amount','label'=>false])?></div>
                                                </td>
                                                
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Name','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>
                                                    <label>Government Gauranties<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Government Gauranties Amount','label'=>false])?></div>
                                                </td>
                                                
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Government Gauranties Name','label'=>false])?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>
                                                    <label>Other Benefits<span class="important-field">*</span></label> 
                                                </td>
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Other Benefits Value','label'=>false])?></div>
                                                </td>
                                                
                                                <td>
                                                    <div><?=$this->Form->control("hhyu",['type'=>'text','placeholder'=>'Other Benefits Name','label'=>false])?></div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Schemes of Central Government MSCS</strong></h4>
                        </div>
                            <div class="col-sm-3">
                                <label>17(a). Whether the MSCS Society is Implementing any Central Government Scheme <span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control('brand_make',['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>17(b). Name of Central Government Scheme<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Item','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>17(c). Total Amout Spent through MSCS<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Registered Brand Name','label'=>false,'required'=>true])?>
                            </div>

                    </div> -->

        <div class="clearfix"></div>
            <div class="box-block-m">
                <div class="col-sm-12">
                    <h4><strong>Schemes of Central Government MSCS</strong></h4>
                </div>
                <div class="col-sm-4">
                    <label>18(c) Whether the MSCS Society is Implementing any Central Government Scheme<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("is_scheme_implemented",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'is_scheme','type'=>'radio','label'=>false,'required'=>true])?>
                </div>

        <div class="clearfix"></div>  
        <div class="scheme_section" style="display:<?= $CooperativeRegistration->is_scheme_implemented == 1 ? 'block' : 'none'; ?>;">
            <?php if(!empty($CooperativeRegistration->multi_state_cooperative_schemes)):
                        foreach ($CooperativeRegistration->multi_state_cooperative_schemes as $shr2 => $multi_implementing_scheme):
                            ?>
                            <div class="scheme_rows" rowclass="<?=$shr2?>">
                                <div class="col-sm-3">
                                    <label>18(d) Name of Central Govt Scheme<span class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_schemes.$shr2.scheme_name",['type'=>'textbox','placeholder'=>'Name of Central Govt Scheme','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>17(e). Total Amout Spent through MSCS<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_schemes.$shr2.amount_spent",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Total Amout Spent through MSCS','label'=>false,'required'=>true,'maxlength'=>'6'])?>
                                </div>
                                <div class="col-sm-12">
                                    <button style="margin-top: -15px;" type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_scheme">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                    <button style="display: none;margin-bottom: 10px" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_scheme">
                                        <i class="fa fa-minus-circle"></i>
                                    </button> </div></div>
            <?php
                        endforeach; else:?>
                        <?php $shr2=0;?>
                            <div class="scheme_rows" rowclass="<?=$shr2?>">

                                <div class="col-sm-3">
                                    <label>18(d) Name of Central Govt Scheme<span class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative.multi_state_cooperative_schemes.$shr2.scheme_name",['type'=>'textbox','placeholder'=>'Name of Central Govt Scheme','label'=>false,'class'=>'scheme_name','required'=>true, 'maxlength'=>'100'])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>18(e). Total Amout Spent through MSCS<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative.multi_state_cooperative_schemes.$shr2.amount_spent",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Total Amout Spent through MSCS','class'=>'scheme_amount','label'=>false,'required'=>true,'maxlength'=>'6'])?>
                                </div>
                                <div class="col-sm-12">
                                    <button style="margin-top: -15px;" type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_scheme">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                    <button style="display: none;margin-bottom: 10px" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_scheme">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div></div>
                    <?php endif;?>
        </div>
    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Benefits Details State Government</strong></h4>
                        </div>
                            <div class="col-sm-3">
                                <label>19(a). Whether any Benefits Received from State Government <span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control("multi_state_cooperative.benefits_received_state_govt",['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'type'=>'radio','class'=>'is_benefit_state_gov','label'=>false,'required'=>true])?>
                            </div>

    <div class="benefit_received_state_gov_data" style="display:<?= $CooperativeRegistration->benefits_received_state_govt == 1 ? 'none' : 'block'; ?>;">
            <p class="col-sm-12 row pacs_member_count_row" style="font-size:18px;text-decoration: underline;">19(b). Details of Benefits Received from Central Government</p>
            <div class="col-sm-12 pacs_member_table">
                <div class="table-responisve tablenew">
                    <table class="table table-striped table-white table-bordered" id="tab_logic2">
                        <thead class="table-sticky-header">
                            <tr style="background-color: #599ec6; color: #fff;">
                                <th style="width: 2%">
                                    S.No.
                                </th>
                                <th style="width: 15%">
                                    Name of State
                                </th>
                                <th style="width: 15%">
                                    Type of Benefits
                                </th>
                                <th style="width: 15%">
                                    Amount of Benefits(in Rs)
                                </th>
                                <th style="width: 15%">
                                    Name of State Goernment Schemes
                                </th>

                            </tr>
                        </thead>
                        <tbody class="benefit_received_state_gov">
                            <?php

                                foreach($states_all2 as $key=>$value){


                                    

                                    echo '<tr>
                                    <td>'.($key+1).'</td>
                                    <td>'.$value['name'].'</td>

                                          <td>
                                            <table>
                                                <tr>
                                                    <td>
                                                        Seed Capital <br>
                                                        Equity or Capital <br> 
                                                        Contribution<br>
                                                        Grant <br>
                                                        Loan<br>
                                                        Tax Benefits <br>
                                                        Other Benefits 
                                                    </td>
                                                   
                                                </tr>
                                            </table>
                                          </td>


                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;">
                                                </td>
                                            </tr>
                                        </td>
                                    </table>

                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                    <input type="textbox" style="margin-bottom: 5px;">
                                                </td>
                                            </tr>
                                        </td>
                                    </table>
                                    
                                    </tr>';


                                }
                        
                            ?>
                        </tbody>
                    </table>
                </div>
        
            </div>
        </div>


                    </div>

                    <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Schemes of State Government MSCS</strong></h4>
                        </div>
                            <div class="col-sm-3">
                                <label>19(c). Whether the MSCS Society is Implementing any State Government Scheme?<span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control("multi_state_cooperative.impl_state_govt_scheme",['options'=>['1'=>'Yes','0'=>'No'],'default'=>1,'type'=>'radio','class'=>'is_state_gov_scheme','label'=>false,'required'=>true])?>
                            </div>
<div class="state_gov_scheme_implemented_data" style="display:<?= $CooperativeRegistration->benefits_received_state_govt == 1 ? 'none' : 'block'; ?>;">
        <p class="col-sm-12 row pacs_member_count_row" style="font-size:18px;text-decoration: underline;">19(d). Name of the State Government implemented by MSCS Society</p>
            <div class="col-sm-12 pacs_member_table">
                <div class="table-responisve tablenew">
                    <table class="table table-striped table-white table-bordered" id="tab_logic2">
                        <thead class="table-sticky-header">
                            <tr style="background-color: #599ec6; color: #fff;">
                                <th style="width: 2%">
                                    S.No.
                                </th>
                                <th style="width: 15%">
                                    Name of State
                                </th>
                                <th style="width: 15%">
                                    Name of State Government Scheme
                                </th>
                                <th style="width: 15%">
                                    Total Amount Spent through MSCS
                                </th>

                            </tr>
                        </thead>
                        <tbody class="state_gov_scheme_implemented">
                            <?php

                                foreach($states_all22 as $key=>$value){


                                    

                                    echo '<tr>
                                    <td>'.($key+1).'</td>
                                    <td>'.$value['name'].'</td>


                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                </td>
                                            </tr>
                                        </td>
                                    </table>

                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="textbox" style="margin-bottom: 5px;"><br> 
                                                </td>
                                            </tr>
                                        </td>
                                    </table>
                                    
                                    </tr>';


                                }
                        
                            ?>
                        </tbody>
                    </table>
                </div>
        
            </div>
        </div>
    </div> 

                    <!-- <div class="clearfix"></div>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Export Details of MSCS</strong></h4>
                        </div>
                            <div class="col-sm-3">
                                <label>20(a). Whether Export is Carried-Out<span
                                    class="important-field">*</span></label>
                                 <?=$this->Form->control('brand_make',['options'=>['1'=>'Yes','0'=>'No'],'type'=>'radio','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>20(b). Item of Exports<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Item of Exports','label'=>false,'required'=>true])?>
                            </div>
                            <div class="col-sm-3">
                                <label>20(c). Quantity(In Unit)<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Quantity(In Unit)','label'=>false,'required'=>true])?>
                            </div>

                            <div class="col-sm-3">
                                <label>20(c). Value of Exported Item(In Rs)<span class="important-field">*</span></label>
                                <?=$this->Form->control('cooperative_society_name_po1',['type'=>'text','placeholder'=>'Value of Exported Item(In Rs)','label'=>false,'required'=>true])?>
                            </div>

                    </div> -->

             <div class="clearfix"></div>
             <div class="box-block-m">
                <div class="col-sm-12">
                    <h4><strong>Export Details of MSCS</strong></h4>
                </div>
                <div class="col-sm-4">
                    <label>20(a) Whether Export is Carried-Out<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("is_export_carried_out",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'is_export','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="clearfix"></div>
        
            <div class="add_export_section" style="display:<?= $CooperativeRegistration->is_export_carried_out == 1 ? 'block' : 'none'; ?>;">
               
                <?php if(!empty($CooperativeRegistration->multi_state_cooperative_export_items)):
                        foreach ($CooperativeRegistration->multi_state_cooperative_export_items as $shr2 => $multi_state_cooperative_export_detail):
                            ?>
                            <?=$this->Form->control("multi_state_cooperative_member_brands.$shr2.id",['type'=>'hidden','required'=>true])?>
                            <div class="export_rows" rowclass="<?=$shr2?>">
                                <div class="col-sm-3">
                                    <label>20(b). Item of Exports<span class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.item_name",['type'=>'textbox','placeholder'=>'Item of Exports','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>20(c). Quantity(In Unit)<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.quantity",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Quantity(In Unit)','label'=>false,'required'=>true,'maxlength'=>'6'])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>20(d). Value of Exported Item(In Rs)<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.value_of_item",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Value of Exported Item(In Rs)','label'=>false,'required'=>true,'maxlength'=>'6'])?>
                                </div>
                                <div class="col-sm-12">
                                    <button style="margin-top: -15px;" type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_export">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                    <button style="display: none;margin-bottom: 10px" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_export">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                    <?php
                        endforeach; else:?>
                        <?php $shr2=0;?>
                            <div class="export_rows" rowclass="<?=$shr2?>">

                                <div class="col-sm-3">
                                    <label>20(b). Item of Exports<span class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.item_name",['type'=>'textbox','placeholder'=>'Item of Exports','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>20(c). Quantity(In Unit)<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.quantity",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Quantity(In Unit)','label'=>false,'required'=>true,'maxlength'=>'6'])?>
                                </div>
                                <div class="col-sm-3">
                                    <label>20(d). Value of Exported Item(In Rs)<span
                                            class="important-field">*</span></label>
                                    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.value_of_item",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Value of Exported Item(In Rs)','label'=>false,'required'=>true,'maxlength'=>'6'])?>
                                </div>
                                <div class="col-sm-12">
                                    <button style="margin-top: -15px;" type="button"
                                        class="pull-right btn btn-primary btn-xs add_row_export">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                    <button style="display: none;margin-bottom: 10px" type="button"
                                        class="pull-right btn btn-danger btn-xs remove_row_export">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                    <?php endif;?>
                </div>
            </div>


                </div>
                <div class="box-footer text-center">
                    <?php
                    if($this->request->session()->read('Auth.User.role_id') == 7)
                    {
                        echo $this->Form->button(__('Save As Draft'),['class' => 'btn btn-primary submit mx-1','formnovalidate'=>'formnovalidate','name'=>'is_draft','value'=>'1']);
                    
                        echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary submit mx-1','name'=>'is_draft','value'=>'0']);
                        echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); 

                    }
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

$('body').on('change', '.is_operational_area', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.area_of_operation_state').show();
        } else {
            $('.area_of_operation_state').hide();
        }

    })

$('body').on('change', '.is_benefit_state_gov', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.benefit_received_state_gov_data').show();
        } else {
            $('.benefit_received_state_gov_data').hide();
        }

    });

$('body').on('change', '.is_state_gov_scheme', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.state_gov_scheme_implemented_data').show();
        } else {
            $('.state_gov_scheme_implemented_data').hide();
        }

    });

$('body').on('change', '.is_benefit_central_gov', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.benefit_received_central_gov_data').show();
        } else {
            $('.benefit_received_central_gov_data').hide();
        }

    });

$('body').on('change', '.own_website', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.have_own_website_url').show();
        } else {
            $('.have_own_website_url').hide();
        }

    });

function checkPlusMinusButtonUrban() {

    var tr_row_main = $('.urban_ward_rows');
    var main = 1;
    $('.add_row_urban_ward ').each(function() {
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


    $("#list").on("change", function() {

        var rows = '';
        $('#list :selected').each(function() {


            rows += '<tr><td></td></tr>';

        });


    });


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


//==========================================Start add multiple brand details==========================
    
    $('body').on('change', '.mscs_brand', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.add_brand_section').show();
        } else {
            $('.add_brand_section').hide();
            $('.item_name').val('');
            $('.brand_name').val('');
        }

    });

    checkPlusMinusButtonBrand();

    function checkPlusMinusButtonBrand() {

        var tr_row_main = $('.member_brand_rows');
        var m = 1;
        $('.add_row_brand').each(function() {
            if (m == 1) {
                $(this).show();
                $(this).parent('div').find('button.remove_row_brand').hide();
            } else {
                $(this).hide();
                $(this).parent('div').find('button.remove_row_brand').show();
            }
            m++;
        });
    }

    var max_brand_name = 1000;
    var count_div_brand_name = parseFloat($(".add_brand_section").find(".member_brand_rows").length);
    $('.add_brand_section').on('click', '.add_row_brand', function(e) {
        e.preventDefault();
        if (count_div_brand_name < max_brand_name) {
            count_div_brand_name++;
            var hr2 = parseFloat($(".add_brand_section .member_brand_rows:last").attr("rowclass")) +
                1;

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'brand-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    hr2: hr2
                },
                success: function(response) {
                    $(".add_brand_section").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonBrand();
                },
            });
        }
    });

    $(".add_brand_section").on('click', '.remove_row_brand', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_brand_name--;
        checkPlusMinusButtonBrand();
    });

    //==========================================End add multiple brand details==========================




    //==========================================Start add multiple central government scheme========================//

    $('body').on('change', '.is_scheme', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.scheme_section').show();
        } else {
            $('.scheme_section').hide();
            $('.scheme_name').val('');
            $('.scheme_amount').val('');
        }

    });

    //phone_number Component add row section
    checkPlusMinusButtonScheme();

    function checkPlusMinusButtonScheme() {

        var tr_row_main = $('.scheme_rows');
        var m = 1;
        $('.add_row_scheme').each(function() {
            //if (tr_row_main.length == m) {
            if (m == 1) {
                $(this).show();
                $(this).parent('div').find('button.remove_row_scheme').hide();
            } else {
                $(this).hide();
                $(this).parent('div').find('button.remove_row_scheme').show();
            }
            m++;
        });
    }

    var max_scheme = 1000;
    var count_div_scheme = parseFloat($(".scheme_section").find(".scheme_rows").length);
    $('.scheme_section').on('click', '.add_row_scheme', function(e) {
        e.preventDefault();
        if (count_div_scheme < max_scheme) {
            count_div_scheme++;
            var hr3 = parseFloat($(".scheme_section .scheme_rows:last").attr("rowclass")) + 1;
            console.log(hr3);
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'scheme-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    hr3: hr3
                },
                success: function(response) {
                    $(".scheme_section").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonScheme();
                },
            });
        }
    });

    $(".scheme_section").on('click', '.remove_row_scheme', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_scheme--;
        checkPlusMinusButtonScheme();
    });


//==========================================End add multiple central government scheme==========================//



//==========================================Start Export add multiple========================//

  //start hide show content on radio button
    $('body').on('change', '.is_export', function() {
        console.log($(this).val());
        if ($(this).val() == 1) {
            $('.add_export_section').show();
        } else {
            $('.add_export_section').hide();
            $('.export_item').val('');
            $('.export_unit').val('');
            $('.export_value').val('');
        }

    });
//end hide show content on radio button

    checkPlusMinusButtonExport();

    function checkPlusMinusButtonExport() {

        var tr_row_main = $('.export_rows');
        var m = 1;
        $('.add_row_export').each(function() {
            if (m == 1) {
                $(this).show();
                $(this).parent('div').find('button.remove_row_export').hide();
            } else {
                $(this).hide();
                $(this).parent('div').find('button.remove_row_export').show();
            }
            m++;
        });
    }

    var max_export = 1000;
    var count_div_export =   parseFloat($(".add_export_section").find(".export_rows").length);
    $('.add_export_section').on('click', '.add_row_export', function(e) {
        e.preventDefault();
        if (count_div_export < max_export) {

            count_div_export=1;
            var hr4 = parseFloat($(".add_export_section .export_rows:last").attr("rowclass")) + 1;
            console.log(hr4);
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'export-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    hr4: hr4
                },
                success: function(response) {
                    $(".add_export_section").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonExport();
                },
            });
        }
    });

    $(".add_export_section").on('click', '.remove_row_export', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_export--;
        checkPlusMinusButtonExport();
    });


//==========================================End Export add multiple==========================//


//==========================================add contact details==========================

    //phone_number Component add row section
    checkPlusMinusButtonMobileNumber();

    function checkPlusMinusButtonMobileNumber() {

        var tr_row_main = $('.phone_number_rows');
        var m = 1;
        $('.add_row_phone_number').each(function() {
            //if (tr_row_main.length == m) {
            if (m == 1) {
                $(this).show();
                $(this).parent('div').find('button.remove_row_phone_number').hide();
            } else {
                $(this).hide();
                $(this).parent('div').find('button.remove_row_phone_number').show();
            }
            m++;
        });
    }

    var max_phone_number = 1000;
    var count_div_phone_number = parseFloat($(".phone_number_section").find(".phone_number_rows").length);
    $('.phone_number_section').on('click', '.add_row_phone_number', function(e) {
        e.preventDefault();
        if (count_div_phone_number < max_phone_number) {
            count_div_phone_number++;
            var hr2 = parseFloat($(".phone_number_section .phone_number_rows:last").attr("rowclass")) +
                1;

            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'contact-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    hr2: hr2
                },
                success: function(response) {
                    $(".phone_number_section").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonMobileNumber();
                },
            });
        }
    });

    $(".phone_number_section").on('click', '.remove_row_phone_number', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_phone_number--;
        checkPlusMinusButtonMobileNumber();
    });

    //==========================================add contact details==========================





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
</script>
<!-- end rural_village_box -->

<script>
(function($) {
    $(document).ready(function() {

        $('body').on('click', '.submit', function(e) {
            if($(this).val() == '0')
            {
                e.preventDefault();

               /* if($('#pacs-pack-total-outstanding-loan').rules().min)
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

                //3 point
                if($('#functional-status').val() == 1)
                {   
                   /* //pacs
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
                    }*/
                    
                }

                //5 point
                // $(".revenue_validate:checked").each(function() {
                //     var flag = $(this).val();
                //     if(flag == 1)
                //     {
                //         $("#pacs-pack-revenue-non-credit").rules("add", {//,#dairy-milk-collection,#fishery-annual-fish-catch
                //             min:1
                //         });
                //     }
                // // });

                if(confirm('Are you sure to submit?'))
                {
                    $("#CooperativeRegistrationForm").valid();
                    //$('#CooperativeRegistrationForm').append('<input type="hidden" name="is_draft" value="0" />');
                    //$("#CooperativeRegistrationForm").submit();
                }
                
            } 
            // else {
            //     $('#loader').show();
            // }
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

        $('body').on('change', '.dividend1', function() {
            console.log($(this).val());
            if ($(this).val() == 1) {
                $('.dividend_change1').show();
            } else {
                $('.dividend_change1').hide();
            }

        });

        $('body').on('change', '.society_is_running', function() {
            console.log($(this).val());
            if ($(this).val() == 1) {
                $('.dividend_change1').show();
            } else {
                $('.dividend_change1').hide();
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
            yearRange: "-122:-0",
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


            if (sector_of_operation_type == 1 || sector_of_operation_type == 20 || sector_of_operation_type == 22) {

                if(sector_of_operation_type == 20)
                {
                    
                      $('#primary_activity_details').text('FSS Detail');
                }

                if(sector_of_operation_type == 22)
                {
                    
                      $('#primary_activity_details').text('LAMPS Detail');
                }

                if(sector_of_operation_type == 1)
                {
                    
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

             if(primary_activity==20 || primary_activity==22)   
            {   
                primary_activity=1; 
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



        if (federation_1 == 1)  
            {   
               
                if(primary_activity ==20 || primary_activity ==22)
                {
                
                    primary_activity=1; 

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

    $('body').on('change', '.society_is_running', function() {
            console.log($(this).val());
            if ($(this).val() == 1) {
                $('.society_is_profile_making').show();
                $('.anual_budget').hide();
                $('.net_profit').hide();
            } else {
                $('.anual_budget').show();
                $('.net_profit').hide();
                $('.net_loss').hide();
                $('.anual_budget').val('');
                $('.society_is_profile_making').hide();
            }

        });

    $('body').on('change', '.is_profit_making_mscs', function(e) {
        e.preventDefault();
        if ($(this).val() == 1) {
            $('.net_profit').show();
            $('.net_loss').hide();
        } else {
            $('.net_loss').show();
            $('.net_profit').hide();
            $('#annual-turnover').val('');
        }
    });

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

    $('body').on('change', '#registration-authoritie-id', function() {
        if ($(this).val() != 1) {
           $("#sector_operation option[value='1']").remove();
        } else {
           $("#sector_operation").append("<option value='1'>Credit</option>");
        }
    });

    $('body').on('change', '.operation_area_location', function() {
        var operation_area_location = $(this).val()
        //sector_rural_location_section
        var state_code = $('#state-code').val();

        //empty urban
        $('.sector_urban_district_code').html('');
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



var rowIdx = 1;  
$('#list').on('change', function () {
  

    var data = $('#list').select2('data')[0].text;
  
 //alert($(this).val());
 var selectedText = $(this).find("option:selected").text();
 var selectedval = $(this).find("option:selected").val();


 var array=$(this).val();
 $('.area_of_operation').html("");
 $('.benefit_received_state_gov').html("");
 $('.state_gov_scheme_implemented').html("");
 var k=1;
 for (i = 0; i < array.length; ++i) { 
 //  alert($('#list').select2('data')[i].text);
 
   var state_name=$('#list').select2('data')[i].text;


   $('.area_of_operation').append("<tr id='R'><td class='row-index text-center'> <p> "+k+ "</p></td><td class='row-index text-center'> <p>"+state_name+"</p></td><td class='row-index text-center'><table><tr><td>Individual members <br>Primary Cooperative Society <br>Taluka Union <br>District Union/Federations<br>State Federations/Union<br>Multi State Cooperative Society(MSCS)<br>Any Other</td></tr></table></td><td><table><tr><td><br><input type='textbox' name='hgty1' style='margin-bottom: 5px;'><br><input type='textbox' name='hgty2' style='margin-bottom: 5px;'><br><input type='textbox' name='hgty3' style='margin-bottom: 5px;'><br><input type='textbox' name='hgty3' style='margin-bottom: 5px;'><br><input type='textbox' name='hgty4' style='margin-bottom: 5px;'><br><input type='textbox' name='hgty5' style='margin-bottom: 5px;'></td></tr></table></td></tr>");

    $('.benefit_received_state_gov').append("<tr id='R'><td class='row-index text-center'> <p> "+k+ "</p></td><td class='row-index text-center'> <p>"+state_name+"</p></td><td class='row-index text-center'><table><tr><td>Seed Capital <br>Equity or Capital <br>Contribution<br>Grant<br>Loan<br>Tax Benefits<br>Other Benefits</td></tr></table></td><td><table><tr><td><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'></td></tr></table></td><td><table><tr><td><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'><br><input type='textbox' style='margin-bottom: 5px;'></td></tr></table></td></tr>");

    $('.state_gov_scheme_implemented').append("<tr id=''><td class='row-index text-center'> <p> "+k+ "</p></td><td class='row-index text-center'> <p>"+state_name+"</p></td><td><table><tr><td><input type='textbox' style='margin-bottom: 5px;'></td></tr></table></td> <td><table><tr><td><input type='textbox' style='margin-bottom: 5px;'></td></tr></table></td></tr>");
    k++;
}




 //   alert(selectedText);
    // last()Adding a row inside the tbody.<td class='text-center'> <button class='btn btn-danger remove'   type='button'>Remove</button></td>
  

   // rowIdx++;
}); 


</script>
<style>
.rdb .radio .form-check {
    margin-right: 21px;
    }

    .loan_warning {
        margin-left:20px;
    /* color: #d73925!important;
    position: absolute;
    bottom: -24px;
    font-weight: normal;
    font-size: 12px; */
}
</style>
<?php $this->end(); ?>