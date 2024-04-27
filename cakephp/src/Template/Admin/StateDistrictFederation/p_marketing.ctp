<div class="marketing_change2 change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '82' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Primary Marketing Cooperative Federation Details</strong></h4>
            <div class="row">
                
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13(a). Whether the cooperative federation has land <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_marketing.has_land",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'marketing_has_land','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_marketing->has_land])?>
                </div>

                <!------land area------>
                <div class="clearfix"></div>
                <div class="col-sm-6 marketing_available_land : <?= $sd_fed_marketing->has_land ?>"
                    style="display:<?= $sd_fed_marketing->has_land == '1' ? 'block' : 'none'; ?>;">
                    <label>13(b). Detail of Land Available with the Cooperative<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        
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
                                            <?= $this->Form->control("sd_federation_lands.land_owned",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 marketing_land_data marketing_land_owned mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_owned])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Leased Land</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_leased",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 marketing_land_data marketing_land_leased mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_leased])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Land allotted by the Government</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_allotted",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 marketing_land_data marketing_land_allotted mb-n','maxlength'=>'10','value'=>$sd_fed_land->land_allotted])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_lands.land_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 marketing_land_total mb-n','value'=>$sd_fed_land->land_total])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="col-sm-4">
                    <label>13(c). Does the cooperative federation have warehouses or godowns?<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_marketing.has_warehouses",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'has_warehouses','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_marketing->has_warehouses])?>
                </div>
                <div class="col-sm-4 warehouses_change"
                    style="display:<?= $sd_fed_marketing->has_warehouses == '1' ? 'block' : 'none'; ?>">
                    <label>13(d). Capacity of the Warehouses or Godowns (MT):<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_marketing.capacity_warehouses",['type'=>'text','placeholder'=>'Capacity of the Warehouses or Godowns (MT)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_marketing->capacity_warehouses])?>
                </div>
                <div class="clearfix"></div>
                
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
                                            <?= $this->Form->control("sd_federation_marketing.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_marketing.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_marketing.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share'])?>
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
                    <?=$this->Form->control("sd_federation_marketing.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="col-sm-4">
                    <label>14. Annual Expenses of the Cooperative Federation (in Rs)<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_marketing.annual_expenses",['type'=>'text','placeholder'=>'Annual Expenses of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_marketing->annual_expenses])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <label>15. Details of the items for which cooperative federation has license to sell:<span
                            class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-hover table-border table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                        <th scope="col" width="50%"><?= __('Items') ?></th>
                                        <th scope="col" width="40%">
                                            <?= __('Does the Cooperative Society have License to sell the item') ?></th>
                                        <th scope="col" width="40%">
                                            <?= __('Does the Cooperative Society Sell the item') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $liecense_to_sell = [];
                                    $sell_the_item = [];
                                    if(!empty($sd_fed_marketing->liecense_to_sell))
                                    {
                                        $liecense_to_sell = $sd_fed_marketing->liecense_to_sell = explode(',',$sd_fed_marketing->liecense_to_sell);
                                    }

                                    if(!empty($sd_fed_marketing->sell_the_item))
                                    {
                                        $sell_the_item = $sd_fed_marketing->sell_the_item = explode(',',$sd_fed_marketing->sell_the_item);
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td>1.</td>
                                        <td>Seeds</td>
                                        <td>
                                            <?= $this->Form->checkbox("sd_federation_marketing.liecense_to_sell[]", array('value'=>'1', 'hiddenField' => false,'checked'=> in_array('1',$liecense_to_sell) ? true : false )); ?>
                                        </td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.sell_the_item[]", array('value'=>'1', 'hiddenField' => false,'checked'=> in_array('1',$sell_the_item) ? true : false)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Food grains Procurement on MSP</td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.liecense_to_sell[]", array('value'=>'2', 'checked' => in_array('2',$liecense_to_sell) ? true : false , 'hiddenField' => false)); ?>
                                        </td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.sell_the_item[]", array('value'=>'2', 'checked' => in_array('2',$sell_the_item) ? true : false , 'hiddenField' => false)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>PDS Shop</td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.liecense_to_sell[]", array('value'=>'3', 'checked' => in_array('3',$liecense_to_sell) ? true : false , 'hiddenField' => false)); ?>
                                        </td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.sell_the_item[]", array('value'=>'3', 'checked' => in_array('3',$sell_the_item) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>Fertilizer</td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.liecense_to_sell[]", array('value'=>'4', 'checked' => in_array('4',$liecense_to_sell) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.sell_the_item[]", array('value'=>'4', 'checked' => in_array('4',$sell_the_item) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>Pesticide</td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.liecense_to_sell[]", array('value'=>'5', 'checked' => in_array('5',$liecense_to_sell) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.sell_the_item[]", array('value'=>'5', 'checked' => in_array('5',$sell_the_item) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6.</td>
                                        <td>Agricultural Equipment on Rent</td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.liecense_to_sell[]", array('value'=>'6', 'checked' => in_array('6',$liecense_to_sell) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                        <td><?= $this->Form->checkbox("sd_federation_marketing.sell_the_item[]", array('value'=>'6', 'checked' => in_array('6',$sell_the_item) ? true : false, 'hiddenField' => false)); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!------land area------>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    //=========================================

    $(".marketing_land_data").bind("change paste keyup", function() {


        var first = '';
        var second = '';
        var third = '';
        var fourth = '';


        first = $('.marketing_land_owned').val();
        second = $('.marketing_land_leased').val();
        third = $('.marketing_land_allotted').val();

        var arr = [first, second, third].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('.marketing_land_total').val(sum);

    });

    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-marketing-paid-up-members').val();
        second = $('#sd-federation-marketing-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-marketing-paid-up-total').val(sum);

    });



    $('body').on('change', '#sd-federation-marketing-is-members-of', function() {
        // $('.member').val('');
        $('.member_change').hide();
        if ($(this).val() == '4') {

            $('.state_member').show();

        } else if ($(this).val() == '5') {

            $('.district_member').show();

        } else if ($(this).val() == '6') {

            $('.other_member').show();

        }
    });

    //========================================
    $('body').on('change', '.marketing_has_building', function() {
        if ($(this).val() == 1) {
            $('.marketing_has_building_change').show();
        } else {
            $('.marketing_has_building_change').hide();
        }
    });

    $('body').on('change', '.marketing_has_land', function() {
        if ($(this).val() == 1) {
            $('.marketing_available_land').show();
        } else {
            $('.marketing_available_land').hide();
        }
    });

    $('body').on('change', '.has_warehouses', function() {
        if ($(this).val() == 1) {
            $('.warehouses_change').show();
        } else {
            $('.warehouses_change').hide();
        }
    });



});
</script>