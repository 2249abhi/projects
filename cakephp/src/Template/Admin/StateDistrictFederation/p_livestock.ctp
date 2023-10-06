<div class="livestock_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '54' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Livestock & Poultry Cooperative Federation Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>10. Type of Livestock/Poultry reared by the cooperative Federation<span class="important-field">*</span></label>
                       <?php $type_society_all = explode(',',$sd_fed_livestock->type_society);  ?>
                    <?=$this->Form->control("sd_federation_livestock.type_society",['options'=>$livesocietytypes,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$type_society_all])?>
                </div>


                <div class="col-sm-4">
                    <label>11(d). Type of product produced by cooperative federation? <span class="important-field">*</span></label>
                    
                    <?php $type_produce_all = explode(',',$sd_fed_livestock->type_produce);  ?>
                    <?= $this->Form->control('sd_federation_livestock.type_produce',['options'=>$lproductproduce,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$type_produce_all])?>
                </div>
                <div class="col-sm-4">
                    <label>11(e). Whether the cooperative federation has common Livestock Shade/poultry farm ? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_livestock.common_work_place',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'common_working_place','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->common_work_place])?>
                </div>

                <div class="col-sm-4">
                    <label>11(f). whether the caretaking of livestock/poultry is done by member themselves? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_livestock.is_work_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'work_is_done_by_members_themself','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_work_by_member])?>
                </div>
                

               <div class="clearfix"></div>
               <div class="col-sm-4 livestock_available_other_training" style="display:<?= $sd_fed_livestock->is_work_by_member==1?'block':'none';?>;">
                    <label>11(f)(i). Whether the regular guidance/training is provided by cooperative federation to members? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_livestock.is_training_provide',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_training_provide])?>
               </div>
                <div class="col-sm-4">
                    <label>11(g). Whether the cooperative federation provide livestock/poultry feed to members <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_livestock.is_poultry_feed',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_poultry_feed])?>
               </div>
                <div class="col-sm-4">
                    <label>11(h). Whether livestock/poultry products are collected from members for marketing by cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_livestock.is_collected_from_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_collected_from_member])?>
                </div>
                
               
                

               <div class="clearfix"></div>
               <div class="col-sm-4">
                    <label>11(i). whether the livestock/poultry waste management facility is available in the federation <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_livestock.is_waste_facility',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_waste_facility])?>
                </div>
              
               <div class="col-sm-4">
                    <label>11(j). whether the cooperative federation operate retail shops/outlet to sale products? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_livestock.is_operate_retail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'cooperative_society_operate_retail_shops','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_waste_facility])?>
                </div>
                <div class="col-sm-4 livestock_available_other_retail" style="display:<?= $sd_fed_livestock->is_operate_retail==1?'block':'none';?>;">
                    <label>11(j)(i). Number of retail shops/outlets operated by cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_livestock.no_of_retail",['placeholder'=>'Retail shops/outlets operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_livestock->no_of_retail])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>11(k). whether the products are sale out of the area of operation of the cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_livestock.is_product_sale_out',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_livestock->is_product_sale_out])?>
                </div>
                <div class="col-sm-4">
                    <label>12. Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $facilities_livestock = explode(',',$sd_fed_livestock->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_livestock.facilities',['options'=>$lfacilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$facilities_livestock])?>
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



    first = $('#sd-federation-livestock-individual-member').val();
    second = $('#sd-federation-livestock-institutional-member').val();


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



        first = $('#sd-federation-livestock-paid-up-members').val();
        second = $('#sd-federation-livestock-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-livestock-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.has_building', function() {
        if ($(this).val() == 1) {
            $('.livestock_has_building_change').show();
        } else {
            $('.livestock_has_building_change').hide();
        }
    });

    $('body').on('change', '.livestock_has_land', function() {
        if ($(this).val() == 1) {
            $('.livestock_available_land').show();
        } else {
            $('.livestock_available_land').hide();
        }
    });

 

    $('body').on('change', '.waste_are_generated_in_livestock', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
   


    
    $('body').on('change', '.livestock_has_loan', function() {
        if ($(this).val() == 1) {
            $('.livestock_available_loan').show();
        } else {
            $('.livestock_available_loan').hide();
        }
    });

    $('body').on('change', '.livestock_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.livestock_available_other_loan').show();
        } else {
            $('.livestock_available_other_loan').hide();
        }
    });

    $('body').on('change', '.common_working_place', function() {
        if ($(this).val() == 1) {
            $('.livestock_available_other_place').show();
   
       
        } else {
           $('.livestock_available_other_place').hide();
        }
    });

    $('body').on('change', '.work_is_done_by_members_themself', function() {
        if ($(this).val() == 1) {
            $('.livestock_available_other_training').show();
   
       
        } else {
           $('.livestock_available_other_training').hide();
        }
    });

    $('body').on('change', '.cooperative_society_operate_retail_shops', function() {
        if ($(this).val() == 1) {
            $('.livestock_available_other_retail').show();
   
       
        } else {
           $('.livestock_available_other_retail').hide();
        }
    });





    // $('body').on('change', '#sd-federation-livestock-facilities', function(e) {
    //     e.preventDefault();
    //     $('#sd-federation-livestock-facilities > option:selected').each(function() {
    //         if ($(this).val() == 1 && $(
    //                 '#sd-federation-livestock-facilities > option:selected').length >
    //             1) {
    //             //console.log('#sector-' + increment + '-village-code');
    //             $('#sd-federation-livestock-facilities').val(
    //                 '1'); // Select the option with a value of '1'
    //             $('#sd-federation-livestock-facilities').trigger(
    //                 'change'); // Notify any JS components that the value chan
    //             return false; // breaks

    //         }
    //     });

    // });

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

