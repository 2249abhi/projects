<?php
/**
 * @author \Abhay Singh
 * @version \1.1
 * @since \July 07, 2020, 10:44 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
$this->assign('title',__('Cooperative Details of ( ' . $CooperativeRegistration->cooperative_society_name. ' ) '));
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
    if($CooperativeRegistration->is_approved == 0)
    {
     $link = '/pending';
    } else if($CooperativeRegistration->is_approved == 1)
    {
     $link = '/accepted';
    } else {
     $link = '/rejected';
    }
 
} elseif($this->request->session()->read('Auth.User.role_id') == 2 || $this->request->session()->read('Auth.User.role_id') == 14) {
    //admin
    if($CooperativeRegistration->is_approved == 0)
    {
     $link = '/admin-pending';
    } else if($CooperativeRegistration->is_approved == 1)
    {
     $link = '/admin-accepted';
    } else {
     $link = '/admin-rejected';
    }
} else {
    $link = '/index';
}
?>

<!-- Main content -->
<section class="content role view">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-info"></i>
                    <h3 class="box-title">
                        <?= __('Cooperative Details of ( ' . $CooperativeRegistration->cooperative_society_name . ' ) ') ?>
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => $link],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Cooperative Registrations'),'escape' => false]
                  );
                  if($this->request->session()->read('Auth.User.role_id') != 8){
                  ?>
                        <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit',$CooperativeRegistration->id ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                        <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $CooperativeRegistration->id],
                    ['confirm' => __('Are you sure you want to delete this Cooperative Registration?', $CooperativeRegistration->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )
                            
                ?>
                <?php } ?>
                    </div>
                </div>

                <?php 
                       ?>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="boxk">
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
                                <td width="30%"><?= h($CooperativeRegistration->cooperative_society_name) ?></td>

                                <th scope="row" width="20%"><?= __('1(b).Registration Authority') ?></th>
                                <td width="20%">
                                    <?= h($regi_authorities[$CooperativeRegistration->registration_authoritie_id]) ?>
                                </td>

                                <th scope="row" width="20%"><?= __('1(c). Cooperative ID') ?></th>
                                <td width="30%"><?= h($CooperativeRegistration->cooperative_id) ?></td>


                            </tr>

                            <tr>
                                <th scope="row"><?= __('1(d). Reference Year') ?></th>
                                <td><?= h($CooperativeRegistration->reference_year) ?></td>
                                <th scope="row"><?= __('1(e). Date of Registration') ?></th>
                                <td><?= date("Y",strtotime($CooperativeRegistration->date_registration))=="1970"?"":date("d/m/Y",strtotime($CooperativeRegistration->date_registration))?>
                                </td>

                                <th scope="row"><?= __('1(f). Registration Number') ?></th>
                                <td><?= h($CooperativeRegistration->registration_number) ?></td>
                            </tr>

                        </table>
                    </div>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Location Details of Registration Office</strong></h4>
                        </div>


                        <table class="table table-striped table-bordered">

                            <tr>
                                <th scope="row"><?= __('2(a). Location of Registered Office') ?></th>
                                <td><?= h($locationOfHeadquarter[$CooperativeRegistration->location_of_head_quarter] ?? '') ?>
                                </td>

                                <th scope="row"><?= __('2(b).  State or UT') ?></th>
                                <td><?= h($states[$CooperativeRegistration->state_code] ?? '') ?></td>
                                <?php if($CooperativeRegistration->location_of_head_quarter == 2){ ?>
                                <th scope="row"><?= __('2(c). District') ?></th>
                                <td><?= h($districtName ?? '') ?></td>
                                <?php } ?>
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
                                <th scope="row"><?= __('2(c). Category of Urban Local Body') ?></th>
                                <td><?=@$localbody_types[$CooperativeRegistration->urban_local_body_type_code]?></td>

                                <th scope="row"><?= __('2(d). Urban Local Body') ?></th>
                                <td><?=@$localbodies[$CooperativeRegistration->urban_local_body_code]?></td>

                                <th scope="row"><?= __('2(e). Locality or Ward') ?></th>
                                <td><?=@$localbodieswards[$CooperativeRegistration->locality_ward_code]?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <?php if($CooperativeRegistration->location_of_head_quarter == 1){ ?>
                                <th scope="row"><?= __('2(f). Pin Code') ?></th>
                                <td><?= h($CooperativeRegistration->pincode) ?></td>
                                <?php } else{ ?>
                                <th scope="row"><?= __('2(g). Pin Code') ?></th>
                                <td><?= h($CooperativeRegistration->pincode) ?></td>
                                <?php } ?>
                                <th></th>
                                <td></td>
                                <th></th>
                                <td></td>

                            </tr>


                        </table>



                    </div>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Contact Details</strong></h4>
                        </div>
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

                    <?php //print_r($gps); ?>
                    <div class="box-block-m">
                        <div class="col-sm-12">
                            <h4><strong>Sector Details</strong></h4>
                        </div>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th scope="row"><?= __('4(a). Sector of Operation') ?></th>
                                <td><?= h($operationSector[$CooperativeRegistration->sector_of_operation_type] ?? '') ?>
                                </td>
                                <th scope="row"><?= __('4(b). Primary Activity') ?></th>

                                <td><?= h($creditPrimaryActivities[$CooperativeRegistration->sector_of_operation] ?? '') ?>
                                </td>
                                
                            </tr>
                            <tr>
                                <th scope="row"> <?= __('5(a). Tier of the Cooperative Society') ?></th>
                                <td><?= h($CooperativeSocietyTypes[$CooperativeRegistration->cooperative_society_type_id] ?? '') ?></td>
                                <th scope="row"> <?= __('5(b). Operation Area Location') ?></th>
                                <?php $o_a_location = ['1'=>'Urban','2'=>'Rural','3'=>'Both']; ?>
                                <td><?= h($o_a_location[$CooperativeRegistration->operation_area_location] ?? '') ?></td>
                                
                            </tr>

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
                                    <th scope="row"><?= __('5(b). Area of operation Urban ') ?><i class="fa fa-info-circle"
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
                                                    $ward .= $localbodieswards[$ward_data];
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
                                    <th scope="row"><?= __('5(b). Area of operation Rural ') ?><i class="fa fa-info-circle"
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


                            <tr>
                                <th scope="row">
                                    <?= __('5(d). Whether Area of Operation (Village, Panchayat, Block or Mandal or Town, Taluka/District) is adjacent to or includes water body') ?>
                                </th>
                                <td><?php if($CooperativeRegistration->is_coastal == '1') { echo 'Yes';
                             
                             }else{ echo "No";}  ?></td>
                                <?php if($CooperativeRegistration->is_coastal == '1') { ?>
                                <th scope="row"><?= __('5(e). Type of water body') ?> </th>
                                <td><?= h($water_body_type[$CooperativeRegistration->water_body_type_id]) ?></td>
                                <?php }  ?>
                                <th scope="row">
                                    <?= __('5(f). Whether the cooperative society is affiliated to Union/Federation') ?>
                                <td><?php if($CooperativeRegistration->is_affiliated_union_federation==1){ echo "Yes";} else{ echo "No"; }  ?>
                                </td>

                            </tr>


                            <tr>
                                <?php if($CooperativeRegistration->is_affiliated_union_federation==1){ ?>

                                <th scope="row"><?= __('5(g). Level of Affiliated Union/Federation') ?></th>
                                <td><?= h($level_of_aff_union[$CooperativeRegistration->affiliated_union_federation_level]) ?>
                                </td>
                                <th scope="row"><?= __('5(h). Name of Affiliated Union/Federation') ?> </th>
                                <td><?= h($dist_central_bannk[$CooperativeRegistration->affiliated_union_federation_name]) ?>
                                </td>
                                <?php } ?>
                                <th scope="row"><?= __('6. Present Functional Status') ?>
                                <td><?= h($presentFunctionalStatus[$CooperativeRegistration->functional_status]) ?></td>

                            </tr>

                            <tr>
                                <th scope="row"><?= __('7. Number of Members of the Cooperative Society') ?></th>
                                <td><?= h($CooperativeRegistration->members_of_society) ?></td>
                                <th scope="row"><?= __('8(a). Year of Latest Audit Completed') ?>
                                    
                                </th>
                                <td><?= h($CooperativeRegistration->audit_complete_year) ?>
                                     <br><span>Note: - Data after 8(a) will be based on Latest Audit Year</span>
                                </td>
                                <th scope="row"><?= __('8(b). Category of Audit') ?>

                                    <?php $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score']; ?>
                                <td><?= h($categoryAudit[$CooperativeRegistration->category_audit]) ?></td>

                            </tr>

                            <tr>
                                <th scope="row"><?= __('9(a). Whether the Cooperative Society is profit making?') ?>
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
                                <th scope="row"><?= __('9(b). Net Profit of the Cooperative Society') ?> </th>
                                <td><?= h($CooperativeRegistration->annual_turnover) ?></td>
                                <?php }  ?>
                                <th scope="row"><?= __('9(c). Whether the dividend paid by the Cooperative Society?') ?>
                                <td><?php if($CooperativeRegistration->is_dividend_paid==1){ echo 'Yes';} else{ echo "No"; } ?>
                                </td>

                            </tr>
                            <?php if($CooperativeRegistration->is_dividend_paid==1){ ?>

                            <tr>
                                <th scope="row"><?= __('9(d). Dividend Rate Paid by the Cooperative Society (in %)') ?>
                                </th>
                                <td> <?= h($CooperativeRegistration->dividend_rate) ?></td>
                            <tr>

                                <?php } ?>






                        </table>
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
                                <th scope="row"><?= __('11(j). Capacity of Dry Storage Infrastructure (In MT)') ?></th>
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
                                    <?= __('11(n). Annual Milk Collection by Cooperative Society (In Liters)') ?></th>
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
                    <?php } ?>

                    <!-- end pack detail  -->

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
                                    <?= __('10. Annual Total Milk Collection (In Liters)') ?></th>
                                <td width="5%">
                                    <?= h($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection ?? '')  ?>
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
                                    <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                </th>

                            </tr>
                        </table>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th scope="row"><?= __('11(a). Credit Facility') ?></th>
                                <td><?php if($CooperativeRegistration->cooperative_registration_dairy[0]->credit_facility==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>
                                <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->credit_facility==1){ ?>
                                <th scope="row">
                                    <?= __('11(b). Total Credit Provided in the Financial Year (In Rs.)') ?></th>
                                <td> <?= h($CooperativeRegistration->cooperative_registration_dairy[0]->credit_provided ?? '')  ?>
                                </td>
                                <?php }  ?>
                                <th scope="row"> <?= __('11(c). Milk Collection Unit Facility') ?></th>
                                <td><?php if($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection_unit==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>
                            </tr>

                            <tr>

                                <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection_unit==1){ ?>
                                <th scope="row"><?= __('11(d). Capacity of Milk Collection Unit (In Liters)') ?></th>
                                <td><?= h($CooperativeRegistration->cooperative_registration_dairy[0]->milk_collection_capicity ?? '')  ?>
                                </td>
                                <?php } ?>

                                <th scope="row"> <?= __('11(e). Transportation of Milk') ?></th>
                                <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->transport_milk==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>

                                <!--<th scope="row"> <?= __('11(d). Milk Collection Unit Facility') ?></th>
                                <td><?php //if($CooperativeRegistration->cooperative_registration_dairy[0]->transport_milk==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td> -->
                            </tr>
                            <tr>
                                <th scope="row"> <?= __('11(f). Bulk Milk Cooling (BMC) Unit Facility') ?></th>
                                <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->bulk_milk_unit==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>
                                <th scope="row"> <?= __('11(g). Milk Testing Facility') ?></th>
                                <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->milk_testing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>
                                <th scope="row"> <?= __('11(h). Pasteurization and Processing Facility') ?></th>
                                <td> <?php if($CooperativeRegistration->cooperative_registration_dairy[0]->processing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>


                            </tr>

                            <tr>
                                <th scope="row"> <?= __('11(i). Other Facilities Provided (Please Specify)') ?></th>
                                <td> <?= h($CooperativeRegistration->cooperative_registration_dairy[0]->other_facility ?? '')  ?>
                                </td>
                                <th scope="row"> </th>
                                <td> </td>
                                <th scope="row"></th>
                                <td> </td>
                            </tr>
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
                                    <?= __('10. Annual Total Fish Catch (in Tonne)') ?></th>
                                <td width="5%">
                                    <?= h($CooperativeRegistration->cooperative_registration_fishery[0]->annual_fish_catch ?? '')  ?>
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
                                    <h3 class="servce-hading">Services/Facilities Provided to the Members</h3>
                                </th>

                            </tr>
                        </table>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th scope="row"><?= __('11(a). Credit Facility') ?></th>
                                <td><?php if($CooperativeRegistration->cooperative_registration_fishery[0]->credit_facility==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>
                                <?php if($CooperativeRegistration->cooperative_registration_fishery[0]->credit_facility==1) { ?>
                                <th scope="row">
                                    <?= __('11(b). Total Credit Provided in the Financial Year (In Rs.)') ?> </th>
                                <td> <?= h($CooperativeRegistration->cooperative_registration_fishery[0]->total_credit_provided ?? '')  ?>
                                </td>
                                <?php } ?>
                                <th scope="row"> <?= __('11(c). Distribution of Fuel') ?> </th>
                                <td> <?php if($CooperativeRegistration->cooperative_registration_fishery[0]->fuel_distribution==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                                </td>
                            </tr>

                            <th scope="row"><?= __('11(d). Marketing') ?></th>
                            <td><?php if($CooperativeRegistration->cooperative_registration_fishery[0]->marketing==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                            </td>

                            <th scope="row"> <?= __('11(e). Cold Storage') ?> </th>
                            <td> <?php if($CooperativeRegistration->cooperative_registration_fishery[0]->cold_storage==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                            </td>

                            <th scope="row"> <?= __('11(f). Transportation') ?> </th>
                            <td> <?php if($CooperativeRegistration->cooperative_registration_fishery[0]->transportation==1){ echo "Yes" ;} else{ echo "No" ;} ?>
                            </td>
                            </tr>
                            <tr>
                                <th scope="row"><?= __('11(g). Other Facilities Provided (Please Specify))') ?></th>
                                <td width="5%">
                                    <?= h($CooperativeRegistration->cooperative_registration_fishery[0]->other_facility ?? '')  ?>
                                </td>
                                <th scope="row"></th>
                                <td></td>
                                <th scope="row"></th>
                                <td> </td>
                            </tr>

                        </table>

                    </div>
                    <?php } ?>


                    <!-- end fisher -->

                </div>
                <!-- /.box-body -->
            </div>
            <?php if($CooperativeRegistration->is_approved == 0 && $this->request->session()->read('Auth.User.role_id') == 8){ ?>
            <div class="approval">
            <?= $this->Form->create('approval',['action'=>'approval']); ?>
            <div class="col-md-12">
                <label style="margin-left:15px;">Remarks</label></div>
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
                            <input type="hidden" name="cooperative_registration_id" value="<?= $CooperativeRegistration->id ?>"/>
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
                            <input type="hidden" name="cooperative_registration_id" value="<?= $CooperativeRegistration->id ?>" />
                            <?php if($CooperativeRegistration->is_approved == 2){ //Rejected ?>
                            <button name="is_approved" value="1" type="submit" class="btn btn-primary accept btn-green" formnovalidate>Accept</button>
                            <?php } 
                                if($CooperativeRegistration->is_approved == 1){//Accepted
                            ?>
                            <button name="is_approved" value="2" type="submit" class="btn btn-danger reject btn-green">Return For Correction</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </div>
            <?php } ?>
            <!-- /.box -->
        </div>
        <!-- ./col -->
    </div>
    <!-- div -->
</section>