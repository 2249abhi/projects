<div class="miscellaneous_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '29' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Miscellaneous Cooperative Federation</strong></h4>
            <div class="row">
               

                <!-------------------------------------paidup share----------------------------------------->
                <div class="col-sm-6">
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
                                            <?= $this->Form->control("sd_federation_miscellaneous.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n share','maxlength'=>'10','value'=>$sd_federation_miscellaneous->paid_up_members])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government or Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_miscellaneous.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n share','maxlength'=>'10','value'=>$sd_federation_miscellaneous->paid_up_government_bodies])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_miscellaneous.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n share','value'=>$sd_federation_miscellaneous->paid_up_total])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
                <!-------------------------------------paidup share----------------------------------------->
               
               
                
                
                

            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {

   


    $(".paid").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-miscellaneous-paid-up-members').val();
        second = $('#sd-federation-miscellaneous-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-miscellaneous-paid-up-total').val(sum);

    });

    //========================================
    $('body').on('change', '.miscellaneous_has_building', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_has_building_change').show();
        } else {
            $('.miscellaneous_has_building_change').hide();
        }
    });

    $('body').on('change', '.miscellaneous_has_land', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_land').show();
        } else {
            $('.miscellaneous_available_land').hide();
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



    
    
   


    
    $('body').on('change', '.miscellaneous_has_loan', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_loan').show();
        } else {
            $('.miscellaneous_available_loan').hide();
        }
    });
    
    $('body').on('change', '.miscellaneous_has_other_loan', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_other_loan').show();
        } else {
            $('.miscellaneous_available_other_loan').hide();
        }
    });

    $('body').on('change', '.miscellaneous_has_common', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_behive').show();
        } else {
            $('.miscellaneous_available_behive').hide();
        }
    });
    
    $('body').on('change', '.miscellaneous_has_rear', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_rear').show();
        } else {
            $('.miscellaneous_available_rear').hide();
        }
    });

    $('body').on('change', '.miscellaneous_has_retail', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_retail').show();
        } else {
            $('.miscellaneous_available_retail').hide();
        }
    });

    $('body').on('change', '.miscellaneous_has_outlet', function() {
        if ($(this).val() == 1) {
            $('.miscellaneous_available_outlet').show();
        } else {
            $('.miscellaneous_available_outlet').hide();
        }
    });



});

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
</script>

