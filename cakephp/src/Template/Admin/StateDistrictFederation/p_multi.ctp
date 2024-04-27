<div class="multi_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '16' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Multipurpose Cooperative Federation</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>13(a). Secondary Activities<span class="important-field">*</span></label>
                    <?php $sd_fed_multipurpose->sec_activity = explode(',',$sd_fed_multipurpose->sec_activity);  ?> 
                    <?=$this->Form->control("sd_federation_multi.sec_activity",['options'=>$multitypes,'empty'=>'Select','label'=>false,'class'=>'select2 type_edu','multiple'=>true,'required'=>true, 'value'=>$sd_fed_multipurpose->sec_activity])?>
                </div>
               
             
<!-- 
                <div class="col-sm-4">    
                    <label>11(b). Whether the cooperative federation has an office building <span  class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_multi.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'multi_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div> -->
               
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4 multi_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_multi->has_building == '1' ? 'block' : 'none'; ?>;">
       
                    <label>11(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_multi.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div>
                <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_multi.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
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
                                            <?= $this->Form->control("sd_federation_multi.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_multi.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_multi.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
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
                    <?=$this->Form->control("sd_federation_multi.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="col-sm-4">
                    <label>13(b). Whether the Cooperative federation has Storage/GODOWN/Warehouse ? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_multi.has_storage',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'multi_has_common','label'=>false,'required'=>true, 'value'=>$sd_fed_multipurpose->has_storage])?>
                </div>
                <div class="col-sm-4 multi_available_behive" style="display:<?= $sd_fed_multipurpose->has_storage==1?'block':'none';?>;">
                    <label>13(b)(i). Capacity of Storage/Godown/Warehouse operated by cooperative federation (in MT) . <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_multi.storage_capacity",['type'=>'text','placeholder'=>'Capacity of Storage/Godown/Warehouse','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10', 'value'=>$sd_fed_multipurpose->storage_capacity])?>
                </div>
              

            
                
                <div class="clearfix"></div>
               
                <div class="col-sm-4">
                    <label>13(c). Whether the cooperative federation provide raw material to members & produced take back from them for marketing? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_multi.provide_raw',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'multi_has_rear','label'=>false,'required'=>true, 'value'=>$sd_fed_multipurpose->provide_raw])?>
                </div>
                <div class="col-sm-4 multi_available_rear" style="display:<?= $sd_fed_multipurpose->provide_raw==1?'block':'none';?>;">
                    <label>13(c)(i). Whether the regular guidance/training is provided by cooperative federation to members? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_multi.guidance_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_multipurpose->guidance_by_member])?>
                </div>
               <div class="col-sm-4">
                    <label>13(d). Whether the cooperative federation operate retail shops/outlet to sale their products? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_multi.is_operate_retail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'multi_has_retail','label'=>false,'required'=>true, 'value'=>$sd_fed_multipurpose->is_operate_retail])?>
                </div>
               
                <div class="clearfix"></div>
                <div class="col-sm-4 multi_available_retail" style="display:<?= $sd_fed_multipurpose->is_operate_retail==1?'block':'none';?>;">
                    <label>13(e). Number of retail shops/outlets operated by the cooperative federation. <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_multi.no_of_retail",['type'=>'text','placeholder'=>'Number of retail shops/outlets operated by the cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10', 'value'=>$sd_fed_multipurpose->no_of_retail])?>
                </div>
                <div class="col-sm-4">
                    <label>13(f).Whether products of society are sale out of the area of operation of the cooperative federation <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_multi.is_product_sale_out',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true, 'value'=>$sd_fed_multipurpose->is_product_sale_out])?>
                </div>
              
                <div class="col-sm-4">
                    <label>14. Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $sd_fed_multipurpose->facilities = explode(',',$sd_fed_multipurpose->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_multi.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false, 'value'=>$sd_fed_multipurpose->facilities])?>
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


first = $('#cooperative-registrations-land-land-owned').val();
second = $('#cooperative-registrations-land-land-leased').val();
third = $('#cooperative-registrations-land-land-allotted').val();

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



    first = $('#sd-federation-multi-individual-member').val();
    second = $('#sd-federation-multi-institutional-member').val();


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



        first = $('#sd-federation-multi-paid-up-members').val();
        second = $('#sd-federation-multi-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-multi-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.multi_has_building', function() {
        if ($(this).val() == 1) {
            $('.multi_has_building_change').show();
        } else {
            $('.multi_has_building_change').hide();
        }
    });

    $('body').on('change', '.multi_has_land', function() {
        if ($(this).val() == 1) {
            $('.multi_available_land').show();
        } else {
            $('.multi_available_land').hide();
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



    
    
   


    
    $('body').on('change', '.multi_has_loan', function() {
        if ($(this).val() == 1) {
            $('.multi_available_loan').show();
        } else {
            $('.multi_available_loan').hide();
        }
    });
    
    $('body').on('change', '.multi_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.multi_available_other_loan').show();
        } else {
            $('.multi_available_other_loan').hide();
        }
    });

    $('body').on('change', '.multi_has_common', function() {
        if ($(this).val() == 1) {
            $('.multi_available_behive').show();
        } else {
            $('.multi_available_behive').hide();
        }
    });
    
    $('body').on('change', '.multi_has_rear', function() {
        if ($(this).val() == 1) {
            $('.multi_available_rear').show();
        } else {
            $('.multi_available_rear').hide();
        }
    });

    $('body').on('change', '.multi_has_retail', function() {
        if ($(this).val() == 1) {
            $('.multi_available_retail').show();
        } else {
            $('.multi_available_retail').hide();
        }
    });

    $('body').on('change', '#sd-federation-multi-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-multi-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-multi-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-multi-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-multi-facilities').trigger(
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
