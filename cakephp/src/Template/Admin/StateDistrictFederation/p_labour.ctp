<div class="labour_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '51' ? 'block;' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Labour Cooperative Federation Details</strong></h4>
            <div class="row">
                <div class="col-sm-4">
                    <label>13(a). Type of Labour Federation<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.type_society",['options'=>$lsocietytypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true,'value'=>$sd_fed_labour->type_society])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>11(b). Whether the cooperative federation has an office building <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'housing_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div>
                <div class="col-sm-4 housing_has_building_change" style="display:<?= $StateDistrictFederation->cooperative_registrations_labour->has_building == '1' ? 'block' : 'none'; ?>;">
              
                    <label>11(c). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                </div> -->

                <div class="clearfix"></div>

                <div class="col-sm-4">
                    <label>13(b). Whether the work is alloted by State/District Federation to the Society? <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.work_allot_state_dist_federation",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_labour->work_allot_state_dist_federation])?>
                </div>
                <div class="col-sm-4">
                    <label>13(c). Whether the proper guidance/training for execution of the work provided by State/District Federation? <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.work_guide_state_dist_federation",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_labour->work_allot_state_dist_federation])?>
                </div>
                <div class="col-sm-4">
                    <label>13(d). Whether cooperative availing any Concession provided by State Government? <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.concession_state_gov",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_labour->work_allot_state_dist_federation])?>
                </div>
                <!------land area------>
              
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(e). Whether cooperative availing any Concession provided by Centre Government? <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.concession_centre_gov",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_labour->work_allot_state_dist_federation])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.authorised_share",['type'=>'text','placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>false,'class'=>'numberadndesimal1 share','maxlength'=>'10','value'=>$sd_fed_labour->authorised_share])?>
                </div> -->
                <div class="clearfix"></div>
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="col-sm-6">
                    <label>12(b). Paid up Share Capital by different Entity<span
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
                                            <?= $this->Form->control("sd_federation_labour.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_labour.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_labour.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div> -->
             
                <!-- <div class="col-sm-4">
                    <label>12(c). Annual Turn Over of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_labour.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_labour->annual_turn_over])?>
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
              
                
            
                <div class="clearfix"></div>
                <!-- </div> -->
                <div class="col-sm-4 ">
                    <!--b2-->
                    <label>14. Facilities provided by Cooperative Federation<span
                            class="important-field"></span></label>
                    

                    <?php $data_facilities = explode(',',$sd_fed_labour->facilities);  ?>
                    <?= $this->Form->control('sd_federation_labour.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'value'=>$data_facilities])?>
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

    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#cooperative-registrations-labour-paid-up-members').val();
        second = $('#cooperative-registrations-labour-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#cooperative-registrations-labour-paid-up-total').val(sum);

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

    $('body').on('change', '#cooperative-registrations-labour-facilities', function(e) {
        e.preventDefault();
        $('#cooperative-registrations-labour-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#cooperative-registrations-labour-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#cooperative-registrations-labour-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#cooperative-registrations-labour-facilities').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });

});
</script>