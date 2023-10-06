<div class="ucb_change change"
    style="display:<?= $StateDistrictFederation->sector_of_operation == '7' ? 'block' : 'none'; ?>;">
    <div class="box-block-m">
        <div class="col-sm-12">
            <h4><strong id="primary_activity_details">Urban Cooperative Bank</strong>
            </h4>
            <div class="row">

                <div class="col-sm-4">
                    <label>13(a). Total Number of branches of UCB:<span class="important-field">*</span></label>
                    <?=$this->Form->control('sd_federation_ucb.ucb_branch',['type'=>'textbox','class'=>'','placeholder'=>'Total Number of branches of UCB','label'=>false,'required'=>true, 'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)','value'=>$sd_fed_ucb->ucb_branch])?>
                </div>
                <div class="col-sm-4">
                    <label>13(b). Whether the UCB is affiliated to NAFCUB?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.has_nafcub",['options'=>['1'=>'Yes','0'=>'No'],'default'=>'0','class'=>'','type'=>'radio','label'=>false,'required'=>true,'value'=>$sd_fed_ucb->has_nafcub])?>
                </div>

                <!-- <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>11. Total Number of Members<span class="important-field">*</span></label>
                    <?=$this->Form->control('members_of_society',['type'=>'textbox','class'=>'','placeholder'=>'Total Number of Members','label'=>false,'required'=>true, 'maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                </div> -->

                <!-- <div class="col-sm-6">
                    <label>10. Detail of Members in UCB<span class="important-field">*</span></label>
                    <div class="box-primary box-st">
                        <div class="box-body table-responsive">

                            <table class="table table-hover table-border table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                        <th scope="col" width="50%"><?= __('Membership Type') ?>
                                        </th>
                                        <th scope="col" width="40%"><?= __('Number of Member') ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>1.</td>
                                        <td>Borrowing Members</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_ucb.borrowing_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>2.</td>
                                        <td>Non-Borrowing Members</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_ucb.non_borrowing_member",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n','maxlength'=>'10','onkeyup'=>'onlyNumbers(this)'])?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total Members</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("members_of_society",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_total mb-n'])?>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="clearfix"></div> -->




                <!-- <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>12(a). Whether the UCB has an office building?<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.has_building",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'ucb_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                </div> -->
                <!-- <div class="col-sm-4 ucb_has_building_change"
                    style="display:<?= $CooperativeRegistration->cooperative_registrations_ucb->has_building == '1' ? 'block' : 'none'; ?>;">

                    <label>12(b). Type of Office Building<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.building_type",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2 building_type','required'=>true])?>
                </div> -->

                <!-- ################################################################ Bank Financial Details ############################################################### -->
                <!-- <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>13. Authorised Share Capital of the UCB (in Rs):<span
                            class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.authorised_share",['type'=>'text','placeholder'=>'Authorised Share Capital of the UCB','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'12'])?>
                </div> -->
                <!-- <div class="clearfix"></div> -->

                <!-- <div class="col-sm-6">
                    <label>12(b). Paid up Share Capital by different Entity:<span
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
                                        <td>Members other than Govt./Govt. Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_ucb.paid_up_members",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 paid mb-n','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Government & Government Bodies</td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_ucb.paid_up_government_bodies",['type'=>'text','label'=>false,'required'=>true,'class'=>'paid numberadndesimal1 mb-n','maxlength'=>'10'])?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: none !important;"></td>
                                        <td style="float: none; border: none !important;"><b>Total</b></td>
                                        <td class="error-text-align">
                                            <?= $this->Form->control("sd_federation_ucb.paid_up_total",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n'])?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->


                <div class="clearfix"></div>
                <!-- <div class="col-sm-4">
                    <label>14(a). Annual Turn Over of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.annual_turn_over",['type'=>'text','placeholder'=>'Annual Turn Over of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                </div> -->
                <div class="col-sm-4">
                    <label>14(a). Annual Income of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.annual_income",['type'=>'text','placeholder'=>'Annual Income of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_ucb->annual_income])?>
                </div>
                <div class="col-sm-4">
                    <label>14(b). Annual Expenditure of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.annual_ucb_expenditr",['type'=>'text','placeholder'=>'Annual Expenditure of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_ucb->annual_ucb_expenditr])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(c). Assets of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.asset_ucb",['type'=>'text','placeholder'=>'Assets of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_ucb->asset_ucb])?>
                </div>
                <div class="col-sm-4">
                    <label>14(d). Liabilities of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.liability_ucb",['type'=>'text','placeholder'=>'Liabilities of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_ucb->liability_ucb])?>
                </div>
                <div class="col-sm-4">
                    <label>14(e). Total Deposit of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.total_deposit",['type'=>'text','placeholder'=>'Total Deposit of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_ucb->total_deposit])?>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <label>14(f). Total loan Outstanding of the UCB(in Rs)<span class="important-field">*</span></label>
                    <?=$this->Form->control("sd_federation_ucb.loan_outstanding",['type'=>'text','placeholder'=>'Total loan Outstanding of the UCB (in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10','value'=>$sd_fed_ucb->loan_outstanding])?>
                </div>


            </div>

            <div class="clearfix"></div>
            <div class="col-sm-4">
                <label>15(a). Whether the UCB implementing any Central/State Government Scheme? <span
                        class="important-field">*</span></label>
                <?=$this->Form->control('sd_federation_ucb.is_gov_scheme_implemented',['options'=>['0'=>'No','1'=>'Yes'],'default'=>0,'type'=>'radio','class'=>'ucb_has_other_loan','label'=>false,'required'=>true, 'onchange'=>'ucb_loan(this)','value'=>$sd_fed_ucb->is_gov_scheme_implemented])?>
            </div>

            <div class="clearfix"></div>

            <div class="ucb_available_other_loan gov_scheme_box" id="gov_scheme_implemented"
                style="display:<?= $sd_fed_ucb->is_gov_scheme_implemented == '1' ? 'block' : 'none'; ?>">
                <?php $gs2=0;?>
                <b style="margin-left:15px;text-decoration: underline;">15(b). Name of the Central Government Schemes or
                    State Government Schemes
                    implemented by
                    UCB:</b>
                <div class="clearfix"></div>

                <?php if(count($sd_fed_imp_sch)!=0){
						    foreach ($sd_fed_imp_sch as $gs2 => $sd_fed_imp_sch){ ?>
                <div class="gov_scheme_rows" rowclass="<?=$gs2?>" style="margin-top: 1em;">

                    <div class="col-sm-3">
                        <label>Name of Government Scheme <span class="important-field">*</span></label>
                        <?= $this->Form->control("sd_federation_implementing_schemes.$gs2.gov_scheme_name",['type'=>'text','placeholder'=>'Name of Government Scheme','label'=>false,'required'=>true,'class'=>'mb-n gov_scheme_name','maxlength'=>'200','value'=>$sd_fed_imp_sch->gov_scheme_name])?>
                        <?php //$this->Form->control("society_implementing_schemes.$gs2.scheme_type",['type'=>'hidden','label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n','maxlength'=>'10','value'=>'1'])?>
                    </div>

                    <div class="col-sm-3">
                        <label>Type of Scheme (Central/State) <span class="important-field">*</span></label>
                        <?php //$this->Form->control("society_implementing_schemes.$gs2.gov_scheme_type",['type'=>'text','label'=>false,'required'=>true,'class'=>'mb-n','maxlength'=>'10'])?>
                        <?=$this->Form->control("sd_federation_implementing_schemes.$gs2.gov_scheme_type",['options'=>['1'=>'Central','0'=>'State'],'id'=>'gov_scheme_type','empty'=>'Select','label'=>false,'class'=>'select2 gov_scheme_type','required'=>true,'value'=>$sd_fed_imp_sch->gov_scheme_type])?>
                    </div>

                    <div class="col-sm-3">
                        <label>Total Amount Spent through UCB (Rs) <span class="important-field">*</span></label>
                        <?= $this->Form->control("sd_federation_implementing_schemes.$gs2.total_amount",['type'=>'text','placeholder'=>'Total Amount Spent through UCB (Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1  mb-n gov_total_amount','maxlength'=>'10','value'=>$sd_fed_imp_sch->total_amount])?>
                    </div>


                    <div class="col-sm-12">
                        <button type="button" class="pull-right btn btn-primary btn-xs add_row_gov_scheme"><i
                                class="fa fa-plus-circle"></i></button>
                        &nbsp;&nbsp;&nbsp;
                        <button style="display: none;" type="button"
                            class="pull-right btn btn-danger btn-xs remove_row_gov_scheme"><i
                                class="fa fa-minus-circle"></i></button>
                    </div>
                </div>

                <?php } }else{  $gs2=0;?>
                    
                <div class="gov_scheme_rows" rowclass="<?=$gs2?>" style="margin-top: 1em;">

                    <div class="col-sm-3">
                        <label>Name of Government Scheme <span class="important-field">*</span></label>
                        <?= $this->Form->control("sd_federation_implementing_schemes.$gs2.gov_scheme_name",['type'=>'text','placeholder'=>'Name of Government Scheme','label'=>false,'required'=>true,'class'=>'mb-n gov_scheme_name','maxlength'=>'200','value'=>$sd_fed_imp_sch->gov_scheme_name])?>
                        <?php //$this->Form->control("society_implementing_schemes.$gs2.scheme_type",['type'=>'hidden','label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n','maxlength'=>'10','value'=>'1'])?>
                    </div>

                    <div class="col-sm-3">
                        <label>Type of Scheme (Central/State) <span class="important-field">*</span></label>
                        <?php //$this->Form->control("society_implementing_schemes.$gs2.gov_scheme_type",['type'=>'text','label'=>false,'required'=>true,'class'=>'mb-n','maxlength'=>'10'])?>
                        <?=$this->Form->control("sd_federation_implementing_schemes.$gs2.gov_scheme_type",['options'=>['1'=>'Central','0'=>'State'],'id'=>'gov_scheme_type','empty'=>'Select','label'=>false,'class'=>'select2 gov_scheme_type','required'=>true,'value'=>$sd_fed_imp_sch->gov_scheme_type])?>
                    </div>

                    <div class="col-sm-3">
                        <label>Total Amount Spent through UCB (Rs) <span class="important-field">*</span></label>
                        <?= $this->Form->control("sd_federation_implementing_schemes.$gs2.total_amount",['type'=>'text','placeholder'=>'Total Amount Spent through UCB (Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1  mb-n gov_total_amount','maxlength'=>'10','value'=>$sd_fed_imp_sch->total_amount])?>
                    </div>


                    <div class="col-sm-12">
                        <button type="button" class="pull-right btn btn-primary btn-xs add_row_gov_scheme"><i
                                class="fa fa-plus-circle"></i></button>
                        &nbsp;&nbsp;&nbsp;
                        <button style="display: none;" type="button"
                            class="pull-right btn btn-danger btn-xs remove_row_gov_scheme"><i
                                class="fa fa-minus-circle"></i></button>
                    </div>
                </div>


                <?php } ?>

            </div>


        </div>




    </div>

</div>
</div>
</div>
<script type="text/javascript">
// function add_row(){

// console.log('add_row')
// }
function ucb_loan(element) {
    // $('.gov_scheme_name').val('').trigger('change');
    // $('.gov_total_amount').val('').trigger('change');
    // $('.gov_scheme_type').val('').trigger('change');
    

    if (element.value == 1) {
       
        document.getElementById('gov_scheme_implemented').style.display = "block";
    } else {
        // $('.gov_scheme_box').append(

        // );
        document.getElementById('gov_scheme_implemented').style.display = "none";
    }
}




$(document).ready(function() {

    checkPlusMinusButtonRural();

    function checkPlusMinusButtonRural() {

        var tr_row_main = $('.gov_scheme_rows');
        var m = 1;
        // console.log('m'+m)
        console.log(tr_row_main)
        $('.add_row_gov_scheme').each(function() {
            if (m == 1) {
                console.log($(this).show());
                $(this).parent('div').find('button.remove_row_gov_scheme').hide();
            } else {
                $(this).hide();
                $(this).parent('div').find('button.remove_row_gov_scheme').show();
            }
            m++;
        });
    }

    var max_scheme = 1000;
    var count_div_scheme = parseFloat($(".gov_scheme_box").find(".gov_scheme_rows").length);

    $('.gov_scheme_box').on('click', '.add_row_gov_scheme', function(e) {
        e.preventDefault();
        if (count_div_scheme < max_scheme) {
            count_div_scheme++;
            var gs3 = parseFloat($(".gov_scheme_box .gov_scheme_rows:last").attr("rowclass")) + 1;
            console.log(gs3);
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'gov-scheme-add-row'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                data: {
                    gs3: gs3
                },
                success: function(response) {
                    $(".gov_scheme_box").append(response);
                    $('.select2').select2();
                    checkPlusMinusButtonRural();
                },
            });
        }
    });

    $(".gov_scheme_box").on('click', '.remove_row_gov_scheme', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        count_div_scheme--;
        checkPlusMinusButtonRural();
    });

    //Component add row section


});
</script>
<script>
$(document).ready(function() {

    $(".before_date").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
        yearRange: "-122:-0",
    });

    //=========================================


    $(".housing_land_data").bind("change paste keyup", function() {


        var first = '';
        var second = '';
        var third = '';
        var fourth = '';


        first = $('#sd-federation-land-land-owned').val();
        second = $('#sd-federation-land-land-leased').val();
        third = $('#sd-federation-land-land-allotted').val();

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

    $(".transport_member_data").bind("change paste keyup", function() {


        var first = '';
        var second = '';



        first = $('#sd-federation-ucb-borrowing-member').val();
        second = $('#sd-federation-ucb-non-borrowing-member').val();


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



        first = $('#sd-federation-ucb-paid-up-members').val();
        second = $('#sd-federation-ucb-paid-up-government-bodies').val();


        var arr = [first, second].filter(cV => cV != "");
        console.log(arr);

        sum = 0;
        count = arr.length;
        $.each(arr, function() {
            sum += parseFloat(this) || 0;
        });
        sum = sum.toFixed(2);
        tval = 0;


        $('#sd-federation-ucb-paid-up-total').val(sum);

    });




    //======================================== 
    $('body').on('change', '.ucb_has_building', function() {
        
        // console.log(document.getElementById('cooperative-registrations-ucb-building-type').selectedIndex)
        // document.getElementById('cooperative-registrations-ucb-building-type').selectedIndex = 0;
        // console.log($('#cooperative-registrations-ucb-building-type').val(''))
    //    console.log($('.building-type')[0])
       $('#sd-federation-ucb-building-type').val('').trigger('change');
        if ($(this).val() == 1) {
            $('.ucb_has_building_change').show();
        } else {
            $('.ucb_has_building_change').hide();
        }
    });

    $('body').on('change', '.ucb_has_land', function() {
        if ($(this).val() == 1) {
            $('.ucb_available_land').show();
        } else {
            $('.ucb_available_land').hide();
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


    $('body').on('change', '.type_edu', function() {
        if ($(this).val() == 1) {
            $('.10a1').show();
            $('.10a2').hide();
            $('.10a3').hide();
        } else if ($(this).val() == 2) {
            $('.10a2').show();
            $('.10a3').hide();
            $('.10a1').hide();
        } else if ($(this).val() == 3) {
            $('.10a3').show();
            $('.10a1').hide();
            $('.10a2').hide();

        } else {
            $('.10a3').hide();
            $('.10a1').hide();
            $('.10a2').hide();
        }
    });


    // $('body').on('change', '.ucb_has_other_loan', function() { 
    //     if ($(this).val() == 1) {
    //     ("#new_gallery").append('<input name"new_gallery" /><a href="#" id="create_new_gallery">Add</a>');
    //     }
    //     else{
    //         ("#new_gallery").remove(); 
    //     }
    // });








    $('body').on('change', '.ucb_has_loan', function() {
        if ($(this).val() == 1) {
            $('.ucb_available_loan').show();

        } else {
            $('.ucb_available_loan').hide();
        }
    });

    // Centreal government fields

    // $('body').on('change', '.ucb_has_other_loan', function() {


    //     console.log($(this).val())
    //     if ($(this).val() == 1) {
    //         $('.ucb_available_other_loan').show();

    //     } else {
    //         $('.ucb_available_other_loan').hide();
    //     }
    // });



    // $('body').on('change', '.ucb_has_other_loan2', function() {

    //     console.log($(this).val())
    //     if ($(this).val() == 1) {
    //         $('.ucb_available_other_loan2').show();
    //     } else {
    //         $('.ucb_available_other_loan2').hide();
    //     }
    // });

    // state government fields
    // $('body').on('change', '.ucb_has_other_loan3', function() {

    //     console.log($(this).val())
    //     if ($(this).val() == 2) {
    //         $('.ucb_available_other_loan3').show();

    //     } else {
    //         $('.ucb_available_other_loan3').hide();
    //     }
    // });


    // $('body').on('change', '.ucb_has_other_loan4', function() {
    //     if ($(this).val() == 2) {
    //         $('.ucb_available_other_loan4').show();
    //     } else {
    //         $('.ucb_available_other_loan4').hide();
    //     }
    // });


    $('body').on('change', '#sd-federation-ucb-facilities', function(e) {
        e.preventDefault();
        $('#sd-federation-ucb-facilities > option:selected').each(function() {
            if ($(this).val() == 1 && $(
                    '#sd-federation-ucb-facilities > option:selected').length >
                1) {
                //console.log('#sector-' + increment + '-village-code');
                $('#sd-federation-ucb-facilities').val(
                    '1'); // Select the option with a value of '1'
                $('#sd-federation-ucb-facilities').trigger(
                    'change'); // Notify any JS components that the value chan
                return false; // breaks

            }
        });

    });

});
</script>
<script>
function onlyNumbers(num) {
    if (/[^0-9]+/.test(num.value)) {
        num.value = num.value.replace(/[^0-9]*/g, "")
    }
}


function onlyNumbersChar(num) {
    if (/[^0-9a-zA-Z]+/.test(num.value)) {
        num.value = num.value.replace(/[^0-9a-zA-Z]*/g, "")
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