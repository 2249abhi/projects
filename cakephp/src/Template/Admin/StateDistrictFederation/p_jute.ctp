<div class="jute_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '90' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Jute & Coir Cooperative Federation Details</strong></h4>
            <div class="row">
                <!-- <div class="col-sm-4">
                    <label>11(a). Type of jute Cooperative<span class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_jute.type_society",['options'=>$jutesocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>11(a). Whether the cooperative federation has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_jute.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 jute_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_jute->has_building == '1' ? 'block' : 'none'; ?>;">
               
                    <label>11(a)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_jute.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

               

                <!-- <div class="clearfix"></div>
                <div class="col-sm-6">
                    <label>12. Member Detail of Cooperative Society<span
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
                                        <?= $this->Form->control("cooperative_registrations_jute.individual_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        
                                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Institutional</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("cooperative_registrations_jute.institutional_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
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
                  
                </div> -->

                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_jute.authorised_share",['type'=>'text','placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div>
                <div class="col-sm-4">
                    <label>12(b). Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_jute.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>14(b). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?=$this->Form->control('cooperative_registrations_jute.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'jute_has_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 jute_available_loan" style="display:<?= $CooperativeRegistration->cooperative_registrations_jute->loan_facilities==1?'block':'none';?>;">
                    <label>14(b)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?=$this->Form->control("cooperative_registrations_jute.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="clearfix"></div>
                <!-- <div style="display:flex;"> -->
                <!-- <div class="col-sm-4">
                    <label>14(c). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?=$this->Form->control('cooperative_registrations_jute.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'jute_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 jute_available_other_loan" style="display:<?= $CooperativeRegistration->cooperative_registrations_jute->loan_other==1?'block':'none';?>;">
                    <label>14(c)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?=$this->Form->control("cooperative_registrations_jute.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                
                
             <div class="clearfix"></div>
             <div class="col-sm-4">
                    <label>13(a). Type of raw material is using? <span class="important-field">*</span></label>
                  
                    <?php $sd_fed_jute->type_raw = explode(',',$sd_fed_jute->type_raw);  ?>
                    <?= $this->Form->control('sd_federation_jute.type_raw',['options'=>$type_jraw,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false, 'value'=>$sd_fed_jute->type_raw])?>
                </div>
                <div class="col-sm-4">
                    <label>13(b). Type of product produced by cooperative federation? <span class="important-field">*</span></label>
                    
                    <?php $sd_fed_jute->type_produce = explode(',',$sd_fed_jute->type_produce);  ?>
                    <?= $this->Form->control('sd_federation_jute.type_produce',['options'=>$productproduce,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false, 'value'=>$sd_fed_jute->type_produce])?>
                </div>
                <div class="col-sm-4">
                    <label>13(c). Whether the cooperative federation has common working place or manufacturing unit? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_jute.common_work_place',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'common_working_place','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->common_work_place])?>
                </div>
                

                <div class="clearfix"></div>
                <div class="col-sm-4 jute_available_other_place" style="display:<?= $sd_fed_jute->common_work_place==1?'block':'none';?>;">
                    <label>13(c)(i). Number of work places or manufacturing units operated by cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_jute.workplace_operate",['type'=>'text','placeholder'=>'Work places or manufacturing units operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_jute->workplace_operate])?>
                </div>
                <div class="col-sm-4">
                    <label>13(d). whether the processing or handwork is done by members themselves? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_jute.is_work_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'work_is_done_by_members_themself','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_work_by_member])?>
                </div>
                <div class="col-sm-4 jute_available_other_training" style="display:<?= $sd_fed_jute->is_work_by_member==1?'block':'none';?>;">
                    <label>13(d)(i). Whether the regular guidance/training is provided by cooperative federation to members? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_jute.is_training_provide',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_training_provide])?>
               </div>

               <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(e). Whether the cooperative federation provide raw material to members & finished products taken after processing? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_jute.is_raw_provide',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_raw_provide])?>
               </div>
                <div class="col-sm-4">
                    <label>13(f). whether the raw material easily available to cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_jute.is_raw_easy_avail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_raw_easy_avail])?>
                </div>
                <div class="col-sm-4">
                    <label>13(g). whether the waste are generated in jute process? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_jute.is_waste_generate',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'waste_are_generated_in_jute','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_waste_generate])?>
                </div>
                

               <div class="clearfix"></div>
               <div class="col-sm-4 waste_management_available" style="display:<?= $sd_fed_jute->is_waste_generate==1?'block':'none';?>;">
                    <label>13(g)(1). Whether the waste management facility is available in the federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_jute.is_waste_facility',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_waste_facility])?>
               </div>
               <div class="col-sm-4">
                    <label>13(h). whether the cooperative federation operate retail shops/outlet to sale products? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_jute.is_operate_retail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'cooperative_society_operate_retail_shops','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_operate_retail])?>
                </div>
                <div class="col-sm-4 jute_available_other_retail" style="display:<?= $sd_fed_jute->is_operate_retail==1?'block':'none';?>;">
                    <label>13(h)(i). Number of retail shops/outlets operated by cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_jute.no_of_retail",['type'=>'text','placeholder'=>'Retail shops/outlets operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)', 'value'=>$sd_fed_jute->no_of_retail])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(i). whether the products are sale out of the area of operation of the cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_jute.is_product_sale_out',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_jute->is_product_sale_out])?>
                </div>
                <div class="col-sm-4">
                    <label>14. Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $sd_fed_jute->facilities = explode(',',$sd_fed_jute->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_jute.facilities',['options'=>$jfacilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false, 'value'=>$sd_fed_jute->facilities])?>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    //=========================================

   

//  $(".transport_member_data").bind("change paste keyup", function() {


// var first = '';
// var second = '';
// var third = '';


// first = $('#cooperative-registrations-land-land-owned').val();
// second = $('#cooperative-registrations-land-land-leased').val();

// var arr = [first, second].filter(cV => cV != "");
// console.log(arr);

// sum = 0;
// count = arr.length;
// $.each(arr, function() {
//     sum += parseFloat(this) || 0;
// });
// sum = sum.toFixed(2);
// tval = 0;


// $('.transport_member_total').val(sum);

// });

$(".transport_member_data").bind("change paste keyup", function() {


    var first = '';
    var second = '';



    first = $('#sd-federation-jute-individual-member').val();
    second = $('#sd-federation-jute-institutional-member').val();


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



        first = $('#sd-federation-jute-paid-up-members').val();
        second = $('#sd-federation-jute-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-jute-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.has_building', function() {
        if ($(this).val() == 1) {
            $('.jute_has_building_change').show();
        } else {
            $('.jute_has_building_change').hide();
        }
    });

    $('body').on('change', '.jute_has_land', function() {
        if ($(this).val() == 1) {
            $('.jute_available_land').show();
        } else {
            $('.jute_available_land').hide();
        }
    });

 

    $('body').on('change', '.waste_are_generated_in_jute', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
   


    
    $('body').on('change', '.jute_has_loan', function() {
        if ($(this).val() == 1) {
            $('.jute_available_loan').show();
        } else {
            $('.jute_available_loan').hide();
        }
    });

    $('body').on('change', '.jute_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.jute_available_other_loan').show();
        } else {
            $('.jute_available_other_loan').hide();
        }
    });

    $('body').on('change', '.common_working_place', function() {
        if ($(this).val() == 1) {
            $('.jute_available_other_place').show();
   
       
        } else {
           $('.jute_available_other_place').hide();
        }
    });

    $('body').on('change', '.work_is_done_by_members_themself', function() {
        if ($(this).val() == 1) {
            $('.jute_available_other_training').show();
   
       
        } else {
           $('.jute_available_other_training').hide();
        }
    });

    $('body').on('change', '.cooperative_society_operate_retail_shops', function() {
        if ($(this).val() == 1) {
            $('.jute_available_other_retail').show();
   
       
        } else {
           $('.jute_available_other_retail').hide();
        }
    });





    $('body').on('change', '#sd-federation-jute-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-jute-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-jute-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-jute-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-jute-facilities').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });

    $('body').on('change', '.coop_bank', function() {
            $('#other-bank').val('');
            if($(this).val() == '4')
            {
                $('.bank_other').show();
            } else {
                $('.bank_other').hide();
            }
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


