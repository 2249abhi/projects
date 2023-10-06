<div class="rural_village_rows" rowclass="<?=$hr2?>">
                            
                        <div class="col-sm-3 rural_location_section">
                            <label>5(c)(i). District <span class="important-field">*</span></label>
                            <?=$this->Form->control("sector.$hr2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_district_code select2','type'=>'select','required'=>true])?>
                        </div>

                        <div class="col-sm-3 rural_location_section">
                            <label>5(c)(ii). Block <span class="important-field">*</span></label>
                            <?=$this->Form->control("sector.$hr2.block_code",['options'=>$blocks,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_block_code select2','type'=>'select','required'=>true])?>
                        </div>

                        <div class="col-sm-3 rural_location_section">
                            <label>5(c)(iii). Gram Panchayat <span class="important-field">*</span></label>
                            <?=$this->Form->control("sector.$hr2.panchayat_code",['options'=>$gps,'empty'=>'Select','increment'=>$hr2,'label'=>false,'class'=>'sector_panchayat_code select2','type'=>'select','required'=>true])?>
                        </div>

                        <div class="col-sm-3 rural_location_section <?php echo "sector_".$hr2."_village_code_div" ?>">
                            <label>5(c)(iv). Village <span class="important-field">*</span></label>
                            <?=$this->Form->control("sector.$hr2.village_code",['options'=>$villages,'multiple'=>true,'increment'=>$hr2,'label'=>false,'class'=>'sector_village_code select2','type'=>'select','required'=>true])?>
                        </div>
                        <div class="col-sm-12 rural_location_section">
                            
                            <button type="button" class="pull-right btn btn-primary btn-xs add_row_rural_village"><i class="fa fa-plus-circle"></i></button>
                            &nbsp;&nbsp;&nbsp;
                            <button style="display: none;" type="button" class="pull-right btn btn-danger btn-xs remove_row_rural_village"><i class="fa fa-minus-circle"></i></button>

                        </div>
</div>
