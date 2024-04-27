<div class="processing_change change" style="display:<?= $StateDistrictFederation->sector_of_operation == '31' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Processing/Industry Cooperative Federation Details</strong>
            </h4>
            <div class="row">
                <!-- <div class="col-sm-6">
                    <label>10(a). Member Details of the Cooperative Federation:<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-border table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                        <th scope="col" width="50%"><?= __('Type of Member') ?>
                                        </th>
                                        <th scope="col" width="40%"><?= __('Number of Members') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Individual</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_processing.individual_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numbers member_count mb-n','maxlength'=>'8'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Institutional</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_processing.institutional_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numbers member_count number mb-n','maxlength'=>'8'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_processing.total_member",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'mb-n'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div> -->
                <div class="col-sm-4">
                    <label>13(a). Type of product produced by cooperative federation<span
                            class="important-field">*</span></label>
                    <?php $processing_products = [
                        '1' => 'Agriculture & Allied',
                        '2' => 'Non-Agriculture'
                    ];  ?>
                    <?=$this->Form->control("sd_federation_processing.type_society",['options'=>$processing_products,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_processing->type_society])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>10(c). Whether the cooperative federation has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'processing_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 processing_has_building_change"
                    style="display:<?= $CooperativeRegistration->cooperative_registrations_processing->has_building == '1' ? 'block' : 'none'; ?>;">
                    <label>10(d). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>11(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                <div class="clearfix"></div>
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="col-sm-6">
                    <label>11(b). Paid up Share Capital by different Entity<span
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
                                            <?= $this->Form->control("sd_federation_processing.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_processing.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_processing.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="clearfix"></div> -->
                <!-- <div class="col-sm-4">
                    <label>12(a). Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="clearfix"></div>
                
                <!-- <div class="col-sm-4 contents-div fixed-seq">
                    <label>12(b). Whether the loan taken from the DCCB/UCB/PCARDB <span
                            class="important-field">*</span></label>
                    <?php //$this->Form->control("cooperative_registrations_processing.loan_from_dccb",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'loan_from_dccb','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 loan_from_dccb_change fixed-seq"
                    style="display:<?php //$CooperativeRegistration->cooperative_registrations_processing->loan_from_dccb == '1' ? 'block' : 'none'; ?>;">
                    <label>12(c). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs):<span
                            class="important-field">*</span></label>
                    <?php //$this->Form->control("cooperative_registrations_processing.dccb_loan_amount",['type'=>'text','label'=>false,'placeholder'=>'Loan and Advances taken from DCCB/UCB/PCARDB (in Rs)','required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>12(d). Whether the loan taken from Other Bank? <span class="important-field">*</span></label>
                    <?php //$this->Form->control("cooperative_registrations_processing.loan_from_other",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'loan_from_other','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 loan_from_other_change fixed-seq"
                    style="display:<?php //$CooperativeRegistration->cooperative_registrations_processing->loan_from_other == '1' ? 'block' : 'none'; ?>;">
                    <label>12(e). Loan and Advances taken from Other Bank (in Rs):<span
                            class="important-field">*</span></label>
                    <?php //$this->Form->control("cooperative_registrations_processing.loan_from_other_amount",['type'=>'text','label'=>false,'placeholder'=>'Loan and Advances taken from Other Bank (in Rs.)','required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(b). Whether the cooperative federation has Processing Unit or Manufacturing Unit in
                        Operation? <span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.processing_unit",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'processing_unit','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->processing_unit])?>
                </div>
                <div class="col-sm-4 processing_unit_change fixed-seq"
                    style="display:<?= $sd_fed_processing->processing_unit == '1' ? 'block' : 'none'; ?>;">
                    <label>13(c). Number of Processing Unit or Manufacturing Unit operated by cooperative federation:<span
                            class="important-field">*</span></label>
                    <?= $this->Form->control("sd_federation_processing.processing_unit_number",['type'=>'text','placeholder'=>'Number of Processing Unit or Manufacturing Unit','label'=>false,'required'=>true,'class'=>'number processing_unit_number','maxlength'=>'10','value'=>$sd_fed_processing->processing_unit_number])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(d). Whether the Processing or Manufacturing work is done by members themself? <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.processing_by_members",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'processing_by_members','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->processing_by_members])?>
                </div>
                <div class="col-sm-4 processing_by_members_change fixed-seq"
                    style="display:<?= $sd_fed_processing->processing_by_members == '1' ? 'block' : 'none'; ?>;">
                    <label>13(e). Whether the work is divided among the members as per their skill?<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.work_divided",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'work_divided','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->work_divided])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(f). Whether the cooperative federation provide raw material to members & produced product
                        taken after processing ?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.product_taken",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'product_taken','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->product_taken])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(g). Whether the raw material easily available to cooperative federation ?<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.material_available",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'material_available','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->material_available])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(h). Whether the wastes are generated in production process ?<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.wastes_generated",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'wastes_generated','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->wastes_generated])?>
                </div>

                <div class="col-sm-4 wastes_generated_change contents-div fixed-seq"
                    style="display:<?= $sd_fed_processing->wastes_generated == '1' ? 'block' : 'none'; ?>;">
                    <label>13(i). Whether the waste management facility is available in the federation ?<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.waste_management_facility",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'waste_management_facility','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->waste_management_facility])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(j). Whether the cooperative federation operate retail shops/outlet to sale products ?<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.operate_shops",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'operate_shops','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->operate_shops])?>
                </div>

                <div class="col-sm-4 operate_shops_change contents-div fixed-seq"
                    style="display:<?= $sd_fed_processing->operate_shops == '1' ? 'block' : 'none'; ?>;">
                    <label>13(k). Number of retail shops/outlets operated by the cooperative federation:<span
                            class="important-field">*</span></label>
                    <?= $this->Form->control("sd_federation_processing.operate_shops_number",['type'=>'text','label'=>false,'required'=>true,'placeholder'=>'Number of retail shops/outlets','class'=>'operate_shops_number number','maxlength'=>'10','value'=>$sd_fed_processing->operate_shops_number])?>
                </div>

                <div class="col-sm-4 contents-div fixed-seq">
                    <label>13(l). Whether products are sale out of the area of operation of the cooperative
                    federation?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_processing.product_sale_out_of_area",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_processing->product_sale_out_of_area])?>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    //=========================================

    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-processing-paid-up-members').val();
        second = $('#sd-federation-processing-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-processing-paid-up-total').val(sum);

    });

    $(".member_count").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-processing-individual-member').val();
        second = $('#sd-federation-processing-institutional-member').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-processing-total-member').val(sum);

    });

    //========================================
    $('body').on('change', '.processing_has_building', function() {
        if ($(this).val() == 1) {
            $('.processing_has_building_change').show();
        } else {
            $('.processing_has_building_change').hide();
        }
    });

    $('body').on('change', '.loan_from_dccb', function() {
        if ($(this).val() == 1) {
            $('.loan_from_dccb_change').show();
        } else {
            $('#sd-federation-processing-dccb-loan-amount').val('');
            $('.loan_from_dccb_change').hide();
        }
    });

    $('body').on('change', '.loan_from_other', function() {
        if ($(this).val() == 1) {
            $('.loan_from_other_change').show();
        } else {
            $('#sd-federation-processing-loan-from-other-amount').val('');
            $('.loan_from_other_change').hide();
        }
    });

    $('body').on('change', '.processing_unit', function() {
        if ($(this).val() == 1) {
            $('.processing_unit_change').show();
        } else {
            $('.processing_unit_number').val('');
            $('.processing_unit_change').hide();
        }
    });


    $('body').on('change', '.processing_by_members', function() {
        if ($(this).val() == 1) {
            $('.processing_by_members_change').show();
        } else {
            $('.work_divided').val('');
            $('.processing_by_members_change').hide();
        }
    });

    $('body').on('change', '.wastes_generated', function() {
        if ($(this).val() == 1) {
            $('.wastes_generated_change').show();
        } else {
            $('.waste_management_facility').val('');
            $('.wastes_generated_change').hide();
        }
    });

    $('body').on('change', '.operate_shops', function() {
        if ($(this).val() == 1) {
            $('.operate_shops_change').show();
        } else {
            $('.operate_shops_number').val('');
            $('.operate_shops_change').hide();
        }
    });

});
</script>
<style>
.contents-div .form-group .radio {
    display: contents !important;
}

.fixed-seq {
    height: 90px;
}
</style>