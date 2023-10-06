<div class="agriculture_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '77' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Agriculture & Allied Cooperative Society Details</strong></h4>
            <div class="row">
                <!-- <div class="col-sm-4">
                    <label>10(a). Type of Agriculture Cooperative<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_agriculture.type_society",['options'=>$agrisocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>10(b). Whether the co-operative society has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_agriculture.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'agriculture_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4 agriculture_has_building_change" style="display:<?= $StateDistrictFederation->sd_federation_agriculture->has_building == '1' ? 'block' : 'none'; ?>;">
              
                    <label>10(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("agriculture_has_building_change.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

               

             <!-- member -->
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
                                    
                                        <?= $this->Form->control("sd_federation_agriculture.individual_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Institutional</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_agriculture.institutional_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
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
                    <?=$this->Form->control("sd_federation_agriculture.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                <div class="clearfix"></div>
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="col-sm-6">
                    <label>12(b). Paid up Share Capital by different Entity<span
                            class="important-field">*</span></label> -->
                    <!-- <div class="box-primary box-st">
                        
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
                                            <?= $this->Form->control("sd_federation_agriculture.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_agriculture.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_agriculture.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                    <!------land area------>
                <!-- </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>13. Annual Turn Over of the Cooperative Society (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_agriculture.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>13(b). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('cooperative_registrations_agriculture.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'agriculture_has_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 agriculture_available_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_agriculture->loan_facilities==1?'block':'none';?>;">
                    <label>13(b)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("cooperative_registrations_agriculture.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(c). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('cooperative_registrations_agriculture.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'agriculture_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 agriculture_available_other_loan" style="display:<?php //$CooperativeRegistration->cooperative_registrations_agriculture->loan_other==1?'block':'none';?>;">
                    <label>13(c)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("cooperative_registrations_agriculture.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                
                
                   
                   
                    
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(a). Do the members themselves have pooled land for the cooperative society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.has_pool_land',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'loom_operate_member','label'=>false,'required'=>true, 'value'=>$sd_fed_agri->has_pool_land])?>
                </div>
                <div class="col-sm-4">
                    <label>13(b). Apart from the pooled/common land of individuals, has the society taken any land from the government? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.has_gov_land',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_agri->has_gov_land])?>
                </div>
                <div class="col-sm-4">
                    <label>13(c). Is the right vested with the members to take back the land from the society<span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.member_vested_right',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'raw_material_easily_available','label'=>false,'required'=>true,'value'=>$sd_fed_agri->member_vested_right])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(d). Do the members themselves work on pooled/common land of the cooperative society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.is_member_work',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_agri->is_member_work])?>
                </div>
                <div class="col-sm-4">
                    <label>13(e). Do the society have common pool of bullocks, machinery, equipments, etc.? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.society_common_pool',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'raw_material_easily_available','label'=>false,'required'=>true, 'value'=>$sd_fed_agri->society_common_pool])?>
                </div>
                <div class="col-sm-4">
                    <label>13(f). Do the society fully utilize common pool resource? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.is_utilize_pool',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_agri->is_utilize_pool])?>
                </div>     
                

                <div class="clearfix"></div>
              
                
                <div class="col-sm-4">
                    <label>13(g). Does the society have the provision of rain water harvesting? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_agriculture.harvesting',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_agri->harvesting])?>
                </div>
              
                
                <div class="col-sm-4">
                    <label>13(h). Which of the following farming mechanisms adopted by the cooperative society? <span class="important-field">*</span></label>
                    <?php $facility = ['1'=>'Modern Farming Equipments','2'=>'Combined Harvester','3'=>'Improved Seed','4'=>'Compost','5'=>'Fertilizers','6'=>'Crop Rotation','7'=> 'Best Farming Practices']?>
                    <?php $farming_mech = explode(',',$sd_fed_agri->farming_mech);  ?>
                    <?= $this->Form->control('sd_federation_agriculture.farming_mech',['options'=>$facility,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'default'=>$farming_mech])?>
                </div>
                <div class="col-sm-4">
                    <label>13(i). The means of irrigation available with the society? <span class="important-field">*</span></label>
                    <?php $facility2 = ['1'=>'Rain water','2'=>'Ground water','3'=>'canal water']?>
                    <?php $irrigation_means = explode(',',$sd_fed_agri->irrigation_means);  ?>

                    <?= $this->Form->control('sd_federation_agriculture.irrigation_means',['options'=>$facility2,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false, 'default'=>$irrigation_means])?>
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



    first = $('#sd-federation-agriculture-individual-member').val();
    second = $('#sd-federation-agriculture-institutional-member').val();


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



        first = $('#sd-federation-agriculture-paid-up-members').val();
        second = $('#sd-federation-agriculture-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-agriculture-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.agriculture_has_building', function() {
        if ($(this).val() == 1) {
            $('.agriculture_has_building_change').show();
        } else {
            $('.agriculture_has_building_change').hide();
        }
    });

    $('body').on('change', '.agriculture_has_land', function() {
        if ($(this).val() == 1) {
            $('.agriculture_available_land').show();
        } else {
            $('.agriculture_available_land').hide();
        }
    });

 

    $('body').on('change', '.raw_material_easily_available', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
   


    
    $('body').on('change', '.agriculture_has_loan', function() {
        if ($(this).val() == 1) {
            $('.agriculture_available_loan').show();
        } else {
            $('.agriculture_available_loan').hide();
        }
    });

    $('body').on('change', '.agriculture_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.agriculture_available_other_loan').show();
        } else {
            $('.agriculture_available_other_loan').hide();
        }
    });

    $('body').on('change', '#sd-federation-agriculture-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-agriculture-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-agriculture-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-agriculture-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-agriculture-facilities').trigger(
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



<!-- processing inside add -->
              

