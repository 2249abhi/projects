<div class="handloom_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '13' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Handloom & Weavers Cooperative Society Details</strong></h4>
            <div class="row">
                <!-- <div class="col-sm-4">
                    <label>10(a). Type of Transport Cooperative Society<span class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_handloom.type_society",['options'=>$societytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>10(a). Whether the co-operative society has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_handloom.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'handloom_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 handloom_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_handloom->has_building == '1' ? 'block' : 'none'; ?>;">
               
                    <label>10(b). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_handloom.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

               

             
             <div class="clearfix"></div>
                <!-- <div class="col-sm-6">
                    <label>11. Member Detail of Cooperative Society<span
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
                                            <?= $this->Form->control("cooperative_registrations_handloom.individual_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Institutional</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("cooperative_registrations_handloom.institutional_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
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
                    
                </div -->

                <!-- <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_handloom.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="clearfix"></div> -->
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
                                            <?= $this->Form->control("cooperative_registrations_handloom.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("cooperative_registrations_handloom.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("cooperative_registrations_handloom.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(a). Annual Turn Over of the Cooperative Society (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("cooperative_registrations_handloom.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <!--<div class="col-sm-4">
                    <label>13(b). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('cooperative_registrations_handloom.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'handloom_has_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 handloom_available_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_handloom->loan_facilities==1?'block':'none';?>;">
                    <label>13(b)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("cooperative_registrations_handloom.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-sm-4">
                    <label>13(c). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('cooperative_registrations_handloom.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'handloom_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 handloom_available_other_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_handloom->loan_other==1?'block':'none';?>;">
                    <label>13(c)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("cooperative_registrations_handloom.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>-->

                
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <label>13. Type of machine used by cooperative society:<span
                                class="important-field">*</span></label>
                        <div class="box-primary box-st">
                            
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-border table-bordered table-striped">
                                   
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Power Looms</td>
                                            <td class="error-text-align">
                                                <?= $this->Form->control("sd_federation_handloom.power_loom_type",['type'=>'textbox','label'=>false,'required'=>true,'class'=>'mb-n','maxlength'=>'20','onkeyup'=>'onlyNumbersChar(this)','value'=>$sd_fed_handloom->power_loom_type])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Handlooms</td>
                                            <td class="error-text-align">
                                                <?= $this->Form->control("sd_federation_handloom.hand_loom_type",['type'=>'textbox','label'=>false,'required'=>true,'class'=>'mb-n','maxlength'=>'20','onkeyup'=>'onlyNumbersChar(this)','value'=>$sd_fed_handloom->hand_loom_type])?>
                                            </td>
                                        </tr>
                                        <tr>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                   
                    
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(a). Whether the looms are operated by members themself? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.operated_member_themself',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'loom_operate_member','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->operated_member_themself])?>
                </div>
                <div class="col-sm-4 handloom_work_divide" style="display:<?= $sd_fed_handloom->operated_member_themself==1?'block':'none';?>;">
                    <label>14(a)(i). Whether the work is divided among the members as per their skill? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.is_user_work_divide',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->is_user_work_divide])?>
                </div>
                <div class="col-sm-4">
                    <label>14(b). Number of looms operated by cooperative society:<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_handloom.no_of_loom",['type'=>'text','placeholder'=>'Number of looms operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_handloom->no_of_loom])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(c). Whether the cooperative society provide raw material to members & produced product taken after processing? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.raw_product_taken',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->raw_product_taken])?>
                </div>
                <div class="col-sm-4">
                    <label>14(d). Whether the raw material easily available to cooperative society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.raw_material_available',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'raw_material_easily_available','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->raw_material_available])?>
                </div>
                <div class="col-sm-4">
                    <label>14(e). Whether the wastes are generated in production process? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.waste_generate',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->waste_generate])?>
                </div>
                

                <div class="clearfix"></div>
              
                <div class="col-sm-4 waste_management_available" style="display:<?= $sd_fed_handloom->raw_material_available==1?'block':'none';?>;">
                    <label>14(f). Whether the waste management facility is available in the society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.waste_available',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'waste_management_facility','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->waste_available])?>
                </div>
                <div class="col-sm-4">
                    <label>14(g). Whether the cooperative society operate retail shops/outlet to sale products? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.operate_retail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->operate_retail])?>
                </div>
                <div class="col-sm-4 retail_operate_society" style="display:<?= $sd_fed_handloom->waste_available==1?'block':'none';?>;">
                    <label>14(f)(i). Number of retail shops/outlets operated by cooperative society:<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_handloom.no_of_retail",['type'=>'text','placeholder'=>'Number of looms operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','onkeyup'=>'onlyNumbers(this)','maxlength'=>'10','value'=>$sd_fed_handloom->no_of_retail])?>
               </div>

                <div class="clearfix"></div>
                
                <div class="col-sm-4">
                    <label>14(h). Whether the products are sale out of area of operation of the cooperative society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_handloom.product_sale_out',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_handloom->product_sale_out])?>
                </div>
                

            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    //=========================================

    $(".transport_land_data").bind("change paste keyup", function() {


        var first = '';
        var second = '';
        var third = '';
        var fourth = '';


        first = $('#sd-federation-lands-land-owned').val();
        second = $('#sd-federation-lands-land-leased').val();
        third = $('#sd-federation-lands-land-allotted').val();

        var arr = [first, second, third].filter(cV => cV != "");
        // console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('.transport_land_total').val(sum);

    });

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



    first = $('#sd-federation-handloom-individual-member').val();
    second = $('#sd-federation-handloom-institutional-member').val();


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



        first = $('#sd-federation-handloom-paid-up-members').val();
        second = $('#sd-federation-handloom-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-handloom-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.handloom_has_building', function() {
        if ($(this).val() == 1) {
            $('.handloom_has_building_change').show();
        } else {
            $('.handloom_has_building_change').hide();
        }
    });

    $('body').on('change', '.handloom_has_land', function() {
        if ($(this).val() == 1) {
            $('.handloom_available_land').show();
        } else {
            $('.handloom_available_land').hide();
        }
    });

    $('body').on('change', '.loom_operate_member', function() {
        if ($(this).val() == 1) {
            $('.handloom_work_divide').show();
        } else {
            $('.handloom_work_divide').hide();
        }
    });

    $('body').on('change', '.raw_material_easily_available', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
    $('body').on('change', '.waste_management_facility', function() {
        if ($(this).val() == 1) {
            $('.retail_operate_society').show();
        } else {
            $('.retail_operate_society').hide();
        }
    });


    
    $('body').on('change', '.handloom_has_loan', function() {
        if ($(this).val() == 1) {
            $('.handloom_available_loan').show();
        } else {
            $('.handloom_available_loan').hide();
        }
    });

    $('body').on('change', '.handloom_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.handloom_available_other_loan').show();
        } else {
            $('.handloom_available_other_loan').hide();
        }
    });

    $('body').on('change', '#sd-federation-handloom-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-handloom-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-handloom-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-handloom-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-handloom-facilities').trigger(
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
