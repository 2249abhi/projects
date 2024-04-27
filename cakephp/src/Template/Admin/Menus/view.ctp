<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 5:01 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Menu $menu
 */
$this->assign('title',__('Menu ( ' . $menu->menu_title . ' ) '));
$this->Breadcrumbs->add(__('Menus'),['action'=>'index']);
$this->Breadcrumbs->add(__('Menu ( ' . $menu->menu_title . ' ) '));
?>

  <!-- Main content -->
  <section class="content menu view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Menu ( ' . $menu->menu_title . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index', $menu->menu_region_id],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Menu'),'escape' => false]
                  );?>
                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $menu->id],
                    ['confirm' => __('Are you sure you want to delete this Menu?', $menu->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <dl class="dl-horizontal">
                  <dt><?= __('Id') ?></dt>
                  <dd><?= $this->Number->format($menu->id) ?></dd>
                  <dt><?= __('Menu Region') ?></dt>
                  <dd><?= $menu->has('menu_region') ? $this->Html->link($menu->menu_region->region, ['controller' => 'MenuRegions', 'action' => 'view', $menu->menu_region->id]) : '' ?></dd>
                  <dt><?= __('Parent Menu') ?></dt>
                  <dd><?= $menu->has('parent_menu') ? $this->Html->link($menu->parent_menu->id, ['controller' => 'Menus', 'action' => 'view', $menu->parent_menu->id]) : '' ?></dd>
                  <dt><?= __('Menu Title') ?></dt>
                  <dd><?= h($menu->menu_title) ?></dd>
                  <dt><?= __('Menu Type') ?></dt>
                  <dd><?= h($menuType[$menu->menu_type]) ?></dd>
                  <dt><?= __('Object Type') ?></dt>
                  <dd><?= h($menu->object_type) ?></dd>
                  <dt><?= __('Module') ?></dt>
                  <dd><?= $menu->has('module') ? $this->Html->link($menu->module->name, ['controller' => 'Modules', 'action' => 'view', $menu->module->id]) : '' ?></dd>              
                  <dt class="<?= ($menu->menu_type != 'object')? 'd-none' : ''; ?>"><?= __('Article') ?></dt>
                    <?php
                    $link = '';
                    if($menu->object_type == 'article' && $menu->has('article')):
                    if($menu->article->is_home == 1){
                      $link = $this->Url->build(['_name'=>'home'], ['fullBase' => true]);
                    } else {
                      $lOption = [
                        'prefix'     => false,
                        'controller' => 'Articles',
                        'action'     => 'page',
                        'id'         => $menu->article->id,
                      ];
                      $link = $this->Url->build($lOption, ['pass' => ['id'], 'fullBase' => true]);
                    }
                    endif;
                    ?>
                  <dd class="<?= ($menu->menu_type != 'object')? 'd-none' : ''; ?>"><?= (!empty($link)) ? $this->Html->link($link, $link, ['target' => '_blank']) : '';?></dd>
                  <dt><?= __('Redirection') ?></dt>
                  <dd><?= h($menuRedirection[$menu->redirection]) ?></dd>              
                  <dt><?= __('Status') ?></dt>
                  <dd><?= ($menu->status == 1) ? __('Active') : __('Inactive'); ?></dd>
                  <dt  class="<?= ($menu->menu_type != 'custom')? 'd-none' : ''; ?>"><?= __('Custom Link') ?></dt>
                  <dd  class="<?= ($menu->menu_type != 'custom')? 'd-none' : ''; ?>"><?= (!empty($menu->custom_link)) ? $this->Html->link($menu->custom_link,$menu->custom_link, ['target' => '_blank']) : ''; ?></dd>
                </dl>
              </div>
              <!-- /.box-body -->
           </div>
           <!-- /.box -->
        </div>
        <!-- ./col -->
     </div>
     <!-- div -->
 </section>