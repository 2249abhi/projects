<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\National Federation[]|\Cake\Collection\CollectionInterface $NationalFederations
 */

?>
<style type="text/css"> 

#code{
    float: right;
    margin-top: 26px;
}
</style>
<!-- Main content -->
<section class="content state Cooperative Federation list">
    <div class="row">
        <div>
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title titleSrch"><?= __('State District Federation'); ?></h3>
                    <div class="SrchBx">
                        <button id="TglSrch" class="btn btn-info btn-right"></button>
                    </div>
                </div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'POST']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                    

                    <!-- <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                            <label for="page_name">Society Name</label>
                                <input type="text" name="society_name" id="society_name" maxlength="100"
                                    placeholder="Enter Society Name" class="form-control" value="<?=$societyName?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                            <label for="status">Registration Number</label>
                                <input type="text" name="registration_number" id="registration_number" maxlength="40"
                                    placeholder="Enter Registration Number" class="form-control"
                                    value="<?=$registrationNumber?>">
                            </div>
                        </div>
                        
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="page_name">State</label>
                                    <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$state])?>
                                </div>
                            </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-1"><label for="page_name">Sector of Operation</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('sector_operation',$sectorOperations,['class'=>'form-control select2','id'=>'sector_operation','empty'=>'--Select--','value'=>$s_sector_operation])?>
                            </div>
                        </div>
                        <div class="col-md-1"><label for="page_name">Primary Activity</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('primary_activity',$primary_activities,['class'=>'form-control select2','id'=>'primary_activity','empty'=>'--Select--','value'=>$s_primary_activity])?>
                            </div>
                        </div>

                            <!-- <div class="col-md-1"><label for="page_name">Location</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php $arr_location = ['1'=>'Urban','2'=>'Rural']; ?>
                                    <?=$this->form->select('location',$arr_location,['class'=>'form-control select2','id'=>'location','empty'=>'--Select--','value'=>$location])?>
                                </div>
                            </div> -->
                            <div class="col-md-1"><label for="page_name">State</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$state])?>
                                </div>
                            </div>

                        <div class="rural_filter1" >
                            
                                <div class="col-md-1"><label for="page_name">District</label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?=$this->form->select('district',$districts,['class'=>'form-control select2','id'=>'district','empty'=>'--Select--','value'=>$s_district])?>
                                    </div>
                                </div>
                                
                        </div>

                        <div class="col-md-1"><label for="page_name">Level of Federation</label></div>
                        <?php $level_fed = ['2'=>'State Federation', '3'=>'District Federation', '4'=>'Block/Taluka/Mandal Federation', '5'=>'Regional Federation'] ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?=$this->form->select('federation_type',$level_fed,['class'=>'form-control select2','empty'=>'--Select--','value'=>$fed_type])?>
                                </div>
                            </div>

                        <div class="col-md-1"><label for="page_name">Federation Code</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->Form->control('federation_code',['type'=>'text','placeholder'=>'Federation Code','label'=>false])?>
                            </div>
                        </div>
                        <div class="urban_filter" style="display:<?= $_GET['location'] == 1 ? 'block' : 'none' ; ?>;">
                                <div class="col-md-1"><label for="page_name">Local Category</label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?=$this->form->select('local_category',$local_categories,['class'=>'form-control select2','id'=>'local_category','empty'=>'--Select--','value'=>$s_local_category])?>
                                    </div>
                                </div>
                                <div class="col-md-1"><label for="page_name">Local Name</label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?=$this->form->select('local_body',$localbodies,['class'=>'form-control select2','id'=>'local_body','empty'=>'--Select--','value'=>$s_local_body])?>
                                    </div>
                                </div>
                            
                        </div>
                        
                            <div class="col-md-2 filter_btn col-md-offset-5 text-center">
                                <button name="search_button" value="search_button" type="submit"
                                    class="btn btn-primary btn-green">Search</button>
                                <?= $this->Html->link(__('Reset'),['controller'=>'StateDistrictFederation','action'=>'list'],['class'=>'btn btn-danger mx-1']); ?>
                            </div>


                        <div class="clearfix"></div>
                        <br>
                        <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
                    </div>
                    
                </div>

            </div>
        </div>
    </div>
<div>
    <div class="col-md-12"><p id="code"> <b>* Federation Code : - (Primary Activity-Level of Federation-Uniqid) </b></p></div>
