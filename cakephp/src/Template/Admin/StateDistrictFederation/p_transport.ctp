<div class="transport_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '68' ? 'block' : 'none'; ?>;">

    
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Transport Cooperative Society Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>11(a). Type of Transport Cooperative Society<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_transport.type_society",['options'=>$trsocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_transport->type_society])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>11(b). Whether the co-operative society has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_transport.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'transport_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 transport_has_building_change" style="display:<?= $CooperativeRegistration->cooperative_registrations_transport->has_building == '1' ? 'block' : 'none'; ?>;">
                
                    <label>11(c). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_transport.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

               

             <!-- member -->
           <!--  <div class="clearfix"></div>
                <div class="col-sm-6">
                    <label>12. Member Detail of Cooperative Society<span
                            class="important-field">*</span></label>

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
                                            <?= $this->Form->control("sd_federation_transport.individual_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Institutional</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_transport.institutional_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
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

                  </div>

                <div class="clearfix"></div>
               <div class="col-sm-4">
                    <label>13(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_transport.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div>
                <div class="clearfix"></div> -->
                <!-------------------------------------paidup share----------------------------------------->
              <!--   <div class="col-sm-6">
                    <label>13(b). Paid up Share Capital by different Entity<span
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
                                            <?= $this->Form->control("sd_federation_transport.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_transport.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_transport.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
              <!--  <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(a). Annual Turn Over of the Cooperative Society (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_transport.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->

                
                <div class="col-sm-4">
                    <label>14(b). Type of Vehicle Ownership in Operation?<span class="important-field">*</span></label>
        
                   <?php     $typeowner = ['1'=>'Individual Members','2'=>'Owned by the society','3'=>'Mixed ownership']; ?>
                    <?= $this->Form->control('sd_federation_transport.type_owner',['options'=>$typeowner,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_transport->type_owner])?> 
                </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <label>15(a). Details of Vehicle operated by cooperative society:<span
                                class="important-field">*</span></label>
                        <div class="box-primary box-st">
                            
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-border table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                            <th scope="col" width="50%"><?= __('Type of Vehicles') ?>
                                            </th>
                                            <th scope="col" width="40%"><?= __('Number') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Bus</td>
                                            <td class="error-text-align">
                                                <?= $this->Form->control("sd_federation_transport.bus_type_detail",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n','onkeyup'=>'onlyNumbers(this)','maxlength'=>'10','value'=>$sd_fed_transport->bus_type_detail])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Truck/Lory</td>
                                            <td class="error-text-align">
                                                <?= $this->Form->control("sd_federation_transport.truck_type_detail",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n','onkeyup'=>'onlyNumbers(this)','maxlength'=>'10','value'=>$sd_fed_transport->truck_type_detail])?>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td>3.</td>
                                            <td>Other</td>
                                            <td class="error-text-align">
                                                <?= $this->Form->control("sd_federation_transport.other_type_detail",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n','onkeyup'=>'onlyNumbers(this)','maxlength'=>'10','value'=>$sd_fed_transport->other_type_detail])?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                   
                    <div class="col-sm-6">
                        <label>15(b). Travel Details of Passenger Vehicle in the Audit Year:<span
                                class="important-field">*</span></label>
                        <div class="box-primary box-st">
                        
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-border table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="10%"><?= __('Vehicle') ?></th>
                                            <th scope="col" width="50%"><?= __('Number of Passenger Vehicle') ?>
                                            </th>
                                            <th scope="col" width="40%"><?= __('Number of Member Travel') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td> Passenger Transport Vehicle </td>
                                        <td> <?=$this->Form->control("sd_federation_transport.no_passenger_vehicle",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_transport->no_passenger_vehicle])?></td>
                                        <td> <?=$this->Form->control("sd_federation_transport.no_member_travel",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_transport->no_member_travel])?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <label>15(c). Travel Details of Freigh Vehicle in the Audit Year:<span
                                class="important-field">*</span></label>
                        <div class="box-primary box-st">
                        
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-border table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="10%"><?= __('Vehicle') ?></th>
                                            <th scope="col" width="50%"><?= __('Number of Freight Vehicle') ?>
                                            </th>
                                            <th scope="col" width="40%"><?= __('Quantity of Goods Transported(MT)') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td> Goods Transport Vehicle </td>
                                        <td> <?=$this->Form->control("sd_federation_transport.no_freight_vehicle",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_transport->no_freight_vehicle])?></td>
                                        <td> <?=$this->Form->control("sd_federation_transport.quantity_good_transport",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_transport->quantity_good_transport])?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-4">
                    <label>15(d). Whether the vehicle are operated/maintained by members themself? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_transport.member_themself',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_transport->member_themself])?>
                </div>
                <div class="col-sm-4">
                    <label>15(e). Whether the members are users of the transport facility of Cooperative Societry? <span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_transport.is_user_transport_facility',['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'type'=>'radio','class'=>'','label'=>false,'required'=>true,'value'=>$sd_fed_transport->is_user_transport_facility])?>
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



first = $('#sd-federation-transport-individual-member').val();
second = $('#sd-federation-transport-institutional-member').val();


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



        first = $('#sd-federation-transport-paid-up-members').val();
        second = $('#sd-federation-transport-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-transport-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.transport_has_building', function() {
        if ($(this).val() == 1) {
            $('.transport_has_building_change').show();
        } else {
            $('.transport_has_building_change').hide();
        }
    });

    $('body').on('change', '.transport_has_land', function() {
        if ($(this).val() == 1) {
            $('.transport_available_land').show();
        } else {
            $('.transport_available_land').hide();
        }
    });

    
    $('body').on('change', '.transport_has_loan', function() {
        if ($(this).val() == 1) {
            $('.transport_available_loan').show();
        } else {
            $('.transport_available_loan').hide();
        }
    });

    $('body').on('change', '.transport_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.transport_available_other_loan').show();
        } else {
            $('.transport_available_other_loan').hide();
        }
    });

    $('body').on('change', '#sd-federation-transport-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-transport-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-transport-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-transport-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-transport-facilities').trigger(
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
</script>