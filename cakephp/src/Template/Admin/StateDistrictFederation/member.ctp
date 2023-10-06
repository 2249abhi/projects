<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration $CooperativeRegistration
 */
$this->assign('title',__('Member of the Federation'));
$this->assign('content_header',__(' Member of the Federation'));

if($StateDistrictFederation->is_draft == 1){
    $form_status = "draft"; 
} else{
    $form_status = "list";
}

// echo "<pre>";
//  print_r($StateDistrictFederation);die;
?>

<section class="content Cooperative Registration add form add_cop_res design_row-sect">
    <div class="row">

        <div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title bold-b">
                        <?= __('Member of the Federation') ?>
                    </h3>
                    <div class="box-tools pull-right">
                        <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => $form_status],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Cooperative Federation List'),'escape' => false]
                  );?>
                    </div>
                </div>
                <div class="box-body">      
                    <div class="box-block-m">

                        <!-- <div class="col-sm-3">
                            <label>Number of Members<span class="important-field">*</span></label>
                            <?=$this->Form->control('primary_cooperative_member_count',['type'=>'textbox','class'=>'number','placeholder'=>'Total number of Members','label'=>false,'value'=>$StateDistrictFederation->primary_cooperative_member_count])?>
                        </div> -->

                        <!-- <div class="col-md-2"><label>Number of Members</label></div>
                        <div class="col-md-1">
                            <div class="form-group">
                              <b><?=count($cooperative_society_name_arr)?></b>
                            </div>
                        </div> --> 


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
                                                    State Name
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
                            // echo "<pre>";
                            // print_r($value); die;


                                foreach($sd_federation_members as  $sd_federation_member){
        
                                            if($sd_federation_member->cooperative_registration_id == $value['id'])
                                                {
                                                    $check = 'checked="true"';
                                                    break;
                                                }else{
                                                    $check = '';
                                                }
                                        }
                                        echo '<tr><td>'.($key+1).'</td><td>'.$value['cooperative_society_name'].'</td><td>'.$states[$value['state_code']].'</td><td>'.$districts[$value['district_code']].'</td><td>'.$value['registration_number'].'</td><td>'.$value['date_registration'].'</td><td> <input type="checkbox"  id="member-checked" '.$check.' name="sd_federation_members['.$key.']" value="'.$value->id.'" onclick = "return false"> </td></tr>';

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
                    </div>



            </div>
        </div>
    </div>
    </div>
</section>
