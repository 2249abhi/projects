<div class="pacs_change change"
                        style="display:<?=$CooperativeRegistration->sector_of_operation==1 ?'block':'none';?>;">
                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong id="primary_activity_details">PACS Details</strong></h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>10(a). Whether the co-operative society has an office building <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[has_building]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'p_has_building','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 p_has_building_change" style="display:none;">
                                        <label>10(b). Type of Office Building<span
                                                class="important-field">*</span></label>
                                        <?php $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government']; ?>
                                        <?=$this->Form->control("pacs[building_type]",['options'=>$buildingTypes,'empty'=>'Select','label'=>false,'class'=>'select2','required'=>true])?>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-sm-4">
                                        <label>10(c). Whether the co-operative society has land <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[is_socitey_has_land]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'is_socitey_has_land','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <!------land area------>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-6 available_land" style="display:none;">
                                        <label>10(d). Land Available with the Cooperative<span
                                                class="important-field">*</span></label>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                            <th scope="col" width="50%"><?= __('Type of possession') ?>
                                                            </th>
                                                            <th scope="col" width="40%"><?= __('Area (in Acre)') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Owned Land</td>
                                                            <td><?= $this->Form->control("area[land_owned]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_data mb-n'])?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Leased Land</td>
                                                            <td><?= $this->Form->control("area[land_leased]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_data mb-n'])?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3.</td>
                                                            <td>Land allotted by the Government</td>
                                                            <td><?= $this->Form->control("area[land_allotted]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_data mb-n'])?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right: none !important;"></td>
                                                            <td style="float: none; border: none !important;">
                                                                <b>Total</b>
                                                            </td>
                                                            <td><?= $this->Form->control("area[land_total]",['type'=>'text','readonly'=>true,'label'=>false,'required'=>true,'class'=>'numberadndesimal1 land_total mb-n'])?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!------land area------>
                                    </div>

                                    <!-- <div class="col-sm-4 available_land" style="display:none;">
                                        <label>10(d). Land Available with the Cooperative<span
                                                class="important-field">*</span></label>
                                        <?php //$availableLand = ['1'=>'Owned Land','2'=>'Leased Land','3'=>'Land allotted by the Government']; ?>
                                        <?php //$this->Form->control("pacs[available_land]",['options'=>$availableLand,'empty'=>'Select','label'=>false,'class'=>'select2 land_avilabele_area','required'=>true])?>
                                    </div>

                                    <div class="col-sm-4 text_land_area" style="display:none;">
                                        <label>10(e). Area(in Acre)<span
                                        class="important-field">*</span></label>
                                        <?php //$this->Form->control("pacs[land_avilabele_area]",['type'=>'text','label'=>false,'required'=>true,'class'=>'numberadndesimal1'])?>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </div>
                                </div>
                                <div class="row sv-handling">
                                    <!-- <div class="col-sm-4">
                                        <label>11(a). Credit Facility <span class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[credit_facility]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'p_credit_facility','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 p_credit_facility_change" style="display:none;">
                                        <label>11(b). Total Credit Provided in the Financial Year (In Rs.)<span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[total_credit]",['placeholder'=>'Total Credit','label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10'])?>
                                    </div> -->

                                    <div class="col-sm-4">
                                        <label>11(a). Total Outstanding Loan extended to Member(In Rs)<span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_total_outstanding_loan]",['placeholder'=>'Total Outstanding Loan extended to Member(in Rs)','label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'50'])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(b). Revenue (Other than interest) from Non-Credit Activities (In
                                            Rs.)<span class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_revenue_non_credit]",['placeholder'=>'Revenue (Other than interest) from Non-Credit Activities (In Rs.)','label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'50'])?>
                                    </div>

                                    <div class="col-sm-4 ex-div">
                                        <label>11(c). Fertilizer Distribution <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[fertilizer_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_fertilizer_distribution','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <span class="loan_warning"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(d). Pesticide Distribution <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pesticide_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_pesticide_distribution','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(e). Seed Distribution <span class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[seed_distribution]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_seed_distribution','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(f). Fair Price Shop License <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[fair_price]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_fair_price','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(g). Procurement of Foodgrains <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[is_foodgrains]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_foodgrains','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(h). Hiring of Agricultural Implements <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[agricultural_implements]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_agricultural_implements','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(i). Dry Storage Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[dry_storage]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_dry_storage','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 p_dry_storage_change" style="display:none;">
                                        <label>11(j). Capacity of Dry Storage Infrastructure (In MT) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[dry_storage_capicity]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10'])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(k). Cold Storage Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[cold_storage]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_cold_storage','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 p_cold_storage_change" style="display:none;">
                                        <label>11(l). Capacity of Cold Storage Infrastructure (In MT) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[cold_storage_capicity]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10'])?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-4">
                                        <label>11(m). Milk Collection Unit Facility <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[milk_unit]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate p_milk_unit','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 p_milk_unit_change" style="display:none;">
                                        <label>11(n). Annual Milk Collection by Cooperative Society (In Liters) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[milk_capicity_unit]",['label'=>false,'required'=>true,'class'=>'numbers','maxlength'=>'10'])?>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-4 ex-div">
                                        <label>11(o). Wheather Cooperative Society is involved in fish catch <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_involved_fish_catch]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate pack_involved_fish_catch','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>

                                    <div class="col-sm-4 pack_annual_fish_catch" style="display:none;">
                                        <label>11(p). Annual Fish Catch by Cooperative Society (In kg) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[pack_annual_fish_catch]",['label'=>false,'required'=>true,'class'=>'numberadndesimal1','maxlength'=>'10'])?>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-4 ex-div">
                                        <label>11(q). Food Processing Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[food_processing]",['options'=>['1'=>'Yes','0'=>'No'],'default'=>0,'class'=>'revenue_validate food_processing','type'=>'radio','label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4 food_processing_change" style="display:none;">
                                        <label>11(r). Type of Food Processing Facilities <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[food_processing_type]",['label'=>false,'required'=>true])?>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>11(s). Other Facilities Provided (Please Specify) <span
                                                class="important-field">*</span></label>
                                        <?=$this->Form->control("pacs[other_facility]",['label'=>false,'required'=>true])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>