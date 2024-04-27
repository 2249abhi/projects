<div class="credit_thrift_change change" style="display:<?= $StateDistrictFederation->sector_of_operation == '18' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Credit & Thrift Federation Details</strong></h4>
            <div class="row">
                <!-- <div class="col-sm-4">
                    <label>10(a). Whether the co-operative society has an office building <span  class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_credit.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'housing_has_building1','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 housing_has_building_change1 <?= $CooperativeRegistration->cooperative_registrations_credit_thrift->has_building ?>" style="display:<?= $CooperativeRegistration->cooperative_registrations_credit_thrift->has_building == '1' ? 'block' : 'none'; ?>;">
                    <label>10(b). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_credit.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4">
                    <label>11(a). Authorised Share Capital (in Rs):<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_credit.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="col-sm-4" >
                    <label>11(b). Paid up Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_credit.paid_up_share",['type'=>'text','placeholder'=>'Paid up Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid share','maxlength'=>'10'])?>
                   
                </div> -->
                <div class="col-sm-4">
                    <label>13(a). Total Deposit (in Rs.)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_credit.total_deposit",['type'=>'text','placeholder'=>'Total Deposit (in Rs.)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_credit->total_deposit])?>
                </div>
                <div class="col-sm-4">
                    <label>13(b). Total Amount of Loan Outstanding (in Rs.)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_credit.pack_total_outstanding_loan",['placeholder'=>'Total Amount of Loan Outstanding (in Rs.)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10','value'=>$sd_fed_credit->pack_total_outstanding_loan])?>
                </div>
                <div class="clearfix"></div>
                <!-------------------------------------paidup share----------------------------------------->
                <div class="col-sm-4 b2">
                    <label>14. Facilities provided by Cooperative Federation<span class="important-field"></span></label>
                    <?php $sd_fed_credit_fac = explode(',',$sd_fed_credit->facilities); ?>
                    <?= $this->Form->control('sd_federation_credit.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>true, 'default'=>$sd_fed_credit_fac])?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
$(document).ready(function() {


    //========================================
    $('body').on('change', '.housing_has_building1', function() {
        console.log('success');
        if ($(this).val() == 1) {
            
            $('.housing_has_building_change1').show();
        } else {
            $('.housing_has_building_change1').hide();
        }
    });

 

    $('body').on('change', '#sd-federation_credit-facilities', function(e) {
            e.preventDefault();
            $('#sd-federation-credit-facilities > option:selected').each(function() {
                if($(this).val() == 1 && $('#sd-federation-credit-facilities > option:selected').length > 1)
                {
                    //console.log('#sector-' + increment + '-village-code');
                    $('#sd-federation-credit-facilities').val('1'); // Select the option with a value of '1'
                    $('#sd-federation-credit-facilities').trigger('change'); 
                    return false; // breaks

                }
            });
            
    });

});
</script>


