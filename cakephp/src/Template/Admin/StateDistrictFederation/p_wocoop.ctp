<div class="wocoop_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '15' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Women Cooperatives Federation Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>13(a). Type of Women Cooperative<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_wocoop.type_society",['options'=>$womensocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2 type_edu','required'=>true,'value'=>$sd_fed_women->type_society])?>
                </div>
               
             

                <!-- <div class="col-sm-4">
                    <label>11(b). Whether the co-operative society has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_wocoop.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'wocoop_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
               
                <div class="clearfix"></div>
                <div class="col-sm-4 wocoop_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_wocoop->has_building == '1' ? 'block' : 'none'; ?>;">
               
                    <label>11(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_wocoop.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

             <!-- member -->
                
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>13(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_wocoop.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>13(b). Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_wocoop.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>13(c). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('sd_federation_wocoop.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'wocoop_has_loan','label'=>false,'required'=>true])?>
                </div>
                
                <div class="clearfix"></div>
                <div class="col-sm-4 wocoop_available_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_wocoop->loan_dccb==1?'block':'none';?>;">
                    <label>13(c)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("sd_federation_wocoop.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>
                <div class="col-sm-4">
                    <label>13(d). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('sd_federation_wocoop.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'wocoop_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 wocoop_available_other_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_wocoop->loan_other==1?'block':'none';?>;">
                    <label>13(d)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("sd_federation_wocoop.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                
                
                   
                   
                    
               

                <div class="clearfix"></div>
                
                <div class="col-sm-4">
                    <label>13(b). Whether the cooperative federation provide raw material to members & finished products taken after processing? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_wocoop.is_raw_material_taken',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'society_conduct','label'=>false,'required'=>true,'value'=>$sd_fed_women->is_raw_material_taken])?>
                </div>
                
                <div class="col-sm-4">
                    <label>13(c). Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $sd_fed_women->facilities = explode(',',$sd_fed_women->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_wocoop.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$sd_fed_women->facilities])?>
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



    first = $('#sd-federation-wocoop-individual-member').val();
    second = $('#sd-federation-wocoop-institutional-member').val();


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



        first = $('#sd-federation-wocoop-paid-up-members').val();
        second = $('#sd-federation-wocoop-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-wocoop-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.wocoop_has_building', function() {
        if ($(this).val() == 1) {
            $('.wocoop_has_building_change').show();
        } else {
            $('.wocoop_has_building_change').hide();
        }
    });

    $('body').on('change', '.wocoop_has_land', function() {
        if ($(this).val() == 1) {
            $('.wocoop_available_land').show();
        } else {
            $('.wocoop_available_land').hide();
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



    
    
   


    
    $('body').on('change', '.wocoop_has_loan', function() {
        if ($(this).val() == 1) {
            $('.wocoop_available_loan').show();
        } else {
            $('.wocoop_available_loan').hide();
        }
    });

    $('body').on('change', '.wocoop_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.wocoop_available_other_loan').show();
        } else {
            $('.wocoop_available_other_loan').hide();
        }
    });

    $('body').on('change', '#sd-federation-wocoop-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-wocoop-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-wocoop-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-wocoop-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-wocoop-facilities').trigger(
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

               