<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\National Federation[]|\Cake\Collection\CollectionInterface $NationalFederations
 */
?>

<!-- Main content -->
<section class="content state Cooperative Federation list">
    <div class="row">
        <div class="col* -xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title titleSrch"><?= __('Draft List District Federation'); ?></h3>
                    <div class="SrchBx">
                        <button id="TglSrch" class="btn btn-info btn-right"></button>
                    </div>
                </div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                    

                    <div class="row1">
                        <div class="col-md-1"><label for="page_name">Society Name</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="society_name" id="society_name" maxlength="100"
                                    placeholder="Enter Society Name" class="form-control" value="<?=$societyName?>">
                            </div>
                        </div>
                        <div class="col-md-1"><label for="status">Registration Number</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="registration_number" id="registration_number" maxlength="40"
                                    placeholder="Enter Registration Number" class="form-control"
                                    value="<?=$registrationNumber?>">
                            </div>
                        </div>
                        <div class="col-md-1"><label for="page_name">State</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?=$this->form->select('state',$states_find,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$state])?>
                                </div>
                            </div>
                    </div>
                    <div class="row2">
                        <!-- <div class="col-md-1"><label for="page_name">Sector of Operation</label></div>
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
                        </div> -->

                            <!-- <div class="row2">
                            <div class="col-md-1"><label for="page_name">Location</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php $arr_location = ['1'=>'Urban','2'=>'Rural']; ?>
                                    <?=$this->form->select('location',$arr_location,['class'=>'form-control select2','id'=>'location','empty'=>'--Select--','value'=>$location])?>
                                </div>
                            </div>
                            <div class="col-md-1"><label for="page_name">State</label></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$state])?>
                                </div>
                            </div>
                        </div>
                        <div class="rural_filter1" >
                            <div class="row2">
                                <div class="col-md-1"><label for="page_name">District</label></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?=$this->form->select('district',$districts,['class'=>'form-control select2','id'=>'district','empty'=>'--Select--','value'=>$s_district])?>
                                    </div>
                                </div>
                                </div>
                        </div>
                        <div class="urban_filter" style="display:<?= $_GET['location'] == 1 ? 'block' : 'none' ; ?>;">
                            <div class="row2">
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
                        </div> -->
                        

                        <div class="clearfix"></div>

                        <div class="row3">
                            <div class="col-md-12 text-center">
                                <button name="search_button" value="search_button" type="submit"
                                    class="btn btn-primary btn-green">Search</button>
                                <?= $this->Html->link(__('Reset'),['controller'=>'StateDistrictFederation','action'=>'draft'],['class'=>'btn btn-danger mx-1']); ?>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <br>
                        <input type="hidden" name="page_length" value="<?=$selectedLen?>">
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
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
                        <?php //if($cooperative_federations_object->status!=1){ 
                            echo $this->Html->link(
                                __('<i class="glyphicon glyphicon-plus"></i> Add New State/District Federation Data'),
                                ['action' => 'add'],
                                ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New State/District Federation Data')]
                        );//} ?>
                    </div>
                    <?php // $this->Html->link('<i class="fa fa-add">Add State Cooperative Federation Society</i>', ['action' => 'add'],['class' => 'btn btn-info btn-xs', 'title' => __('Add Form'), 'escape' => false]) ?>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                               
                                <th scope="col">
                                    <?= $this->Paginator->sort('cooperative_federation_name','Cooperative Federation Name') ?>
                                </th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('state_name','State Name') ?>
                                </th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('registration_number','Registration Number') ?>
                                </th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('registration_date','Registration Date') ?>
                                </th>
                                <th scope="col">
                                    <?= 'Contact Detail' ?>
                                </th>
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
                                <td><?= wordwrap($state_district_federation_data->cooperative_federation_name,50,'<br>') ?></td>
                                <td><?= h(strtoupper($states_find[$state_district_federation_data->state_code])) ?></td>
                                
                                <td><?= h($state_district_federation_data->registration_number) ?></td>
                                <td><?=!empty($state_district_federation_data->date_registration)?date('d/m/Y',strtotime($state_district_federation_data->date_registration)):""?>
                                </td>
                                <td><?= nl2br("Name: ".$state_district_federation_data->contact_person . "\nDesignation: ".$designations[$state_district_federation_data->designation] ."\nPhone Number: ".$state_district_federation_data->mobile ."\nEmail: ".$state_district_federation_data->email ) ?></td>
                                <!-- <td><?= h($state_district_federation_data->members_of_society) ?></td> -->
                                

                                <td class="actions">
                                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $state_district_federation_data->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>

                                    <?php //if($this->request->session()->read('Auth.User.role_id') != 11)
                                       // {
                                            ?>
                                    <?php echo $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $state_district_federation_data->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                                    <?php //$this->Html->link('<i class="fa fa-list"></i>', ['action' => 'members_list', $state_district_federation_data->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Members of Federation'), 'escape' => false]) ?>
                                    <?php //$this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $state_district_federation_data->id], ['confirm' => __('Are you sure you want to delete this Fishery Federation?', $CooperativeFederation->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                                    <?php //} ?>
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
<script>
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


    // $('#state').val('');
    // $('#district').val('');
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
            type: 'get',
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


$('#searchBanner').on('change', '#state', function(e) {
    e.preventDefault();
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

    //$('.rural_filter').show();  

});

$('#searchBanner').on('change', '#local_category', function(e) {
    e.preventDefault();
    <?php if($this->request->session()->read('Auth.User.role_id') == 11)
                {?>
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


$('#searchBanner').on('change', '#local_body', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-locality-ward'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            urban_local_body_code: $(this).val()
        },
        success: function(response) {
            $("#ward").html(response);
        },
    });
});



//on change district if rural
$('#searchBanner').on('change', '#district', function(e) {

    $('#block').val('');
    $('#panchayat').val('');
    $('#village').val('');

    e.preventDefault();
    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-blocks'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            district_code: $(this).val()
        },
        success: function(response) {
            $("#block").html(response);
        },
    });
});


$('#searchBanner').on('change', '#block', function(e) {
    e.preventDefault();
    $('#panchayat').val('');
    $('#village').val('');

    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-gp'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            block_code: $(this).val()
        },
        success: function(response) {
            $("#panchayat").html(response);
        },
    });
});

$('#searchBanner').on('change', '#panchayat', function(e) {
    e.preventDefault();
    $('#village').val('');
    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-villages'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            gp_code: $(this).val()
        },
        success: function(response) {
            $("#village").html(response);
        },
    });
});

$('#searchBanner').on('change', '#sector_operation', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        cache: false,
        url: '<?=$this->Url->build(['action'=>'get-primary-activity'])?>',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        data: {
            sector_operation: $(this).val()
        },
        success: function(response) {
            $("#primary_activity").html(response);
        },
    });

});
</script>
<?php $this->end(); ?>