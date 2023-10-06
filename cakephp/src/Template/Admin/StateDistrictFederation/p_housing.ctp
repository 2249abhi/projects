<div class="housing_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '47' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Housing Cooperative Federation Details</strong></h4>
            <div class="row">
                <!-- <div class="col-sm-4">
                    <label>13(a). Type of Housing Society<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.type_society",['options'=>$societytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_housing->type_society])?>
                </div> -->
             

                <!-- <div class="clearfix"></div> -->

                <div class="col-sm-4">
                    <label>13(a). Whether the cooperative federation has land <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.has_land",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'housing_has_land','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_housing->has_land])?>
                </div>
                <!------land area------>
                <div class="clearfix"></div>
                <div class="col-sm-6 housing_available_land"
                    style="display:<?= $sd_fed_housing->has_land == '1' ? 'block' : 'none'; ?>;">
                    <label>13(b). Detail of Land Available with the Cooperative<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">

                            <table class="table table-hover table-border table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                        <th scope="col" width="50%"><?= __('Type of possession') ?>
                                        </th>
                                        <th scope="col" width="40%"><?= __('Area (in Acre)') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Owned Land</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_owned",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_data mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_owned])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Leased Land</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_leased",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_data mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_leased])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Land allotted by the Government</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_allotted",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_data mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_allotted])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 housing_land_total mb-n','value'=>$sd_fed_land->land_total])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!------land area------>
                </div>
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>11(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.authorised_share",['type'=>'text','placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>false,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
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
                                            <?= $this->Form->control("sd_federation_housing.paid_up_members",['type'=>'text','label'=>false,'required'=>false,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_housing.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>false,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_housing.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>false,'class'=>'numberadndesimal1 mb-n share'])?>
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
                    <label>12(a). Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_housing->annual_turn_over])?>
                </div> -->
                <div class="col-sm-4">
                    <label>14. Annual Expenses of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.annual_expenses",['type'=>'text','placeholder'=>'Annual Expenses of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_housing->annual_expenses])?>
                </div>
                <div class="clearfix"></div>
                <!-- <div style="display:flex;"> -->
                <div class="col-sm-4 ">
                    <!--flex-between -->
                    <label>15(a). Number of houses/flats completed till end of audited year<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.number_of_houses_audit_year",['type'=>'text','placeholder'=>'Number of houses/flats completed during the audit year:','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_housing->number_of_houses_audit_year])?>
                </div>
                <div class="col-sm-4">
                    <label>15(b). Number of houses/flats handover to the members till end of audited year<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.number_of_houses_during_year",['type'=>'text','placeholder'=>'Number of houses/flats handover to the members during the year','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_housing->number_of_houses_during_year])?>
                </div>
                <div class="col-sm-4">
                    <label>15(c). Number of houses/flats under construction till end of audited year<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.number_of_houses_construction",['type'=>'text','placeholder'=>'Number of houses/flats under construction','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_housing->number_of_houses_construction])?>
                </div>
                <div class="clearfix"></div>
                <!-- </div> -->
                <div class="col-sm-4 ">
                    <!--b2-->
                    <label>16(a). Facilities provided by Cooperative Federation<span
                            class="important-field"></span></label>
                    <?php $fac = explode(',',$sd_fed_housing->facilities); //echo "<pre>";print_r($fac); ?>
                    <?= $this->Form->control('sd_federation_housing.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'default'=>$fac])?>
                </div>
                <div class="col-sm-4">
                    <label>16(b). Whether the Federation facilitate its member in getting Loan for construction of houses
                        or additional structures within the complex? <span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_housing.loan_facilities",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'loan_facilities','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_housing->loan_facilities])?>
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


        first = $('#sd-federation-lands-land-owned').val();
        second = $('#sd-federation-lands-land-leased').val();
        third = $('#sd-federation-lands-land-allotted').val();

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

    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-housing-paid-up-members').val();
        second = $('#sd-federation-housing-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-housing-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.housing_has_building', function() {
        if ($(this).val() == 1) {
            $('.housing_has_building_change').show();
        } else {
            $('.housing_has_building_change').hide();
        }
    });

    $('body').on('change', '.housing_has_land', function() {
        if ($(this).val() == 1) {
            $('.housing_available_land').show();
        } else {
            $('.housing_available_land').hide();
        }
    });

    $('body').on('change', '#sd-federation-housing-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-housing-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-housing-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-housing-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-housing-facilities').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });

});
</script>