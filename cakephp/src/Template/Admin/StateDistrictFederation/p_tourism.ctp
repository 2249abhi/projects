<div class="tourism_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '99' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary tourism Cooperative Society Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>11(a). Type of Tourism Cooperative<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tourism.type_society",['options'=>$tourismsocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_tourism->type_society])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>11(b). Whether the co-operative society has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tourism.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'tourism_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 tourism_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_tourism->has_building == '1' ? 'block' : 'none'; ?>;">
               
                    <label>11(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tourism.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

               


                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>11(b). Do the members themselves have pooled resources (vehicles, accommodation, land etc.) for the cooperative society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_tourism.pool_resource',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_tourism->pool_resource])?>
                </div>
                <div class="col-sm-4">
                    <label>11(c). Apart from the pooled/common resources of individuals, has the society taken any resources from the government or non-members? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_tourism.any_resource_taken',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'raw_material_easily_available','label'=>false,'required'=>true,'value'=>$sd_fed_tourism->any_resource_taken])?>
                </div>
                <div class="col-sm-4">
                    <label>11(d). Is the right vested with the members to take back the shared resources from the society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_tourism.is_right_vested',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'raw_material_easily_available','label'=>false,'required'=>true,'value'=>$sd_fed_tourism->is_right_vested])?>
                </div>

                <div class="col-sm-4 ">
                    <!--b2-->
                    <label>11(e). Facilities provided by Cooperative Society<span
                            class="important-field"></span></label>
                    <?php $facilities_tourism = explode(',',$sd_fed_tourism->facilities);  ?>
                    <?= $this->Form->control('sd_federation_tourism.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$facilities_tourism])?>
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



    first = $('#sd-federation-tourism-individual-member').val();
    second = $('#sd-federation-tourism-institutional-member').val();


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


        
        first = $('#sd-federation-tourism-paid-up-members').val();
        second = $('#sd-federation-tourism-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-tourism-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.tourism_has_building', function() {
        if ($(this).val() == 1) {
            $('.tourism_has_building_change').show();
        } else {
            $('.tourism_has_building_change').hide();
        }
    });

    $('body').on('change', '.tourism_has_land', function() {
        if ($(this).val() == 1) {
            $('.tourism_available_land').show();
        } else {
            $('.tourism_available_land').hide();
        }
    });

 

    $('body').on('change', '.raw_material_easily_available', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
   


    
    $('body').on('change', '.tourism_has_loan', function() {
        if ($(this).val() == 1) {
            $('.tourism_available_loan').show();
        } else {
            $('.tourism_available_loan').hide();
        }
    });

    $('body').on('change', '.tourism_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.tourism_available_other_loan').show();
        } else {
            $('.tourism_available_other_loan').hide();
        }
    });

    // $('body').on('change', '.bank_type', function() {
    //     if ($(this).val() == 1) {
    //         $('.cooperative_bank').show();
    //         $('.
    //').hide();
       
    //     } else if ($(this).val() == 2) {
    //         $('.
    //').show();
    //         $('.cooperative_bank').hide();
            
            
    //     }
    // });

    $('body').on('change', '#sd-federation-tourism-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-tourism-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-tourism-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-tourism-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-tourism-facilities').trigger(
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
