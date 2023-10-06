<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 5:01 pm
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Menu $menu
 */
$this->assign('title',__('Add New Menu'));
$this->Breadcrumbs->add(__('Menus'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Menu'));
?>

  <!-- Main content -->
  <section class="content menu add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Menu') ?>
            <small><?= __('Add New Menu') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                '<i class="fa fa-arrow-circle-left"></i>',
                ['action' => 'index', $menu->menu_region_id],
                ['class' => 'btn btn-info btn-xs','title' => __('Back to Menus'),'escape' => false]
              );?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
            echo $this->Form->create($menu,['id' => 'menu-add-frm']); ?>
            <div class="box-body">
              <?php
              echo $this->Form->control('menu_region_id', ['options' => $menuRegions]);
              echo $this->Form->control('parent_id', ['options' => $menuParent, 'empty' =>__('Select Parent Menu')]);
              echo $this->Form->control('menu_title');
              if ($menuLanguages !== false) {
                foreach ($menuLanguages as $menuLanguage) {
                  if ($menuLanguage['id'] != SYSTEM_LANGUAGE_ID && $menuLanguage['status'] == 1) {
                    echo $this->Form->control('menu_translations.' . $menuLanguage['id'] . '.id', ['type' => 'hidden']);
                    echo $this->Form->control('menu_translations.' . $menuLanguage['id'] . '.language_id', ['type' => 'hidden', 'value' => $menuLanguage['id']]);
                    echo $this->Form->control('menu_translations.' . $menuLanguage['id'] . '.culture', ['type' => 'hidden', 'value' => $menuLanguage['culture']]);
                    echo $this->Form->control('menu_translations.' . $menuLanguage['id'] . '.menu_title', ['label' => __('Menu Title (' . $menuLanguage['name'] . ')'), 'dir' => $menuLanguage['direction']]);
                  }
                }
              }
              echo $this->Form->control('menu_type', ['options' => $menuType]); ?>
              <div class="mt-call mt-custom">
                <?=$this->Form->control('custom_link', ['type' => 'text', 'required' => 'required', ]);?>
              </div>
              <div class="mt-call mt-internal">
                <?=$this->Form->control('internal_link', ['type' => 'text', 'required' => 'required', ]);?>
              </div>
              <div class="mt-call mt-object">
                <?=$this->Form->control('object_id', ['options' => $articles, 'label' => __('Article'), 'required' => 'required', 'empty'=>__('Please Select Article')]);?>
              </div>
              <?=$this->Form->control('redirection', ['options' => $menuRedirection]);?>
              <?=$this->Form->control('sort_order', ['type' => 'number']);?>
              <?=$this->Form->control('status');?>
            </div>
            <div class="box-footer">
              <?php 
              echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
              echo $this->Html->link(__('Cancel'),['action' => 'index', $menu->menu_region_id],['class' => 'btn btn-danger mx-1']); ?>
            </div>
          <?php echo $this->Form->end();?>
      </div>
    </div>
  </section>
<?php $this->append('bottom-script');?>
<script>
(function($){
    $(document).ready(function(){
      $('#menu-type').on('change',function(){
        var $menu_type = $(this).val();
        $('.mt-call').hide();
        $('.mt-call input, .mt-call select').attr('disabled','disabled');
        $('.mt-' + $menu_type).show();
        $('.mt-' + $menu_type + ' input, .mt-' + $menu_type + ' select').removeAttr('disabled','disabled');
      }).change();
      if(typeof $.validator !== "undefined"){
          $("#menu-add-frm").validate();
      }
    });
})($);
</script>
<?php $this->end(); ?>