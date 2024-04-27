<div class="member_brand_rows" rowclass="<?=$mhr2?>">   
    <div class="col-sm-3">
		<label>14(a) Item Name<span class="important-field">*</span></label>
		<?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.item_name",['type'=>'textbox','placeholder'=>'Item Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
	</div>
	<div class="col-sm-3">
		<label>14(a) Registered Brand Name<span class="important-field">*</span></label>
		<?=$this->Form->control("multi_state_cooperative_member_brands.$mhr2.brand_name",['type'=>'textbox','placeholder'=>'Registered Brand Name','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
	</div>

  
    <div class="col-sm-12">
	                    
    <button style="margin-top: -15px;" type="button" class="pull-right btn btn-primary btn-xs add_row_brand">
                                <i class="fa fa-plus-circle"></i>
    </button>
    <button style="display: none;margin-bottom: 10px" type="button"
                                class="pull-right btn btn-danger btn-xs remove_row_brand">
                                <i class="fa fa-minus-circle"></i>
    </button>
</div>
</div>
<script>
function isNumber(evt) { 
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>