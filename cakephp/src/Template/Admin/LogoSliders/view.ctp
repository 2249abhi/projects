<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 27, 2018, 10:52 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LogoSlider $logoSlider
 */
$this->assign('title',__('Logo Slider ( ' . $logoSlider->title . ' ) '));
$this->Breadcrumbs->add(__('Logo Sliders'),['action'=>'index']);
$this->Breadcrumbs->add(__('Logo Slider ( ' . $logoSlider->title . ' ) '));
?>

  <!-- Main content -->
  <section class="content logoSlider view">
     <div class="row">
        <div class="col-md-12">
           <div class="box box-solid">
              <div class="box-header with-border">
                 <i class="fa fa-info"></i>
                 <h3 class="box-title"><?= __('Logo Slider ( ' . $logoSlider->title . ' ) ') ?></h3>
                 <div class="box-tools pull-right">
                  <?=$this->Html->link(
                    '<i class="glyphicon glyphicon-arrow-left"></i>',
                    ['action' => 'index'],
                    ['class' => 'btn btn-info btn-xs','title' => __('Back to Logo Slider'),'escape' => false]
                  );?>
                  <?= $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', ['action' => 'edit', ],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]) ?>

                  <?= $this->Form->postLink(
                    '<i class="fa fa-trash-o"></i>',
                    ['action' => 'delete', $logoSlider->id],
                    ['confirm' => __('Are you sure you want to delete this Logo Slider?', $logoSlider->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                )?>
              </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                 <dl class="dl-horizontal">
              <dt><?= __('Title') ?></dt>
              <dd><?= h($logoSlider->title) ?></dd>
              <dt><?= __('Logo Image') ?></dt>
              <dd><?= h($logoSlider->logo_image) ?></dd>
              <dt><?= __('Id') ?></dt>
              <dd><?= $this->Number->format($logoSlider->id) ?></dd>
              <dt><?= __('Logo Cat Id') ?></dt>
              <dd><?= $this->Number->format($logoSlider->logo_cat_id) ?></dd>
              <dt><?= __('Website') ?></dt>
              <dd><?= h($logoSlider->website) ?></dd>
              <dt><?= __('Created') ?></dt>
              <dd><?= h($logoSlider->created) ?></dd>
              <dt><?= __('Status') ?></dt>
              <dd><?= $logoSlider->status ? __('Yes') : __('No'); ?></dd>
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