<?php
/**
 * @author \Aman Tiwari
 * @version \1.1
 * @since \July 16, 2020, 11:41 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Banner[]|\Cake\Collection\CollectionInterface $contacts
 */
$this->assign('title',__('Feedback'));
$this->Breadcrumbs->add(__('Feedback'));
$selectedLen = isset($selectedLen)?$selectedLen:'';
$bannerTitle = isset($bannerTitle)?$bannerTitle:'';
$bannerCatId = isset($bannerCatId)?$bannerCatId:'';
?>
  <!-- Main content -->
  <section class="content banner index">
    <div class="row">
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <!-- <div class="box box-primary">
          <div class="box-header">
              <h3 class="box-title"><?= __('Search - Feedback'); ?></h3>
          </div> 
          <?php //echo $this->Form->create('searchRoles',['id' =>'searchRoles','type'=>'get']); ?>
            <div class="box-body table-responsive">
              <?php //echo $this->element('errorlist'); ?> 

              <div class="row2">    
                <div class="col-md-2"></div>
                <div class="col-md-1"><label for="page_name">Banners</label></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <input type="text" name="title" id="title" maxlength="40" placeholder="Enter title" class="form-control" value=<?=$bannerTitle?>>
                  </div>
                </div>
                <div class="col-md-1"><label for="banner-category-id">Status</label></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <?=$this->form->select('banner_category_id',$bannerCategories,['class'=>'form-control','empty'=>'--Select--','value'=>$bannerCatId])?>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row3">
                <div class="col-md-5"></div>
                <div class="col-md-1"><button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button></div>
                <div class="col-md-1">
                  <?php if(empty($bannerTitle) && empty($bannerCatId)){ ?>
                    <button type="reset" class="btn btn-danger">Reset</button>                  
                  <?php } else { ?>
                    <?= $this->Html->link(__('Reset'),['controller'=>'banners','action'=>'index'],['class'=>'btn btn-danger']); ?>
                  <?php }?>
                </div>
              </div>
              <div class="clearfix"></div>
              <br>
              <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
          </div>
          <?= $this->Form->end(); ?>
          </div> -->
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Feedback') ?></h3>
            <!-- <div class="box-tools">
              <?=$this->Html->link(
                      __('<i class="glyphicon glyphicon-plus"></i> New Banner'),
                      ['action' => 'add'],
                      ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Banner')]
                  );?>
            </div> -->
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('Name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('Email Id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('Mobile No.') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('Message') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                    
                </tr>
              </thead>
              <tbody>
                <?php foreach ($contacts as $contact): ?>
                  <tr>
                    <td><?= $this->Number->format($contact->id) ?></td>
                    <td><?= h($contact->name) ?></td>
                    <td><?= h($contact->email) ?></td>
                    <td><?= h($contact->mobile) ?></td>
                    <td><?= h($contact->remark) ?></td>
                    <td><?= date('d-m-Y', strtotime($contact->created)); ?></td>
                    
                  </tr>
                    <?php endforeach; ?>
                    <?php if(count($contacts) == 0):?>
                  <tr>
                      <td colspan="10"><?= __('Record not found!'); ?></td>
                  </tr>
                  <?php endif;?>
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
              <?= $this->Paginator->first('<<') ?>
              <?= $this->Paginator->prev('<') ?>
              <?= $this->Paginator->numbers() ?>
              <?= $this->Paginator->next('>') ?>
              <?= $this->Paginator->last('>>') ?>
            </ul>
          </div>
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>