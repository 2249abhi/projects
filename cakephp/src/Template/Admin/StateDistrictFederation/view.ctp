<?php
/**
 * @author \Abhay Singh
 * @version \1.1
 * @since \July 07, 2020, 10:44 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Cooperative Details of ( ' . $CooperativeRegistration->cooperative_federation_name. ' ) '));
$this->Breadcrumbs->add(__('Cooperative Registrations'),['action'=>'index']);
$this->Breadcrumbs->add(__('Cooperative Details'));
$this->assign('top_head',__('hide'));

$link = '';
if($this->request->session()->read('Auth.User.role_id') == 7)
{
    //Data Entry Operater
    if($CooperativeRegistration->is_draft == 0)
    {
        $link = '/data-entry-pending';
    } else {
        $link = '/draft';
    }
    
} elseif($this->request->session()->read('Auth.User.role_id') == 8) {
    //district nodal
    if($CooperativeRegistration->is_draft == 0)
    {
     $link = '/list';
    } else if($CooperativeRegistration->is_draft == 1)
    {
     $link = '/draft';
    } else {
     $link = '/rejected';
    }
 
} elseif($this->request->session()->read('Auth.User.role_id') == 2 || $this->request->session()->read('Auth.User.role_id') == 14) {
    //admin
    if($CooperativeRegistration->is_draft == 0)
    {
     $link = '/list';
    } else if($CooperativeRegistration->is_draft == 1)
    {
     $link = '/draft';
    } else {
     $link = '/admin-rejected';
    }
} 
?>


<?php
    

    $facility = ['1'=>'Modern Farming Equipments','2'=>'Combined Harvester','3'=>'Improved Seed','4'=>'Compost','5'=>'Fertilizers','6'=>'Crop Rotation','7'=> 'Best Farming Practices'];

    $facility2 = ['1'=>'Rain water','2'=>'Ground water','3'=>'canal water'];

    $bee_types = ['1'=>'Queen Bee','2'=>'Drones Bee','3'=>'Workers Bees'];
    $bee_products = ['40'=>'Honey','41'=>'Bee Pollen','42'=>'Others'];
    $bee_facility = ['1'=>'Do not have basic amenities','54'=>'Business Correspondence'];

    $credit_thrift_society = ['1'=>'Do not have basic amenities','12'=>'Consumer/Thrift Store','13'=>'Fair Price Shop','14'=>'Insurance Services','15'=>'LPG distributorship','29'=>'Banking Counter'];
    
    $misc_credit = ['1'=>'Do not have basic amenities','61'=>'Credit Facility to non-members','62'=>'Business Correspondence','63'=>'Consumer/Thrift Store','64'=>'Fair Price Shop','65'=>'LPG distributorship'];
    
    $lab_op = ['1'=>'Do not have basic amenities','24'=>'Bargaining for wages/remuneration','25'=>'Timely payments of wages/remuneration','26'=>'Health centre/Medical check-up camps','27'=>'Business Correspondence','28'=>'Consumer Store/Outlet'];

    $housing_services = ['1'=>'Do not have basic amenities','2'=>'Consumer Store/Fair Price Shop','3'=>'Sports complex/playground/park','4'=>'Community Hall','5'=>'Health centre/Medical check-up camps','6'=>'Transport service','7'=>'Creches/common room','8'=>'School','9'=>'Shopping centre','10'=>'Convention Centre','11'=>'Auditorium','30'=>'Electricity','31'=>'Water'];
    
    $consumer_co = ['1'=>'Do not have basic amenities','16'=>'Home Delivery Services','17'=>'Health Care Services','18'=>'Welfare Services','19'=>'Fair Price Shop','20'=>'LPG distributorship','21'=>'Business Correspondent (Insurance, Financial & Banking, etc.) Services','22'=>'Credit Facility','23'=>'Own Departmental Store/Supermarket'];
    
    $handicraft_rm = ['1'=>'Textiles or Leather material','2'=>'Wood,metal,clay,bone,horn,glass, or stone','3'=>'Paper or canvas','4'=>'Plants (bamboo etc.) other than wood','5'=>'other','6'=>'Jute Fibers','7'=>'Coir Fiber','8'=>'Coconut Fiber','9'=>'Other Fibers'];

    $handicraft_tp = ['1'=>'Textiles & Related products','2'=>'Pottery & Related products'];
    
    $handicraft_fp = ['1'=>'Do not have basic amenities','38'=>'Business Correspondence'];

    $processing_tp = ['1'=>'Agriculture & Allied','2'=>'Non-Agriculture'];

    $wocoop_f = ['1'=>'Do not have basic amenities','51'=>'Health centre/Medical check-up camps','52'=>'Business Correspondence','53'=>'Consumer Store/Outlet'];
    
  $farming_mech_all = array_intersect_key($facility,array_flip(explode(',',$sd_fed_agri->farming_mech)));

  $irrigation_means_all = array_intersect_key($facility2,array_flip(explode(',',$sd_fed_agri->irrigation_means)));

  $housing_services = array_intersect_key($housing_services,array_flip(explode(',',$sd_federation_housing->facilities)));

  $bee_types = array_intersect_key($bee_types,array_flip(explode(',',$sd_fed_bee_farm->type_bee)));
  $bee_products = array_intersect_key($bee_products,array_flip(explode(',',$sd_fed_bee_farm->type_product)));
  $bee_facility = array_intersect_key($bee_facility,array_flip(explode(',',$sd_fed_bee_farm->facilities)));

  $credit_thrift_society = array_intersect_key($credit_thrift_society,array_flip(explode(',',$sd_fed_credit->facilities)));

  $misc_credit = array_intersect_key($misc_credit,array_flip(explode(',',$sd_federation_cmiscellaneous->facilities)));
  
  $consumer_co = array_intersect_key($consumer_co,array_flip(explode(',',$sd_federation_consumer->facilities)));
  
  $lab_op = array_intersect_key($lab_op,array_flip(explode(',',$sd_federation_labour->facilities)));
  
  $handicraft_rm = array_intersect_key($handicraft_rm,array_flip(explode(',',$sd_federation_handicraft->type_raw)));
  
  $handicraft_tp = array_intersect_key($handicraft_tp,array_flip(explode(',',$sd_federation_handicraft->type_produce)));

  $handicraft_fp = array_intersect_key($handicraft_fp,array_flip(explode(',',$sd_federation_handicraft->facilities)));

  $wocoop_f = array_intersect_key($wocoop_f,array_flip(explode(',',$sd_federation_wocoop->facilities)));


?>

<!-- Main content -->
<section class="content role view view_sec">
    <div class="row">
        <!-- heading -->
        <div class="col-md-12 top-heading_div">
            <h3>Data Collection form for Primary Cooperatives</h3>
        </div>
        <!-- heading ends -->
        <div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-info"></i>
                    <h3 class="box-title">
                        <?= __('Cooperative Details of ( ' . $CooperativeRegistration->cooperative_federation_name . ' ) ') ?>
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => $link],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Cooperative Registrations'),'escape' => false]
                  );
                  if($this->request->session()->read('Auth.User.role_id') == 8){
                  ?>
                        <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit',$CooperativeRegistration->id ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                        <!-- <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $CooperativeRegistration->id],
                    ['confirm' => __('Are you sure you want to delete this Cooperative Registration?', $CooperativeRegistration->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )
                            
                ?> -->
                        <?php } ?>
                    </div>
                </div>

                <?php 
                       ?>
                <!-- /.box-header -->
                <div class="box-body" style="max-height: 100% !important;">
                    <div class="boxk">
                        <div class="view-table">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('Form Status') ?></th>
                                    <td><?php $status = ['1'=>'In Draft','0'=>'Submitted'];
										echo $status[$CooperativeRegistration->is_draft];
										?></td>
                                    <th scope="row"><?= __('Approval Status') ?></th>
                                    <td><?php $a_status = ['0'=>'Pending','1'=>'Accepted','2'=>'Rejected'];
										echo $a_status[$CooperativeRegistration->is_approved];
										?></td>
                                    <?php if($CooperativeRegistration->is_approved == 1 || $CooperativeRegistration->is_approved == 2){ ?>
                                    <th scope="row"><?= __('Remarks') ?></th>
                                    <td><?= $CooperativeRegistration->remark ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <th scope="row" width="20%"><?= __('1(a). Cooperative Society Name') ?></th>
                                    <td width="30%">
                                        <?= $CooperativeRegistration->cooperative_federation_name; ?>
                                    </td>

                                    <th scope="row"><?= __('1(b). Sector of Operation') ?></th>
                                    <td>
                                        <?= h($operationSector[$CooperativeRegistration->sector_of_operation_type] ?? '') ?>
                                    </td>

                                    <th scope="row"><?= __('1(c). Primary Activity') ?></th>
                                    <td>
                                        <?= h($creditPrimaryActivities[$CooperativeRegistration->sector_of_operation] ?? '') ?>
                                    </td>

                                    <!-- <th scope="row" width="20%"><?= __('1(c). Cooperative ID') ?></th>
                                    <td width="30%"><?= h($CooperativeRegistration->cooperative_id) ?></td> -->


                                </tr>
                                
                                <tr>
                                    <th scope="row" width="20%"><?= __('1(d).Registration Authority') ?></th>
                                    <td width="20%">
                                        <?= h($regi_authorities[$CooperativeRegistration->registration_authority_id]) ?>
                                    </td>
                                
                                    <?php if($CooperativeRegistration->sector_of_operation==35)  { ?>
                                    <th scope="row"><?= __('1(e). Type of Miscellaneous Cooperatives') ?></th>
                                    <td><?= h($miscellaneoustypes[$CooperativeRegistration->cooperative_registrations_cmiscellaneous->type_miscellaneous]) ?></td>
                                    <?php }else{ ?>  
                                    <?php if($CooperativeRegistration->sector_of_operation==29)  { ?>
                                    <th scope="row"><?= __('1(e). Type of Miscellaneous Cooperatives') ?></th>
                                    <td><?= h($miscellaneoustypes[$CooperativeRegistration->cooperative_registrations_miscellaneous->type_miscellaneous]) ?></td>
                                    <th scope="row"><?= __('1(e). Registration Number') ?></th>
                                    <?php }else{ ?>
                                     
                                    <th scope="row"><?= __('1(e). Registration Number') ?></th>
                                    <?php } ?>
                                    <td><?= h($CooperativeRegistration->registration_number) ?></td>
                                    <?php } ?>

                                    <th scope="row"><?= __('1(e). Date of Registration') ?></th>
                                    <td><?= date("Y",strtotime($CooperativeRegistration->date_registration))=="1970"?"":date("d/m/Y",strtotime($CooperativeRegistration->date_registration))?>
                                    </td>

                                </tr>

                                <tr>
                                    <!-- <th scope="row"><?= __('1(d). Reference Year') ?></th>
                                    <td><?= h($CooperativeRegistration->reference_year) ?></td> -->

                                    <th scope="row"><?= __('1(g). Present Functional Status') ?>
                                    <td><?= h($presentFunctionalStatus[$CooperativeRegistration->functional_status]) ?>
                                    </td>
                                    
                                    <th scope="row"><?= __('1(h). Level of Federation') ?> 
                                    <?php $form_filling_for = ['2'=>'State Federation', '3'=>'District Federation', '4'=>'Block/Taluka/Mandal Federation', '5'=>'Regional Federation'] ?>
                                    <td><?= h($form_filling_for[$CooperativeRegistration->federation_type]) ?> 
                                    
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Location Details of Registration Office</strong></h4>
                        </div>
                        <div class="view-table">

                            <table class="table table-striped table-bordered">

                                <tr>
                                    <th scope="row"><?= __('2(a). Location of Registered Office') ?></th>
                                    <td><?= h($locationOfHeadquarter[$CooperativeRegistration->location_of_head_quarter] ?? '') ?>
                                    </td>

                                    <th scope="row"><?= __('2(b).  State or UT') ?></th>
                                    <td><?= h($states[$CooperativeRegistration->state_code] ?? '') ?></td>
                                    
                                    <th scope="row"><?= __('2(c). District') ?></th>
                                    <td><?= h($districtName ?? '') ?></td>
                                    
                                </tr>
                                <?php if($CooperativeRegistration->location_of_head_quarter == 2){ ?>
                                <tr>


                                    <th scope="row"><?= __('2(d). Block') ?></th>
                                    <td><?= h($blockName) ?></td>

                                    <th scope="row"><?= __('2(e). Gram Panchayat') ?></th>
                                    <td><?= h($panchayatName ?? '') ?></td>
                                    <th scope="row"><?= __('2(f). Village ') ?></th>
                                    <td><?= h($villageName ?? '') ?></td>
                                </tr>

                                <?php } ?>
                                <?php if($CooperativeRegistration->location_of_head_quarter == 1){ ?>
                                <tr>
                                    <th scope="row"><?= __('2(d). Category of Urban Local Body') ?></th>
                                    <td><?=@$localbody_types[$CooperativeRegistration->urban_local_body_type_code]?>
                                    </td>

                                    <th scope="row"><?= __('2(e). Urban Local Body') ?></th>
                                    <td><?=@$localbodies[$CooperativeRegistration->urban_local_body_code]?></td>

                                    <th scope="row"><?= __('2(f). Locality or Ward') ?></th>
                                    <td><?=@$localbodieswards[$CooperativeRegistration->locality_ward_code]?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <?php if($CooperativeRegistration->location_of_head_quarter == 1){ ?>
                                    <th scope="row"><?= __('2(g). Pin Code') ?></th>
                                    <td><?= h($CooperativeRegistration->pincode) ?></td>
                                    <th scope="row"><?= __('2(h). Full Address') ?></th>
                                    <td><?= h($CooperativeRegistration->full_address) ?></td>
                                    <?php } else{ ?>
                                    <th scope="row"><?= __('2(g). Pin Code') ?></th>
                                    <td><?= h($CooperativeRegistration->pincode) ?></td>
                                    <th scope="row"><?= __('2(h). Full Address') ?></th> 
                                    <td><?= wordwrap($CooperativeRegistration->full_address,100,",") ?></td>
                                    <?php } ?>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>

                                </tr>


                            </table>
                        </div>


                    </div>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Contact Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('3(a) Name') ?></th>
                                    <td><?= h($CooperativeRegistration->contact_person ?? '') ?>
                                    </td>
                                    <th scope="row"><?= __('3(b) Designation') ?></th>
                                    <td><?= h($designations[$CooperativeRegistration->designation] ?? '') ?>
                                    </td>

                                    <?php if($CooperativeRegistration->designation==6)
                            {
                            ?>
                                    <th scope="row"><?= __('3(b)(i) Designation Other') ?></th>
                                    <td><?= h($CooperativeRegistration->designation_other ?? '') ?>
                                    </td>
                                    <?php } ?>

                                    <th scope="row">
                                        <?= __('3(c). Mobile Number') ?></th>
                                    <td><?= h($CooperativeRegistration->mobile) ?></td>
                                </tr>

                                <tr>
                                    <th scope="row"><?= __('3(d). Landline:') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->landline) ?></td>
                                    <th scope="row"><?= __('3(e). Email ID:') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->email) ?></td>
                                    <th scope="row"></th>
                                    <td></td>
                                </tr>


                            </table>
                        </div>
                    </div>

                    <?php //print_r($gps); ?>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Sector Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <table class="table table-striped table-bordered">
                                <tr>
                                <th scope="row"> <?= __('4(a). Operation Area Location') ?></th>
                                    <?php $o_a_location = ['1'=>'Urban','2'=>'Rural','3'=>'Both']; ?>
                                    <td><?= h($o_a_location[$CooperativeRegistration->operation_area_location] ?? '') ?>
                                    </td>

                                </tr>

                                <!-- <?php if($CooperativeRegistration->operation_area_location == 1){ ?>
                                <tr>


                                    <th scope="row"><?= __('4(c)(i). District') ?></th>
                                    <td><?= h($CooperativeRegistration->district_code) ?></td>

                                    <th scope="row"><?= __('2(e). Gram Panchayat') ?></th>
                                    <td><?= h($panchayatName ?? '') ?></td>
                                    <th scope="row"><?= __('2(f). Village ') ?></th>
                                    <td><?= h($villageName ?? '') ?></td> 
                                </tr>

                                <?php } ?>

                                <?php if($CooperativeRegistration->operation_area_location == 2){ ?>
                                <tr>

                                    <th scope="row"><?= __('4(c)(i). District') ?></th>
                                    <td><?= @$districts[$CooperativeRegistration->district_code] ?></td>

                                    <th scope="row"><?= __('2(e). Gram Panchayat') ?></th>
                                    <td><?= h($panchayatName ?? '') ?></td>
                                    <th scope="row"><?= __('2(f). Village ') ?></th>
                                    <td><?= h($villageName ?? '') ?></td>
                                </tr>

                                <?php } ?>

                                <?php if($CooperativeRegistration->operation_area_location == 3){ ?>
                                <tr>

                                    <th scope="row"><?= __('4(c)(i). District') ?></th>
                                    <td><?= h($CooperativeRegistration->district_code) ?></td>

                                    <th scope="row"><?= __('2(e). Gram Panchayat') ?></th>
                                    <td><?= h($panchayatName ?? '') ?></td>
                                    <th scope="row"><?= __('2(f). Village ') ?></th>
                                    <td><?= h($villageName ?? '') ?></td>
                                </tr>

                                <?php } ?> -->

                                <?php if($CooperativeRegistration->is_affiliated_union_federation == 0){ ?>
                                <?php //$affiliated_union_fed = ['1'=>'Urban','2'=>'Rural','3'=>'Both']; ?>
                                <tr>
                                    <th scope="row">
                                        <?= __('4(f). Whether the Federation is affiliated to Union/Federation?') ?></th>
                                    <td><?= h($affiliated_union_fed[$CooperativeRegistration->is_affiliated_union_federation] ?? '') ?></td>
                                
                                <?php } else if($CooperativeRegistration->is_affiliated_union_federation == 1) {?>
                                <?php //$affiliated_union_fed = ['1'=>'Urban','2'=>'Rural','3'=>'Both']; ?>

                                    <th scope="row">
                                        <?= __('4(f). Whether the Federation is affiliated to Union/Federation?') ?></th>
                                    <td><?= h($affiliated_union_fed[$CooperativeRegistration->is_affiliated_union_federation] ?? '') ?></td>

                                    <th scope="row">
                                    <?= __('4(g). Level of Affiliated Union/Federation') ?></th>
                                    <td><?= h($sector_details_affiliation_level[$StateDistrictFederation->affiliated_union_federation_level] ?? '') ?></td>
                                
                                    <th scope="row">
                                    <?= __('4(h). Name of Affiliated Union/Federation') ?></th>
                                    <td><?= h($SecondaryActivities[$StateDistrictFederation->affiliated_union_federation_name] ?? '') ?></td>
                                                                
                                </tr>
                                <?php } ?>

                                <tr>
                                    <th scope="row"> <?= __('5. Total Number of Members of the Cooperative Federation') ?></th>
                                    <td><?= h($CooperativeRegistration->members_of_federation) ?>
                                    </td>
                                    

                                </tr>

                                <!-- <tr>
                                    <th scope="row"> <?= __('5(a). Tier of the Cooperative Society') ?></th>
                                    <td><?= h($CooperativeSocietyTypes[$CooperativeRegistration->cooperative_society_type_id] ?? '') ?>
                                    </td>
                                    

                                </tr> -->

                                <?php
                                
                            
                            if(!empty($area_operation_level_urban_row))
                            {
                                ?>
                                <tr>
                                    <th><b style="text-decoration: underline;">Urban</b></th>
                                    <td></td>
                                    <th scope="row"></th>
                                    <td></td>
                                    <th scope="row"></th>
                                    <td></td>

                                </tr>
                                <tr>
                                    <th scope="row"><?= __('5(b). Area of operation Urban ') ?><i
                                            class="fa fa-info-circle"
                                            title="For multiple selection in area operation"></i></th>
                                    <td><?= h($areaOfOperations[$area_of_operation_id_urban]) ?></td>
                                    <th scope="row"></th>
                                    <td></td>
                                    <th scope="row"></th>
                                    <td></td>
                                </tr>
                                <?php 
                                
                                foreach($area_operation_level_urban_row as $urban_row)
                                { ?>
                                <tr>
                                    <th scope="row"><?= __('5(c)(i). District') ?></th>
                                    <td><?= h($districtarry[$urban_row['district_code']] ?? '') ?></td>

                                    <th scope="row"><?= __('5(c)(ii). Category of Urban Local Body') ?></th>
                                    <td><?= h($localbody_types[$urban_row['local_body_type_code']] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('5(c)(iii). Urban Local Body') ?></th>
                                    <td><?= h($localbodies[$urban_row['local_body_code']] ?? '') ?></td>

                                    <th scope="row"><?= __('5(d)(iv). Locality or Ward') ?></th>
                                    <td><?php
                                            $ward='';
                                            
                                                foreach($area_operation_level_urban_wards[$urban_row['row_id']] as $ward_data)
                                                {
                                                    // echo $value1;
                                                    if(count($area_operation_level_urban_wards[$urban_row['row_id']]) > 1)
                                                    {
                                                        $ward .= $localbodieswards[$ward_data].' , ';
                                                    }else
                                                    {                                                        
                                                        $ward .= $localbodieswards[$ward_data] ?? 'All Wards <i class="fa fa-info-circle" title="'.$ward_data.'"></i>';
                                                    }
                                                    
                                                    $j++;
                                                }
                                            
                                           
                                           // echo $result = rtrim($vilage,',');
                                             echo rtrim($ward,' , ');
                                        ?></td>

                                </tr>

                                <?php
                                }
                            }

                            if(!empty($area_operation_level_row)){ ?>
                                <tr>
                                    <th><b style="text-decoration: underline;">Rural</b></th>
                                    <td></td>
                                    <th scope="row"></th>
                                    <td></td>
                                    <th scope="row"></th>
                                    <td></td>

                                </tr>
                                <tr>
                                    <th scope="row"><?= __('5(b). Area of operation Rural ') ?><i
                                            class="fa fa-info-circle"
                                            title="For multiple selection in area operation"></i></th>
                                    <td><?= h($areaOfOperations[$area_of_operation_id_rural]) ?></td>
                                    <th scope="row"></th>
                                    <td></td>
                                    <th scope="row"></th>
                                    <td></td>
                                </tr>
                                <?php  
                                
                                foreach ($area_operation_level_row as $key=>$value)
                            { ?>

                                <tr>
                                    <th scope="row"><?= __('5(c)(i). District') ?></th>
                                    <td><?= h($districtarry[$value['district_code']] ?? '') ?></td>
                                    <th scope="row"><?= __('5(c)(ii). Block') ?> </th>
                                    <td><?= h($blocklist[$value['block_code']] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('5(c)(iii). Gram Panchayat') ?>
                                    <td><?php 
                                    
                                    if($value['gp_village_all'] == 1)
                                    {
                                        $panchayats = '';
                                        $p = 1;

                                        foreach($area_operation_level_row_all_gp[$value['row_id']] as $gp_key => $row_gp)
                                        {
                                            $panchayats .= $gp_code_name[$row_gp].' , ';
                                        }

                                        echo  'All Gram Panchayats <i class="fa fa-info-circle" title="'.rtrim($panchayats,' , ').'"></i>';

                                    } else {
                                        echo h($gps[$value['panchayat_code']] ?? '');
                                    }
                                    
                                     ?></td>
                                    <th scope="row"><?= __('5(c)(iv). Village') ?>
                                    <td><?php //h($districtarry[$area_operation_level[0]['district_code']] ?? '') 
    
                                $area_operation_level_row_v_1[$value['row_id']];
    
                               // print_r($area_operation_level_row_v_1[$value['row_id']]);
                                 $vilage='';
    
                                 $i=1;
                                foreach($area_operation_level_row_v_1[$value['row_id']] as $key1=>$value1)
                                {
    
                                       // echo $value1;
                                 
                                    if($i < count($area_operation_level_row_v_1[$value['row_id']]))
                                    {
                                         $vilage .= $gpsv[$value1].' , ';
                                     }else
                                     {
                                         $vilage .= $gpsv[$value1];
                                     }
                                  
                                  $i++;
                                }
                                // echo $result = rtrim($vilage,',');
                                if($value['gp_village_all'] == 1 || $value['gp_village_all'] == 2)
                                {
                                    echo 'All Villages <i class="fa fa-info-circle" title="'.$vilage.'"></i>'   ;
                                } else {
                                    echo $vilage;
                                }
                        ?></td>
                                </tr>

                                <?php  } 
                                }
                            ?>


                                <!-- <tr>
                             <th scope="row"><?= __('5(c)(iii). Block') ?></th>
                            <td><?= h($blocklist[$area_operation_level[0]['block_code']]) ?></td>
                            <th scope="row"><?= __('5(c)(iv). Panchayat') ?> </th>
                           <td><?= h($gps[$area_operation_level[0]['panchayat_code']]) ?></td>                     
                             <th scope="row"><?= __('5(c)(v). Village') ?>
                             <td><?= h($gpsv[$area_operation_level[0]['village_code']]) ?></td>                          
                                                         
                        </tr>  -->


                                <!-- <tr>
                                    <th scope="row">
                                        <?php echo wordwrap('(5(d). Whether Area of Operation (Village, Panchayat, Block or Mandal or Town, Taluka/District) is adjacent to or includes water body',40,'<br>'); ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->is_coastal == '1') { echo 'Yes';
                             
                             }else{ echo "No";}  ?></td>
                                    <?php if($CooperativeRegistration->is_coastal == '1') { ?>
                                    <th scope="row"><?= __('5(e). Type of water body') ?> </th>
                                    <td><?= h($water_body_type[$CooperativeRegistration->water_body_type_id]) ?></td>
                                    <?php }  ?>


                                </tr> -->


                                <!-- <tr>
                                    <th scope="row">
                                        <?= wordwrap('5(f). Whether the cooperative society is affiliated to Union/Federation',25,'<br>') ?>
                                    <td><?php if($CooperativeRegistration->is_affiliated_union_federation==1){ echo "Yes";} else{ echo "No"; }  ?>
                                    </td>
                                    <?php if($CooperativeRegistration->is_affiliated_union_federation==1){ ?>

                                    <th scope="row"><?= __('5(g). Level of Affiliated Union/Federation') ?></th>
                                    <td><?= h($level_of_aff_union[$CooperativeRegistration->affiliated_union_federation_level]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('5(h). Name of Affiliated Union/Federation') ?> </th>
                                    <td><?= h($dist_central_bannk[$CooperativeRegistration->affiliated_union_federation_name]) ?>
                                    </td>
                                    <?php } ?>
                                    <th scope="row"><?= __('6. Present Functional Status') ?>
                                    <td><?= h($presentFunctionalStatus[$CooperativeRegistration->functional_status]) ?>
                                    </td>

                                </tr>

                                <tr>
                                    <th scope="row"><?= __('7. Number of Members of the Cooperative Society') ?></th>
                                    <td><?= h($CooperativeRegistration->members_of_society) ?></td>
                                    <th scope="row"><?= __('Whether Financial Audit has been done?') ?></th>
                                    <td><?php if($CooperativeRegistration->financial_audit==1){ echo "Yes";} else{ echo "No"; }  ?>
                                    </td>

                                </tr>
                                <tr>
                                    <?php if($CooperativeRegistration->financial_audit==1){ ?>
                                    <th scope="row"><?= __('8(a). Year of Latest Audit Completed') ?>

                                    </th>
                                    <td><?= h($CooperativeRegistration->audit_complete_year) ?>
                                        <br><span class="view-span-break">Note: - Data after 8(a) will be based on
                                            Latest Audit Year</span>
                                    </td>
                                    <th scope="row"><?= __('8(b). Category of Audit') ?>

                                        <?php $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score']; ?>
                                    <td><?= h($categoryAudit[$CooperativeRegistration->category_audit]) ?></td>

                                    <?php } ?>
                                </tr> -->

                                <!-- <tr>

                                    <th scope="row">
                                        <?= wordwrap('9(a). Whether the Cooperative Society is profit making?',30,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->is_profit_making==1)
                            {
                                echo "Yes";
                            }
                            else                                
                            {
                                echo 'No' ;
                            } ?>

                                    </td>
                                    <?php if($CooperativeRegistration->is_profit_making==1)
                            {?>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('9(b). Net Profit of the Cooperative Society') ?> </th>
                                    <td><?= h($CooperativeRegistration->annual_turnover) ?></td>
                                    <?php }  ?>
                                    <th scope="row">
                                        <?= wordwrap('9(c). Whether the dividend paid by the Cooperative Society?',30,'<br>') ?>
                                        4
                                    <td><?php if($CooperativeRegistration->is_dividend_paid==1){ echo 'Yes';} else{ echo "No"; } ?>
                                    </td>

                                </tr>
                                <?php if($CooperativeRegistration->is_dividend_paid==1){ ?>

                                <tr>
                                    <th scope="row">
                                        <?= wordwrap('9(d). Dividend Rate Paid by the Cooperative Society (in %)',30,'<br>') ?>
                                    </th>
                                    <td> <?= h($CooperativeRegistration->dividend_rate) ?></td>
                                <tr>

                                    <?php } ?>


                                <tr>
                                    <th scope="row">10(a). Select the type of bank where the cooperative society have a
                                        bank account?</th>
                                    <?php $b_type = ['1'=>'Cooperative', '2'=>'Commercial']; ?>
                                    <td><?php
                                        $bank_types = '';
                                            foreach($CooperativeRegistration->bank_type as $bank)
                                            {
                                                $bank_types .= $b_type[$bank].',' ?? '';
                                            }

                                            $bank_types = rtrim($bank_types,',');
                                            echo $bank_types;
                                           
                                            ?></td>
                                    <th scope="row">10(b). Select the name of the bank where the cooperative society has
                                        its bank account: -</th>
                                    <td><?php 
                                                $banks = '';
                                                foreach($CooperativeRegistration->cooperative_society_bank_id as $bank_id)
                                                {
                                                    $banks .= $arr_banks[$bank_id].',' ?? '';
                                                }

                                                $banks = rtrim($banks,',');
                                                echo $banks;
                                    ?></td>
                                </tr>
                                <?php
                                    if(in_array('38',$CooperativeRegistration->cooperative_society_bank_id))
                                    {?>
                                <tr>
                                    <th>10(c). Other bank name</th>
                                    <td><?= h($CooperativeRegistration->other_bank) ?></td>
                                </tr> -->
                                <!-- <?php } ?> -->



                            </table>
                        </div>
                    </div>

                    <div class="box-block-m">
                    <div class="col-sm-12">
                        <h4><strong>Other Membership details</strong></h4>
                    </div>
                    <div class="view-table">
                        <table class="table table-striped table-bordered">
                            <?php if($CooperativeRegistration->other_member == 0){ ?>
                            <tr>
                                <th scope="row"><?= __('7(a) Other members details ?') ?>
                                </th>
                                <td>No</td>   
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th scope="row"><?= __('7(a) Other members details ?') ?>
                                </th>
                                <td>Yes</td>

                                <th scope="row"><?= __('7(b) Number of Members') ?>
                                </th>
                                <td><?= h($CooperativeRegistration->other_member_count) ?></td>     
                            </tr>
                            <tr>
                            <div class="col-sm-12 other_section other_section_box"
                            style="display:<?php //echo $StateDistrictFederation->other_member == 1 ? 'block' : 'none'; ?>;">

                            </div>
                            </tr>
                            <?php } ?>

                                    </table>
                                </div>
                            </div>


                            <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Financial Details</strong></h4>
                            </div>
                            <div class="view-table">
                                <table class="table table-striped table-bordered">
                                <?php if($CooperativeRegistration->financial_audit == 0){?>    
                                <tr>
                                    <th scope="row"><?= __('8(a). Whether Financial Audit has been done?') ?>
                                    </th>
                                    <td><?= No ?></td>
                                </tr>
                                <?php } else { ?>
                                <tr>
                                    <th scope="row"><?= __('8(a). Whether Financial Audit has been done?') ?>
                                    </th>
                                    <td><?= Yes ?></td>

                                    <th scope="row"><?= __('8(b). Year of Latest Audit Completed') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->audit_complete_year) ?></td>

                                    <th scope="row"><?= __('8(c). Category of Audit') ?>
                                    </th>
                                    <td><?= h($category_of_audit[$CooperativeRegistration->category_audit]) ?></td>
                                </tr>
                                <?php } ?>
                                
                                <tr>
                                    <th scope="row"><?= __('9(a). Type of bank where the cooperative federation have a bank account?') ?>
                                    </th>
                                    <td><?= h($bankType[$CooperativeRegistration->bank_type]) ?></td>

                                    <?php if($CooperativeRegistration->cooperative_society_bank_id == 1 || $CooperativeRegistration->cooperative_society_bank_id == 2){?>
                                    <th scope="row"><?= __('9(b). Bank name where the cooperative federation has its bank account:') ?>
                                    </th>
                                    <td><?= h($arr_banks[$CooperativeRegistration->cooperative_society_bank_id]) ?></td>
                                    
                                    <?php } else if($CooperativeRegistration->cooperative_society_bank_id == 1){?>
                                    <th scope="row"><?= __('9(b). Bank name where the cooperative federation has its bank account:') ?>
                                    </th>
                                    <td><?= h($arr_banks[$CooperativeRegistration->cooperative_society_bank_id]) ?></td>
                                        
                                    <?php } else if($CooperativeRegistration->cooperative_society_bank_id == 2){?>

                                    <?php }?>

                                    <?php if($CooperativeRegistration->other_bank == 38){?>
                                    <th scope="row"><?= __('9(c). Other bank name (Please Specify)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->other_bank) ?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->has_building == 0){?>
                                    <th scope="row"><?= __('10(a). Whether the cooperative federation has an office building ?') ?>
                                    </th>
                                    <td><?= No ?></td>
                                    
                                    <?php } else {?>
                                    
                                    <th scope="row"><?= __('10(a). Whether the cooperative federation has an office building ?') ?>
                                    </th>
                                    <td><?= Yes ?></td>
                                    
                                    <th scope="row"><?= __('10(b). Type of Office Building') ?>
                                    </th>
                                    <td><?= h($buildingTypes[$CooperativeRegistration->building_type]) ?></td>
                                    <?php }?>
                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->federation_doing_business == 0){ ?>
                                    <th scope="row"><?= __('11. Is the Federation/State Cooperative Federation doing a business activity?') ?>
                                    </th>
                                    <td><?= No ?></td>

                                    <th scope="row"><?= __('11(e). Authorised Share Capital (Rs in Lakh):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->authorised_share) ?></td>

                                    <th scope="row"><?= __('11(f). Annual Turn Over of the Cooperative Federation (Rs in Lakh)') ?>
                                    </th>
                                    <td><?= h($buildingTypes[$CooperativeRegistration->building_type]) ?></td>
                                    
                                    <?php } else {?>

                                    <th scope="row"><?= __('11. Is the Federation/State Cooperative Federation doing a business activity?') ?>
                                    </th>
                                    <td><?= Yes ?></td>

                                    <?php }?>
                                    
                                    
                                    
                                </tr>
                                
                                <tr>

                                    <th scope="row"><?= __('11(a). Whether the Cooperative Federation is profit making?') ?>
                                    </th>
                                    <?php if($CooperativeRegistration->is_profit_making == 1){?>
                                    <td><?= Yes ?></td>
                                    
                                    <th scope="row"><?= __('11(b). Net Profit of the Cooperative Federation (Rs. in Lakhs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->net_profit) ?></td>


                                    <?php } else {?>
                                    <td><?= No ?></td>
                                    
                                    <th scope="row"><?= __('11(b). Net Loss of the Cooperative Federation (Rs. in Lakhs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->net_loss) ?></td>
                                    <?php } ?>
                                                                    
                                </tr>

                                <tr>
                                    <th scope="row"><?= __('11(c). Whether the dividend is paid by the Cooperative Federation?') ?>
                                    </th>
                                    <?php if($CooperativeRegistration->is_dividend_paid == 1){?>
                                    <td><?= Yes ?></td>
                                    
                                    <th scope="row"><?= __('11(d). Dividend Rate Paid by the Cooperative Federation (in %)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->dividend_rate) ?></td>


                                    <?php } else {?>
                                    <td><?= No ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>

                                    <?php if($CooperativeRegistration->federation_doing_marketing == 0){ ?>
                                    <th scope="row"><?= __('12. Is the Federation/State Cooperative Federation doing marketing?') ?>
                                    </th>
                                    <td><?= No ?></td>
                                    <?php } else { ?>
                                    <th scope="row"><?= __('12. Is the Federation/State Cooperative Federation doing marketing?') ?>
                                    </th>
                                    <td><?= Yes ?></td>

                                    <th scope="row"><?= __('12(a). Value of marketed product (Rs. in Lakhs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->marketed_product_value) ?></td>

                                    <?php }?>
                                </tr>

                                </table>
                            </div>
                            </div>

                            <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Whether the Primary Cooperative is the member of the Federation?</strong></h4>
                        </div>
                        <div class="view-table">
                            <table class="table table-striped table-bordered">
                            <?php if($CooperativeRegistration->is_federation_member = 0){ ?>
                                <tr>
                                    
                                    <th scope="row"><?= __('6(a). Whether the Primary Cooperative is the member of the Federation?') ?></th>
                                    <td><?= No ?>
                                    </td>

                                </tr>
                                <?php } else if($CooperativeRegistration->is_federation_member = 1){?>
                                <tr>
                                    
                                    <th scope="row"><?= __('6(a). Whether the Primary Cooperative is the member of the Federation?') ?></th>
                                    <td><?= Yes ?>
                                    </td>

                                    <th scope="row"><?= __('6(b). Number of Members') ?></th>
                                    <td><?= h($CooperativeRegistration->primary_cooperative_member_count ?? '') ?>
                                    </td>

                                </tr>
                                    <?php } ?>
                                <tr>
                                
                                <div class="member_report_div" id="member-report-div">
                                <div class="col-sm-12 pacs_member_table">
                                <div class="table-responisve tablenew tablenew1">
                                    <table class="table table-striped table-white table-bordered" id="tab_logic2">
                                        <thead class="table-sticky-header">
                                            <tr style="background-color: #599ec6; color: #fff;">
                                                <th style="width: 2%">
                                                    S.No.
                                                </th>
                                                <th style="width: 38%">
                                                    Cooperative Federation Name
                                                </th>
                                                <th style="width: 15%">
                                                    District Name
                                                </th>
                                                <th style="width: 15%">
                                                    Registration Number
                                                </th>
                                                <th style="width: 15%">
                                                    Registration Date
                                                </th>
                                                <th style="width: 15%">
                                                    Mark Cooperative Federation If A Member
                                                </th>
                                            </tr>

                                        </thead>
                                        <tbody class="pacs_members">
                                             <?php 
                                                



                                $check = '';
                                foreach($cooperative_society_name_arr as $key=>$value){

                                foreach($sd_federation_members as  $sd_federation_member){

                                            if($sd_federation_member->cooperative_registration_id == $value['id'])
                                                {
                                                    $check = 'checked="true"';
                                                    break;
                                                }else{
                                                    $check = '';
                                                }
                                        }
                                        echo '<tr><td>'.($key+1).'</td><td>'.$value['cooperative_society_name'].'</td><td>'.$districts[$value['district_code']].'</td><td>'.$value['registration_number'].'</td><td>'.$value['date_registration'].'</td><td> <input type="checkbox"  id="member-checked" '.$check.' name="sd_federation_members['.$key.']" value="'.$value->id.'" onclick = "society_checked(this)"> </td></tr>';

                                                }
                                            ?>

                                            <?php if(count($sd_federation_members) == 0):?>
                                            <tr>
                                                <td colspan="6"><?= __('Record not found!'); ?></td>
                                            </tr>
                                            <?php endif;?>
                                        </tbody>
                                    </table>
                                </div>

                                </div>
                                </div>
                                </tr>



                            </table>
                        </div>
                    </div>


                    <?php 
              //  echo "<pre>";
               // print_r($CooperativeRegistration);  
               ##################################
                ###  pac detail cooperative_registration_pacs ###
               #################################                
                 ?>
                    <?php if($CooperativeRegistration->sector_of_operation==1 || $CooperativeRegistration->sector_of_operation==20 || $CooperativeRegistration->sector_of_operation==22)  { ?>

                    <?php  
                            $pack_detail='PACS';
                          if($CooperativeRegistration->sector_of_operation==20) {
                            $pack_detail='FSS';
                          }
                            if($CooperativeRegistration->sector_of_operation==22) {
                            $pack_detail='LAMPS';
                          }
                            if($CooperativeRegistration->sector_of_operation==22) {
                            $pack_detail='LAMPS';
                          }
                           
                            ?>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong><?php echo  $pack_detail; ?> Details</strong></h4>
                        </div>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th scope="row">
                                    <?= __('10(a). Whether the co-operative society has an office building') ?></th>
                                <td><?php
                            if($CooperativeRegistration->cooperative_registration_pacs[0]->has_building==1)
                            {
                                echo 'Yes';
                            }else
                            {
                                echo 'No';
                            }

                          ?>
                                </td>
                                <?php  
                            if($CooperativeRegistration->cooperative_registration_pacs[0]->has_building==1)
                            { ?>
                                <th scope="row"><?= __('10(b). Type of Office Building') ?></th>
                                <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registration_pacs[0]->building_type] ?? '') ?>
                                </td>

                                <?php } ?>

                                <th scope="row"><?= __('10(c). Whether the co-operative society has land') ?></th>
                                <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->is_socitey_has_land==1) { echo "Yes";}else{ echo "No" ;} ?>
                                </td>
                                <th></th>
                                <td></td>
                            </tr>
                            <th><?= __('10(d). Land Available with the Cooperative') ?></th>
                            <td></td>
                            <th></th>
                            <td></td>
                            <th></th>
                            <td></td>
                            </tr>
                            <tr>
                                <th> <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->is_socitey_has_land==1) { ?>
                                    <div class="box-primary box-st">
                                        <!-- /.box-header -->
                                        <div class="box-body table-responsive">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                        <th scope="col" width="50%"><?= __('Type of possession') ?></th>
                                                        <th scope="col" width="40%"><?= __('Area (in Acre)') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1.</td>
                                                        <td>Owned Land</td>

                                                        <td><?= $CooperativeRegistration['cooperative_registrations_lands'][0]['land_owned']?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2.</td>
                                                        <td>Leased Land</td>
                                                        <td><?= $CooperativeRegistration['cooperative_registrations_lands'][0]['land_leased']?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3.</td>
                                                        <td>Land allotted by the Government</td>
                                                        <td><?= $CooperativeRegistration['cooperative_registrations_lands'][0]['land_allotted']?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td colspan="2" style="float:right;"><b>Total</b></td>
                                                        <td><?= $CooperativeRegistration['cooperative_registrations_lands'][0]['land_total']?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </th>
                                <td></td>
                                <th></th>
                                <td></td>
                                <th></th>
                                <td></td>
                            </tr>
                        </table>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th scope="row">
                                    <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                </th>

                            </tr>
                        </table>
                        <div>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('11(a). Total Outstanding Loan extended to Member(In Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->pack_total_outstanding_loan) ?? '' ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('11(b). Revenue (Other than interest) from Non-Credit Activities (In Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->pack_revenue_non_credit) ?? '' ?>
                                    </td>

                                    <th scope="row"> <?= __('11(c). Fertilizer Distribution') ?></th>
                                    <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->fertilizer_distribution==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><?= __('11(d). Pesticide Distribution') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->pesticide_distribution==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row"><?= __('11(e). Seed Distribution') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->seed_distribution==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row"> <?= __('11(f). Fair Price Shop License') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->fair_price==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><?= __('11(g). Procurement of Foodgrains') ?></th>
                                    <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->is_foodgrains==1){ echo "Yes" ;} else{ echo "No" ;} ?>

                                    </td>
                                    <th scope="row"><?= __('11(h). Hiring of Agricultural Implements') ?></th>
                                    <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->agricultural_implements==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row"> <?= __('11(i). Dry Storage Facilities') ?></th>
                                    <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->dry_storage==1){ echo "Yes" ;} else{ echo "No" ;} ?>

                                    </td>
                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->dry_storage==1){ ?>
                                    <th scope="row"><?= __('11(j). Capacity of Dry Storage Infrastructure (In MT)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->dry_storage_capicity ?? '') ?>
                                        <?php } ?>
                                    </td>
                                    <th scope="row"><?= __('11(k). Cold Storage Facilities') ?></th>
                                    <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->cold_storage==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->cold_storage==1){  ?>
                                    <th scope="row"> <?= __('11(l). Capacity of Cold Storage Infrastructure (In MT)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->cold_storage_capicity) ?? '' ?>
                                    </td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <th scope="row"><?= __('11(m). Milk Collection Unit Facility') ?></th>
                                    <td><?php if($CooperativeRegistration->cooperative_registration_pacs[0]->milk_unit==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->milk_unit==1){ ?>
                                    <th scope="row">
                                        <?= __('11(n). Annual Milk Collection by Cooperative Society (In Liters)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->milk_capicity_unit ?? '') ?>
                                    </td>
                                    <?php } ?>

                                    <th scope="row">
                                        <?= __('11(o). Wheather Cooperative Society is involved in fish catch') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->pack_involved_fish_catch==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>

                                    <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->pack_involved_fish_catch==1){ ?>
                                    <th scope="row"><?= __('11(p). Annual Fish Catch by Cooperative Society (In kg)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->pack_annual_fish_catch ?? '') ?>
                                    </td>
                                    <?php } ?>

                                    <th scope="row"> <?= __('11(q). Food Processing Facilities') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->food_processing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registration_pacs[0]->food_processing==1){  ?>
                                    <th scope="row"><?= __('11(r). Type of Food Processing Facilities') ?></th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->food_processing_type ?? '') ?>
                                    </td>
                                    <?php } ?>
                                    <th scope="row"><?= __('11(s). Other Facilities Provided (Please Specify)') ?></th>
                                    <td><?= h($CooperativeRegistration->cooperative_registration_pacs[0]->other_facility ?? '') ?>
                                    </td>
                                    <th scope="row"></th>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- end pack detail  -->

                    <!-- cooperative housing detail  -->
                    <?php if($CooperativeRegistration->sector_of_operation==47)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Primary Housing Cooperative Federation Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('13(a). Whether the cooperative federation has land') ?></th>
                                        <td><?php if($sd_federation_housing->has_land == 0) { echo "No"; } else{ echo "Yes"; } ?>                                            
                                        </td>
                                    
                                    </tr>

                                    <tr>
                                        <?php if($sd_federation_housing->has_land == 1){ ?>
                                        <th scope="row">
                                        <div class="box-primary box-st">
                                                     
                                                     <!-- /.box-header -->
                                                    <div class="box-body table-responsive">
                                                   
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" width="5%"><?= __('S No.') ?></th>
                                                                    <th scope="col" width="30%">
                                                                        <?= __('Type of possession') ?>
                                                                    </th>
                                                                    <th scope="col" width="30%">
                                                                        <?= __('Area (in Acre)') ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Owned Land</td>

                                                                    <td>
                                                                        <?php echo $sd_federation_lands->land_owned; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Leased Land</td>
                                                                    <td><?= h($sd_federation_lands->land_leased) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3.</td>
                                                                    <td>Land allotted by the Government</td>
                                                                    <td><?= h($sd_federation_lands->land_allotted) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2" style="float:right;"><b>Total</b>
                                                                    </td>
                                                                    <td><?= h($sd_federation_lands->land_total) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </th>
                                        <?php } ?>
                                    </tr>
                                    
                                    <tr>
                                        
                                        <th scope="row"><?= __('14. Annual Expenses of the Cooperative Federation (in Rs)') ?></th>
                                        <td><?= $sd_federation_housing->annual_expenses ?>                                            
                                        </td>
                                    
                                    </tr>
                                    
                                    <tr>

                                        <th scope="row"><?= __('15(a). Number of houses/flats completed till end of audited year') ?></th>
                                        <td><?= $sd_federation_housing->annual_expenses ?>                                            
                                        </td>
                                        <th scope="row"><?= __('15(b). Number of houses/flats handover to the members till end of audited year') ?></th>
                                        <td><?= $sd_federation_housing->number_of_houses_during_year ?>                                            
                                        </td>
                                        <th scope="row"><?= __('15(c). Number of houses/flats under construction till end of audited year') ?></th>
                                        <td><?= $sd_federation_housing->number_of_houses_construction ?>                                            
                                        </td>
                                    
                                    </tr>
                                    <?php //$housing_services = array_intersect_key($housing_services,array_flip(explode(',',$sd_federation_housing->facilities))); ?>


                                    <tr>

                                        <th scope="row"><?= __('16(a). Facilities provided by Cooperative Federation') ?></th>
                                        <td><?= wordwrap(implode(", ",$housing_services),21,'<br>'); ?>
                                        <?php /*
                                        $socityarray = explode(',',$sd_federation_housing->facilities);
                                        
                              
                                            foreach($socityarray as $value)
                                            {
                                                echo wordwrap($societyFacilities[$value],9," ,", FALSE);
                                            }
*/
                                            ?>                                        
                                        </td>
                                        <th scope="row"><?= __('16(b). Whether the Federation facilitate its member in getting Loan for <br> construction of houses or additional structures within the complex?') ?></th>
                                        <td><?php if($sd_federation_housing->loan_facilities == 0){ echo "No"; } else{ echo "Yes"; } ?>                                            
                                        </td>
                                    
                                    </tr>
                                    

                                        
                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- cooperative transport detail  -->
                    <?php if($CooperativeRegistration->sector_of_operation==68)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Transport Cooperative Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('11(a). Type of Transport Cooperative Society') ?></th>
                                        <td><?= h($trsocietytypes[$CooperativeRegistration->cooperative_registrations_transport->type_society]) ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('11(b). Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                    if($CooperativeRegistration->cooperative_registrations_transport->has_building==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                        </td>
                                    </tr>
                                    <tr>

                                        <?php  
                                                    if($CooperativeRegistration->cooperative_registrations_transport->has_building==1)
                                                    { ?>
                                        <th scope="row"><?= __('11(c). Type of Office Building') ?></th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_transport->building_type] ?? '') ?>
                                        </td>

                                        <?php } ?>


                                    </tr>


                                    <tr>
                                        <th><?= __('12.Member Detail of Cooperative Society') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Type of Member') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('No. of Member') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Individual</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_transport->individual_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Institutional</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_transport->institutional_member) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->members_of_society) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>


                                </table>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </th>

                                </tr>
                            </table>


                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('13(a). Authorised Share Capital (in Rs):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_transport->authorised_share) ?? '' ?>
                                    </td>

                                    <th><?= __('13(b). Paid up Share Capital by different Entity') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                            <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                            </th>
                                                            <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Members test</td>

                                                            <td><?= $CooperativeRegistration['cooperative_registrations_transport']['paid_up_members']?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Government or Government Bodies</td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_transport']['paid_up_government_bodies']?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td></td>
                                                            <td><b>Total</b></td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_transport']['paid_up_total']?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>





                                <tr>
                                    <th scope=" row">
                                        <?= __('14(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_transport->annual_turn_over) ?? '' ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('14(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registrations_transport->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_transport->loan_dccb==1) { ?>
                                    <th scope="row">
                                        <?= __('14(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_transport->loan_from_dcb) ?>
                                    </td>
                                    <?php } ?>
                                </tr>

                                <tr>

                                    <th scope="row">
                                        <?= __('14(c).Whether the loan taken from Other Bank?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_transport->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                    <?php if($CooperativeRegistration->cooperative_registrations_transport->loan_other==1) { ?>
                                    <th scope="row"><?= __('14(c(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_transport->loan_from_other) ?>
                                    </td>
                                    <?php } ?>

                                </tr>
                                <tr>
                                    <th scope="row"><?= __('14(d). Type of Vehicle Ownership in Operation?') ?>
                                    </th>
                                    <td><?php
                                            $typeowner = ['1'=>'Individual Members','2'=>'Owned by the society','3'=>'Mixed ownership']; 
                    
                                             echo $typeowner[$CooperativeRegistration->cooperative_registrations_transport->type_owner];
                                              ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th><?= __('15(a).Details of Vehicle operated by cooperative society:') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">

                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                            <th scope="col" width="50%">
                                                                <?= __('Vehicles') ?>
                                                            </th>
                                                            <th scope="col" width="40%"><?= __('Number') ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Bus</td>

                                                            <td><?= $CooperativeRegistration['cooperative_registrations_transport']['bus_type_detail']?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Truck/Lory</td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_transport']['truck_type_detail']?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td></td>
                                                            <td><b>Other</b></td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_transport']['other_type_detail']?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th><?= __('15(b). Travel Details of Passenger Vehicle in the Audit Year') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">

                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('Vehicle') ?></th>
                                                            <th scope="col" width="50%">
                                                                <?= __('Number of Passenger Vehicle') ?>
                                                            </th>
                                                            <th scope="col" width="40%">
                                                                <?= __('Number of Member Travel') ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td> Passenger Transport Vehicle </td>
                                                            <td><?= h($CooperativeRegistration->cooperative_registrations_transport->no_passenger_vehicle)?>
                                                            </td>
                                                            <td><?= h($CooperativeRegistration->cooperative_registrations_transport->no_member_travel)?>
                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th><?= __('15(c). Travel Details of Freight Vehicle in the Audit Year') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">

                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('Vehicle') ?></th>
                                                            <th scope="col" width="50%">
                                                                <?= __('Number of Freight Vehicle') ?>
                                                            </th>
                                                            <th scope="col" width="40%">
                                                                <?= __('Quantity of Goods Transported(MT)') ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td> Passenger Transport Vehicle </td>
                                                            <td><?= h($CooperativeRegistration->cooperative_registrations_transport->no_freight_vehicle) ?>
                                                            </td>
                                                            <td><?= h($CooperativeRegistration->cooperative_registrations_transport->quantity_good_transport)?>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>


                                <tr>
                                    <th scope=" row">
                                        <?= __('15(d).Whether the vehicle are operated/maintained by members themself') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_transport->member_themself==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('15(e). Whether the members are users of the transport facility of Cooperative Societry?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_transport->is_user_transport_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- cooperative handloom detail  -->
                    <?php if($CooperativeRegistration->sector_of_operation==13)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Primary Handloom & Weavers Cooperative Society Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <!-- <tr>

                                        </td>

                                        <th scope="row">
                                            <?= __('10(a). Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                        if($CooperativeRegistration->cooperative_registrations_handloom->has_building==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                    </tr> -->
                                    <!-- <tr>

                                        <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_handloom->has_building==1)
                                                        { ?>
                                        <th scope="row"><?= __('10(b). Type of Office Building') ?></th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_handloom->building_type] ?? '') ?>
                                        </td>

                                        <?php } ?>


                                    </tr> -->


                                    <tr>
                                        <th><?= __('13. Type of machine used by cooperative society:') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <!-- <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Type of Member') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('No. of Member') ?>
                                                                </th>
                                                            </tr>
                                                        </thead> -->
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Power Looms</td>

                                                                <td>
                                                                    <?= h($sd_federation_handloom->power_loom_type) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Handlooms</td>
                                                                <td><?= h($sd_federation_handloom->hand_loom_type) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <!-- <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->members_of_society) ?? '' ?>
                                                                </td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>

                                    <tr>
                                       
                                        <th scope="row">
                                            <?= __('14(a). Whether the looms are operated by members themself?') ?>
                                        </th>
                                        <td><?php
                                                        if($sd_federation_handloom->operated_member_themself==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                        
                                        <?php
                                                        if($sd_federation_handloom->operated_member_themself==1)
                                                        { ?>
                                        <th scope="row">
                                            <?= __('14(a)(i). Whether the work is divided among the members as per their skill?') ?>
                                        </th>
                                        <td><?php
                                                        if($sd_federation_handloom->is_user_work_divide==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                        <?php } ?>
                                    
                                        
                                        <th scope="row"><?= __('14(b). Number of looms operated by cooperative society:') ?></th>
                                        <td><?= h($sd_federation_handloom->no_of_loom ?? '') ?>
                                        </td>

                                       


                                    </tr>
                                    <tr>

                                        <th scope="row">
                                            <?= __('14(c). Whether the cooperative society provide raw material<br> to members & produced product taken after processing?') ?>
                                        </th>
                                        <td><?php
                                                        if($sd_federation_handloom->raw_product_taken==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                                                        
                                        <th scope="row"><?= __('14(d). Whether the raw material easily available to cooperative society?') ?></th>
                                        <td><?php
                                                        if($sd_federation_handloom->raw_material_available==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                        
                                        <th scope="row"><?= __('14(e). Whether the wastes are generated in production process? ') ?></th>
                                        <td><?php
                                                        if($sd_federation_handloom->waste_generate ==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        
                                    <?php
                                                        if($sd_federation_handloom->raw_material_available==1)
                                                        { ?>
                                        <th scope="row">
                                            <?= __('14(f). Whether the waste management facility is available in the society?') ?>
                                        </th>
                                        <td><?php
                                                        if($sd_federation_handloom->waste_available==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                        <?php } ?>
                                                                        
                                        <th scope="row"><?= __('14(g). Whether the cooperative society operate retail shops/outlet to sale products??') ?></th>
                                        <td><?php
                                                        if($sd_federation_handloom->operate_retail==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                        
                                        <?php
                                                        if($sd_federation_handloom->waste_available==1)
                                                        { ?>
                                        <th scope="row"><?= __('14(f)(i). Number of retail shops/outlets operated by cooperative society:') ?></th>
                                        <td><?php
                                                        if($sd_federation_handloom->no_of_retail ==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                        <?php } ?>

                                    </tr>
                                    <tr>

                                        <th scope="row">
                                            <?= __('14(h). Whether the products are sale out of area of operation of the cooperative society?') ?>
                                        </th>
                                        <td><?php
                                                        if($sd_federation_handloom->product_sale_out==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>                          
                
                                    </tr>

                                </table>
                            </div>
                            <!-- <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </th>

                                </tr>
                            </table> -->


                            <!-- <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_handloom->authorised_share) ?? '' ?>
                                    </td>

                                    <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header
                                            <div class="box-body table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                            <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                            </th>
                                                            <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Members test</td>

                                                            <td><?= $CooperativeRegistration['cooperative_registrations_handloom']['paid_up_members']?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Government or Government Bodies</td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_handloom']['paid_up_government_bodies']?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td></td>
                                                            <td><b>Total</b></td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_handloom']['paid_up_total']?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>





                                <tr>
                                    <th scope=" row">
                                        <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_handloom->annual_turn_over) ?? '' ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('13(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registrations_handloom->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_handloom->loan_dccb==1) { ?>
                                    <th scope="row">
                                        <?= __('13(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_handloom->loan_from_dcb) ?>
                                    </td>
                                    <?php } ?>
                                    <th scope="row">
                                        <?= __('13(c).Whether the loan taken from Other Bank?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>
                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_handloom->loan_other==1) { ?>
                                    <th scope="row"><?= __('13(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_handloom->loan_from_other) ?>
                                    </td>
                                    <?php } ?>


                                </tr>

                                <tr>
                                    <th><?= __('14.Type of machine used by cooperative society:') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header 
                                            <div class="box-body table-responsive">

                                                <table class="table table-hover table-bordered table-striped">

                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Power Looms</td>

                                                            <td><?= $CooperativeRegistration['cooperative_registrations_handloom']['power_loom_type']?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Handlooms</td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_handloom']['hand_loom_type']?>
                                                            </td>
                                                        </tr>


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>



                                <tr>
                                    <th scope=" row">
                                        <?= __('15(a) .Whether the looms are operated by members themself?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->operated_member_themself==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_handloom->operated_member_themself==1) { ?>

                                    <th scope="row">
                                        <?= __('15(a)(i).Whether the work is divided among the members as per their skill?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->is_user_work_divide==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php } ?>


                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('15(b).Number of looms operated by cooperative society') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_handloom->no_of_loom ?>
                                    </td>
                                    <th scope=" row">
                                        <?= __('15(c) .Whether the cooperative society provide raw material to members & produced product taken after processing?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->raw_product_taken==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('15(d) .Whether the raw material easily available to cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->raw_material_available==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('15(e).Whether the wastes are generated in production process?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->waste_generate==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>


                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_handloom->raw_material_available==1) { ?>
                                    <th scope=" row">
                                        <?= __('15(f) .Whether the waste management facility is available in the society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->waste_available==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php } ?>
                                    <th scope="row">
                                        <?= __('15(g) .Whether the cooperative society operate retail shops/outlet to sale products?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->operate_retail==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_handloom->waste_available==1) { ?>
                                    <th scope="row">
                                        <?= __('15(f)(i).Number of retail shops/outlets operated by cooperative society:') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_handloom->no_of_retail ?>
                                    </td>
                                    <?php } ?>
                                    <th scope="row">
                                        <?= __('15(h).Whether the products are sale out of area of operation of the cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_handloom->product_sale_out==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                            </table> -->
                        </div>
                    </div>
                    <?php } ?>


                    <?php if($CooperativeRegistration->sector_of_operation==14)  { ?>
                    <div class="box-block-m">
                    <div class="col-sm-12">
                        <h4><strong>Primary Handicraft Cooperative Federation Details</strong></h4>
                    </div>
                    <div class="view-table">
                    <div style="overflow:auto; white-space: nowrap;">
                    <table class=" table table-striped table-bordered">
                    <tr>

                        <th scope=" row">
                            <?= __('13(a). Type of raw material is using?') ?>
                        </th>
                        <td> <?= wordwrap(implode(", ",$handicraft_rm),21,'<br>'); ?>                           
                        </td>

                        <th scope=" row">
                            <?= __('13(b). Type of product produced by cooperative federation? ') ?>
                        </th>
                        <td><?= wordwrap(implode(", ",$handicraft_tp),21,'<br>'); ?>
                        </td>

                        <th scope="row">
                            <?= __('13(c). Whether the cooperative federation<br> has common working place or manufacturing unit?') ?></th>
                        <td> <?php if($sd_federation_handicraft->common_work_place==1) { echo "Yes";}else{ echo "No" ;} ?>
                        </td>

                    </tr>

                    <tr>

                    <?php if($sd_federation_handicraft->common_work_place==1) { ?>
                    <th scope="row">
                        <?= __('13(c)(i). Number of work places or manufacturing<br> units operated by cooperative federation?') ?></th>
                    <td><?= $sd_federation_handicraft->workplace_operate ?>
                    </td>
                    <?php } ?>

                    <th scope="row">
                    <?= __('13(d). whether the processing or handicraft <br>work is done by members themself?') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_work_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>

                    <?php if($sd_federation_handicraft->is_work_by_member==1) { ?>
                    <th scope="row">
                    <?= __('13(d)(i). Whether the regular guidance/training is<br> provided by cooperative federation to members?') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_training_provide==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>
                    <?php } ?>

                    </tr>

                    <tr>

                    <th scope="row">
                    <?= __('13(e). Whether the cooperative federation provide raw<br> material to members & finished products taken after processing?') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_raw_provide==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>

                    <th scope="row">
                    <?= __('13(f). whether the raw material easily available to<br> cooperative federation? ') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_raw_easy_avail==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>

                    <th scope="row">
                    <?= __('13(g). whether the waste are generated in handicraft process? ') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_waste_generate==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>
                
                    </tr>

                    <tr>

                    <?php if($sd_federation_handicraft->is_waste_generate==1) { ?>
                    <th scope="row">
                    <?= __('13(g)(i). Whether the waste management facility is<br> available in the federation?') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_waste_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>
                    <?php } ?>

                    <th scope="row">
                    <?= __('13(h). whether the cooperative federation operate retail<br> shops/outlet to sale products? ') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_operate_retail==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>

                    <th scope="row">
                    <?= __('13(h)(i). Number of retail shops/outlets<br>  operated bycooperative federation? ') ?></th>
                    <td> <?= $sd_federation_handicraft->no_of_retail ?>
                    </td>
                    </tr>
                    
                    <tr>

                    <th scope="row">
                    <?= __('13(i). whether the products are sale out of the area of operation of the cooperative federation?') ?></th>
                    <td> <?php if($sd_federation_handicraft->is_waste_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                    </td>

                    <th scope="row">
                    <?= __('13(j). Facility provided by cooperative federation? ') ?></th>
                    <td><?= wordwrap(implode(", ",$handicraft_fp),21,'<br>'); ?>
                    </td>

                    </tr>

                    </table>
                    </div>     
                    </div>     
                    </div>     
                    <?php } ?>


                    <?php if($CooperativeRegistration->sector_of_operation==96)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Primary Sericulture Cooperative Society Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <tr>

                                        <th scope="row">
                                            <?= __('11(a).Type of Silkworm rearing is carried out by cooperative') ?>
                                        </th>
                                        <td>
                                            <?php 
                                    
                                    $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_sericulture->type_society);
                                    

                                            foreach($socityarray1 as $value)
                                            {
                                                echo wordwrap($sertisocietytypes[$value],3,",");
                                            }

                                            ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('11(b). Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                        if($CooperativeRegistration->cooperative_registrations_sericulture->has_building==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                    </tr>
                                    <tr>

                                        <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_sericulture->has_building==1)
                                                        { ?>
                                        <th scope="row"><?= __('11(b)(i). Type of Office Building') ?></th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_sericulture->building_type] ?? '') ?>
                                        </td>

                                        <?php } ?>


                                    </tr>
                                </table>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </th>

                                </tr>
                            </table>


                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_sericulture->authorised_share) ?? '' ?>
                                    </td>
                                    <th scope=" row">
                                        <?= __('12(b). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_sericulture->annual_turn_over) ?? '' ?>
                                    </td>

                                </tr>
                                <!--  <tr>
                                       
                                        <th scope="row">
                                            <?= __('12(c). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_sericulture->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td> 
                                    
                                    </tr> -->

                                <!-- <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_sericulture->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('13(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_sericulture->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?> -->
                                <!-- <th scope="row">
                                            <?= __('13(c).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td> 

                                    </tr> -->
                                <!-- <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_sericulture->loan_other==1) { ?>
                                        <th scope="row"><?= __('13(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_sericulture->loan_from_other) ?>
                                        </td>
                                        <?php } ?>

                                    
                                    </tr> -->
                                <th scope="row">
                                    <?= wordwrap('12(c).Whether the cooperative society has common rearing house ?',50,'<br>') ?>
                                </th>
                                <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->common_work_place==1) { echo "Yes";}else{ echo "No" ;} ?>
                                </td>
                                <?php if($CooperativeRegistration->cooperative_registrations_sericulture->common_work_place==1) { ?>
                                <th scope=" row">
                                    <?= __('12(c)(i) .Number of rearing houses operated by cooperative society:') ?>
                                </th>
                                <td><?= $CooperativeRegistration->cooperative_registrations_sericulture->no_rear_house; ?>
                                </td>
                                <?php } ?>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('12(d) . whether the rearing of silkworm is done by member themself?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_work_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_work_by_member==1) { ?>
                                    <th scope="row">
                                        <?= wordwrap('12(d)(i). Whether the regular guidance/training is provided by cooperative society to members? ',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_training_provide==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php } ?>

                                </tr>

                                <tr>



                                    <th scope=" row">
                                        <?= wordwrap('12(e) .Whether the cooperative society has sufficient rearing appliances for rearing house?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_rear_appliance==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row">
                                        <?= __('12(f) .Whether mulberry leaves are easily available to cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_mulberry_easy_available==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('12(g) .whether the cleaning facility of cocoon is available with cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_cleaning_facility_cocoon==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('12(h) .whether the spinning & weaving activities are carried out by cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_spinning_weav==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('12(i) .whether the waste management facility is available in the society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_waste_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('12(j) .whether the cooperative society operate retail shops/outlet to sale products?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_operate_retail==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_sericulture->is_operate_retail==1) { ?>
                                    <th scope="row">
                                        <?= __('12(j)(i).Number of retail shops/outlets operated by cooperative society?') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_sericulture->no_of_retail; ?>
                                    </td>

                                    <?php } ?>
                                    <th scope="row">
                                        <?= __('13.Facility provided by cooperative society?') ?>
                                    </th>
                                    <td><?php 
                                    
                                            $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_sericulture->facilities);
                                            
                                
                                                    foreach($socityarray1 as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($CooperativeRegistration->sector_of_operation==54)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Livestock & Poultry Cooperative Society Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <tr>

                                        <th scope="row">
                                            <?= __('10.Type of Livestock/Poultry reared by the cooperative society') ?>
                                        </th>
                                        <td>
                                            <?php 
                                    
                                       $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_livestock->type_society);
                                    
                        
                                            foreach($socityarray1 as $value)
                                            {
                                                echo wordwrap($livesocietytypes[$value],3,",");
                                            }

                                            ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('11(a). Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                        if($CooperativeRegistration->cooperative_registrations_livestock->has_building==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                    </tr>
                                    <tr>

                                        <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_livestock->has_building==1)
                                                        { ?>
                                        <th scope="row"><?= __('11(a)(i). Type of Office Building') ?></th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_livestock->building_type] ?? '') ?>
                                        </td>

                                        <?php } ?>


                                    </tr>



                                </table>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </th>

                                </tr>
                            </table>


                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('11(b). Authorised Share Capital (in Rs):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_livestock->authorised_share) ?? '' ?>
                                    </td>

                                    <th scope=" row">
                                        <?= __('11(c). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_livestock->annual_turn_over) ?? '' ?>
                                    </td>
                                </tr>

                                <!-- <tr>
                                        
                                         <th scope="row">
                                            <?= __('12(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_livestock->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td> 
                                    
                                    </tr> -->

                                <!--  <tr>
                                       <?php if($CooperativeRegistration->cooperative_registrations_livestock->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('12(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_livestock->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('12(c).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td> 

                                    </tr> -->
                                <!--   <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_livestock->loan_other==1) { ?>
                                        <th scope="row"><?= __('12(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_livestock->loan_from_other) ?>
                                        </td>
                                        <?php } ?> 

                                    
                                    </tr>-->


                                <tr>
                                    <!--  <th scope=" row">
                                            <?= __('13(a) .Type of primary activity in livestock & poultry cooperative society?') ?>
                                        </th>
                                        <td><?php 
                                         $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_livestock->type_pa);
                                            
                                
                                                    foreach($socityarray as $value)
                                                    {
                                                        echo wordwrap($type_lpa[$value],10,",");
                                                    }

                                                    ?> </td> -->
                                    <th scope="row">
                                        <?= __('11(d).Type of product produced by cooperative society?') ?>
                                    </th>
                                    <td><?php 
                                    
                                            $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_livestock->type_produce);
                                            
                                
                                                    foreach($socityarray1 as $value)
                                                    {
                                                        echo wordwrap($lproductproduce[$value],3,",");
                                                    }

                                                    ?></td>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= wordwrap('11(e).Whether the cooperative society has common Livestock Shade/poultry farm ?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->common_work_place==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_livestock->common_work_place==1) { ?>
                                    <th scope=" row">
                                        <?= __('11(e)(i) .Area of Shade/Farm operated by cooperative society in sq. meter?') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_livestock->square_meter; ?>
                                    </td>
                                    <?php } ?>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('11(f) .whether the caretaking of livestock/poultry  is done by members themself?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_work_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_livestock->is_work_by_member==1) { ?>
                                    <th scope="row">
                                        <?= wordwrap('11(f)(i).Whether the regular guidance/training is provided by cooperative society to members?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_training_provide==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php } ?>

                                </tr>

                                <tr>



                                    <th scope=" row">
                                        <?= wordwrap('11(g) .Whether the cooperative society provide livestock/poultry feed to members',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_poultry_feed==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row">
                                        <?= __('11(h) .whether livestock/poultry products are collected from members for marketing by cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_collected_from_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('11(i) . whether the livestock/poultry waste management facility is available in the society') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_waste_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>




                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('11(j) .whether the cooperative society operate retail shops/outlet to sale products?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_operate_retail==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_livestock->is_operate_retail==1) { ?>
                                    <th scope="row">
                                        <?= __('11(j)(i).Number of retail shops/outlets operated by cooperative society?') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_livestock->no_of_retail; ?>
                                    </td>

                                    <?php } ?>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= wordwrap('11(k) .whether the products are sale out of the area of operation of the cooperative society?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_livestock->is_product_sale_out==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('12.Facility provided by cooperative society?') ?>
                                    </th>
                                    <td><?php 
                                    
                                            $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_livestock->facilities);
                                            
                                
                                                    foreach($socityarray1 as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($CooperativeRegistration->sector_of_operation==90)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Primary Jute & Coir Cooperative Society Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <tr>

                                        </td>

                                        <th scope="row">
                                            <?= __('11(a). Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                        if($CooperativeRegistration->cooperative_registrations_jute->has_building==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                    </tr>
                                    <tr>

                                        <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_jute->has_building==1)
                                                        { ?>
                                        <th scope="row"><?= __('11(a)(i). Type of Office Building') ?></th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_jute->building_type] ?? '') ?>
                                        </td>

                                        <?php } ?>


                                    </tr>





                                </table>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </th>

                                </tr>
                            </table>


                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_jute->authorised_share) ?? '' ?>
                                    </td>

                                </tr>
                                <!-- <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                              
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_jute']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_jute']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_jute']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr> -->





                                <tr>
                                    <th scope=" row">
                                        <?= __('12(b). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_jute->annual_turn_over) ?? '' ?>
                                    </td>
                                    <!-- <th scope="row">
                                            <?= __('12(c). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_jute->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td> -->

                                </tr>

                                <!-- <tr>
                                  <?php if($CooperativeRegistration->cooperative_registrations_jute->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('12(c)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_jute->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('12(d).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_jute->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td> 

                                    </tr> -->
                                <!--   <tr>
                                   <?php if($CooperativeRegistration->cooperative_registrations_jute->loan_other==1) { ?>
                                        <th scope="row"><?= __('12(d)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_jute->loan_from_other) ?>
                                        </td>
                                        <?php } ?> 

                                    
                                    </tr>-->


                                <tr>
                                    <th scope=" row">
                                        <?= __('12(c) .Type of raw material is using?') ?>
                                    </th>
                                    <td><?php 
                                        $facility1 = ['1'=>'Textiles or Leather material','2'=>'Wood,metal,clay,bone,horn,glass, or stone','3'=>'Paper or canvas','4'=>'Plants (bamboo etc.) other than wood','5'=>'other'];
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_jute->type_raw);
                                            
                                
                                                    foreach($socityarray as $value)
                                                    {
                                                        echo wordwrap($type_jraw[$value],10,",");
                                                    }

                                                    ?> </td>
                                    <th scope="row">
                                        <?= __('12(d).Type of product produced by cooperative society?') ?>
                                    </th>
                                    <td><?php 
                                       
                                            $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_jute->type_produce);
                                            
                                
                                                    foreach($socityarray1 as $value)
                                                    {
                                                        echo wordwrap($productproduce[$value],3,",");
                                                    }

                                                    ?></td>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= wordwrap('12(e).Whether the cooperative society has common working place or manufacturing unit?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->common_work_place==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_jute->common_work_place==1) { ?>
                                    <th scope=" row">
                                        <?= __('12(e)(i) .Number of work places or manufacturing units operated by cooperative society?') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_jute->workplace_operate; ?>
                                    </td>
                                    <?php } ?>



                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('12(f) .whether the processing or jute work is done by members themself?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_work_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_jute->is_work_by_member==1) { ?>
                                    <th scope="row">
                                        <?= wordwrap('12(f)(i).Whether the regular guidance/training is provided by cooperative society to members?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_training_provide==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php } ?>

                                </tr>

                                <tr>



                                    <th scope=" row">
                                        <?= wordwrap('12(g) .Whether the cooperative society provide raw material to members & finished products taken after processing?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_raw_provide==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row">
                                        <?= __('12(h) .whether the raw material easily available to cooperative society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_raw_easy_avail==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('12(i) .whether the waste are generated in jute process?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_waste_generate==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                    <?php if($CooperativeRegistration->cooperative_registrations_jute->is_waste_generate==1) { ?>
                                    <th scope="row">
                                        <?= __('12(i)(1). Whether the waste management facility is available in the society?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_waste_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('12(j) .whether the cooperative society operate retail shops/outlet to sale products?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_operate_retail==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_jute->is_operate_retail==1) { ?>
                                    <th scope="row">
                                        <?= __('12(j)(1).Number of retail shops/outlets operated by cooperative society?') ?>
                                    </th>
                                    <td><?= $CooperativeRegistration->cooperative_registrations_jute->no_of_retail; ?>
                                    </td>

                                    <?php } ?>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= wordwrap('12(k) .whether the products are sale out of the area of operation of the cooperative society?',50,'<br>') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_jute->is_product_sale_out==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('13.Facility provided by cooperative society?') ?>
                                    </th>
                                    <td><?php 
                                    
                                            $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_jute->facilities);
                                            
                                
                                                    foreach($socityarray1 as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($CooperativeRegistration->sector_of_operation==84)  { ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Primary Education & Training Cooperative Society Details</strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">
                                    <tr>

                                        <th scope="row">
                                            <?= __('10(a).Type of education Cooperative') ?>
                                        </th>
                                        <td><?= h($educationsocietytypes[$CooperativeRegistration->cooperative_registrations_education->type_society] ?? '') ?>
                                        </td>

                                        <?php if($CooperativeRegistration->cooperative_registrations_education->type_society==1) { ?>
                                        <th scope="row">
                                            <?= __('10(a)(i).Level of education Courses offered by Cooperative Society?') ?>
                                        </th>

                                        <td>

                                            <?= h($levelofcourses[$CooperativeRegistration->cooperative_registrations_education->level_of_edu] ?? '') ?>
                                        </td>
                                        <?php } ?>

                                        <?php if($CooperativeRegistration->cooperative_registrations_education->type_society==2) { ?>
                                        <th scope="row">
                                            <?= __('10(a)(i). Duration of training courses offered by Cooperative Society?') ?>
                                        </th>
                                        <td>

                                            <?= h($durationofcourses[$CooperativeRegistration->cooperative_registrations_education->duration_of_course] ?? '') ?>
                                        </td>
                                        <?php } ?>


                                        <?php if($CooperativeRegistration->cooperative_registrations_education->type_society==3) { ?>
                                        <th scope="row">
                                            <?= __('10(a)(i).Level & duration of training & education Courses offered by Cooperative Society?') ?>
                                        </th>
                                        <td>
                                            <?php 
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_education->level_and_duration_of_course);
                                            
                                  
                                                    foreach($socityarray as $value)
                                                    {
                                                        echo wordwrap($leveldurationofcourses[$value],3,",");
                                                    }

                                                    ?>


                                        </td>
                                        <?php } ?>




                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <?= __('10(b). Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                        if($CooperativeRegistration->cooperative_registrations_education->has_building==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                        </td>
                                    </tr>
                                    <tr>

                                        <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_education->has_building==1)
                                                        { ?>
                                        <th scope="row"><?= __('10(b)(i). Type of Office Building') ?></th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_education->building_type] ?? '') ?>
                                        </td>

                                        <?php } ?>

                                        <th scope="row"><?= __('10(c). Whether the co-operative society has land') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_education->has_land==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>


                                    </tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_education->has_land==1) { ?>
                                    <tr>
                                        <th><?= __('10(c)(i). Land Available with the Cooperative') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>

                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Type of possession') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Area (in Acre)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Owned Land</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_land->land_owned) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Leased Land</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_land->land_leased) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.</td>
                                                                <td>Land allotted by the Government</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_land->land_allotted) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_land->land_total) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <?php } ?>


                                    <tr>
                                        <th><?= __('11.Member Detail of Cooperative Society') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Type of Member') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('No. of Member') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Individual</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_education->individual_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Institutional</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_education->institutional_member) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->members_of_society) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>


                                </table>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row">
                                        <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                    </th>

                                </tr>
                            </table>


                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->authorised_share) ?? '' ?>
                                    </td>

                                    <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="box-primary box-st">
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                            <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                            </th>
                                                            <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>Members test</td>

                                                            <td><?= $CooperativeRegistration['cooperative_registrations_education']['paid_up_members']?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>Government or Government Bodies</td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_education']['paid_up_government_bodies']?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td></td>
                                                            <td><b>Total</b></td>
                                                            <td><?= $CooperativeRegistration['cooperative_registrations_education']['paid_up_total']?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                    <th></th>
                                    <td></td>
                                </tr>





                                <tr>
                                    <th scope=" row">
                                        <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->annual_turn_over) ?? '' ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('13(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                    <td> <?php if($CooperativeRegistration->cooperative_registrations_education->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>

                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_education->loan_dccb==1) { ?>
                                    <th scope="row">
                                        <?= __('13(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->loan_from_dcb) ?>
                                    </td>
                                    <?php } ?>
                                    <th scope="row">
                                        <?= __('13(c).Whether the loan taken from Other Bank?') ?>
                                    </th>
                                    <td><?php if($CooperativeRegistration->cooperative_registrations_education->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                    </td>

                                </tr>
                                <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_education->loan_other==1) { ?>
                                    <th scope="row"><?= __('13(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->loan_from_other) ?>
                                    </td>
                                    <?php } ?>


                                </tr>


                                <tr>
                                    <th scope="row"><?= __('14(a). Number of courses offered in the audited year') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->course_in_audit) ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('14(b). Number of students completed courses offered in the audited year?') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->stu_in_audit) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('14(c). Number of training courses conducted in the audited year:') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->training_course_in_audit) ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('14(d). Number of participants attended training in the audited year?') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->participants_in_audit) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('14(e).Whether the Society conduct any course/training for international Participants?') ?>
                                    </th>
                                    <td>
                                        <?php
                                                        if($CooperativeRegistration->cooperative_registrations_education->course_international_participant==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>

                                    </td>
                                    <th scope="row"><?= __('14(e)(i). Number of trainings/courses conducted?') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->no_of_training_course) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <?= __('14(e)(ii).Number of participants attended training/course:') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->attended_training) ?>
                                    </td>
                                    <th scope="row"><?= __('14(f). Whether the Society has recruited own faculty:') ?>
                                    </th>
                                    <td>
                                        <?php
                                              
                                        if($CooperativeRegistration->cooperative_registrations_education->society_recruit==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        } 
                                                        ?>


                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><?= __('14(f)(i).Number of Regular Faculties::') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->no_regular_faculty) ?>
                                    </td>
                                    <th scope="row">
                                        <?= __('14(f)(ii).Number of Adhoc/Contractual/Visiting Faculties:') ?>
                                    </th>
                                    <td><?= h($CooperativeRegistration->cooperative_registrations_education->no_other_faculty) ?>
                                    </td>
                                </tr>

                                <th scope="row">
                                    <?= __('14(g).Facility provided by cooperative society?') ?>
                                </th>
                                <td><?php 
                                    
                                            $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_education->facilities);
                                            
                                
                                                    foreach($socityarray1 as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($CooperativeRegistration->sector_of_operation==7)  { 
                        
                      $yesNo = ['No','Yes'];
                    $stateCentral =['State','Central'];
                      ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Urban Cooperative Bank
                                </strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">

                                    <tr>
                                        <th scope="row">
                                            <?= __('13(a). Total Number of branches of UCB:') ?>
                                        </th>
                                        <td><?php //= h($CooperativeRegistration->cooperative_registrations_ucb->ucb_branch ?? '') ?>
                                        <?php echo  $sd_federation_ucb->ucb_branch  ; ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('13(b). Whether the UCB is affiliated to NAFCUB?') ?>
                                        </th>
                                        <td><?php //= h($yesNo[$CooperativeRegistration->cooperative_registrations_ucb->has_nafcub ?? '']) ?>
                                        <?php if($sd_federation_ucb->has_nafcub == 0){   ?>
                                        <td><?= No ?></td>
                                        <?php } else {?>
                                        <td><?= Yes ?></td>
                                        <?php }?>
                                        </td>
                                    </tr>


                                    <tr>

                                        <th><?= __('14(a). Annual Income of the UCB(in Rs)') ?></th>
                                        <td><?php echo  $sd_federation_ucb->annual_income; ?>
                                        </td>

                                        <th><?= __('14(b). Annual Expenditure of the UCB(in Rs)') ?></th>
                                        <td><?php echo  $sd_federation_ucb->annual_ucb_expenditr; ?>
                                        </td>

                                    </tr>

                                    <tr>

                                        <th><?= __('14(c). Assets of the UCB(in Rs)') ?></th>
                                        <td><?php echo  $sd_federation_ucb->asset_ucb; ?>
                                        </td>

                                        <th><?= __('14(d). Liabilities of the UCB(in Rs)') ?></th>
                                        <td><?php echo  $sd_federation_ucb->liability_ucb; ?>
                                        </td>

                                        <th><?= __('14(e). Total Deposit of the UCB(in Rs)') ?></th>
                                        <td><?php echo  $sd_federation_ucb->total_deposit; ?>
                                        </td>

                                    </tr>

                                    <tr>

                                        <th><?= __('14(f). Total loan Outstanding of the UCB(in Rs)') ?></th>
                                        <td><?php echo  $sd_federation_ucb->loan_outstanding; ?>
                                        </td>
                                        
                                    </tr>

                                    <tr>

                                        <th><?= __('15(a). Whether the UCB implementing any Central/State Government Scheme?') ?></th>
                                        <?php if($sd_federation_ucb->is_gov_scheme_implemented == 0 ){ ?>
                                        <td><?= No ?>
                                        </td>
                                        <?php } else {?>
                                        <td><?= Yes ?>
                                        </td>
                                        <?php }?>
                                        
                                    </tr>
                                    
                                    <!-- <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                               
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Membership Type') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Number of Member') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Borrowing Members</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_ucb->borrowing_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Non-Borrowing Members</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->non_borrowing_member) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total Members</b></td>
                                                                <td><?= h($CooperativeRegistration->members_of_society) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr> -->

                                    <!-- <tr>
                                        <th scope="row">
                                            <?= __('13(a). Whether the UCB has an office building?') ?>
                                        </th>
                                        <td><?= h($yesNo[$CooperativeRegistration->cooperative_registrations_ucb->has_building ?? '']) ?>
                                        </td>

                                        <?php if($CooperativeRegistration->cooperative_registrations_ucb->has_building==1) {?>
                                        <th scope="row">
                                            <?= __('13(b). Type of Office Building') ?>
                                        </th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_ucb->building_type ?? '']) ?>
                                        </td>
                                        <?php } ?>

                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('14. Authorised Share Capital of the UCB (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->authorised_share ?? '') ?>
                                        </td>
                                    </tr> -->

                                    <!-- <tr>
                                        <th><?= __('14(b). Paid up Share Capital by different Entity:') ?></th>
                                    </tr> -->
                                    <!-- <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                               
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members other than Govt./Govt. Bodies</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_ucb->paid_up_members) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government & Government Bodies</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->paid_up_government_bodies) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->paid_up_total) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr> -->

                                    <!-- <tr>
                                        <th scope="row">
                                            <?= __('15(a). Annual Turn Over of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->annual_turn_over ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('15(b). Annual Income of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->annual_income ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('15(c). Annual Expenditure of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->annual_ucb_expenditr ?? '') ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('15(d). Assets of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->asset_ucb ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('15(e). Liabilities of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->liability_ucb ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('15(f). Total Deposit of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->total_deposit ?? '') ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('15(g). Total loan Outstanding of the UCB(in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_ucb->loan_outstanding ?? '') ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('16(a). Whether the UCB implementing any Central/State Government Scheme?') ?>
                                        </th>
                                        <td><?= h($yesNo[$CooperativeRegistration->cooperative_registrations_ucb->is_gov_scheme_implemented ?? '']) ?>
                                        </td>
                                    </tr>


                                    <?php if($CooperativeRegistration->cooperative_registrations_ucb->is_gov_scheme_implemented==1) {?>
                                    <tr>
                                        <th><?= __('16(b). Name of the Central Government Schemes or State Government Schemes implemented by UCB:') ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <!-- <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Name of Government Scheme') ?>
                                                                </th>
                                                                <th scope="col" width="10%">
                                                                    <?= __('Type of Scheme (Central/State)') ?>
                                                                </th>
                                                                <th scope="col" width="30%">
                                                                    <?= __('Total Amount Spent through UCB (Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $slNo=1; foreach($CooperativeRegistration->society_implementing_schemes as $key=>$society_implementing_scheme){?>
                                                            <tr>
                                                                <td><?php echo $slNo; ?></td>


                                                                <td>
                                                                    <?= h($society_implementing_scheme->gov_scheme_name) ?? '' ?>
                                                                </td>
                                                                <td><?= h($stateCentral[$society_implementing_scheme->gov_scheme_type]) ?? '' ?>
                                                                </td>
                                                                <td><?= h($society_implementing_scheme->total_amount) ?? '' ?>
                                                                </td>
                                                            </tr>


                                                            <?php $slNo++;} ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr> 

                                    <?php } ?>-->
                                </table>
                            </div>
                        </div>
                        </div>
                        <?php } ?>

                        <?php if($CooperativeRegistration->sector_of_operation==15)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary Women Cooperatives Federation Details</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>

                                            <th scope="row">
                                                <?= __('13(a). Type of Women Cooperative') ?>
                                            </th>
                                            <td><?= h($womensocietytypes[$sd_federation_wocoop->type_society] ?? '') ?>
                                            </td>

                                        </tr>

                                        <tr>

                                            <th scope="row">
                                                <?= __('13(b). Whether the cooperative federation provide<br> raw material to members & finished products taken after processing?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_federation_wocoop->is_raw_material_taken==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>

                                        </tr>

                                        <tr>
                                           
                                            <th scope="row"><?= __('13(c). Facility provided by cooperative federation?') ?></th>
                                            <td><?= wordwrap(implode(", ",$wocoop_f),21,'<br>'); ?>
                                            </td>

                                        </tr>

                                    </table>
                                </div>
                                <!-- <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_wocoop->authorised_share) ?? '' ?>
                                        </td>

                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_wocoop->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_wocoop->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>

                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_wocoop->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('13(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_wocoop->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('13(c).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_wocoop->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_wocoop->loan_other==1) { ?>
                                        <th scope="row">
                                            <?= __('13(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_wocoop->loan_from_other) ?>
                                        </td>
                                        <?php } ?>


                                    </tr>


                                    <tr>

                                        <th scope="row">
                                            <?= __('13(d). Whether the cooperative society provide raw material to members & finished products taken after processing?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_wocoop->is_raw_material_taken==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                    </tr>

                                    <th scope="row">
                                        <?= __('13(e).Facility provided by cooperative society?') ?>
                                    </th>
                                    <td><?php 
                                
                                        $socityarray1 = explode(',',$CooperativeRegistration->cooperative_registrations_wocoop->facilities);
                                        
                            
                                                foreach($socityarray1 as $value)
                                                {
                                                    echo wordwrap($societyFacilities[$value],3,",");
                                                }

                                                ?>
                                    </td>
                                    </tr>

                                </table> -->
                            </div>
                        </div>
                        <?php } ?>

                        <?php if($CooperativeRegistration->sector_of_operation==77)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary Agriculture & Allied Cooperative Society Details</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>
                                            <!-- <th scope="row">
                                                <?= __('10(a).Type of Agriculture Cooperative') ?>
                                            </th>
                                            <td><?= h($sd_fed_agri->type_society ?? '') ?>
                                            </td> -->

                                            <th scope="row">
                                                <?= __('13(a). Do the members themselves have pooled land for the cooperative society?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->has_pool_land==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('13(b). Apart from the pooled/common land of individuals, has the society taken any land from the government?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->has_gov_land==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>
                                        </tr>

                                        <tr>

                                            <th scope="row">
                                                <?= __('13(c). Is the right vested with the members to take back the land from the society') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->member_vested_right==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('13(d). Do the members themselves work on pooled/common land of the cooperative society?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->is_member_work==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>
                                        </tr>

                                        <tr>

                                            <th scope="row">
                                                <?= __('13(e). Do the society have common pool of bullocks, machinery, equipments, etc.?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->society_common_pool==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('13(f). Do the society fully utilize common pool resource?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->is_utilize_pool==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>
                                        </tr>

                                        <tr>

                                            <th scope="row">
                                                <?= __('13(g). Does the society have the provision of rain water harvesting?') ?>
                                            </th>
                                            <td><?php
                                                    if($sd_fed_agri->harvesting==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>

                                        </tr>

                                        <tr>

                                            <th scope="row"><?= __('13(h). Which of the following farming mechanisms adopted by the cooperative society?') ?></th>
                                            
                                            <td><?= wordwrap(implode(", ",$farming_mech_all),21,'<br>'); ?></td>
                                            </td>

                                            <th scope="row"><?= __('13(i). Which of the following farming mechanisms adopted by the cooperative society?') ?></th>
                                            <td><?= wordwrap(implode(", ",$irrigation_means_all),30,'<br>'); ?></td>
                                            </td>


                                        </tr>


                                     


                                    </table>
                                </div>
                              <!--  <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_agriculture->authorised_share) ?? '' ?>
                                        </td>

                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_agriculture']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_agriculture']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_agriculture']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_agriculture->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_agriculture->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>

                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_agriculture->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('13(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_agriculture->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('13(c).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_agriculture->loan_other==1) { ?>
                                        <th scope="row">
                                            <?= __('13(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_agriculture->loan_from_other) ?>
                                        </td>
                                        <?php } ?>


                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('14(a) .Do the members themselves have pooled land for the cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->has_pool_land==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('14(b).Apart from the pooled/common land of individuals, has the society taken any land from the government?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->has_gov_land==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>



                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('14(c).Is the right vested with the members to take back the land from the society') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->member_vested_right==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                        <th scope=" row">
                                            <?= __('14(d) .Do the members themselves work on pooled/common land of the cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->is_member_work==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>



                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('14(e) Do the society have common pool of bullocks, machinery, equipments, etc.?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->society_common_pool==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('14(f).Do the society fully utilize common pool resource?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->is_utilize_pool==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>


                                    </tr>

                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_agriculture->raw_material_available==1) { ?>
                                        <th scope=" row">
                                            <?= __('14(g) .Does the society have the provision of rain water harvesting?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_agriculture->harvesting==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('14(h) .Which of the following farming mechanisms adopted by the cooperative society?') ?>
                                        </th>
                                        <td><?php $facility = ['1'=>'Modern Farming Equipments','2'=>'Combined Harvester','3'=>'Improved Seed','4'=>'Compost','5'=>'Fertilizers','6'=>'Crop Rotation','7'=> 'Best Farming Practices'];
                                        
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_agriculture->farming_mech);
                                            
                                
                                                foreach($socityarray as $value)
                                                {
                                                    echo wordwrap( $facility[$value],3,",");
                                                }

                                        ?>
                                        </td>

                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('14(i).The means of irrigation available with the society?:') ?>
                                        </th>
                                        <td>

                                            <?php 
                                            $facility2 = ['1'=>'Rain water','2'=>'Ground water','3'=>'canal water'];
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_agriculture->irrigation_means);
                                            
                                
                                                foreach($socityarray as $value)
                                                {
                                                    echo wordwrap( $facility2[$value],3,",");
                                                }

                                                ?>

                                        </td>

                                    </tr>

                                </table>
                            </div>
                        </div> -->
                        </div>
                        </div>
                        <?php } ?> 


                        <?php if($CooperativeRegistration->sector_of_operation==79)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Bee Farming Cooperative</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>

                                            <th scope="row">
                                                <?= __('13(a). Type of Bees are rearing by cooperative federation') ?>
                                            </th>
                                            <td>
                                            <?= wordwrap(implode(", ",$bee_types),21,'<br>'); ?>
                                            </td>
                                        </tr>

                                        <tr>

                                            <th scope="row">
                                                <?= __('13(b). Whether the Cooperative federation has Common Beehive Yard?') ?>
                                            </th>
                                            <?php if($sd_fed_bee_farm->common_yard == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <th scope="row">
                                            <?= __('13(b)(i). Number of behives operated by cooperative federation.') ?>
                                            </th>
                                            <td><?= $sd_fed_bee_farm->no_of_behives ?></td>
                                            <?php }?>
                                            
                                        </tr>


                                        <tr>

                                            <th scope="row"><?= __('13(c). Type of Beehives are used for rearing bee by cooperative:') ?></th>
                                            <td><?= h($beehivetypes[$sd_fed_bee_farm->type_behives] ?? '') ?>
                                            </td>

                                        </tr>

                                        <tr>

                                            <th scope="row"><?= __('13(d). Whether the rearing of bees is done by member themselves?') ?></th>
                                            
                                            <?php if($sd_fed_bee_farm->rear_by_member == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <th scope="row">
                                            <?= __('13(d)(i). Whether the regular guidance/training is provided by cooperative federation to members?') ?>
                                            </th>
                                            <td><?php if($sd_fed_bee_farm->guidance_by_member == 0){ echo "No"; } else { echo "Yes"; } ?></td>
                                            <?php }?>

                                        </tr> 

                                         <tr>

                                            <th scope="row"><?= __('13(e). Type of products of bee farming:') ?></th>
                                            
                                            <td>
                                            <?= wordwrap(implode(", ",$bee_products),21,'<br>'); ?>
                                            </td>

                                            <th scope="row"><?= __('13(f). Whether the Honey Bee Plants/Flowers are grown by cooperative federation?') ?></th>
                                            
                                            <?php if($sd_fed_bee_farm->is_bee_plant_grow == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <?php }?>

                                        </tr> 
                                        
                                        <tr>
                                            
                                            <th scope="row"><?= __('13(g). Whether the cleaning, processing & packaging facility is available with cooperative federation?') ?></th>
                                            <?php if($sd_fed_bee_farm->is_cleaning_process == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <?php }?>

                                            <th scope="row"><?= __('13(h). Whether the waste management facility is available in the cooperative federation?') ?></th>
                                            <?php if($sd_fed_bee_farm->is_waste_facility == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <?php }?>

                                        </tr>
                                        
                                        <tr>
                                            
                                            <th scope="row"><?= __('13(i). Whether the Cooperative federation ha its own Brand for Honey Products?') ?></th>
                                            <?php if($sd_fed_bee_farm->own_brand_honey == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <?php }?>

                                            <th scope="row"><?= __('13(j). Whether the cooperative federation operate retail shops/outlet to sale products? ') ?></th>
                                            <?php if($sd_fed_bee_farm->is_operate_retail == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <?php }?>

                                        </tr>
                                        
                                        <tr>
                                            
                                            <?php if($sd_fed_bee_farm->is_operate_retail == 1) { ?>
                                            <th scope="row"><?= __('13(k). Number of retail shops/outlets operated by the cooperative federation.') ?></th>
                                            <td><?= $sd_fed_bee_farm->no_of_retail ?></td>
                                            <?php }?>

                                            <th scope="row"><?= __('13(l). Whether products are sale out of the area of operation of the cooperative federation?') ?></th>
                                            <?php if($sd_fed_bee_farm->is_product_sale_out == 0) { ?>
                                            <td><?= No ?></td>
                                            <?php } else {?>
                                            <td><?= Yes ?></td>
                                            <?php }?>

                                        </tr>
                                        
                                        <tr>

                                            <th scope="row">
                                                <?= __('14. Facility provided by cooperative federation? ') ?>
                                            </th>
                                            <td>
                                            <?= wordwrap(implode(", ",$bee_facility),21,'<br>'); ?>
                                            </td>
                                        </tr>
                                        



                                    </table>
                                </div>
                                <!-- <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_bee->authorised_share) ?? '' ?>
                                        </td>

                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <!-- <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_bee']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_bee']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_bee']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>-->





                                    <!-- <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_bee->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(b). Whether the Cooperative society has Common Beehive Yard?') ?>
                                        </th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_bee->common_yard==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>


                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_bee->common_yard==1) { ?>
                                        <th scope="row">
                                            <?= __('13(b)(i). Number of behives operated by cooperative society.') ?>
                                        </th>
                                        <td><?= h($beehivetypes[$CooperativeRegistration->cooperative_registrations_bee->type_behives]) ?>
                                        </td>
                                        <?php } ?>


                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(c) .Type of Beehives are used for rearing bee by cooperative:') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_bee->type_behives) ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('13(d). Whether the rearing of bees is done by member themselves?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->rear_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>



                                    </tr>

                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_bee->rear_by_member==1) { ?>
                                        <th scope="row">
                                            <?= __('13(d)(i).Whether the regular guidance/training is provided by cooperative society to members?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->guidance_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <?php } ?>

                                        <th scope=" row">
                                            <?= __('13(e) Type of products of bee farming:') ?>
                                        </th>
                                        <td>
                                            <?php 
                                    
                                        $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_bee->type_product);
                                        
                            
                                            foreach($socityarray as $value)
                                            {
                                                echo wordwrap( $type_product[$value],3,",");
                                            }

                                    ?>

                                        </td>



                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('13(f) Whether the Honey Bee Plants/Flowers are grown by cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->is_bee_plant_grow==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(g).Whether the cleaning, processing & packaging facility is available with cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->is_cleaning_process==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>


                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('13(h) .Whether the waste management facility is available in the cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->is_waste_facility==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('13(i) .Whether the Cooperative society ha its own Brand for Honey Products?') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_bee->own_brand_honey) ?>
                                        </td>

                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(j) .Whether the cooperative society operate retail shops/outlet to sale products? ') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->is_operate_retai==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('13(k) .Number of retail shops/outlets operated by the cooperative society.') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_bee->no_of_retail) ?>
                                        </td>

                                    </tr> -->

                                    <!-- <tr>
                                        <th scope="row">
                                            <?= __('13(l) .Whether products are sale out of the area of operation of the cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_bee->is_product_sale_out==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('14. Facility provided by cooperative society:') ?>
                                        </th>
                                        <td>

                                            <?php 
                                        $facility2 = ['1'=>'Rain water','2'=>'Ground water','3'=>'canal water'];
                                        $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_bee->facilities);
                                        
                            
                                            foreach($socityarray as $value)
                                            {
                                                echo wordwrap( $societyFacilities[$value],3,",");
                                            }

                                            ?>

                                        </td>

                                    </tr> -->

                                </table>
                            </div>
                        </div>
                        <?php } ?>
                      
                        <?php if($CooperativeRegistration->sector_of_operation==16)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary Multipurpose Cooperative Federation</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>

                                        <th scope="row">
                                            <?= __('13(a). Secondary Activities') ?>
                                        </th>
                                        <td>
                                        <?= h($multitypes[$sd_federation_multi->sec_activity]) ?>                                             
                                        </td>
                                        
                                        </tr>
                                        
                                        <tr>
                                        
                                        <th scope="row">
                                            <?= __('13(b). Whether the Cooperative <br>federation has Storage/GODOWN/Warehouse ?') ?>
                                        </th>
                                        <td><?php if($sd_federation_multi->has_storage==1){echo 'Yes';}else{echo 'No';}?>
                                        </td>
                                        
                                        <?php if($sd_federation_multi->has_storage==1) { ?>
                                            <th scope="row">
                                            <?= __('13(b)(i). Capacity of Storage/Godown/Warehouse<br> operated by cooperative federation (in MT) .') ?>
                                        </th>
                                        <td><?= $sd_federation_multi->storage_capacity ?>
                                        </td>
                                        <?php } ?>
                                        </tr>

                                        <tr>

                                        <th scope="row">
                                            <?= __('13(c). Whether the cooperative federation<br> provide raw material to members & produced take back from them for marketing?') ?>
                                        </th>
                                        <td><?php if($sd_federation_multi->provide_raw==1){echo 'Yes';}else{echo 'No';}?>
                                        </td>
                                        
                                        <?php if($sd_federation_multi->has_storage==1) { ?>
                                        <th scope="row">
                                            <?= __('13(c)(i). Whether the regular guidance/training<br> is provided by cooperative federation to members?') ?>
                                        </th>
                                        <td><?php if($sd_federation_multi->guidance_by_member==1){echo 'Yes';}else{echo 'No';}?>
                                        </td>
                                        <?php } ?>

                                        <th scope="row">
                                            <?= __('13(d). Whether the cooperative federation operate <br>retail shops/outlet to sale their products?') ?>
                                        </th>
                                        <td><?php if($sd_federation_multi->is_operate_retail==1){echo 'Yes';}else{echo 'No';}?>
                                        </td>


                                        </tr>
                                        
                                        <tr>

                                        <?php if($sd_federation_multi->is_operate_retail==1){ ?>
                                        <th scope="row">
                                            <?= __('13(e). Number of retail shops/outlets operated by the cooperative federation.') ?>
                                        </th>
                                        <td><?= $sd_federation_multi->no_of_retail ?>
                                        </td>
                                        <?php } ?>
                                        
                                        <?php if($sd_federation_multi->has_storage==1) { ?>
                                        <th scope="row">
                                            <?= __('13(f).Whether products of society are sale out of the area of operation of the cooperative federation') ?>
                                        </th>
                                        <td><?php if($sd_federation_multi->is_product_sale_out==1){echo 'Yes';}else{echo 'No';}?>
                                        </td>
                                        <?php } ?>

                                        <th scope="row">
                                            <?= __('14. Facility provided by cooperative federation?') ?>
                                        </th>
                                        <td>
                                        </td>


                                        </tr>



                                    </table>
                                </div>
                                <!-- <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_multi->authorised_share) ?? '' ?>
                                        </td>

                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                            
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_multi']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_multi']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_multi']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_multi->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(b). Whether the Cooperative society has Storage/GODOWN/Warehouse ? ') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_multi->has_storage==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                    
                                    </tr>

                                    
                                    <tr>
                                    <?php if($CooperativeRegistration->cooperative_registrations_multi->has_storage==1) { ?>
                                        <th scope="row"><?= __('13(b)(i).  Capacity of Storage/Godown/Warehouse operated by cooperative society (in MT)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_multi->storage_capacity) ?>
                                        </td>
                                        <?php } ?>

                                    
                                    </tr>
                                    
                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(c) .Whether the cooperative society provide raw material to members & produced take back from them for marketing?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_multi->provide_raw==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <?php if($CooperativeRegistration->cooperative_registrations_multi->provide_raw==1) { ?>
                                    
                                        <th scope="row">
                                            <?= __('13(d).Whether the regular guidance/training is provided by cooperative society to members?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_multi->guidance_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <?php } ?>
                                        
                                    

                                    </tr>

                                    <tr>
                                <th scope="row">
                                            <?= __('13(d)(i).Whether the cooperative society operate retail shops/outlet to sale their products? ') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_multi->is_operate_retail==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <?php if($CooperativeRegistration->cooperative_registrations_multi->is_operate_retail==1) { ?>
                                    
                                        <th scope="row">
                                            <?= __('13(k) .Number of retail shops/outlets operated by the cooperative society.') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_multi->no_of_retail) ?>
                                        </td>
                                        <?php } ?>
                                    
                                        
                                    

                                    </tr>

                                
                                    <tr>
                                    <th scope="row">
                                            <?= __('13(l) .Whether products are sale out of the area of operation of the cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_multi->is_product_sale_out==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                    <th scope="row">
                                            <?= __('14. Facility provided by cooperative society:') ?>
                                        </th>
                                        <td>
                                        
                                            <?php 
                                            $facility2 = ['1'=>'Rain water','2'=>'Ground water','3'=>'canal water'];
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_multi->facilities);
                                            
                                
                                                foreach($socityarray as $value)
                                                {
                                                    echo wordwrap( $societyFacilities[$value],3,",");
                                                }

                                                ?>
                                        
                                    </td>
                                        
                                    </tr>

                                </table> -->
                            </div>
                        </div>
                        <?php } ?>

                        <?php if($CooperativeRegistration->sector_of_operation==98)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary Social Welfare & Cultural Cooperative Society</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>

                                        
                                            
                                             <th scope="row">
                                                <?= __('11(a).Type of Social Welfare & Cooperative Society') ?>
                                            </th>
                                            <td>
                                            <?php 
                                           
                                           $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_social->type_society);
                                           
                                 
                                               foreach($socityarray as $value)
                                               {
                                                //    echo wordwrap($type_social[$value],3,",");
                                                //    echo "<br>";
                                                   echo $type_social[$value];
                                               }
   
                                       ?>    
                                                
                                            </td>
                                            <th scope="row">
                                            <?= __('11(b).Whether the co-operative society has an office building') ?>
                                        </th>
                                        <td><?php
                                                    if($CooperativeRegistration->cooperative_registrations_social->has_building==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                        </td>
                                            
                                           
                                        </tr>
                                        <tr>

                                            <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_social->has_building==1)
                                                        { ?>
                                            <th scope="row"><?= __('11(b)(i). Type of Office Building') ?></th>
                                            <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_social->building_type] ?? '') ?>
                                            </td>

                                            <?php } ?>

                                            <th scope="row">
                                            <?= __('11(c). Type of Social & Cultural activities are carried out by Cooperatives') ?>
                                        </th>
                                        <td>
                                           
                                            <?php 
                                            $socityarray = array_flip(explode(',',$CooperativeRegistration->cooperative_registrations_social->type_social_culture_activity));
                                              echo wordwrap(implode(", ",array_intersect_key($activitiestypes,$socityarray)),30,"<br>\n");
                                                ?>
                                        
                                       </td>


                                        </tr>



                                    </table>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_social->authorised_share) ?? '' ?>
                                        </td>
                                    </tr>
                                    <div class="clearfix"></div>
                                    <tr>
                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                             
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_social']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_social']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_social']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_social->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(b). Whether the Cooperative society has common/pooled resource for its social & cultural activities?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_social->has_common==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                    
                                    </tr>

                                    
                                    <tr>
                                    <th scope="row">
                                            <?= __('13(c). Whether the social & cultural activities are carried by member themself?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_social->is_operate_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                    <?php if($CooperativeRegistration->cooperative_registrations_social->is_operate_by_member==1) { ?>
                                        <th scope="row"><?= __('13(c)(i).  Whether the proper guidance/training is provided by cooperative society to members?') ?>
                                        </th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_social->guidance_by_member==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <?php } ?>

                                    
                                    </tr>
                                    
                                    <tr>
                                    <th scope="row">
                                            <?= __('13(d). Facility provided by cooperative society:') ?>
                                        </th>
                                        <td>
                                           
                                            <?php 
                                            $facility2 = ['1'=>'Rain water','2'=>'Ground water','3'=>'canal water'];
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_social->facilities);
                                            // $socityarray = array_flip(explode(',',$CooperativeRegistration->cooperative_registrations_social->facilities));
                                            //   echo wordwrap(implode(", ",array_intersect_key($facility,$socityarray)),30,"<br>\n");
                                  
                                                foreach($socityarray as $value)
                                                {
                                                    echo wordwrap( $societyFacilities[$value],3,",");
                                                }
    
                                                ?>
                                        
                                       </td>
                                       <th scope="row">
                                            <?= __('13(e) Whether the society is operating utility Vehicle(like ambulance,van,bus,goods container etc)?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_social->is_operate_vehicle==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                       
                                    

                                    </tr>

                                    <tr>
                                  
                                        <?php if($CooperativeRegistration->cooperative_registrations_social->is_operate_vehicle ==1) { ?>
                                     
                                        <th scope="row">
                                            <?= __('13(e)(i) Number of vehicles operated by the cooperative society.') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_social->no_of_vehicle) ?>
                                        </td>
                                        <?php } ?>
                                    
                                        
                                    

                                    </tr>

                                  
                                   

                                </table>
                            </div>
                        </div>
                       <?php } ?>


                       <?php if($CooperativeRegistration->sector_of_operation==35)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Miscellaneous Credit Federation</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>
                                            <th scope="row">
                                            <?= __('13(a). Total Deposits with the Federation (in Rs)') ?>
                                            </th>
                                            <td>
                                                <?php echo $sd_federation_cmiscellaneous->total_deposit ; ?>
                                            </td>

                                            <th scope="row">
                                            <?= __('13(b). Total Amount of Loan Outstanding (in Rs)') ?>
                                            </th>
                                            <td>
                                                <?php echo $sd_federation_cmiscellaneous->loan_outstanding ; ?>
                                            </td>
                                            
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                            <?= __('14. Facility provided by cooperative federation?') ?>
                                            </th>
                                            <td>
                                            <?= wordwrap(implode(", ",$misc_credit),30,'<br>'); ?>
                                            </td>
                                            
                                        </tr>
                                        



                                    </table>
                                </div>
                                <!-- <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table> -->


                                <!-- <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_cmiscellaneous->authorised_share) ?? '' ?>
                                        </td>
                                    </tr>
                                    <div class="clearfix"></div>
                                    <tr>
                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div>
                                            
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped box-primary">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_cmiscellaneous']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_cmiscellaneous']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_cmiscellaneous']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_cmiscellaneous->annual_turn_over) ?? '' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(b). Total Deposits with the SOCIETY (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_cmiscellaneous->total_deposit) ?? '' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(c). Total Amount of Loan Outstanding (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_cmiscellaneous->loan_outstanding) ?? '' ?>
                                        </td> 
                                    </tr>   
                                    
                                    <tr>
                                        <th><?= __('13(d). Members Detail') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div>
                                                <!-- /.box-header -->
                                                <!-- <div class="box-body table-responsive">

                                                    <table class="box-primary table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Type of Member') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Number of Members') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Individual</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_cmiscellaneous->individual_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Institutional</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_cmiscellaneous->institutional_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->members_of_society) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>

                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('14 Facilities provided by Cooperative Society') ?>
                                        </th>
                                        <td><?php 
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_cmiscellaneous->facilities);
                                            
                                  
                                                    foreach($socityarray as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                        </td>
                                    </tr>
                                

                                </table> -->
                            </div>
                        </div>
                        <?php } ?>
                       
                       <?php if($CooperativeRegistration->sector_of_operation==29)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Miscellaneous Non-Credit Cooperative Society</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>

                                        
                                            
                                            
                                            <th scope="row">
                                                <?= __('11(b).Whether the co-operative society has an office building') ?>
                                            </th>
                                            <td><?php
                                                        if($CooperativeRegistration->cooperative_registrations_miscellaneous->has_building==1)
                                                        {
                                                            echo 'Yes';
                                                        }else
                                                        {
                                                            echo 'No';
                                                        }

                                                        ?>
                                            </td>
                                            
                                            <?php  
                                                        if($CooperativeRegistration->cooperative_registrations_miscellaneous->has_building==1)
                                                        { ?>
                                            <th scope="row"><?= __('11(b)(i). Type of Office Building') ?></th>
                                            <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_miscellaneous->building_type] ?? '') ?>
                                            </td>

                                            <?php } ?>
                                        </tr>
                                        
                                    </table>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_miscellaneous->authorised_share) ?? '' ?>
                                        </td>
                                    </tr>
                                    <div class="clearfix"></div>
                                    <tr>
                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                            
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped table-width">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_miscellaneous']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_miscellaneous']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_miscellaneous']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_miscellaneous->annual_turn_over) ?? '' ?>
                                        </td>
                                    </tr>   

                                
                                

                                </table>
                            </div>
                        </div>
                        <?php } ?>


                        <?php if($CooperativeRegistration->sector_of_operation==102)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary Tribal SC/ST Cooperative Society Details</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>



                                            <th scope="row">
                                                <?= __('11(a).Type of SC/ST Cooperative') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_tribal->type_society ?? '') ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('11(b).Whether the co-operative society has an office building') ?>
                                            </th>
                                            <td><?php
                                                    if($CooperativeRegistration->cooperative_registrations_tribal->has_building==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>
                                        </tr>
                                        <tr>

                                            <?php  
                                                    if($CooperativeRegistration->cooperative_registrations_tribal->has_building==1)
                                                    { ?>
                                            <th scope="row"><?= __('11(b)(i). Type of Office Building') ?></th>
                                            <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_tribal->building_type] ?? '') ?>
                                            </td>

                                            <?php } ?>


                                        </tr>




                                    </table>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tribal->authorised_share) ?? '' ?>
                                        </td>

                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_tribal']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_tribal']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_tribal']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tribal->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('13(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_tribal->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>

                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_tribal->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('13(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tribal->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('13(c).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tribal->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_tribal->loan_other==1) { ?>
                                        <th scope="row">
                                            <?= __('13(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tribal->loan_from_other) ?>
                                        </td>
                                        <?php } ?>


                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(d) Whether the work is allotted by State/District Federation to the Society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tribal->state_district_federation==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('13(e).Whether the cooperative society provide raw material to members & finished products taken after processing?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tribal->society_provide_raw_material==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>



                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('13(f) Facilities provided by Cooperative Society') ?>
                                        </th>
                                        <td><?php 
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_tribal->facilities);
                                            
                                  
                                                    foreach($socityarray as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                        </td>
                                    </tr>






                                </table>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if($CooperativeRegistration->sector_of_operation==99)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary tourism Cooperative Society Details</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="overflow:auto; white-space: nowrap;">
                                    <table class=" table table-striped table-bordered">
                                        <tr>



                                            <th scope="row">
                                                <?= __('11(a).Type of Tourism Cooperative') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_tourism->type_society ?? '') ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('11(b).Whether the co-operative society has an office building') ?>
                                            </th>
                                            <td><?php
                                                    if($CooperativeRegistration->cooperative_registrations_tourism->has_building==1)
                                                    {
                                                        echo 'Yes';
                                                    }else
                                                    {
                                                        echo 'No';
                                                    }

                                                    ?>
                                            </td>
                                        </tr>
                                        <tr>

                                            <?php  
                                                    if($CooperativeRegistration->cooperative_registrations_tourism->has_building==1)
                                                    { ?>
                                            <th scope="row"><?= __('11(b)(i). Type of Office Building') ?></th>
                                            <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_tourism->building_type] ?? '') ?>
                                            </td>

                                            <?php } ?>


                                        </tr>




                                    </table>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>


                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"> <?= __('12. Member Details of the Cooperative Society:') ?>
                                        </th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Type of Member') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Number of Members') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Individual</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_tourism->individual_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Institutional</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_tourism->institutional_member) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td colspan="2" style="float:right;"><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->members_of_society) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>

                                    </tr>
                                    <tr>
                                        <th scope="row"><?= __('13(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tourism->authorised_share) ?? '' ?>
                                        </td>

                                        <th><?= __('13(b). Paid up Share Capital by different Entity') ?></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                                <!-- /.box-header -->
                                                <div class="box-body table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%"><?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%"><?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members test</td>

                                                                <td><?= $CooperativeRegistration['cooperative_registrations_tourism']['paid_up_members']?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_tourism']['paid_up_government_bodies']?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= $CooperativeRegistration['cooperative_registrations_tourism']['paid_up_total']?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                    </tr>





                                    <tr>
                                        <th scope=" row">
                                            <?= __('14(a). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tourism->annual_turn_over) ?? '' ?>
                                        </td>
                                        <th scope="row">
                                            <?= __('14(b). Whether the loan taken from the DCCB/UCB/PCARDB?') ?></th>
                                        <td> <?php if($CooperativeRegistration->cooperative_registrations_tourism->loan_dccb==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>

                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_tourism->loan_dccb==1) { ?>
                                        <th scope="row">
                                            <?= __('14(b)(i).  Loan and Advances taken from DCCB/UCB/PCARDB (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tourism->loan_from_dcb) ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row">
                                            <?= __('14(c).Whether the loan taken from Other Bank?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tourism->loan_other==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <?php if($CooperativeRegistration->cooperative_registrations_tourism->loan_other==1) { ?>
                                        <th scope="row">
                                            <?= __('14(c)(i). Loan and Advances taken from Bank (in Rs.)') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_tourism->loan_from_other) ?>
                                        </td>
                                        <?php } ?>


                                    </tr>

                                    <tr>
                                        <th scope=" row">
                                            <?= __('14(d) Do the members themselves have pooled resources (vehicles, accommodation, land etc.) for the cooperative society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tourism->pool_resource==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>

                                        <th scope="row">

                                            <?= wordwrap('14(e) Apart from the pooled/common resources of individuals, has the society taken any resources from the government or non-members?',60,'<br>') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tourism->any_resource_taken==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>



                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('14(f) Is the right vested with the members to take back the shared resources from the society?') ?>
                                        </th>
                                        <td><?php if($CooperativeRegistration->cooperative_registrations_tourism->is_right_vested==1) { echo "Yes";}else{ echo "No" ;} ?>
                                        </td>
                                        <th scope=" row">
                                            <?= __('14(g) Facilities provided by Cooperative Society') ?>
                                        </th>
                                        <td><?php 
                                            $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_tourism->facilities);
                                            
                                
                                                    foreach($socityarray as $value)
                                                    {
                                                        echo wordwrap($societyFacilities[$value],3,",");
                                                    }

                                                    ?>
                                        </td>
                                    </tr>






                                </table>
                            </div>
                        </div>
                        <?php } ?>




                        <?php if($CooperativeRegistration->sector_of_operation==51)  { ?>

                        <div class="box-block-m">
                            <div class="col-sm-12">
                                <h4><strong>Primary Labour Cooperative Federation Details</strong></h4>
                            </div>
                            <div class="view-table">
                                <div style="">
                                    <table class=" table table-striped table-bordered">
                                        <tr>
                                            <th scope="row"><?= __('13(a). Type of Labour Federation') ?></th>
                                            <td><?= h($lsocietytypes[$sd_federation_labour->type_society]) ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('13(b). Whether the work is alloted by State/District<br> Federation to the Society? ') ?>
                                            </th>
                                            <td><?php
                                                if($sd_federation_labour->work_allot_state_dist_federation == 1)
                                                {
                                                    echo 'Yes';
                                                }else
                                                {
                                                    echo 'No';
                                                }

                                                ?>
                                            </td>

                                            </tr>

                                            <tr>

                                            <th scope="row">
                                                <?= __('13(c). Whether the proper guidance/training for execution of the work <br>provided by State/District Federation?') ?>
                                            </th>
                                            <td><?php
                                                if($sd_federation_labour->work_guide_state_dist_federation == 1)
                                                {
                                                    echo 'Yes';
                                                }else
                                                {
                                                    echo 'No';
                                                }

                                                ?>
                                            </td>
                                        
                                            <th scope="row"><?= __('13(d). Whether cooperative availing any Concession provided by State Government? ') ?></th>
                                            <td><?php
                                                if($sd_federation_labour->concession_state_gov == 1)
                                                {
                                                    echo 'Yes';
                                                }else
                                                {
                                                    echo 'No';
                                                }

                                                ?>
                                            </td>

                                            </tr>

                                            <tr>
                                            <th scope="row">
                                                <?= __('13(e). Whether cooperative availing any Concession provided by Centre Government?') ?>
                                            </th>
                                            <td><?php
                                                if($sd_federation_labour->work_allot_state_dist_federation == 1)
                                                {
                                                    echo 'Yes';
                                                }else
                                                {
                                                    echo 'No';
                                                }

                                                ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('14. Facilities provided by Cooperative Federation') ?>
                                            </th>
                                            <td>
                                            <?= wordwrap(implode(", ",$lab_op),21,'<br>'); ?>
                                            </td>
                                        </tr>
                                        
                                        
                                    </table>
                                </div>
                               
                            </div>
                            <?php } ?>



                            <?php if($CooperativeRegistration->sector_of_operation==82)  { ?>

                            <div class="box-block-m">
                                <div class="col-sm-12">
                                    <h4><strong>Primary Marketing Cooperative Society Details</strong></h4>
                                </div>
                                <div class="view-table">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row">
                                                <?= __('13(a). Whether the cooperative federation has land') ?>
                                            </th>
                                            <td><?php
                                        if($sd_federation_marketing->has_land==1)
                                        {
                                            echo 'Yes';
                                        }else
                                        {
                                            echo 'No';
                                        }

                                        ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th><?= "13(b). Detail of Land Available with the Cooperative"; ?>
                                                <div class="box-primary box-st">
                                                     
                                                     <!-- /.box-header -->
                                                    <div class="box-body table-responsive">
                                                   
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" width="5%"><?= __('S No.') ?></th>
                                                                    <th scope="col" width="30%">
                                                                        <?= __('Type of possession') ?>
                                                                    </th>
                                                                    <th scope="col" width="30%">
                                                                        <?= __('Area (in Acre)') ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Owned Land</td>

                                                                    <td>
                                                                        <?php echo $sd_federation_lands->land_owned; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Leased Land</td>
                                                                    <td><?= h($sd_federation_lands->land_leased) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3.</td>
                                                                    <td>Land allotted by the Government</td>
                                                                    <td><?= h($sd_federation_lands->land_allotted) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2" style="float:right;"><b>Total</b>
                                                                    </td>
                                                                    <td><?= h($sd_federation_lands->land_total) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                
                                            </th>

                                        </tr>
                                            
                                        

                                        <!-- <tr>


                                            <th scope="row">
                                                <?= __('10(c). Whether the co-operative society has land') ?>
                                            </th>
                                            <td><?php if($CooperativeRegistration->cooperative_registrations_marketing->has_land==1) { echo "Yes";}else{ echo "No" ;} ?>
                                            </td>
                                            <th><?= __('10(d).  Detail of Land Available with the Cooperative') ?></th>
                                            <td></td>
                                            <th></th>
                                            <td></td>
                                        </tr> -->

                                        <!-- <th></th>
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        </tr> -->
                                        <!-- <tr>
                                            <th> <?php if($CooperativeRegistration->cooperative_registrations_marketing->has_land==1) { ?>
                                                <div class="box-primary box-st">
                                                    
                                                    <div class="box-body table-responsive">

                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                    <th scope="col" width="50%">
                                                                        <?= __('Type of possession') ?>
                                                                    </th>
                                                                    <th scope="col" width="40%">
                                                                        <?= __('Area (in Acre)') ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Owned Land</td>

                                                                    <td>
                                                                        <?= h($CooperativeRegistration->cooperative_registrations_land->land_owned) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Leased Land</td>
                                                                    <td><?= h($CooperativeRegistration->cooperative_registrations_land->land_leased) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3.</td>
                                                                    <td>Land allotted by the Government</td>
                                                                    <td><?= h($CooperativeRegistration->cooperative_registrations_land->land_allotted) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2" style="float:right;"><b>Total</b>
                                                                    </td>
                                                                    <td><?= h($CooperativeRegistration->cooperative_registrations_land->land_total) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </th>

                                        </tr> -->
                                        <tr>
                                            <th>13(c). Does the cooperative federation have warehouses or godowns?</th>
                                            <td><?php if($sd_federation_marketing->has_warehouses == 1) { echo "Yes";}else{ echo "No" ;} ?>
                                            </td>

                                            <?php if($sd_federation_marketing->has_warehouses==1) {?>
                                            <th>13(d). Capacity of the Warehouses or Godowns (MT):</th>
                                            <td><?= h($sd_federation_marketing->capacity_warehouses) ?? '' ?>
                                            <?php } ?>
                                        
                                        </tr>

                                        <tr>
                                        
                                            <th>14. Annual Expenses of the Cooperative Federation (in Rs)</th>
                                            <td><?= h($sd_federation_marketing->annual_expenses) ?? '' ?>
                                            </td>
                                        </tr>

                                        <tr>
                                        <th><?php echo "15. Details of the items for which cooperative federation has license to sell:" ; ?></th>
                                        </tr>
                                        <tr>
                                            <div class="col-sm-8 text-bold">
                                                
                                                <td>
                                                    <table class="table table-hover table-border table-bordered table-striped table-design-bg">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%">S No.</th>
                                                                <th scope="col" width="50%">Items</th>
                                                                <th scope="col" width="40%">Does the Cooperative Society have License to sell the item</th>
                                                                <th scope="col" width="40%">Does the Cooperative Society Sell the item</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Seeds</td>
                                                                <td>

                                                                    <?php if($sd_federation_marketing->liecense_to_sell==1) { echo "Yes";}else{ echo "No" ;} ?>

                                                                </td>
                                                                <td>
                                                                    <?php if($sd_federation_marketing->sell_the_item==1) { echo "Yes";}else{ echo "No" ;} ?>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Food grains Procurement on MSP</td>
                                                                <td>
                                                                    <?php $sqlvalue = $sd_federation_marketing->liecense_to_sell; $HiddenProducts = explode(',',$sqlvalue); 
                                                                   if(in_array(2, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php $sqlvalue2 = $sd_federation_marketing->sell_the_item; $HiddenProducts2 = explode(',',$sqlvalue2);
                                                             if(in_array(2, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.</td>
                                                                <td>PDS Shop</td>
                                                                <td><?php if(in_array(3, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(3, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>4.</td>
                                                                <td>Fertilizer</td>
                                                                <td><?php if(in_array(4, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(4, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>5.</td>
                                                                <td>Pesticide</td>
                                                                <td><?php if(in_array(5, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(5, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>6.</td>
                                                                <td>Agricultural Equipment on Rent</td>
                                                                <td><?php if(in_array(6, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(6, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </div>

                                        </tr>
                                    </table>

                                    <!-- <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row">
                                                <h3 class="servce-hading">Services/Facilities Provided to the Members
                                                </h3>
                                            </th>

                                        </tr>
                                    </table> -->

                                    <!-- <div class="view-table">
                                        <table class="table table-striped table-bordered">
                                            <div class="col-sm-3 text-bold">
                                                <th scope="row"><?= __('11(a). Authorised Share Capital (in Rs):') ?>
                                                </th>

                                            </div>
                                            <div class="col-sm-3 text-bold">
                                                <td><?= h($CooperativeRegistration->cooperative_registrations_marketing->authorised_share) ?? '' ?>
                                                </td>
                                            </div>

                                            <div class="col-sm-4 text-bold">
                                                <th><?= __('11(b). Paid up Share Capital by different Entity') ?></th>
                                            </div>
                                            <td></td>
                                            <th></th>
                                            <td></td>
                                            <th></th>
                                            <td></td>

                                            <div class="clearfix"></div>
                                            <div class="col-sm-6 text-bold">
                                                <div class="box-primary box-st">
                                                    <!-- /.box-header
                                                    <div class="box-body">
                                                        <table
                                                            class="table table-hover table-bordered table-striped table-border table-design-bg">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                    <th scope="col" width="50%">
                                                                        <?= __('Name of Entity') ?>
                                                                    </th>
                                                                    <th scope="col" width="40%">
                                                                        <?= __('Amount (in Rs)') ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Members test</td>

                                                                    <td><?= $CooperativeRegistration['cooperative_registrations_marketing']['paid_up_members']?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Government or Government Bodies</td>
                                                                    <td><?= $CooperativeRegistration['cooperative_registrations_marketing']['paid_up_government_bodies']?>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="border-right: none !important;"></td>
                                                                    <td style="border: none !important;"><b>Total</b>
                                                                    </td>
                                                                    <td><?= $CooperativeRegistration['cooperative_registrations_marketing']['paid_up_total']?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div> -->
                                                </div>

                                            </div>
                                            <td></td>
                                            <th></th>
                                            <td></td>
                                            <th></th>
                                            <td></td>
                                            <div class="clearfix"></div>

                                            <!-- <div class="col-sm-3 text-bold">
                                                <th scope="row">
                                                    <?= __('12(a). Annual Turn Over of the Cooperative Society (in Rs)2') ?>
                                                </th>
                                                <td><?= h($CooperativeRegistration->cooperative_registrations_marketing->annual_turn_over) ?? '' ?>
                                                </td>
                                            </div>
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-4 text-bold">
                                                <th scope="row">
                                                    <?= __('12(b). Annual Expenses of the Cooperative Society (in Rs)') ?>
                                                </th>
                                                <td> <?= h($CooperativeRegistration->cooperative_registrations_marketing->annual_expenses) ?? '' ?>
                                                </td>
                                            </div>
                                            <div class="clearfix">
                                            </div> -->





                                            <!-- <div class="col-sm-4 text-bold">
                                                <th scope="row">13(a).Details of the items for which co-operative
                                                    society
                                                    has
                                                    license to sell</th>
                                            </div> -->
                                            <!-- <div class="clearfix"></div> -->
                                            <!-- <div class="col-sm-8 text-bold">
                                                <td>
                                                    <table
                                                        class="table table-hover table-border table-bordered table-striped table-design-bg">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%">S No.</th>
                                                                <th scope="col" width="50%">Items</th>
                                                                <th scope="col" width="40%">Does the Cooperative
                                                                    <br>Society
                                                                    have
                                                                    License to sell the item
                                                                </th>
                                                                <th scope="col" width="40%">Does the Cooperative<br>
                                                                    Society
                                                                    Sell
                                                                    the item</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Seeds</td>
                                                                <td>

                                                                    <?php if($CooperativeRegistration->cooperative_registrations_marketing->liecense_to_sell==1) { echo "Yes";}else{ echo "No" ;} ?>

                                                                </td>
                                                                <td>
                                                                    <?php if($CooperativeRegistration->cooperative_registrations_marketing->sell_the_item==1) { echo "Yes";}else{ echo "No" ;} ?>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Food grains Procurement on MSP</td>
                                                                <td>
                                                                    <?php $sqlvalue = $CooperativeRegistration->cooperative_registrations_marketing->liecense_to_sell; $HiddenProducts = explode(',',$sqlvalue); 
                                                                   if(in_array(2, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php $sqlvalue2 = $CooperativeRegistration->cooperative_registrations_marketing->sell_the_item; $HiddenProducts2 = explode(',',$sqlvalue2);
                                                             if(in_array(2, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>3.</td>
                                                                <td>PDS Shop</td>
                                                                <td><?php if(in_array(3, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(3, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>4.</td>
                                                                <td>Fertilizer</td>
                                                                <td><?php if(in_array(4, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(4, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>5.</td>
                                                                <td>Pesticide</td>
                                                                <td><?php if(in_array(5, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(5, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>6.</td>
                                                                <td>Agricultural Equipment on Rent</td>
                                                                <td><?php if(in_array(6, $HiddenProducts)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                                <td><?php if(in_array(6, $HiddenProducts2)) { echo "Yes";}else{ echo "No" ;} ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </div> -->
                                            <!-- <th scope="row"></th>
                                            <td>
                                            </td> -->




                                        </table>
                                    </div>
                                </div>
                                <?php } ?>



                                <?php if($CooperativeRegistration->sector_of_operation==11)  {  

                                    $yesNo = ['No','Yes'];
                    // $stateCentral =['State','Central'];
                      ?>

                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Primary Sugar Cooperative (MILLS) Federation Details
                                </strong></h4>
                        </div>
                        <div class="view-table">
                            <div style="overflow:auto; white-space: nowrap;">
                                <table class=" table table-striped table-bordered">

                                    <tr>
                                        <th scope="row">
                                            <?= __('13(a). Number of Sugar mills or Manufacturing Plant operated by cooperative federation: ') ?>
                                        </th>
                                        <td><?= h($sd_federation_sugar->suger_mills_no) ?>
                                        </td>

                                        <!-- <?php if($CooperativeRegistration->cooperative_registrations_sugar->has_building==1) {?>
                                        <th scope="row">
                                            <?= __('11(b). Type of Office Building') ?>
                                        </th>
                                        <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_sugar->building_type ?? '']) ?>
                                        </td>
                                        <?php } ?> -->

                                    </tr>

                                    <tr>
                                    
                                    <tr>
                                        <th><?= __('13(b). Area of the Cooperative Sugar Mill(s):') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                               
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Area (in acre)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Build-up Area</td>

                                                                <td>
                                                                    <?= h($sd_federation_sugar->build_up_area) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Open Land Area</td>
                                                                <td><?= h($sd_federation_sugar->open_land_area) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($sd_federation_sugar->total_area) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>

                                    </tr>

                                    <!-- <tr>
                                        <th scope="row">
                                            <?= __('12(a). Authorised Share Capital (in Rs):') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_sugar->authorised_share ?? '') ?>
                                        </td>
                                    </tr> -->

                                    <!-- <tr>
                                        <th><?= __('12(b). Paid up Share Capital by different Entity') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                               
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Amount (in Rs)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Members</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_sugar->paid_up_members) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Government or Government Bodies</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_sugar->paid_up_government_bodies) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>	Total</b></td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_sugar->paid_up_total) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </th>
                                        
                                    </tr> -->

                                    <!-- <tr>
                                        <th scope="row">
                                            <?= __('13(a). Number of Sugar mills or Manufacturing Plant operated by cooperative society:') ?>
                                        </th>
                                        <td><?= h($CooperativeRegistration->cooperative_registrations_sugar->suger_mills_no ?? '') ?>
                                        </td>
                                    </tr> -->

                                    <!-- <tr>
                                        <th><?= __('13(b). Area of the Cooperative Sugar Mill(s):') ?></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="box-primary box-st">
                                               
                                                <div class="box-body table-responsive">

                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                <th scope="col" width="50%">
                                                                    <?= __('Name of Entity') ?>
                                                                </th>
                                                                <th scope="col" width="40%">
                                                                    <?= __('Area (in acre)') ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1.</td>
                                                                <td>Build-up Area</td>

                                                                <td>
                                                                    <?= h($CooperativeRegistration->cooperative_registrations_sugar->build_up_area) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2.</td>
                                                                <td>Open Land Area</td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_sugar->open_land_area) ?? '' ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total</b></td>
                                                                <td><?= h($CooperativeRegistration->cooperative_registrations_sugar->total_area) ?? '' ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </th>
                                    </tr> -->

                                    <tr>
                                        <th scope="row">
                                            <?= __('14(a). Liecensed Capicity (in term of Tonne Crushing per Day (TCD))') ?>
                                        </th>
                                        <td><?= h($sd_federation_sugar->liecensed_capicity ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('14(b). Installed Capicity (in term of Tonne Crushing per day (TCD))')   ?>
                                        </th>
                                        <td><?= h($sd_federation_sugar->installed_capicity ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?= __('14(c). Crushing period of sugarcane (in term of month)') ?>
                                        </th>
                                        <td><?php echo date_format($sd_federation_sugar->crushing_period_start,"d/m/Y");
                                        echo "  to  ";
                                       echo date_format($sd_federation_sugar->crushing_period_end,"d/m/Y"); ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?= __('15(a). Type of product produced by Cooperative Society') ?>
                                        </th>
                                        <td><?php $product_produced = ['1'=>'White Sugar','2'=>'Raw Sugar (Brown)','3'=>'Molasses','4'=>'Bagasse','5'=>'Pressmud','6'=>'Ethanol','7'=>'Biogas','8'=>'Rectified Spirit','9'=>'Extra Neutral Alcohol', '10'=>'Liquor','11'=>'compost','12'=>'Cogeneration','13'=>'Bio-CNG','14'=>'Fertilizer','15'=>'Particle Board','16'=>'Paper','17'=>'Acetic Acid','18'=>'Fossil Oil','19'=>'Other'];
                                        array_flip(explode(',',$sd_federation_sugar->product_produced));
                                              echo wordwrap(implode(", ",array_intersect_key($product_produced,array_flip(explode(",",$sd_federation_sugar->product_produced)))),20,"<br>\n"); ?>
                                        </td>

                                        <th scope="row">
                                            <?php echo ('15(b). Whether the cooperative society operate retail shops/outlet to sale products?');  ?>
                                        </th>
                                        <td><?= h($yesNo[$sd_federation_sugar->retail_shops] ?? '') ?>
                                        </td>

                                        <?php if($sd_federation_sugar->retail_shops == 1){ ?>
                                        <th scope="row">
                                            <?php echo wordwrap('15(c). Number of retail shops/outlets operated by the coopertive society',60,"<br>\n"); ?>
                                        </th>
                                        <td><?= h($sd_federation_sugar->retail_shops_no ?? '') ?>
                                        </td>
                                        <?php } ?>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?php echo wordwrap('16(a). Whether inputs for sugarcane cultivation are provided to members by the Cooperative Society',60,"<br>\n"); ?>
                                        </th>
                                        <td><?= h($yesNo[$sd_federation_sugar->sugercane_input_provided] ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?php echo wordwrap('16(b). Whether the facilities of Loans and Advances are offered to the Members?',60,"<br>\n"); ?>
                                        </th>
                                        <td><?= h($yesNo[$sd_federation_sugar->loan_facility] ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?php echo wordwrap('16(c). Whether the waste management facility is available in the Cooperative Mill?',60,"<br>\n"); ?>
                                        </th>
                                        <td><?= h($yesNo[$sd_federation_sugar->waste_management]?? ''); ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?php echo wordwrap('16(d). Whether any benefits (subsidy/scheme) received from Central Government?',60,"<br>\n");  ?>
                                        </th>
                                        <td><?= h($yesNo[$sd_federation_sugar->central_government_benefits] ?? '') ?>
                                        </td>

                                        <th scope="row">
                                            <?php echo wordwrap('16(e). Whether any benefits (subsidy/scheme) received from State Government?',60,"<br>\n"); ?>
                                        </th>
                                        <td><?= h($yesNo[$sd_federation_sugar->state_government_benefits] ?? '') ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                                <?php } ?>

                                <!-- start dairy tab -->
                                <?php //echo "<pre>";print_r($CooperativeRegistration); ?>

                                <?php if($CooperativeRegistration->sector_of_operation==9) { ?>
                                <div class="box-block-m">
                                    <div class="col-sm-12">
                                        <h4><strong>Dairy Details</strong></h4>
                                    </div>

                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row" style=" width: 435px;">
                                                <?= __('13. Annual Total Milk Collection (In Liters)') ?></th>
                                            <td width="5%">
                                                <?php //= h($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection ?? '')  ?>
                                                <?= h($sd_federation_dairy->milk_collection ?? '')  ?>
                                            </td>
                                            <th scope="row"></th>
                                            <td></td>
                                            <th scope="row"></th>
                                            <td> </td>
                                        </tr>
                                    </table>

                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row">
                                                <h3 class="servce-hading">Services/Facilities Provided to the Members
                                                </h3>
                                            </th>

                                        </tr>
                                    </table>

                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row"><?= __('14(a). Credit Facility') ?></th>
                                            <td><?php if($sd_federation_dairy->credit_facility == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                            <?php if($sd_federation_dairy->credit_facility == 1){ ?>
                                            <th scope="row"><?= __('14(b). Total Credit Provided in the Financial Year (In Rs.)') ?></th>
                                            <td><?= $sd_federation_dairy->credit_provided ?>
                                            </td>
                                            <?php } ?>

                                            <th scope="row"><?= __('14(c). Milk Collection Unit Facility') ?></th>
                                            <td><?php if($sd_federation_dairy->milk_collection_unit == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                        </tr>

                                        <tr>

                                            <?php if($sd_federation_dairy->milk_collection_unit == 1){ ?>
                                            <th scope="row"><?= __('14(d). Capacity of Milk Collection Unit (In Liters)') ?></th>
                                            <td><?= $sd_federation_dairy->milk_collection_capicity ?>
                                            </td>
                                            <?php } ?>
                                            
                                            <th scope="row"><?= __('14(e). Transportation of Milk') ?></th>
                                            <td><?php if($sd_federation_dairy->transport_milk == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                            <th scope="row"><?= __('14(f). Bulk Milk Cooling (BMC) Unit Facility') ?></th>
                                            <td><?php if($sd_federation_dairy->bulk_milk_unit == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <th scope="row"><?= __('14(g). Milk Testing Facility') ?></th>
                                            <td><?php if($sd_federation_dairy->milk_testing == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                            <th scope="row"><?= __('14(h). Pasteurization and Processing Facility ') ?></th>
                                            <td><?php if($sd_federation_dairy->processing == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                            <th scope="row"><?= __('14(i). Other Facilities Provided (Please Specify) ') ?></th>
                                            <td><?= $sd_federation_dairy->other_facility ?>
                                            </td>

                                        </tr>

                                        <!-- <tr>

                                            <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection_unit==1){ ?>
                                            <th scope="row">
                                                <?= __('11(d). Capacity of Milk Collection Unit (In Liters)') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection_capicity ?? '')  ?>
                                            </td>
                                            <?php } ?>

                                            <th scope="row"> <?= __('11(e). Transportation of Milk') ?></th>
                                            <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->transport_milk==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>

                                            <!--<th scope="row"> <?= __('11(d). Milk Collection Unit Facility') ?></th>
                                <td><?php //if($CooperativeRegistration->cooperative_registration_dairy[0]->transport_milk==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td> 
                                        </tr>
                                        <tr>
                                            <th scope="row"> <?= __('11(f). Bulk Milk Cooling (BMC) Unit Facility') ?>
                                            </th>
                                            <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->bulk_milk_unit==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>
                                            <th scope="row"> <?= __('11(g). Milk Testing Facility') ?></th>
                                            <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->milk_testing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>
                                            <th scope="row"> <?= __('11(h). Pasteurization and Processing Facility') ?>
                                            </th>
                                            <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->processing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>


                                        </tr> -->

                                        <!-- <tr>
                                            <th scope="row">
                                                <?= __('11(i). Other Facilities Provided (Please Specify)') ?>
                                            </th>
                                            <td> <?= h($CooperativeRegistration->cooperative_registration_dairy[0]->other_facility ?? '')  ?>
                                            </td>
                                            <th scope="row"> </th>
                                            <td> </td>
                                            <th scope="row"></th>
                                            <td> </td>
                                        </tr> -->
                                    </table>
                            </div>
                            <?php } ?>

                            <!-- end  -->

                            <!-- start fisher -->

                            <?php if($CooperativeRegistration->sector_of_operation==10) { ?>
                            <div class="box-block-m">
                                <div class="col-sm-12">
                                    <h4><strong>Fishery Details</strong></h4>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row" style=" width: 435px;">
                                            <?= __('13. Annual Total Fish Catch (in Tonne)') ?></th>
                                        <td width="82%">
                                            <?= h($sd_federation_fishery->annual_fish_catch ?? '')  ?>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr>
                                </table>

                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th scope="row"><?= __('14(a). Credit Facility') ?></th>
                                        <td><?php if($sd_federation_fishery->credit_facility==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                        </td>
                                        <?php if($sd_federation_fishery->credit_facility==1) { ?>
                                        <th scope="row">
                                            <?= __('14(b). Total Credit Provided in the Financial Year (In Rs.)') ?>
                                        </th>
                                        <td> <?= h($sd_federation_fishery->total_credit_provided ?? '')  ?>
                                        </td>
                                        <?php } ?>
                                        <th scope="row"> <?= __('14(c). Distribution of Fuel') ?> </th>
                                        <td> <?php if($sd_federation_fishery->fuel_distribution==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    <th scope="row"><?= __('14(d). Marketing') ?></th>
                                    <td><?php if($sd_federation_fishery->marketing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row"> <?= __('14(e). Cold Storage ') ?> </th>
                                    <td> <?php if($sd_federation_fishery->cold_storage==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>

                                    <th scope="row"> <?= __('14(f). Transportation') ?> </th>
                                    <td> <?php if($sd_federation_fishery->transportation==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                    </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?= __('14(g). Other Facilities Provided (Please Specify) ') ?>
                                        </th>
                                        <td width="5%">
                                            <?= h($sd_federation_fishery->other_facility ?? '')  ?>
                                        </td>
                                        <th scope="row"></th>
                                        <td></td>
                                        <th scope="row"></th>
                                        <td> </td>
                                    </tr>

                                </table>

                            </div>
                            <?php } ?>

                        

                    

                            <?php if($CooperativeRegistration->sector_of_operation==18) { ?>
                            <div class="box-block-m">
                                <div class="col-sm-12">
                                    <h4><strong>Primary Credit & Thrift Federation Details</strong></h4>
                                </div>
                                
                                <div class="view-table">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row"><?= __('13(a). Total Deposit (in Rs.)') ?>
                                            </th>
                                            <td>
                                                <?php echo  $sd_fed_credit->total_deposit  ; ?></td>
                                            
                                            <th scope="row"><?= __('13(b). Total Amount of Loan Outstanding (in Rs.)') ?> 
                                            </th>
                                            <td> <?= h($sd_fed_credit->pack_total_outstanding_loan); ?></td>
                                        </tr>

                                        <tr>
                                            <th scope="row"> <?= __('14. Facilities provided by Cooperative Federation') ?>
                                            </th>
                                            <td>
                                            <?= wordwrap(implode(", ",$credit_thrift_society),21,'<br>'); ?>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <?php } ?>


                            <?php if($CooperativeRegistration->sector_of_operation==80) { ?>
                            <div class="box-block-m">
                                <div class="col-sm-12">
                                    <h4><strong>Consumer Cooperative Federation Details</strong></h4>
                                </div>

                                <table class="table table-striped table-bordered">
                                    <tr>

                                        <th scope="row"> <?= __('13(a). Whether the cooperative federation is operating Own Departmental Store/Supermarket? ') ?>
                                        </th>
                                        <td><?php if($sd_federation_consumer->has_store == 1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                        </td>

                                    </tr>
                                    <tr>

                                    <?php if($sd_federation_consumer->has_store == 1){  ?>
                                        <th scope="row"> <?= __('13(b). No.of outlets/shops of Cooperative Federation: ') ?>
                                        </th>
                                        <td><?= $sd_federation_consumer->no_of_outlets ?>
                                        </td>
                                    <?php } ?>
                                    </tr>

                                    <tr>

                                        <th scope="row"> <?= __('14. Facilities provided by Cooperative Federation') ?>
                                        </th>
                                        <td><?= wordwrap(implode(", ",$consumer_co),21,'<br>'); ?>
                                        </td>

                                    </tr>
                                    <!-- <tr>
                                        <th scope="row">
                                            <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                        </th>

                                    </tr> -->
                                </table>
                                <!-- <div class="view-table"> 
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row">
                                                <?= __('10(a). Whether the co-operative society has an office building') ?>
                                            </th>
                                            <td><?php if($CooperativeRegistration->cooperative_registrations_consumer->has_building==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_consumer->has_building==1) { ?>
                                            <th scope="row">
                                                <?= __('10(b). Type of Office Building') ?> </th>
                                            <td> <?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_consumer->building_type])  ?>
                                            </td>
                                            <?php } ?>

                                            <th scope="row">
                                                <?= __('11(a). Whether the co-operative society is operating Own Departmental Store/Supermarket?') ?>
                                            </th>
                                            <td><?php if($CooperativeRegistration->cooperative_registrations_consumer->has_store==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_consumer->has_store==1) { ?>
                                            <th scope="row">
                                                <?= __('11(b).  No.of outlets/shops of Cooperative Society') ?> </th>
                                            <td> <?= h($CooperativeRegistration->cooperative_registrations_consumer->no_of_outlets)  ?>
                                            </td>
                                            <?php } ?>

                                        </tr>
                                        <tr>
                                            <th scope="row"> <?= __('12(a).  Authorised Share Capital (in Rs)') ?> </th>
                                            <td> <?= h($CooperativeRegistration->cooperative_registrations_consumer->authorised_share)  ?>

                                            </td>


                                            <th scope="row"><?= __('12(b). Paid up Share Capital (in Rs)') ?></th>
                                            <td> <?= h($CooperativeRegistration->cooperative_registrations_consumer->paid_up_share)  ?>

                                            </td>

                                            <th scope="row">
                                                <?= __('12(c). Annual Turn Over of the Cooperative Society (in Rs)') ?>
                                            </th>
                                            <td> <?= h($CooperativeRegistration->cooperative_registrations_consumer->annual_turn_over)  ?>

                                            </td>


                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <?= __('13(a). Facilities provided by Cooperative Society') ?>
                                            </th>
                                            <td width="5%"><?php 
                                        $socityarray = explode(',',$CooperativeRegistration->cooperative_registrations_consumer->facilities);
                                        
                                        $facilities = [
                                    '0'=>'Do not have basic amenities',
                                    '1'=>'Consumer Store/Fair Price Shop',
                                    '2'=>'Sports complex/playground/park',
                                    '3'=>'Community Hall',
                                    '4'=>'Health centre/Medical check-up camps',
                                    '5'=>'Transport service',
                                    '6'=>'Creches/common room',
                                    '7'=>'School',
                                    '8'=>'Shopping centre',
                                    '9'=>'Convention Centre',
                                    '10'=>'Auditorium'
                                ];
                                foreach($socityarray as $value)
                                {
                                    echo wordwrap($societyFacilities[$value],3,",");
                                }

                                ?>
                                            </td>
                                            <!-- <td width="5%">
                                                <?= h($CooperativeRegistration->cooperative_registrations_consumer->facilities)  ?>
                                            </td> -->
                                            <!-- <th scope="row"></th>
                                            <td></td>
                                            <th scope="row"></th>
                                            <td> </td>
                                        </tr>

                                    </table> 

                                </div>-->
                            </div>
                            <?php } ?>

                            <?php if($CooperativeRegistration->sector_of_operation==31) { 
                                //Processing
                                ?>
                            <div class="box-block-m">
                                <div class="col-sm-12">
                                    <h4><strong>Primary Processing/Industry Cooperative Federation Details</strong></h4>
                                </div>
                                <div class="view-table">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row">
                                                <?= __('13(a). Type of product produced by cooperative federation') ?>
                                            </th>
                                            <td><?= h($processing_tp[$sd_federation_processing->type_society]) ?></td>
                                        </tr>

                                        <tr>

                                            <th scope="row">
                                                <?= __('13(b). Whether the cooperative federation has <br>Processing Unit or Manufacturing Unit in Operation?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->processing_unit==1)
                                                {
                                                    echo "Yes";
                                                } 
                                                else{
                                                    echo "No";
                                                } ?>
                                            </td>
                                            
                                            <?php if($sd_federation_processing->processing_unit==1){ ?>
                                            <th scope="row">
                                                <?= __('13(c). Number of Processing Unit or <br>Manufacturing Unit operated by cooperative federation:') ?>
                                            </th>
                                            <td>
                                                <?= $sd_federation_processing->processing_unit_number ?>
                                            </td>
                                            <?php } ?>

                                            <th scope="row">
                                                <?= __('13(d). Whether the Processing or <br>Manufacturing work is done by members themself?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->processing_by_members==1){echo "Yes";} else {echo "No";} ?>
                                            </td>

                                        </tr>

                                        <tr>

                                        <?php if($sd_federation_processing->processing_by_members==1){ ?>
                                            <th scope="row">
                                                <?= __('13(e). Whether the work is divided among the members as per their skill?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->work_divided==1){echo "Yes";}else{echo "No";} ?>
                                            </td>
                                            <?php } ?>
                                            
                                            <th scope="row">
                                                <?= __('13(f). Whether the cooperative federation provide raw material<br>  to members &produced product taken after processing ?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->product_taken==1){echo "Yes";}else{echo "No";} ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('13(g). Whether the raw material<br> easily available to cooperative federation ?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->material_available==1){echo "Yes";} else {echo "No";} ?>
                                            </td>

                                        </tr>
                                        <tr>

                                            <th scope="row">
                                                <?= __('13(h). Whether the wastes are generated in production process ?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->wastes_generated==1)
                                                {
                                                    echo "Yes";
                                                } 
                                                else{
                                                    echo "No";
                                                } ?>
                                            </td>
                                            
                                            <?php if($sd_federation_processing->wastes_generated==1){ ?>
                                            <th scope="row">
                                                <?= __('13(i). Whether the waste management facility<br> is available in the federation ?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->processing_unit_number==1){echo "Yes";} else {echo "No";} ?>
                                            </td>
                                            <?php } ?>

                                            <th scope="row">
                                                <?= __('13(j). Whether the cooperative federation <br>operate retail shops/outlet to sale products ?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->operate_shops==1){echo "Yes";} else {echo "No";} ?>
                                            </td>

                                        </tr>
                                        <tr>

                                            <?php if($sd_federation_processing->operate_shops==1){ ?>
                                            <th scope="row">
                                                <?= __('13(k). Number of retail shops/outlets operated by the cooperative federation:') ?>
                                            </th>
                                            <td>
                                                <?= $sd_federation_processing->operate_shops_number ?>
                                            </td>
                                            <?php } ?>
                                            
                                            <th scope="row">
                                                <?= __('13(l). Whether products are sale out of the area of operation of the cooperative federation?') ?>
                                            </th>
                                            <td>
                                                <?php if($sd_federation_processing->product_sale_out_of_area==1)
                                                {
                                                    echo "Yes";
                                                } 
                                                else{
                                                    echo "No";
                                                } ?>
                                            </td>
                                            
                                        </tr>
                                        
                                        
                                        
                                        <!-- <tr>
                                            <th>
                                                <div class="box-primary box-st">
                                                    <!-- /.box-header --
                                                    <div class="box-body table-responsive">

                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                    <th scope="col" width="50%">
                                                                        <?= __('Type of Member') ?>
                                                                    </th>
                                                                    <th scope="col" width="40%">
                                                                        <?= __('Number of Members') ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Individual</td>

                                                                    <td>
                                                                        <?= h($CooperativeRegistration->cooperative_registrations_processing->individual_member) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Institutional</td>
                                                                    <td><?= h($CooperativeRegistration->cooperative_registrations_processing->institutional_member) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2" style="float:right;"><b>Total</b>
                                                                    </td>
                                                                    <td><?= h($CooperativeRegistration->cooperative_registrations_processing->total_member) ?? '' ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </th>

                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('10(b). Type of product produced by cooperative society') ?>
                                            </th>
                                            <?php $processing_products = [
                                        '1' => 'Agriculture & Allied',
                                        '2' => 'Non-Agriculture'
                                    ];  ?>
                                            <td><?= h($processing_products[$CooperativeRegistration->cooperative_registrations_processing->type_society]) ?? '' ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <?= __('10(c). Whether the co-operative society has an office building') ?>
                                            </th>
                                            <td><?php
                                        if($CooperativeRegistration->cooperative_registrations_processing->has_building==1)
                                        {
                                            echo 'Yes';
                                        }else
                                        {
                                            echo 'No';
                                        }

                                        ?>
                                            </td>
                                            <?php  
                                        if($CooperativeRegistration->cooperative_registrations_processing->has_building==1)
                                        { ?>
                                            <th scope="row"><?= __('10(d). Type of Office Building') ?></th>
                                            <td><?= h($buildingTypes[$CooperativeRegistration->cooperative_registrations_processing->building_type] ?? '') ?>
                                            </td>

                                            <?php } ?>
                                        </tr>

                                    </table>

                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th scope="row"><?= __('11(a). Authorised Share Capital (in Rs):') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->authorised_share) ?? '' ?>
                                            </td>

                                            <th><?= __('11(b). Paid up Share Capital by different Entity') ?></th>
                                            <td></td>

                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="box-primary box-st">
                                                    <!-- /.box-header --
                                                    <div class="box-body table-responsive">
                                                        <table class="table table-hover table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" width="10%"><?= __('S No.') ?></th>
                                                                    <th scope="col" width="50%">
                                                                        <?= __('Name of Entity') ?>
                                                                    </th>
                                                                    <th scope="col" width="40%">
                                                                        <?= __('Amount (in Rs)') ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Members</td>

                                                                    <td><?= $CooperativeRegistration['cooperative_registrations_processing']['paid_up_members']?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Government or Government Bodies</td>
                                                                    <td><?= $CooperativeRegistration['cooperative_registrations_processing']['paid_up_government_bodies']?>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td></td>
                                                                    <td><b>Total</b></td>
                                                                    <td><?= $CooperativeRegistration['cooperative_registrations_processing']['paid_up_total']?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </th>
                                            <td></td>
                                            <th></th>
                                            <td></td>
                                            <th></th>
                                            <td></td>
                                        </tr>


                                        </tr>

                                        <tr>
                                            <th scope=" row">
                                                <?= __('12(a). Annual Turn Over of the Cooperative Society (in Rs)3') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->annual_turn_over) ?? '' ?>
                                            </td>


                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(b). Whether the loan taken from the DCCB/UCB/PCARDB') ?></th>
                                            <td> <?= h($CooperativeRegistration->cooperative_registrations_processing->loan_from_dccb == '1' ? 'yes' : 'No') ?>
                                            </td>

                                            <?php if($CooperativeRegistration->cooperative_registrations_processing->loan_from_dccb=='1') { ?>
                                            <th scope="row">
                                                <?= __('12(c). Loan and Advances taken from DCCB/UCB/PCARDB (in Rs):') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->dccb_loan_amount) ?? '' ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <?= __('12(d). Whether the loan taken from Other Bank?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->loan_from_other == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_processing->loan_from_other=='1') { ?>
                                            <th scope="row">
                                                <?= __('12(e). Loan and Advances taken from Other Bank (in Rs):') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->loan_from_other_amount) ?? '' ?>
                                            </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(f). Whether the cooperative society has Processing Unit or Manufacturing Unit in Operation?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->processing_unit == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_processing->processing_unit=='1') { ?>
                                            <th scope="row">
                                                <?= __('12(g). Number of Processing Unit or Manufacturing Unit operated by cooperative society:') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->processing_unit_number) ?? '' ?>
                                            </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(h). Whether the Processing or Manufacturing work is done by members themself?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->processing_by_members == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_processing->processing_by_members=='1') { ?>
                                            <th scope="row">
                                                <?= __('12(i). Whether the work is divided among the members as per their skill?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->work_divided == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(j). Whether the cooperative society provide raw material to members & produced product taken after processing ?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->product_taken == '1' ? 'Yes' : 'No') ?>
                                            </td>

                                            <th scope="row">
                                                <?= __('12(k). Whether the raw material easily available to cooperative society ?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->material_available == '1' ? 'Yes' : 'No') ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(l). Whether the wastes are generated in production process ?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->wastes_generated == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_processing->wastes_generated=='1') { ?>
                                            <th scope="row">
                                                <?= __('12(m). Whether the waste management facility is available in the society ?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->waste_management_facility == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(n). Whether the cooperative society operate retail shops/outlet to sale products ?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->operate_shops == '1' ? 'Yes' : 'No') ?>
                                            </td>
                                            <?php if($CooperativeRegistration->cooperative_registrations_processing->operate_shops=='1') { ?>
                                            <th scope="row">
                                                <?= __('12(o). Number of retail shops/outlets operated by the cooperative society:') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->operate_shops_number) ?? '' ?>
                                            </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <?= __('12(p). Whether products are sale out of the area of operation of the cooperative society?') ?>
                                            </th>
                                            <td><?= h($CooperativeRegistration->cooperative_registrations_processing->product_sale_out_of_area == '1' ? 'Yes' : 'No') ?>
                                            </td>

                                        </tr> -->
                                    </table>



                                </div>
                            </div>

                            <?php } ?>

                            

                            <!-- /.box-body -->
                        <!-- </div> -->

                        



                        <!-- <?php if($CooperativeRegistration->is_approved == 0 && $this->request->session()->read('Auth.User.role_id') == 8){ ?>
                        <div class="approval">
                            <?= $this->Form->create('approval',['action'=>'approval']); ?>
                            <div class="col-md-12">
                                <label style="margin-left:15px;">Remarks</label>
                            </div>
                            <div class="row1 col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <textarea id="remark" name="remark" rows="8" cols="100" required></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row2 col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="hidden" name="cooperative_registration_id"
                                            value="<?= $CooperativeRegistration->id ?>" />
                                        <button name="is_approved" value="1" type="submit"
                                            class="btn btn-primary accept btn-green" formnovalidate>Accept</button>
                                        <button name="is_approved" value="2" type="submit"
                                            class="btn btn-danger reject btn-green">Return For Correction</button>
                                    </div>
                                </div>
                            </div>
                            <?= $this->Form->end(); ?>
                        </div>
                        <?php } 
                      if(($this->request->session()->read('Auth.User.role_id') == 2 || $this->request->session()->read('Auth.User.role_id') == 14)  && $CooperativeRegistration->is_approved != 0) { ?>
                        <div class="approval">
                            <?= $this->Form->create('approval',['action'=>'approval']); ?>
                            <div class="col-md-12">
                                <label style="margin-left:15px;">Remarks</label>
                            </div>
                            <div class="row1 col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <textarea id="remark" name="remark" rows="8" cols="100" required></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row2 col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="hidden" name="cooperative_registration_id"
                                            value="<?= $CooperativeRegistration->id ?>" />
                                        <?php if($CooperativeRegistration->is_approved == 2){ //Rejected ?>
                                        <button name="is_approved" value="1" type="submit"
                                            class="btn btn-primary accept btn-green" formnovalidate>Accept</button>
                                        <?php } 
                                if($CooperativeRegistration->is_approved == 1){//Accepted
                            ?>
                                        <button name="is_approved" value="2" type="submit"
                                            class="btn btn-danger reject btn-green">Return For Correction</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?= $this->Form->end(); ?>
                        </div>
                        <?php } ?> -->
                        <!-- /.box -->
                    </div>
                    <!-- ./col -->
                </div>
                <!-- div -->

                


</section>



<style>
.view-table {
    width: 100%;
    overflow-x: auto;
}

.view-span-break {
    white-space: normal !important;
}
</style>