</div>

    <div class="row">
        <div class="col-xs-12">
            <div class="export_excel-div">
                    <button name="export_excel" value="export_excel" class="mt4px btn btn-green">Export to Excel</button>
                </div>

            <div class="box box-primary box-st">

                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <div class="sf">
                        <div style="float:right;">
                            <!--div class="box-tools">
                     <?php if($this->request->session()->read('Auth.User.role_id') != 11) //role id 11 for state nodal
                                        {
                                            ?>
                        <span class="addneww btn btn-warning btn-xs"><i class="glyphicon glyphicon-plus"></i></span> 
						<?=$this->Html->link(
                __(' New National Federations Data'),
                ['action' => 'add-national'],
                ['class' => 'btn btn-success btnreg', 'escape' => false, 'title' => __('Add New National Federations Data')]
              );?> 
          <?php } ?>
                    </div-->
                        </div>
                    </div>
                    <div class="box-tools pull-right">
                        <?php if(!in_array($this->request->session()->read('Auth.User.role_id'),[1,2])){ 
                            echo $this->Html->link(
                                __('<i class="glyphicon glyphicon-plus"></i> Add New State/District Federation Data'),
                                ['action' => 'add'],
                                ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New State/District Federation Data')]
                        );} ?>
                    </div>
                    <?php // $this->Html->link('<i class="fa fa-add">Add State Cooperative Federation Society</i>', ['action' => 'add'],['class' => 'btn btn-info btn-xs', 'title' => __('Add Form'), 'escape' => false]) ?>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>

                                <th scope="col">
                                    <?= $this->Paginator->sort('cooperative_federation_name','Cooperative Federation Name') ?>
                                </th>

                                <!-- <th scope="col"><?= $this->Paginator->sort('location','Location') ?></th> -->
                                    
                                
                                <th scope="col">
                                    <?= $this->Paginator->sort('state_code','State Name') ?>
                                </th>

                                <th scope="col">
                                    <?= $this->Paginator->sort('district_code','District Name') ?>
                                </th>
                                
                                <th scope="col">
                                    <?= $this->Paginator->sort('sector_of_operation','Sector Name') ?>
                                </th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('sector_of_operation','Level of Federation') ?>
                                </th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('registration_number','Registration No') ?>
                                </th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('sector_of_operation','No of Members') ?>
                                </th>

                                <th scope="col">
                                    <?= $this->Paginator->sort('federation_code','Federation Code') ?>
                                </th>
                                

                                <!-- <th scope="col">
                                    <?= $this->Paginator->sort('registration_date','Registration Date') ?>
                                </th> -->

                                

                                <!-- <th scope="col">
                                    <?= 'Contact Detail' ?>
                                </th> -->
                                <!-- <th scope="col">
                                    <?php echo  $this->Paginator->sort('members_of_society','Total Number Of Members') ?>
                                </th> -->
                                <th scope="col" class="actions" style="width:180px"><?= __('Actions') ?></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                  
                  foreach ($state_district_federations_arr as $state_district_federation_data){ 
                   
                ?>
                            <tr>
                                <td>
                                    <?php echo $this->Number->format($i + $counter) ?>

                                </td>
                                <!-- <td><?= h(strtoupper($state_district_federation_data->cooperative_federation_name)) ?></td> -->
                                <td><?= wordwrap($state_district_federation_data->cooperative_federation_name,30,'<br>') ?></td>
                                <!-- <td><?= h($arr_location[$state_district_federation_data->location_of_head_quarter]) ?></td> -->
                                <td><?= h(strtoupper($sOption[$state_district_federation_data->state_code])) ?></td>
                                

                                <td>
                                    <?php 
                                    if($state_district_federation_data->location_of_head_quarter == 2)
                                    {
                                        echo $arr_districts[$state_district_federation_data->district_code] ?? '';
                                    }

                                    if($state_district_federation_data->location_of_head_quarter == 1)
                                    {
                                       echo $arr_localbodies[$state_district_federation_data->urban_local_body_code] ?? '';
                                    }
                                 ?>
                                </td>

                                <td><?= h($primary_activities[$state_district_federation_data->sector_of_operation]) ?></td>
                                <td>
                                    <?php 
                                    if($state_district_federation_data->federation_type == 2)
                                    {
                                        echo "State Federation";
                                    }
                                    elseif($state_district_federation_data->federation_type == 3)
                                    {
                                        echo "District Federation";
                                    }
                                    elseif($state_district_federation_data->federation_type == 4)
                                    {
                                        echo "Block/Taluka/Mandal Federation";
                                    }
                                    elseif($state_district_federation_data->federation_type == 5)
                                    {
                                        echo "Regional Federation";
                                    }
                                   ?>     
                             </td>

                                <td><?= h($state_district_federation_data->registration_number) ?></td>
                                <?php $all_member = $state_district_federation_data->primary_cooperative_member_count; 


                                 ?>
                                
                                <td><?= $this->Html->link($all_member == NULL ? '0' : $all_member, ['action' => 'member', $state_district_federation_data->id],['class' => 'btn btn-primary btn-xs', 'title' => __('Member')]); ?></td>
                                     <?php  
                        $code = str_pad($state_district_federation_data->sector_of_operation, 3, '0', STR_PAD_LEFT)."-".str_pad($state_district_federation_data->federation_type, 2, '0', STR_PAD_LEFT)."-".str_pad($state_district_federation_data->id, 5, '0', STR_PAD_LEFT);	
                                      
 
									 ?>
                                <td><?= $code ?></td>
                                
                                <!-- <td><?=!empty($state_district_federation_data->date_registration)?date('d/m/Y',strtotime($state_district_federation_data->date_registration)):""?> -->
                                </td>
                                <!-- <td><?= nl2br("Name: ".$state_district_federation_data->contact_person . "\nDesignation: ".$designations[$state_district_federation_data->designation] ."\nPhone Number: ".$state_district_federation_data->mobile ."\nEmail: ".$state_district_federation_data->email ) ?>
                                </td> -->
                                <!-- <td><?= h($state_district_federation_data->members_of_society) ?></td> -->


                                <td class="actions">

                                    <?php if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
                                        { 
                                          echo $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $state_district_federation_data->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]);  echo "&nbsp" ;
                                          echo $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $state_district_federation_data->id], ['confirm' => __('Are you sure you want to delete this Fishery Federation?', $CooperativeFederation->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]);
                                        }else{
                                           echo $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $state_district_federation_data->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]);  echo "&nbsp" ;
                                           echo $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $state_district_federation_data->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]);
                                        }
                                      ?>

                                </td>
                            </tr>
                            <?php $i++;} ?>
                            <?php if(count($state_district_federations_arr) == 0){ ?>
                            <tr>
                                <td colspan="9"><?= __('Record not found!'); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?= $this->Form->end(); ?>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">

                    <div class="col-md-4 text-left">
                        <?php echo 'Showing '.$paginatorInformation['start'].' to '.$paginatorInformation['end'].' of, '.$paginatorInformation['count'].' entries'; ?>
                    </div>
                    <div class="col-md-8">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?= $this->Paginator->first('<<') ?>
                            <?= $this->Paginator->prev('<') ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('>') ?>
                            <?= $this->Paginator->last('>>') ?>
                        </ul>
                    </div>
                </div>

            </div>
            <!-- /.box -->
        </div>

    </div>


</section>
<style>
table.table.table-hover.table-bordered a {
    color: white;
}
</style>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
$(document).ready(function() {

    $('.select2').select2();

    $(".all_date").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        //maxDate: new Date(),
    });

    $("#searchBanner").show();
    $("#TglSrch").click(function() {
        $("#searchBanner").slideToggle(1500);
    });

});

