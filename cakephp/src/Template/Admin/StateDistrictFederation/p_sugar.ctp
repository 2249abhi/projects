<div class="sugar_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '11' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Sugar Cooperative (MILLS) Federation Details</strong></h4>
            <div class="row">
                
                <!-- <div class="clearfix"></div> -->
                <!-- <div class="col-sm-4">
                    <label>11(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div> -->
                <!-- <div class="clearfix"></div> -->
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
                                            <?= $this->Form->control("sd_federation_sugar.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_sugar.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_sugar.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(a). Number of Sugar mills or Manufacturing Plant operated by cooperative federation: <span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.suger_mills_no",['type'=>'text','placeholder'=>'Number of Sugar mills','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_sugar->suger_mills_no])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <label>13(b). Area of the Cooperative Sugar Mill(s):<span
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
                                        <th scope="col" width="40%"><?= __('Area (in acre)') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Build-up Area</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_sugar.build_up_area",['type'=>'text','label'=>false,'required'=>true,'class'=>'area numberadndesimal1 mb-n','maxlength'=>'10','value'=>$sd_fed_sugar->build_up_area])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Open Land Area</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_sugar.open_land_area",['type'=>'text','label'=>false,'required'=>true,'class'=>'area numberadndesimal1 mb-n','maxlength'=>'10','value'=>$sd_fed_sugar->open_land_area])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_sugar.total_area",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n area_total','value'=>$sd_fed_sugar->total_area])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!------land area------>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-3">
                    <label>14(a). Liecensed Capicity (in term of Tonne Crushing per Day (TCD))<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.liecensed_capicity",['type'=>'text','placeholder'=>'Liecensed Capicity','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_sugar->liecensed_capicity])?>
                </div>
                <div class="col-sm-3">
                    <label>14(b). Installed Capicity (in term of Tonne Crushing per day (TCD))<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.installed_capicity",['type'=>'text','placeholder'=>'Installed Capicity','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_sugar->installed_capicity])?>
                </div>
                <div class="col-sm-6">
                    <label>14(c). Crushing period of sugarcane (in term of month)<span class="important-field">*</span></label><br/>
                    <?php 
                    if(!empty($sd_fed_sugar->crushing_period_start))
                    {
                        $sd_fed_sugar->crushing_period_start = date_format($sd_fed_sugar->crushing_period_start,"d/m/Y");
                    }
                     
                    if(!empty($sd_fed_sugar->crushing_period_end))
                    {
                        $sd_fed_sugar->crushing_period_end = date_format($sd_fed_sugar->crushing_period_end,"d/m/Y");
                    }
                        
                    ?>
                    <div class="col-sm-3">
                        <?=$this->Form->control("sd_federation_sugar.crushing_period_start",['type'=>'text','placeholder'=>'Start Date','label'=>false,'required'=>true,'class'=>'crushing_start','readonly'=>true,'value'=>$sd_fed_sugar->crushing_period_start])?>
                    </div>
                    <div class="col-sm-3">
                    <?=$this->Form->control("sd_federation_sugar.crushing_period_end",['type'=>'text','placeholder'=>'End date','label'=>false,'required'=>true,'class'=>'crushing_end','readonly'=>true,'value'=>$sd_fed_sugar->crushing_period_end])?>
                    </div>
                </div>
                <div class="col-sm-3 ">
                   
                    <label>15(a). Type of product produced by Cooperative Federation<span class="important-field"></span></label>
                    <?php 
                        $product_produced = ['1'=>'white Sugar','2'=>'Raw Sugar (Brown)','3'=>'Molasses','4'=>'Bagasse','5'=>'Pressmud','6'=>'Ethanol','7'=>'Biogas','8'=>'Rectified Spirit','9'=>'Extra Neutral Alcohol', '10'=>'Liquor','11'=>'compost','12'=>'Cogeneration','13'=>'Bio-CNG','14'=>'Fertilizer','15'=>'Particle Board','16'=>'Paper','17'=>'Acetic Acid','18'=>'Fossil Oil','19'=>'Other'];
                    $sd_fed_sugar->product_produced = explode(',', $sd_fed_sugar->product_produced);  ?>
                    <?= $this->Form->control('sd_federation_sugar.product_produced',['options'=>$product_produced,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>true,'value'=>$sd_fed_sugar->product_produced])?>
                </div>
                <div class="col-sm-3">
                    <label>15(b). Whether the cooperative federation operate retail shops/outlet to sale products?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.retail_shops",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'retail_shops','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_sugar->retail_shops])?>
                </div>
                <div class="col-sm-3 retail_shops_change " style="display:<?= $sd_fed_sugar->retail_shops == '1' ? 'block' : 'none'; ?>">
                    <label>15(c). Number of retail shops/outlets operated by the coopertive federation<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.retail_shops_no",['type'=>'text','placeholder'=>'Number of retail shops/outlets','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_sugar->retail_shops_no])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>16(a). Whether inputs for sugarcane cultivation are provided to members? <span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.sugercane_input_provided",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_sugar->sugercane_input_provided])?>
                </div>
                <div class="col-sm-4">
                    <label>16(b). Whether the facilities of Loans and Advances are offered to the Members?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.loan_facility",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_sugar->loan_facility])?>
                </div>
                <div class="col-sm-4">
                    <label>16(c). Whether the waste management facility is available in the Cooperative Mill?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.waste_management",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_sugar->waste_management])?>
                </div>
                <div class="col-sm-4">
                    <label>16(d). Whether any benefits (subsidy/scheme) received from Central Government?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.central_government_benefits",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_sugar->central_government_benefits])?>
                </div>
                <div class="col-sm-4">
                    <label>16(e). Whether any benefits (subsidy/scheme) received from State Government?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_sugar.state_government_benefits",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_sugar->state_government_benefits])?>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    //=========================================

    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-sugar-paid-up-members').val();
        second = $('#sd-federation-sugar-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-sugar-paid-up-total').val(sum);

    });

    $(".area").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-sugar-build-up-area').val();
        second = $('#sd-federation-sugar-open-land-area').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('.area_total').val(sum);

    });
    
    $('body').on('change', '.sugar_has_building', function() {
        if ($(this).val() == 1) {
            $('.sugar_has_building_change').show();
        } else {
            $('.sugar_has_building_change').hide();
        }
    });

   

    $('body').on('change', '.retail_shops', function() {
        $('#sd-federation-sugar-retail-shops-no').val('');
        if ($(this).val() == 1) {
            $('.retail_shops_change').show();
        } else {
            
            $('.retail_shops_change').hide();
        }
    });

    $(".crushing_start").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            maxDate: new Date(),
			yearRange: "-122:-0",
        });

    $(".crushing_end").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
        yearRange: "-122:-0",
    });


});
</script>