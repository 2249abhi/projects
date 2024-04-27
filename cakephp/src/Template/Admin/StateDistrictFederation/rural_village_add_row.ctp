<div class="clearfix"></div>
<div class="rural_village_rows add_row_sec extra_rural_rows" rowclass="<?=$hr2?>">
    <div class="col-sm-3 rural_location_section">
        <label>5(c)(i). District <span class="important-field">*</span></label>
        <?=$this->Form->control("sector.$hr2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_district_code select2','type'=>'select','required'=>true])?>
    </div>

    <div class="col-sm-3 rural_location_section">
        <label>5(c)(ii). Block <span class="important-field">*</span></label>
        <?=$this->Form->control("sector.$hr2.block_code",['options'=>$blocks,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_block_code select2','type'=>'select','required'=>true])?>
    </div>

    
    <div class="col-sm-12 rural_location_section">

        <button type="button" class="pull-right btn btn-primary btn-xs add_row_rural_village"><i
                class="fa fa-plus-circle"></i></button>
        &nbsp;&nbsp;&nbsp;
        <button style="display: none;" type="button"
            class="pull-right btn btn-danger btn-xs remove_row_rural_village"><i
                class="fa fa-minus-circle"></i></button>

    </div>
</div>

<style>
.rural_village_rows.extra_rural_rows {
    background: none;
    border: none;
    margin: 0;
}

.rural_village_rows.extra_rural_rows button {
    width: 20px !important;
    height: 20px !important;
    border-radius: 0 !important;
}
</style>