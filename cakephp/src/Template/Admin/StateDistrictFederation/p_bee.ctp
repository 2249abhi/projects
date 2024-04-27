<div class="bee_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '79' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Bee Farming Cooperative Federation</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>13(a). Type of Bees are rearing by cooperative federation<span class="important-field">*</span></label>
                    <?php $sd_fed_bee->type_bee = explode(',',$sd_fed_bee->type_bee);  ?> 
                    <?=$this->Form->control("sd_federation_bee.type_bee",['options'=>$beetypes,'empty'=>'Select','label'=>false,'class'=>'select2 type_edu','multiple'=>true,'required'=>true,'value'=>$sd_fed_bee->type_bee])?>
                </div>
               
             

                <!-- <div class="col-sm-4">    
                    <label>11(b). Whether the cooperative federation has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_bee.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'bee_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
               
                <div class="clearfix"></div>
                <div class="col-sm-4 bee_has_building_change" style="display:<?= $StateDistrictFederation->sd_federation_bee->has_building == '1' ? 'block' : 'none'; ?>;">
                
                    <label>11(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_bee.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_bee.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                
               

             <!-- member -->
            

                <!-- <div class="clearfix"></div> -->
               
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
                                            <?= $this->Form->control("sd_federation_bee.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_bee.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_bee.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
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
                    <label>13(a). Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_bee.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="col-sm-4">
                    <label>13(b). Whether the Cooperative federation has Common Beehive Yard? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_bee.common_yard',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'bee_has_common','label'=>false,'required'=>true,'value'=>$sd_fed_bee->common_yard])?>
                </div>

                <!-- <div class="col-sm-4">
                    <label>13(c). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_bee.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'bee_has_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 bee_available_loan" style="display:<?= $StateDistrictFederation->sd_federation_bee->loan_dccb==1?'block':'none';?>;">
                    <label>13(c)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_bee.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                
                <!-- <div class="clearfix"></div> -->
                
                <!-- <div style="display:flex;"> -->
                <!-- <div class="col-sm-4">
                    <label>13(d). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_bee.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'bee_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 bee_available_other_loan" style="display:<?= $StateDistrictFederation->sd_federation_bee->loan_other==1?'block':'none';?>;">
                    <label>13(d)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_bee.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
               
                
                <div class="clearfix"></div>
                <div class="col-sm-4 bee_available_behive" style="display:<?= $sd_fed_bee->common_yard==1?'block':'none';?>;">
                    <label>13(b)(i). Number of behives operated by cooperative federation. <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_bee.no_of_behives",['type'=>'text','placeholder'=>'Number of behives operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_bee->no_of_behives])?>
                </div>
                <div class="col-sm-4">
                    <label>13(c). Type of Beehives are used for rearing bee by cooperative:<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_bee.type_behives",['options'=>$beehivetypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_bee->type_behives])?>
                </div>
                <div class="col-sm-4">
                    <label>13(d). Whether the rearing of bees is done by member themselves? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_bee.rear_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'bee_has_rear','label'=>false,'required'=>true,'value'=>$sd_fed_bee->rear_by_member])?>
                </div>

                <div class="clearfix"></div>
                
                <div class="col-sm-4 bee_available_rear" style="display:<?= $sd_fed_bee->rear_by_member==1?'block':'none';?>;">
                    <label>13(d)(i). Whether the regular guidance/training is provided by cooperative federation to members? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_bee.guidance_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_bee->guidance_by_member])?>
                </div>
                <div class="col-sm-4">
                    <label>13(e). Type of products of bee farming:<span class="important-field">*</span></label>
                    <?php $sd_fed_bee->type_product = explode(',',$sd_fed_bee->type_product);  ?> 
                    <?=$this->Form->control("sd_federation_bee.type_product",['options'=>$type_product,'empty'=>'Select','label'=>false,'class'=>'select2','multiple'=>true,'required'=>true,'value'=>$sd_fed_bee->type_product])?>
                </div> 
                <div class="col-sm-4">
                    <label>13(f). Whether the Honey Bee Plants/Flowers are grown by cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_bee.is_bee_plant_grow',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_bee->is_bee_plant_grow])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(g). Whether the cleaning, processing & packaging facility is available with cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_bee.is_cleaning_process',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_bee->is_cleaning_process])?>
                </div>
                <div class="col-sm-4">
                    <label>13(h). Whether the waste management facility is available in the cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_bee.is_waste_facility',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_bee->is_waste_facility])?>
                </div>
                <div class="col-sm-4">
                    <label>13(i). Whether the Cooperative federation ha its own Brand for Honey Products? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_bee.own_brand_honey',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_bee->own_brand_honey])?>
                </div>
                   
                <div class="clearfix"></div>
                
                <div class="col-sm-4">
                    <label>13(j). Whether the cooperative federation operate retail shops/outlet to sale products? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_bee.is_operate_retail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'bee_has_retail','label'=>false,'required'=>true,'value'=>$sd_fed_bee->is_operate_retail])?>
                </div>
                <div class="col-sm-4 bee_available_retail" style="display:<?= $sd_fed_bee->is_operate_retail==1?'block':'none';?>;">
                    <label>13(k). Number of retail shops/outlets operated by the cooperative federation. <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_bee.no_of_retail",['type'=>'text','placeholder'=>'Number of retail shops/outlets operated by the cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_bee->no_of_retail])?>
                </div>
                <div class="col-sm-4">
                    <label>13(l). Whether products are sale out of the area of operation of the cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_bee.is_product_sale_out',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_bee->is_product_sale_out])?>
                </div>
                
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14. Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $sd_fed_bee_facilities = explode(',',$sd_fed_bee->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_bee.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'default'=>$sd_fed_bee_facilities])?>
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


first = $('#sd-federation-bee-land-owned').val();
second = $('#sd-federation-bee-land-leased').val();
third = $('#sd-federation-bee-land-allotted').val();

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



    first = $('#sd-federation-bee-individual-member').val();
    second = $('#sd-federation-bee-institutional-member').val();


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



        first = $('#sd-federation-bee-paid-up-members').val();
        second = $('#sd-federation-bee-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-bee-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.bee_has_building', function() {
        if ($(this).val() == 1) {
            $('.bee_has_building_change').show();
        } else {
            $('.bee_has_building_change').hide();
        }
    });

    $('body').on('change', '.bee_has_land', function() {
        if ($(this).val() == 1) {
            $('.bee_available_land').show();
        } else {
            $('.bee_available_land').hide();
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



    
    
   


    
    $('body').on('change', '.bee_has_loan', function() {
        if ($(this).val() == 1) {
            $('.bee_available_loan').show();
        } else {
            $('.bee_available_loan').hide();
        }
    });
    
    $('body').on('change', '.bee_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.bee_available_other_loan').show();
        } else {
            $('.bee_available_other_loan').hide();
        }
    });

    $('body').on('change', '.bee_has_common', function() {
        if ($(this).val() == 1) {
            $('.bee_available_behive').show();
        } else {
            $('.bee_available_behive').hide();
        }
    });
    
    $('body').on('change', '.bee_has_rear', function() {
        if ($(this).val() == 1) {
            $('.bee_available_rear').show();
        } else {
            $('.bee_available_rear').hide();
        }
    });

    $('body').on('change', '.bee_has_retail', function() {
        if ($(this).val() == 1) {
            $('.bee_available_retail').show();
        } else {
            $('.bee_available_retail').hide();
        }
    });

    $('body').on('change', '#sd-federation-bee-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-bee-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-bee-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-bee-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-bee-facilities').trigger(
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
            



            

