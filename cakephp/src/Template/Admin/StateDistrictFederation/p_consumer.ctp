<div class="consumer_change change" 
style="display:<?= $StateDistrictFederation->sector_of_operation == '80' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Consumer Cooperative Federation Details</strong></h4>
            <div class="row">
               
                

                <div class="col-sm-4">
                    <label>13(a). Whether the cooperative federation is operating Own Departmental Store/Supermarket? <span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_consumer.has_store",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'housing_has_store','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_consumer->has_store])?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4 housing_has_store_change" style="display:<?= $sd_fed_consumer->has_store == '1' ? 'block' : 'none'; ?>;">
                    <label>13(b). No.of outlets/shops of Cooperative Federation:<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_consumer.no_of_outlets",['type'=>'text','placeholder'=>'No. of outlets/shops of Cooperative Society','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_consumer->no_of_outlets])?>
                </div>
                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>12(a). Authorised Share Capital (in Rs):<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_consumer.authorised_share",['type'=>'text','placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div>
                <div class="col-sm-4">
                    <label>12(b). Paid up Share Capital (in Rs):<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_consumer.paid_up_share",['type'=>'text','placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 share','maxlength'=>'10'])?>
                </div>
                <div class="col-sm-4">
                    <label>12(c). Annual Turn Over of the Cooperative Federation (in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_consumer.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the Cooperative Society (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="col-sm-6" >
                    <label>11(b). Paid up Share Capital by different Entity<span class="important-field">*</span></label>
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
                                        <td><?= $this->Form->control("sd_federation_consumer.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td><?= $this->Form->control("sd_federation_consumer.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td><?= $this->Form->control("sd_federation_consumer.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  
                </div> -->
                <!-------------------------------------paidup share----------------------------------------->
                <!-- <div class="clearfix"></div> -->
                
                
                    <div class="clearfix"></div>   
                <!-- </div> -->
                <div class="col-sm-4 "><!--b2-->
                    <label>14. Facilities provided by Cooperative Federation<span class="important-field"></span></label>
                    <?php $sd_fed_consumer->facilities = explode(',',$sd_fed_consumer->facilities);  ?>
                    <?= $this->Form->control('sd_federation_consumer.facilities',['options'=>$facilities,'label'=>false,'multiple'=>true,'class'=>'select2','required'=>false,'default'=>$sd_fed_consumer->facilities])?>
                </div>
                <!-- <div class="col-sm-4">
                    <label>14(b). Whether the Society facilitate its member in getting Loan for construction of houses or additional structures within the complex? <span   class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_consumer.loan_facilities",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'loan_facilities','type'=>'radio','label'=>false,'required'=>true])?>
                </div> -->
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
        


        first = $('#sd-federation-consumer-paid-up-members').val();
        second = $('#sd-federation-consumer-paid-up-government-bodies').val();
        

        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-consumer-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.housing_has_building', function() {
        if ($(this).val() == 1) {
            $('.housing_has_building_change').show();
        } else {
            $('.housing_has_building_change').hide();
        }
    });

    $('body').on('change', '.housing_has_store', function() {
        if ($(this).val() == 1) {
            $('.housing_has_store_change').show();
        } else {
            $('.housing_has_store_change').hide();
        }
    });

    $('body').on('change', '#sd-federation-consumer-facilities', function(e) {
            e.preventDefault();
            $('#sd-federation-consumer-facilities > option:selected').each(function() {
                if($(this).val() == 1 && $('#sd-federation-consumer-facilities > option:selected').length > 1)
                {
                    //console.log('#sector-' + increment + '-village-code');
                    $('#sd-federation-consumer-facilities').val('1'); // Select the option with a value of '1'
                    $('#sd-federation-consumer-facilities').trigger('change'); // Notify any JS components that the value chan
                    return false; // breaks

                }
            });
            
        });

});
</script>


