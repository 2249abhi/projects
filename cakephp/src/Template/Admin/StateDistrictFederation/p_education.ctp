<div class="education_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '84' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Education and Training Cooperatives Federation Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>13(a). Type of education Cooperative<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_education.type_society",['options'=>$educationsocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2 type_edu','required'=>true, 'value'=>$sd_fed_education->type_society])?>
                </div>
               
                <div class="col-sm-4 10a1" style="display:<?= $sd_fed_education->type_society == '1' ? 'block' : 'none'; ?>;">
                    <label>13(a)(i). Level of education Courses offered by Cooperative Federation? <span class="important-field">*</span></label>
                 
                    <?= $this->Form->control('sd_federation_education.level_of_edu',['options'=>$levelofcourses,'label'=>false,'empty'=>'Select','class'=>'select2','required'=>true, 'value'=>$sd_fed_education->level_of_edu])?>
                </div>

                <div class="col-sm-4 10a2" style="display:<?= $sd_fed_education->type_society == '2' ? 'block' : 'none'; ?>;">
                    <label>13(a)(i). Duration of training courses offered by Cooperative Federation? <span class="important-field">*</span></label>
                    <?= $this->Form->control('sd_federation_education.duration_of_course',['options'=>$durationofcourses,'label'=>false,'empty'=>'Select','class'=>'select2','required'=>true, 'value'=>$sd_fed_education->duration_of_course])?>
                </div>

                <div class="col-sm-4 10a3" style="display:<?= $sd_fed_education->type_society == '3' ? 'block' : 'none'; ?>;">
                    <label>13(a)(i). Level & duration of training & education Courses offered by Cooperative Federation? <span class="important-field">*</span></label>
                   <?php $sd_fed_education->level_and_duration_of_course = explode(',',$sd_fed_education->level_and_duration_of_course);  ?>
                    <?= $this->Form->control('sd_federation_education.level_and_duration_of_course',['options'=>$leveldurationofcourses,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>true, 'value'=>$sd_fed_education->level_and_duration_of_course])?>
                </div>

                <!-- <div class="col-sm-4">
                    <label>10(b). Whether the cooperative federation has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_education.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'education_has_building','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_education->type_society])?>
                </div> -->
               
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4 education_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_education->has_building == '1' ? 'block' : 'none'; ?>;">
                
                    <label>10(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_education.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true, 'value'=>$sd_fed_education->building_type])?>
                </div> -->
                <div class="col-sm-4">
                    <label>13(b). Whether the cooperative federation has land <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_education.has_land",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'education_has_land','type'=>'radio','label'=>false,'required'=>true, 'value'=>$sd_fed_education->has_land])?>
                </div>
                <!------land area------>
                <div class="clearfix"></div>
                <div class="col-sm-6 education_available_land"
                    style="display:<?= $sd_fed_education->has_land == '1' ? 'block' : 'none'; ?>;">
                    <label>13(b)(i). Detail of Land Available with the Cooperative<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">

                            <table class="table table-hover table-border table-bordered table-striped">
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
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_owned",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_data mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_owned])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Leased Land</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_leased",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_data mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_leased])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Land allotted by the Government</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_allotted",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_data mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_allotted])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_total mb-n','value'=>$sd_fed_land->land_total])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!------land area------>
                </div>

               

             <!-- member -->
             <div class="clearfix"></div>
                <!-- <div class="col-sm-6">
                    <label>11. Member Detail of Cooperative Federation<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        
                        <div class="box-body table-responsive">

                            <table class="table table-hover table-border table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                        <th scope="col" width="50%"><?= __('Type of Member') ?>
                                        </th>
                                        <th scope="col" width="40%"><?= __('No. of Member') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Individual</td>
                                        <td class="error-text-align">
                                    
                                        <?= $this->Form->control("sd_federation_education.individual_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Institutional</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_education.institutional_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("members_of_society",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_total mb-n'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                 
                 </div>  -->

                <!-- <div class="clearfix"></div> -->
                <!-- <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_education.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1  share','maxlength'=>'10'])?>
                </div> -->
                <div class="clearfix"></div>
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="col-sm-6">
                    <label>12(b). Paid up Share Capital by different Entity<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-border table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                        <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                        </th>
                                        <th scope="col" width="40%"><?= __('Amount (in Rs)') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Members</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_education.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_education.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_education.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>13. Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_education.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>13(b). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('cooperative_registrations_education.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'education_has_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 education_available_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_education->loan_facilities==1?'block':'none';?>;">
                    <label>13(b)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("cooperative_registrations_education.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(c). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('cooperative_registrations_education.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'education_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 education_available_other_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_education->loan_other==1?'block':'none';?>;">
                    <label>13(c)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("cooperative_registrations_education.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                
                <div class="col-sm-4">
                    <label>14(a). Number of courses offered in the audited year: <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.course_in_audit',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10' ,'onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->course_in_audit])?>
                </div>
                <div class="col-sm-4">
                    <label>14(b). Number of students completed courses offered in the audited year? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.stu_in_audit',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_education->stu_in_audit])?>
                </div>
                <div class="col-sm-4">
                    <label>14(c). Number of training courses conducted in the audited year:<span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.training_course_in_audit',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->training_course_in_audit])?>
                </div>
                
                <div class="col-sm-4">
                    <label>14(d). Number of participants attended training in the audited year <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.participants_in_audit',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->participants_in_audit])?>
                </div>
                <div class="col-sm-4">
                    <label>14(e). Whether the Federation conduct any course/training for international Participants? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.course_international_participant',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'society_conduct','label'=>false,'required'=>true, 'value'=>$sd_fed_education->course_international_participant])?>
                </div>
                <div class="col-sm-4 about_course_training" style="display:<?= $sd_fed_education->course_international_participant==1?'block':'none';?>;">
                    <label>14(e)(i). Number of trainings/courses conducted? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.no_of_training_course',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->no_of_training_course])?>
                </div>
                

                <div class="clearfix"></div>
                <div class="col-sm-4 about_course_training" style="display:<?= $sd_fed_education->course_international_participant==1?'block':'none';?>;">
                    <label>14(e)(ii). Number of participants attended training/course: <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.attended_training',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->attended_training])?>
                </div>
              
                <div class="col-sm-4">
                    <label>14(f). Whether the Federation has recruited own faculty: <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.society_recruit',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'society_has_recruit','label'=>false,'required'=>true, 'value'=>$sd_fed_education->society_recruit])?>
                </div>

                <div class="col-sm-4 about_recruit" style="display:<?= $sd_fed_education->society_recruit==1?'block':'none';?>;">
                    <label>14(f)(i). Number of Regular Faculties: <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.no_regular_faculty',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->no_regular_faculty])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4 about_recruit" style="display:<?= $sd_fed_education->society_recruit==1?'block':'none';?>;">
                    <label>14(f)(ii). Number of Adhoc/Contractual/Visiting Faculties: <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_education.no_other_faculty',['type'=>'text','class'=>'','label'=>false,'required'=>true,'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_education->no_other_faculty])?>
                </div>
                <div class="col-sm-4">
                    <label>14(g). Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $sd_fed_education->facilities = explode(',',$sd_fed_education->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_education.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false, 'value'=>$sd_fed_education->facilities])?>
                </div>
                
                
                

            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    //=========================================

   
    $(".housing_land_data").bind("change paste keyup", function() {


var first = '';
var second = '';
var third = '';
var fourth = '';


first = $('#sd-federation-lands-land-owned').val();
second = $('#sd-federation-lands-land-leased').val();
third = $('#sd-federation-lands-land-allotted').val();

var arr = [first, second, third].filter(cV => cV != "");
console.log(arr);

sum = 0;
count = arr.length;
$.each(arr, function() {
    sum += parseFloat(this) || 0;
});
sum = sum.toFixed(2);
tval = 0;


$('.housing_land_total').val(sum);

});

$(".transport_member_data").bind("change paste keyup", function() {


    var first = '';
    var second = '';



    first = $('#sd-federation-education-individual-member').val();
    second = $('#sd-federation-education-institutional-member').val();


    var arr = [first, second].filter(cV => cV != "");
    console.log(arr);

    sum = 0;
    count = arr.length;
    $.each(arr, function() {
        sum += parseInt(this) || 0;
        
});
//sum = sum.toFixed(2);
tval = 0;


$('.transport_member_total').val(sum);

})




    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-education-paid-up-members').val();
        second = $('#sd-federation-education-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-education-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.education_has_building', function() {
        if ($(this).val() == 1) {
            $('.education_has_building_change').show();
        } else {
            $('.education_has_building_change').hide();
        }
    });

    $('body').on('change', '.education_has_land', function() {
        if ($(this).val() == 1) {
            $('.education_available_land').show();
        } else {
            $('.education_available_land').hide();
        }
    });

 
    
    $('body').on('change', '.raw_material_easily_available', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    $('body').on('change', '.society_conduct', function() {
        if ($(this).val() == 1) {
            $('.about_course_training').show();
        } else {
            $('.about_course_training').hide();
        }
    });

    $('body').on('change', '.society_has_recruit', function() {
        if ($(this).val() == 1) {
            $('.about_recruit').show();
        } else {
            $('.about_recruit').hide();
        }
    });


    $('body').on('change', '.type_edu', function() {
        if ($(this).val() == 1) {
            $('.10a1').show();
            $('.10a2').hide();
            $('.10a3').hide();
        } else if ($(this).val() == 2) {
            $('.10a2').show();
            $('.10a3').hide();
            $('.10a1').hide();
        }
        else if ($(this).val() == 3) {
            $('.10a3').show();
            $('.10a1').hide();
            $('.10a2').hide();

        }
        else {
            $('.10a3').hide();
            $('.10a1').hide();
            $('.10a2').hide();
        }
    });


    
    
   


    
    $('body').on('change', '.education_has_loan', function() {
        if ($(this).val() == 1) {
            $('.education_available_loan').show();
        } else {
            $('.education_available_loan').hide();
        }
    });

    $('body').on('change', '.education_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.education_available_other_loan').show();
        } else {
            $('.education_available_other_loan').hide();
        }
    });

    $('body').on('change', '#sd-federation-education-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-education-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-education-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-education-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-education-facilities').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });

});
</script>
<script>
    function onlyNumbers(num){
    if ( /[^0-9]+/.test(num.value) ){
       num.value = num.value.replace(/[^0-9]*/g,"")
    }
 }


 function onlyNumbersChar(num){
    if ( /[^0-9a-zA-Z]+/.test(num.value) ){
       num.value = num.value.replace(/[^0-9a-zA-Z]*/g,"")
    }
 }

 

//  onkeypress'=>'return isAlfa(event)',  [0-9a-zA-Z]
//  function isAlfa(evt) {
//     evt = (evt) ? evt : window.event;
//     var charCode = (evt.which) ? evt.which : evt.keyCode;
//     if (charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)) {
//         return false;
//     }
//     return true;
// }
</script>
