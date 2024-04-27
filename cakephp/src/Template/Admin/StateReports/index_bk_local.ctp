<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cooperative Registration[]|\Cake\Collection\CollectionInterface $CooperativeRegistrations
 */
$this->assign('title',__('Report'));
$this->assign('content_header',__('Report'));
$this->Breadcrumbs->add(__('Report'));
?>
<!-- Main content -->
<section class="content Cooperative Registration index">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <div class="box box-primary">
                <div class="box-header" >
                    <h3 class="box-title titleSrch"><?= __('Search - Report'); ?></h3>
                    <div class="SrchBx">
                        <button id="TglSrch" class="btn btn-info"></button>
                    </div>
                </div>
                <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
                <div class="box-body table-responsive">
                    <?= $this->element('errorlist'); ?>

                    
                    
                    <div class="row2">        
                        <div class="col-md-1"><label for="page_name">Location</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php $arr_location = ['1'=>'Urban','2'=>'Rural']; ?>
                                <?=$this->form->select('location',$arr_location,['class'=>'form-control','id'=>'location','default'=>2,'empty'=>'--Select--','value'=>$s_location])?>
                            </div>
                        </div>                   
                        <div class="col-md-1"><label for="page_name">State</label></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=$this->form->select('state',$sOption,['class'=>'form-control select2','id'=>'state','empty'=>'--Select--','value'=>$s_state])?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="row3">
                        <div class="col-md-12 text-center">
                            <button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button>
                              <?= $this->Html->link(__('Reset'),['controller'=>'Reports','action'=>'index'],['class'=>'btn btn-danger mx-1']); ?>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <br>
                    <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <?php if(isset($s_state) && !empty($s_state)){ ?>
    <div class="row">
        <div class="col-xs-12">
            <h3><?= $sOption[$s_state] ?? '' ?></h3>
        </div>
    </div>
    <?php } ?>
    <?php  if($s_location == 2){ ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">
                <p><?php 
                    foreach($first as $key => $val)
                    {
                       // echo $val.'</br>';
                    }
                ?></p>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('district','District') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('panchayat','Panchayat') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('villages','Villages') ?></th>
                                 <th scope="col" style="text-align: center;"><?= $this->Paginator->sort('pacs','Pacs')?> <br>
                                 Total | Total Data Entered </th>
                                <th scope="col" style="text-align: center;"><?= $this->Paginator->sort('dairy','Dairy') ?> <br>
                                 Total | Total Data Entered </th>                               
                                <th scope="col" style="text-align: center; width:223px"><?= $this->Paginator->sort('fisheries','Fisheries') ?> <br>
                                 Total | Total Data Entered </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                          //  echo "<pre>";
                           // print_r($district_codes);

                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                  foreach ($district_codes as $key=>$district_code): 
                ?>
                            <tr>
                                <td> <?php  
                        echo $this->Number->format($i + $counter) 
                      ?>

                                </td>
                                <td><?= h($arr_districts[$district_code['district_code']]) ?></td>
                                <td><?= h($panchayats[$district_code['district_code']] ?? 0) ?></td>
                                <td><?= h($villages[$district_code['district_code']] ?? 0) ?></td>
                                  <td style="text-align: center;"><?= h($pacs[$district_code['district_code']] ?? 0) ?> | <?= h($district_nodal_tatal[$state_code][$district_code['district_code']]['pacs'] ?? 0) ?></td>
                                <td style="text-align: center;"><?= h($dairies[$district_code['district_code']] ?? 0) ?> | <?= h($district_nodal_tatal[$state_code][$district_code['district_code']]['dairy'] ?? 0) ?></td>
                              
                                <td style="text-align: center;width: 223px;"><?= h($fisheries[$district_code['district_code']] ?? 0) ?> | <?= h($district_nodal_tatal[$state_code][$district_code['district_code']]['fisfhery'] ?? 0) ?></td>
                            </tr>
                            <?php $i++;endforeach; ?>
                            <?php if(count($district_codes) == 0):?>
                            <tr>
                                <td colspan="6"><?= __('Record not found!'); ?></td>
                            </tr>
                            <?php endif;?>
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
    <?php } 
    if($s_location == 1){        
        ?>
        <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">                
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('category','Category') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('localbody','Local Body') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('ward','Ward') ?></th>
                                   <th scope="col"><?= $this->Paginator->sort('pacs','Pacs') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('dairy','Dairy') ?></th>                             
                                <th scope="col"><?= $this->Paginator->sort('fisheries','Fisheries') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                                   
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
    <?php } ?>

</section>
<?php $this->append('bottom-script');?>
<script type="text/javascript">
        $(document).ready(function(){

            $('.select2').select2();

            $("#searchBanner").show();
            $("#TglSrch").click(function(){
           // $("#searchBanner").slideToggle(1500);
            });
        });

        $("#TglSrch").on('click', function() {
      //  $(this).toggleClass('is-active').next("#searchBanner").stop().slideToggle(1500);

     

  });
</script>
<?php $this->end(); ?>