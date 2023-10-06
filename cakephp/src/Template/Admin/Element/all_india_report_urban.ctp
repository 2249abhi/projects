<div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-st">
                <p><?php 
                    foreach($first as $key => $val)
                    {
                        echo $val.'</br>';
                    }
                ?></p>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id','Sr.No.') ?></th>
                                <th scope="col">
                                    <?= $this->Paginator->sort('State','State') ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('Urban Local Body','Urban Local Body') ?> 
                                    <br>
                                    <?php 
                                    echo "Total  = ".array_sum($urban_bodys);
                                    ?>
                                </th>
                                <th scope="col"><?= $this->Paginator->sort('Locality or Ward','Locality or Ward') ?>
                                     <br>
                                    <?php 
                                    echo "Total  = ".array_sum($urban_bodys_wards);
                                    ?>                                 

                                </th>
                                <th scope="col" width="25%" style="text-align: center;width: 335px;">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="3"  style="text-align: center;">
                                                <?= $this->Paginator->sort('PACS','PACS')?></th>
                                            </tr>
                                            <tr>
                                                <th width="33%">Total Record 
                                                <?php 
                                                $total_pac = array_sum($district_nodal_tatal_without_state['pacs']);

                                                if($total_pac == '')
                                                {
                                                $total_pac='0';
                                                }                                     

                                                echo $total_pac;
                                                ?></th>
                                                <th width="33%"> Data Entered Today 
                                                    <?php 
                                                    echo array_sum($pacs_today);
                                                    ?>
                                                </th>
                                                <th width="33%">Total Data Entered 
                                                <?php 
                                                echo array_sum($pacs);
                                                ?>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table> 
                               </th>
                                <th scope="col" width="25%" style="text-align: center;width: 335px;">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="3"  style="text-align: center;">
                                            <?= $this->Paginator->sort('dairy','Dairy') ?>
                                        </th>
                                     </tr>
                                    <tr>
                                    <th width="33%">Total Record 
                                    <?php 

                                    $total_dairy = array_sum($district_nodal_tatal_without_state['dairy']);
                                    if($total_dairy == '')
                                    {
                                    $total_dairy='0';
                                    }

                                    echo $total_dairy;
                                    ?></th>
                                    <th width="33%"> Data Entered Today  <?php 
                                    echo array_sum($dairies_today);
                                    ?> </th>

                                    <th width="33%">Total Data Entered 

                                  
                                    <?php 
                                    echo array_sum($dairies);
                                    ?>
                                    </th></tr>
                                    </thead>
                                </table>
                           </th>                               
                                <th scope="col" width="25%" style="text-align: center;width: 335px;">   <table class="table table-hover table-bordered table-striped"><thead><tr><th colspan="3"  style="text-align: center;"><?= $this->Paginator->sort('fisheries','Fisheries') ?></th></tr><tr><th width="33%">Total Record  <?php 
                                    
                                        $total_fisfhery = array_sum($district_nodal_tatal_without_state['fisfhery']);

                                         if($total_fisfhery == '')
                                        {
                                            $total_fisfhery='0';
                                        }
                                          
                                    echo $total_fisfhery;
                                    ?></th>
                                    
                                    <th width="33%"> Data Entered Today   <?php 
                                    echo array_sum($fisheries_today);
                                    ?> </th>
                                    <th width="33%">Total Data Entered  <?php 
                                    echo array_sum($fisheries);
                                    ?></th></tr>
                                </thead></table></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                $i=1;
                  $paginatorInformation = $this->Paginator->params();
                  $pageOffset = ($paginatorInformation['page'] - 1);
                  $perPage = $paginatorInformation['perPage'];
                  $counter = (($pageOffset * $perPage));
                     foreach ($state_code as $key=>$district_code): 
                ?>
                            <tr>
                                <td> <?php  
                        echo $this->Number->format($i + $counter) 
                      ?>

                                </td>
                                <td><?= h($sOption[$district_code['state_code']]) ?></td>
                                <td><?= h($urban_bodys[$district_code['state_code']] ?? 0) ?></td>
                                <td><?= h($urban_bodys_wards[$district_code['state_code']] ?? 0) ?></td>
                                  <td style="text-align: center;">
                                    <table class="table"><tbody><tr><td width="33%" <?php if(array_sum($district_nodal_tatal['pacs'][$district_code['state_code']]) <  ($pacs[$district_code['state_code']])) { ?>style=" background-color: #e9cdcd;" <?php } ?> ><?= h( array_sum($district_nodal_tatal['pacs'][$district_code['state_code']]) ?? 0) ?></td> <td width="33%"><?= h($pacs_today[$district_code['state_code']] ?? 0) ?></td><td width="33%">  <?= h($pacs[$district_code['state_code']] ?? 0) ?></td> </tr>
                                    </tbody></table> 
                                    </td>
                                <td style="text-align: center;">  

                                         <table class="table"><tbody><tr><td <?php if(array_sum($district_nodal_tatal['dairy'][$district_code['state_code']]) <  ($dairies[$district_code['state_code']])) { ?>style=" background-color: #e9cdcd;" <?php } ?>><?= h( array_sum($district_nodal_tatal['dairy'][$district_code['state_code']]) ?? 0) ?></td>
                                         <td width="30%">  <?= h($dairies_today[$district_code['state_code']] ?? 0) ?></th>
                                         <td width="30%">  <?= h($dairies[$district_code['state_code']] ?? 0) ?></th> </tr>
                                    </tbody></table>

                                  </td>
                              
                                <td style="text-align: center;width: 223px;">

                                       <table class="table"><tbody><tr><td <?php if(array_sum($district_nodal_tatal['fisfhery'][$district_code['state_code']]) <  ($fisheries[$district_code['state_code']])) { ?>style=" background-color: #e9cdcd;" <?php } ?>><?= h(array_sum($district_nodal_tatal['fisfhery'][$district_code['state_code']]) ?? 0) ?></td>
                                       <td width="33%">  <?= h($fisheries_today[$district_code['state_code']] ?? 0) ?></td>
                                       <td width="33%">  <?= h($fisheries[$district_code['state_code']] ?? 0) ?></td> </tr>
                                    </tbody></table>

                                  </td>
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