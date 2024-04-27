<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 29, 2018, 10:44 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocaleTarget $localeTarget
 */
$this->assign('title',__('Add New Locale Target'));
$this->Breadcrumbs->add(__('Locale Targets'),['action'=>'index']);
$this->Breadcrumbs->add(__('Add New Locale Target'));
$searchIn = [1=>'Both translated and untranslated',2=>'Translated',3=>'Untranslated'];

$stringContains = isset($stringContains)?$stringContains:'';
$translationLanguage = isset($translationLanguage)?$translationLanguage:'';
$searchInSet =  isset($searchInSet)?$searchInSet:'';
?>

  <!-- Main content -->
  <section class="content localeTarget add form">
    <div class="row">
      <div class="col-md-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <div class="box box-primary">
          <div class="box-header">
              <h3 class="box-title"><?= __('Filter Translatable Strings'); ?></h3>
          </div>          
            <div class="box-body">
              <?= $this->element('errorlist'); ?>
              <?= $this->Form->create('filterForm',['id' =>'filterForm','type'=>'get']); ?>
              <div class="row">
                <div class="col-md-8">
                  <?= $this->Form->control('string_contains',['value'=>$stringContains]); ?> 
                </div>
              </div> 
              <div class="row">               
                <div class="col-md-4">
                  <?php $pushArr =['all'=>'All'];
                  $languagesOpt = array_merge($pushArr,$languagesList);
                  echo $this->Form->control('translation_language', ['options' => $languagesOpt,'value'=>$translationLanguage]);?>
                </div>              
                <div class="col-md-4">
                  <?= $this->Form->control('search_in', ['options' => $searchIn,'value'=>$searchInSet]);?>
                </div>
                <div class="col-md-4">
                  <br>
                  <button name="filter" value="filter" type="submit" class="btn btn-primary btn-green">Filter</button>
                </div>
              </div>
              <?= $this->Form->end();?>
              <?php if (!empty($stringQuery)) { ?>
              <?= $this->Form->create('localeTarget',['id' => 'localeTarget-add-frm']); ?>
              <table class="table table-striped">
                <tr>
                  <th>Source String</th>
                  <th>Translation for <span class="tranlang"><?php if (!empty($languagesList[$translationLanguage])) { echo $languagesList[$translationLanguage]; }?> </span> </th>
                </tr>
                <?php
                if(!empty($stringQuery) && empty($filterData)) {
                  if ($translationLanguage == 'all') {
                    $fKey = 1;
                  foreach ($languagesList as $key => $LanguageValue) {?>
                    <tr>
                      <input type="hidden" name="translated[<?=$fKey?>][language]" value="<?=$key?>">
                      <input type="hidden" name="translated[<?=$fKey?>][locale_source_id]" value="<?=$stringQuery['id'];?>">
                      <td><?=$LanguageValue;?></td>
                      <td><input type="text" class="form-control" name="translated[<?=$fKey?>][translation]"></td>
                    </tr>
                  <?php $fKey++; }
                } else { ?>
                  <tr>
                    <input type="hidden" name="translated[1][language]" value="<?=$translationLanguage?>">
                    <input type="hidden" name="translated[1][locale_source_id]" value="<?=$stringQuery['id'];?>">
                    <td><?=$languagesList[$translationLanguage]; ?></td>
                    <td><input type="text" class="form-control" name="translated[1][translation]"></td>
                  </tr>
                <?php }
                } elseif (!empty($stringQuery)) {
                foreach ($filterData as $key => $filterValue) {
                  $fKey = $key+1;
                  ?>
                  <tr>
                    <input type="hidden" name="translated[<?=$fKey?>][language]" value="<?=$filterValue['language']?>">
                    <input type="hidden" name="translated[<?=$fKey?>][id]" value="<?=$filterValue['id'];?>">
                    <input type="hidden" name="translated[<?=$fKey?>][locale_source_id]" value="<?=$filterValue['locale_source_id'];?>">
                    <td><?php //echo $filterValue['locale_source']['source'];
                      echo $languagesList[$filterValue['language']]; ?>
                    </td>
                    <td><input type="text" required="required" value="<?=$filterValue['translation'] ?>" class="form-control" name="translated[<?=$fKey?>][translation]"></td>
                  </tr>                
              <?php } } ?>
            </table>              
            <div class="box-footer">
              <?php 
              echo $this->Form->button(__('Save Translations'),['class' => 'btn btn-primary']);
              echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
            </div>
            <?= $this->Form->end();?>
          <?php } else {
            if ($setQuery) { ?>
            <table class="table table-striped">
              <tr>
                <td colspan="10"><?= __('Record not found in source.'); ?></td>
              </tr>
            </table>
          <?php } }?>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php $this->append('bottom-script');?>
<script>
(function($){
    $(document).ready(function(){
        if(typeof $.validator !== "undefined"){
            $("#localeTarget-add-frm").validate();
        }
    });
})($);

$('#translation-language').on('change',function(){
  var selectedtext = $("#translation-language option:selected").text();
  $('.tranlang').html(selectedtext);
})
</script>
<?php $this->end(); ?>