$('#searchBanner').on('change', '#location', function(e) {


    $('#block').val('');
    $('#panchayat').val('');
    $('#village').val('');


    if ($(this).val() == 1) {
        $('.rural_filter').hide();
        $('.urban_filter').show();

    } else if ($(this).val() == 2) {
        $('.urban_filter').hide();
        $('.rural_filter').show();
    } else {
        $('.urban_filter').hide();
        $('.rural_filter').hide();
    }
});


$('#searchBanner').on('change', '#state', function(e) {
    e.preventDefault();


    var location_of_head_quarter = $('select[name="location"] option:selected').val();
    if (location_of_head_quarter == 1) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: '<?=$this->Url->build(['action'=>'get-urban-local-bodies'])?>',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            data: {
                state_code: $(this).val()
            },
            success: function(response) {
                $("#local_category").html(response);
            },
        });

        $('.rural_filter').hide();
        $('.urban_filter').show();

    } else {
        //else if(location_of_head_quarter==2){

        $('#district').val('');
        $('#block').val('');
        $('#panchayat').val('');
        $('#village').val('');
        $.ajax({
            type: 'POST',
            cache: false,
            url: '<?=$this->Url->build(['action'=>'get-districts'])?>',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            data: {
                state_code: $(this).val()
            },
            success: function(response) {
                $("#district").html(response);
            },
        });

        $('.rural_filter').show();
        $('.urban_filter').hide();

    }


});


$('#searchBanner').on('change', '#local_category', function(e) {
    e.preventDefault();
    <?php if($this->request->session()->read('Auth.User.role_id') == 11){ ?>
                
    var state_code = "<?php echo $this->request->session()->read('Auth.User.state_code') ?>";
    <?php } else { ?>
    var state_code = $('#state option:selected').val();
    <?php } ?>
    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-urban-local-body'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            urban_local_body_type_code: $(this).val(),
            state_code: state_code
        },
        success: function(response) {
            $("#local_body").html(response);
        },
    });
});


$('body').on('change', '#sector_operation', function(e) {

            e.preventDefault();
            $('.change').hide();

            $('#sector-of-operation-other').val('');
            $('.sector_of_operation_other').hide();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '<?=$this->Url->build(['action'=>'get-primary-activity'])?>',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]')
                        .val());
                },
                data: {
                    sector_operation: $(this).val()
                },
                success: function(response) {
                    console.log(response)
                    $("#primary_activity").html(response);

                },
            });
            });

// $('#searchBanner').on('change', '#sector_operation', function(e) {
//     e.preventDefault();
//     $.ajax({
//         type: 'GET',
//         cache: false,
//         url: '<?=$this->Url->build(['action'=>'get-primary-activity'])?>',
//         beforeSend: function(xhr) {
//             xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
//         },
//         data: {
//             sector_operation: $(this).val()
//         },
//         success: function(response) {
//             $("#primary_activity").html(response);
//         },
//     });

// });

</script>
<?php $this->end(); ?>