<div class="urban_ward_rows" rowclass="<?=$uw2?>">
                        <div class="col-sm-3 urban_location_section1">
                                    <label>5(c)(i). District <span class="important-field">*</span></label>
                                    <?=$this->Form->control("sector_urban.$uw2.district_code",['options'=>$districts,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'sector_urban_district_code select2','type'=>'select','required'=>true])?>
                                </div>
                        <div class="col-sm-3 urban_location_section">
                            <label>5(c)(i). Category of Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control("sector_urban.$uw2.local_body_type_code",['options'=>$localbody_types,'empty'=>'Select','increment'=>$uw2,'label'=>false,'class'=>'select2 sector_urban_local_body_type_code','required'=>true])?>
                        </div>
                        <div class="col-sm-3 urban_location_section">
                            <label>5(c)(ii). Urban Local Body <span class="important-field">*</span></label>
                            <?=$this->Form->control("sector_urban.$uw2.local_body_code",['options'=>'','empty'=>'Select','label'=>false,'increment'=>$uw2,'class'=>'select2 sector_urban_local_body_code','required'=>true])?>
                        </div>

                        <!-- <div class="col-sm-3 urban_location_section">
                            <label>5(c)(iii). Locality or Ward <span class="important-field"></span></label>
                            <?=$this->Form->control("sector_urban.$uw2.locality_ward_code",['options'=>'','multiple'=>true,'increment'=>$uw2,'label'=>false,'class'=>'select2 sector_locality_ward_code','required'=>true])?>
                        </div> -->
                        <div class="col-sm-12 urban_location_section">                            
                            <button type="button" class="pull-right btn btn-primary btn-xs add_row_urban_ward"><i class="fa fa-plus-circle"></i></button>
                            &nbsp;&nbsp;&nbsp;
                            <button style="display: none;" type="button" class="pull-right btn btn-danger btn-xs remove_row_urban_ward"><i class="fa fa-minus-circle"></i></button>

                        </div>
</div>
