<div class="sericulture_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '96' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Sericulture Cooperative Federation Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>11(a). Type of Silkworm rearing is carried out by cooperative<span class="important-field">*</span></label>

                       <?php $sericulture_society = explode(',',$sd_fed_sericulture->type_society);  ?>
                    <?=$this->Form->control("sd_federation_sericulture.type_society",['options'=>$sertisocietytypes,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$sericulture_society])?>
                </div>


            
               <div class="col-sm-4">
                    <label>13(d). Whether the cooperative federation has common rearing house? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.common_work_place',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'common_working_place','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->common_work_place])?>
                </div>
                <div class="col-sm-4 sericulture_available_other_place" style="display:<?= $sd_fed_sericulture->common_work_place==1?'block':'none';?>;">
                    <label>13(d)(i). Number of rearing houses operated by cooperative federation: <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_sericulture.no_rear_house",['type'=>'text','placeholder'=>'Work places or manufacturing units operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_sericulture->no_rear_house])?>
                </div>
                

                <div class="clearfix"></div>
               
                <div class="col-sm-4">
                    <label>13(e). whether the rearing of silkworm is done by member themself? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.is_work_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'work_is_done_by_members_themself','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_work_by_member])?>
                </div>
                <div class="col-sm-4 sericulture_available_other_training" style="display:<?= $sd_fed_sericulture->is_work_by_member==1?'block':'none';?>;">
                    <label>13(e)(i). Whether the regular guidance/training is provided by cooperative federation to members? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_sericulture.is_training_provide',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_training_provide])?>
               </div>
               <div class="col-sm-4">
                    <label>13(f). Whether the cooperative federation has sufficient rearing appliances for rearing house? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_sericulture.is_rear_appliance',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_rear_appliance])?>
               </div>

               <div class="clearfix"></div>
               
                <div class="col-sm-4">
                    <label>13(g). Whether mulberry leaves are easily available to cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.is_mulberry_easy_available',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_mulberry_easy_available])?>
                </div>
                <div class="col-sm-4">
                    <label>13(h). whether the cleaning facility of cocoon is available with cooperative federation?<span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.is_cleaning_facility_cocoon',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_cleaning_facility_cocoon])?>
                </div>
                <div class="col-sm-4">
                    <label>14(i). whether the spinning & weaving activities are carried out by cooperative federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.is_spinning_weav',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_spinning_weav])?>
                </div>
               
                

               <div class="clearfix"></div>
               
                <div class="col-sm-4">
                <label>13(j). whether the waste management facility is available in the federation? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.is_waste_facility',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_waste_facility])?>
                </div>
                <div class="col-sm-4">
                    <label>13(k). whether the cooperative federation operate retail shops/outlet to sale products? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_sericulture.is_operate_retail',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'cooperative_society_operate_retail_shops','label'=>false,'required'=>true,'value'=>$sd_fed_sericulture->is_operate_retail])?>
                </div>
                <div class="col-sm-4 sericulture_available_other_retail" style="display:<?= $sd_fed_sericulture->is_operate_retail==1?'block':'none';?>;">
                    <label>13(k)(i). Number of retail shops/outlets operated by cooperative federation? <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_sericulture.no_of_retail",['type'=>'text','placeholder'=>'Retail shops/outlets operated by cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_sericulture->no_of_retail])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14. Facility provided by cooperative federation? <span class="important-field">*</span></label>

                   <?php $facilities_sericulture = explode(',',$sd_fed_sericulture->facilities);  ?>
                    <?= $this->Form->control('sd_federation_sericulture.facilities',['options'=>$sfacilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$facilities_sericulture])?>
                </div>
                
                 

            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    //=========================================

   


$(".transport_member_data").bind("change paste keyup", function() {


    var first = '';
    var second = '';



    first = $('#sd-federation-sericulture-individual-member').val();
    second = $('#sd-federation-sericulture-institutional-member').val();


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



        first = $('#sd-federation-sericulture-paid-up-members').val();
        second = $('#sd-federation-sericulture-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-sericulture-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.has_building', function() {
        if ($(this).val() == 1) {
            $('.sericulture_has_building_change').show();
        } else {
            $('.sericulture_has_building_change').hide();
        }
    });

    $('body').on('change', '.sericulture_has_land', function() {
        if ($(this).val() == 1) {
            $('.sericulture_available_land').show();
        } else {
            $('.sericulture_available_land').hide();
        }
    });

 

    $('body').on('change', '.waste_are_generated_in_sericulture', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
   


    
    $('body').on('change', '.sericulture_has_loan', function() {
        if ($(this).val() == 1) {
            $('.sericulture_available_loan').show();
        } else {
            $('.sericulture_available_loan').hide();
        }
    });

    $('body').on('change', '.sericulture_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.sericulture_available_other_loan').show();
        } else {
            $('.sericulture_available_other_loan').hide();
        }
    });

    $('body').on('change', '.common_working_place', function() {
        if ($(this).val() == 1) {
            $('.sericulture_available_other_place').show();
   
       
        } else {
           $('.sericulture_available_other_place').hide();
        }
    });

    $('body').on('change', '.work_is_done_by_members_themself', function() {
        if ($(this).val() == 1) {
            $('.sericulture_available_other_training').show();
   
       
        } else {
           $('.sericulture_available_other_training').hide();
        }
    });

    $('body').on('change', '.cooperative_society_operate_retail_shops', function() {
        if ($(this).val() == 1) {
            $('.sericulture_available_other_retail').show();
   
       
        } else {
           $('.sericulture_available_other_retail').hide();
        }
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

