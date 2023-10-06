<div class="social_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '98' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Social Welfare & Cultural Cooperative Federation</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>11(a). Type of Social Welfare & Cultural Cooperatives<span class="important-field">*</span></label>
                 
                    <?=$this->Form->control("sd_federation_social.type_society",['options'=>$type_social,'empty'=>'Select','label'=>false,'class'=>'select2 type_edu','socialple'=>false,'required'=>true,'value'=>$sd_federation_social->type_society])?>
                </div>
               
             
               
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>11(c). Type of Social & Cultural activities are carried out by Cooperatives<span class="important-field">*</span></label>

                    <?php $culture_activity = explode(',',$sd_federation_social->type_social_culture_activity);  ?>
                    <?=$this->Form->control("sd_federation_social.type_social_culture_activity",['options'=>$activitiestypes,'label'=>false,'class'=>'select2 type_edu','multiple'=>true,'required'=>true,'value'=>$culture_activity])?>
                </div>
               
                <div class="col-sm-4">
                    <label>13(b). Whether the Cooperative federation has common/pooled resource for its social & cultural activities? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_social.has_common',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'social_has_common','label'=>false,'required'=>true,'value'=>$sd_federation_social->has_common])?>
                </div>
                <div class="col-sm-4">
                    <label>13(c). Whether the social & cultural activities are carried by member themself? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_social.is_operate_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'social_has_rear','label'=>false,'required'=>true,'value'=>$sd_federation_social->is_operate_by_member])?>
                </div>
              
                
                <div class="clearfix"></div>
               
                
                <div class="col-sm-4 social_available_rear" style="display:<?= $sd_federation_social->is_operate_by_member==1?'block':'none';?>;">
                    <label>13(c)(i). Whether the proper guidance/training is provided by cooperative federation to members? <span class="important-field">*</span></label> 
                    <?=$this->Form->control('sd_federation_social.guidance_by_member',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_federation_social->guidance_by_member])?>
                </div>
                <div class="col-sm-4">
                    <label>13(d). Facility provided by cooperative federation? <span class="important-field">*</span></label>
             
                    <?php $facilitiy_all = explode(',',$sd_federation_social->facilities);  ?> 
                    <?= $this->Form->control('sd_federation_social.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$facilitiy_all])?>
                </div>
               <div class="col-sm-4">
                    <label>13(e). Whether the federation is operating utility Vehicle(like ambulance,van,bus,goods container etc)? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_social.is_operate_vehicle',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'social_has_retail','label'=>false,'required'=>true,'value'=>$sd_federation_social->is_operate_vehicle])?>
                </div>
               
                <div class="clearfix"></div>
                <div class="col-sm-4 social_available_retail" style="display:<?= $sd_federation_social->is_operate_vehicle==1?'block':'none';?>;">
                    <label>13(e)(i). Number of vehicles operated by the cooperative federation. <span class="important-field">*</span></label> 
                    <?=$this->Form->control("sd_federation_social.no_of_vehicle",['placeholder'=>'Number of vehicles operated by the cooperative society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_federation_social->no_of_vehicle])?>
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



    first = $('#sd-federation-social-individual-member').val();
    second = $('#sd-federation-social-institutional-member').val();


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



        first = $('#sd-federation-social-paid-up-members').val();
        second = $('#sd-federation-social-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-social-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.social_has_building', function() {
        if ($(this).val() == 1) {
            $('.social_has_building_change').show();
        } else {
            $('.social_has_building_change').hide();
        }
    });

    $('body').on('change', '.social_has_land', function() {
        if ($(this).val() == 1) {
            $('.social_available_land').show();
        } else {
            $('.social_available_land').hide();
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



    
    
   


    
    $('body').on('change', '.social_has_loan', function() {
        if ($(this).val() == 1) {
            $('.social_available_loan').show();
        } else {
            $('.social_available_loan').hide();
        }
    });
    
    $('body').on('change', '.social_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.social_available_other_loan').show();
        } else {
            $('.social_available_other_loan').hide();
        }
    });

    $('body').on('change', '.social_has_common', function() {
        if ($(this).val() == 1) {
            $('.social_available_behive').show();
        } else {
            $('.social_available_behive').hide();
        }
    });
    
    $('body').on('change', '.social_has_rear', function() {
        if ($(this).val() == 1) {
            $('.social_available_rear').show();
        } else {
            $('.social_available_rear').hide();
        }
    });

    $('body').on('change', '.social_has_retail', function() {
        if ($(this).val() == 1) {
            $('.social_available_retail').show();
        } else {
            $('.social_available_retail').hide();
        }
    });

    $('body').on('change', '#sd-federation-social-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-social-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-social-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-social-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-social-facilities').trigger(
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
