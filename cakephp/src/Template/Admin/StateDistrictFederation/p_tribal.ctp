<div class="tribal_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '102' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Tribal SC/ST Cooperative Society Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>13(a). Type of SC/ST Cooperative<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tribal.type_society",['options'=>$tribalsocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_tribal->type_society])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>11(b). Whether the co-operative society has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tribal.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'tribal_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 tribal_has_building_change" style="display:<?= $StateDistrictFederation->cooperative_registrations_tribal->has_building == '1' ? 'block' : 'none'; ?>;">
           
                    <label>11(b)(i). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tribal.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

               

            
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tribal.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1  share','maxlength'=>'10','value'=>$sd_fed_tribal->authorised_share])?>
                </div>
                <div class="clearfix"></div>
                <!-------------------------------------paidup share----------------------------------------->
                <div class="col-sm-6">
                    <label>14(b). Paid up Share Capital by different Entity<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        <!-- /.box-header -->
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
                                            <?= $this->Form->control("sd_federation_tribal.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10','value'=>$sd_fed_tribal->paid_up_members])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_tribal.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10','value'=>$sd_fed_tribal->paid_up_government_bodies])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_tribal.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share','value'=>$sd_fed_tribal->paid_up_total])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!------land area------>
                </div>
                <!-------------------------------------paidup share----------------------------------------->
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>15(a). Annual Turn Over of the Cooperative Society (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_tribal.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_tribal->annual_turn_over])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>13(b). Whether the loan taken from the DCCB/UCB/PCARDB? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('sd_federation_tribal.loan_dccb',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'tribal_has_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 tribal_available_loan" style="display:<?php //$StateDistrictFederation->cooperative_registrations_tribal->loan_facilities==1?'block':'none';?>;">
                    <label>13(b)(i). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("sd_federation_tribal.loan_from_dcb",['type'=>'text','placeholder'=>'Total  Loan taken from DCCB/UCB/PCARDB(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(c). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?php //$this->Form->control('sd_federation_tribal.loan_other',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'tribal_has_other_loan','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 tribal_available_other_loan" style="display:<?php //$StateDistrictFederation->cooperative_registrations_tribal->loan_other==1?'block':'none';?>;">
                    <label>13(c)(i). Loan and Advances taken from Bank (in Rs.) <span class="important-field">*</span></label> 
                    <?php //$this->Form->control("sd_federation_tribal.loan_from_other",['type'=>'text','placeholder'=>'Total  Loan taken from other Bank(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                
                    
                <!-- <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(a). Select the type of bank where the cooperative society have a bank account <span class="important-field">*</span></label>
                    <?php $typebank = ['0' => 'Select','1'=>'Cooperative bank','2'=>'Commercial bank']?>
                    <?php //$this->Form->control('sd_federation_tribal.bank_type',['options'=>$typebank,'default'=>0,'type'=>'select','class'=>'bank_types select2','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 cooperative_bank" style="display:<?php //$StateDistrictFederation->cooperative_registrations_tribal->bank_type==1?'block':'none';?>;">
                    <label>14(b). Select the name of the cooperative bank where the cooperative society has its bank account:- <span class="important-field">*</span></label>
                    <?php $coop_bank = ['0' => 'Select','1'=>'StCB','2'=>'DCCB','3'=>'UCB','4'=>'Other (Please write the name)']?>
                    <?php //$this->Form->control('sd_federation_tribal.coop_bank',['options'=>$coop_bank,'default'=>0,'type'=>'select','class'=>'select2 coop_bank','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 commercial_bank" style="display:<?php //$StateDistrictFederation->cooperative_registrations_tribal->bank_type==2?'block':'none';?>;">
                    <label>14(b). Select the name of the commercial bank where the cooperative society has its bank account:- <span class="important-field">*</span></label>
                  
                    <?php //$this->Form->control('sd_federation_tribal.comm_bank',['options'=>$list_banks,'default'=>0,'type'=>'select','class'=>'select2 bank_list','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 bank_other"  style="display:none;">
                            <label>11(s). Other bank name (Please Specify) <span class="important-field">*</span></label>
                            <?=$this->Form->control('sd_federation_tribal.other_bank',['type'=>'textbox','class'=>'','placeholder'=>'Other bank name','label'=>false,'required'=>true, 'maxlength'=>'250'])?>
                </div> -->

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>15(b). Whether the work is allotted by State/District Federation to the Society? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_tribal.state_district_federation',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_tribal->state_district_federation])?>
                </div>
                <div class="col-sm-4">
                    <label>15(c). Whether the cooperative society provide raw material to members & finished products taken after processing? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_tribal.society_provide_raw_material',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'raw_material_easily_available','label'=>false,'required'=>true,'value'=>$sd_fed_tribal->society_provide_raw_material])?>
                </div>
                <div class="col-sm-4 ">
                    <!--b2-->
                    <label>15(d). Facilities provided by Cooperative Society<span
                            class="important-field"></span></label>
                    <?php $facilities_tribal = explode(',',$sd_fed_tribal->facilities);  ?>
                    <?= $this->Form->control('sd_federation_tribal.facilities',['options'=>$tribal_facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$facilities_tribal])?>
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



    first = $('#sd-federation-tribal-individual-member').val();
    second = $('#sd-federation-tribal-institutional-member').val();


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



        first = $('#sd-federation-tribal-paid-up-members').val();
        second = $('#sd-federation-tribal-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-tribal-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.tribal_has_building', function() {
        if ($(this).val() == 1) {
            $('.tribal_has_building_change').show();
        } else {
            $('.tribal_has_building_change').hide();
        }
    });

    $('body').on('change', '.tribal_has_land', function() {
        if ($(this).val() == 1) {
            $('.tribal_available_land').show();
        } else {
            $('.tribal_available_land').hide();
        }
    });

 

    $('body').on('change', '.raw_material_easily_available', function() {
        if ($(this).val() == 1) {
            $('.waste_management_available').show();
        } else {
            $('.waste_management_available').hide();
        }
    });

    
    
   


    
    $('body').on('change', '.tribal_has_loan', function() {
        if ($(this).val() == 1) {
            $('.tribal_available_loan').show();
        } else {
            $('.tribal_available_loan').hide();
        }
    });

    $('body').on('change', '.tribal_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.tribal_available_other_loan').show();
        } else {
            $('.tribal_available_other_loan').hide();
        }
    });

    // $('body').on('change', '.bank_types', function() {
    //     if ($(this).val() == 1) {
    //         $('.cooperative_bank').show();
    //         $('.commercial_bank').hide();
       
    //     } else if ($(this).val() == 2) {
    //         $('.commercial_bank').show();
    //         $('.cooperative_bank').hide();
            
            
    //     }
    // });



    $('body').on('change', '#sd-federation-tribal-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-tribal-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-tribal-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-tribal-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-tribal-facilities').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });



    // $('body').on('change', '.coop_bank', function() {
    //         $('#other-bank').val('');
    //         if($(this).val() == '4')
    //         {
    //             $('.bank_other').show();
    //         } else {
    //             $('.bank_other').hide();
    //         }
    //     });

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
