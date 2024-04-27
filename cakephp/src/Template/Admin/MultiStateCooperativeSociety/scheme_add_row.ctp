<div class="scheme_rows" rowclass="<?=$shr2?>">   
    <div class="col-sm-3">
        <label>17(b)Name of Central Govt Scheme<span class="important-field">*</span></label>
        <?=$this->Form->control("multi_state_cooperative.multi_state_cooperative_schemes.$shr2.scheme_name",['type'=>'textbox','placeholder'=>'Name of Central Govt Scheme','label'=>false,'class'=>'scheme_name','required'=>true, 'maxlength'=>'200'])?>
    </div>
    <div class="col-sm-3">
        <label>17(c). Total Amout Spent through MSCS<span class="important-field">*</span></label>
        <?=$this->Form->control("multi_state_cooperative.multi_state_cooperative_schemes.$shr2.amount_spent",['type'=>'text','class'=>'numberadndesimal','placeholder'=>'Total Amout Spent through MSCS','class'=>'scheme_amount','label'=>false,'required'=>true,'maxlength'=>'6'])?>
    </div>
    <div class="col-sm-12">
                        
    <button style="margin-top: -15px;" type="button" class="pull-right btn btn-primary btn-xs add_row_scheme">
                                <i class="fa fa-plus-circle"></i>
    </button>
    <button style="display: none;margin-bottom: 10px" type="button"
                                class="pull-right btn btn-danger btn-xs remove_row_scheme">
                                <i class="fa fa-minus-circle"></i>
    </button>
</div>
</div>
