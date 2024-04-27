<div class="export_rows" rowclass="<?=$shr2?>">   
    <div class="col-sm-3">
        <label>20(b). Item of Exports<span class="important-field">*</span></label>
        <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.item_name",['type'=>'textbox','placeholder'=>'Item of Exports','label'=>false,'required'=>true, 'maxlength'=>'100'])?>
    </div>
    <div class="col-sm-3">
    <label>20(c). Quantity(In Unit)<span
            class="important-field">*</span></label>
    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.quantity",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Quantity(In Unit)','label'=>false,'required'=>true,'maxlength'=>'6'])?>
</div>
<div class="col-sm-3">
    <label>20(d). Value of Exported Item(In Rs)<span
            class="important-field">*</span></label>
    <?=$this->Form->control("multi_state_cooperative_export_items.$shr2.value_of_item",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Value of Exported Item(In Rs)','label'=>false,'required'=>true,'maxlength'=>'6'])?>
</div>
    <div class="col-sm-12">
                        
    <button style="margin-top: -15px;" type="button" class="pull-right btn btn-primary btn-xs add_row_export">
                                <i class="fa fa-plus-circle"></i>
    </button>
    <button style="display: none;margin-bottom: 10px" type="button"
                                class="pull-right btn btn-danger btn-xs remove_row_export">
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