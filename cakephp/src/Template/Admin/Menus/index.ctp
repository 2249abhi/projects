<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 5:01 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Menu[]|\Cake\Collection\CollectionInterface $menus
 */
use Cake\Routing\Router;
$this->assign('title',__('Menus'));
$this->Breadcrumbs->add(__('Menus'));
?>
  <!-- Main content -->
  <section class="content menu index">
    <div class="row">
      <div class="col-xs-12">
      <?= $this->Flash->render() ; ?>
        <div class="box box-primary">

          <div class="box-header">
              <h3 class="box-title"><?= __('Search - Menus'); ?></h3>
          </div> 
          <?= $this->Form->create('searchBanner',['id' =>'searchBanner','type'=>'get']); ?>
            <div class="box-body table-responsive">
              <?= $this->element('errorlist'); ?> 
              <?php //pr($title);?>
              <div class="row2">    
                <div class="col-md-2"></div>
                <div class="col-md-1"><label for="page_name">Title</label></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <input type="text" name="title" id="title" maxlength="40" placeholder="Enter title" class="form-control" value='<?=@$title?>'>
                  </div>
                </div>

                <div class="col-md-1"><button name="search_button" value="search_button" type="submit" class="btn btn-primary btn-green">Search</button></div>
                <div class="col-md-1">
                  <?php if(empty($title)){ ?>
                    <button type="reset" class="btn btn-danger">Reset</button>                  
                  <?php } else { ?>
                    <?= $this->Html->link(__('Reset'),['controller'=>'menus','action'=>'index',$menu_region_id],['class'=>'btn btn-danger']); ?>
                  <?php }?>
                </div>
                
              </div>
              <div class="clearfix"></div>
              <br>
              <input type="hidden" name="page_length" value="<?=@$selectedLen?>">
          </div>
          <?= $this->Form->end(); ?>

          <div class="box-header">
            <h3 class="box-title"><?= __('List of') ?> <?= __('Menus') ?></h3>
            <div class="box-tools">
              <?=$this->Html->link(
                  '<i class="fa fa-arrow-circle-left"></i>',
                  ['controller' => 'MenuRegions', 'action' => 'index'],
                  ['class' => 'btn btn-info','title' => __('Back to Menu Regions'),'escape' => false]
              );?>
              <?=$this->Html->link(
                  __('<i class="glyphicon glyphicon-plus"></i>Add New Menu'),
                  ['action' => 'add',$menu_region_id],
                  ['class' => 'btn btn-success', 'escape' => false, 'title' => __('Add New Menu')]
                );?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('menu_title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('menu_type') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('custom_link') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('object_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                    <th width="100" scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($menus as $menu): ?>
                  <tr>
                    <td><?= $this->Number->format($menu->id) ?></td>
                    <td><?= h($menu->menu_title) ?></td>
                    <td><?= h($menuType[$menu->menu_type]) ?></td>
                    <td><?= (!empty($menu->custom_link)) ? $this->Html->link($menu->custom_link,$menu->custom_link, ['target' => '_blank']) : ''; ?></td>
                    <?php
                    $link = '';
                    if($menu->object_type == 'article' && $menu->has('article')){
                      $lOption = [
                        'prefix'     => false,
                        'controller' => 'Articles',
                        'action'     => 'page',
                        'id'         => $menu->article->id,
                      ];
                      $link = $this->Url->build($lOption, ['pass' => ['id'], 'fullBase' => true]);
                    } else if ($menu->menu_type == 'internal') {
                      $isShow = true;
                      $lbption = ['prefix'=> false,'controller'=>$menu->internal_link];
                      $link = Router::url($lbption, ['_full' => true]);
                    }
                    ?>
                    <td><?= (!empty($link)) ? $this->Html->link($link, $link, ['target' => '_blank']) : '';?></td>
                    <td><?= ($menu->status == 1) ? __('Active') : __('Inactive');?></td>
                    <td class="actions">
                      <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $menu_region_id, $menu->id],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]) ?>
                      <?= $this->Html->link('<i class="fa fa-edit"></i>', ['action' => 'edit', $menu_region_id, $menu->id],['class' => 'btn btn-warning btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>
                      <?= $this->Form->postLink('<i class="fa fa-trash-o"></i>', ['action' => 'delete', $menu_region_id, $menu->id], ['confirm' => __('Are you sure you want to delete this Menu?', $menu->id),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($menus) == 0):?>
                <tr>
                    <td colspan="15"><?= __('Record not found!'); ?></td>
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