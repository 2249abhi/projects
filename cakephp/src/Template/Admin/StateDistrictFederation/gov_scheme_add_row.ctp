<div class="gov_scheme_rows" rowclass="<?=$gs2?>">

    <div class="col-sm-3">
        <label>Name of Government Scheme <span class="important-field">*</span></label>
        <?= $this->Form->control("sd_federation_implementing_schemes.$gs2.gov_scheme_name",['type'=>'text','placeholder'=>'Name of Government Scheme','label'=>false,'required'=>true,'class'=>'transport_member_data mb-n gov_scheme_name','maxlength'=>'200'])?>
        <?php //$this->Form->control("society_implementing_schemes.$gs2.scheme_type",['type'=>'hidden','label'=>false,'required'=>true,'class'=>'numberadndesimal1 mb-n','maxlength'=>'10','value'=>'1'])?>
    </div>

    <div class="col-sm-3">
        <label>Type of Scheme (Central/State) <span class="important-field">*</span></label>
        <?php //$this->Form->control("society_implementing_schemes.$gs2.gov_scheme_type",['type'=>'text','label'=>false,'required'=>true,'class'=>'transport_member_data mb-n','maxlength'=>'10'])?>
        <?=$this->Form->control("sd_federation_implementing_schemes.$gs2.gov_scheme_type",['options'=>['1'=>'Central','0'=>'State'],'id'=>'gov_scheme_type','empty'=>'Select','label'=>false,'class'=>'select2 gov_scheme_type','required'=>true])?>
    </div>

    <div class="col-sm-3">
        <label>Total Amount Spent through UCB (Rs) <span class="important-field">*</span></label>
        <?= $this->Form->control("sd_federation_implementing_schemes.$gs2.total_amount",['type'=>'text','placeholder'=>'Total Amount Spent through UCB (Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1 transport_member_data mb-n gov_total_amount','maxlength'=>'10'])?>
    </div>

    <div class="col-sm-12">

        <button type="button" class="pull-right btn btn-primary btn-xs add_row_gov_scheme"><i
                class="fa fa-plus-circle"></i></button>
        &nbsp;&nbsp;&nbsp;
        <button style="display: none;" type="button" class="pull-right btn btn-danger btn-xs remove_row_gov_scheme"><i
                class="fa fa-minus-circle"></i></button>

    </div>

</div>
<script>
       $('.select2').select2();
// function isNumber(evt) { 
//     evt = (evt) ? evt : window.event;
//     var charCode = (evt.which) ? evt.which : evt.keyCode;
//     if (charCode > 31 && (charCode < 48 || charCode > 57)) {
//         return false;
//     }
//     return true;
// }
</script>