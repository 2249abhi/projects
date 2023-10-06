<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \November 30, 2018, 11:57 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 */
$this->assign('title',__('Edit Article ( ' . $article->title .' ) '));
$this->Breadcrumbs->add(__('Articles'),['action'=>'index']);
$this->Breadcrumbs->add(__('Edit Article ( ' . $article->title .' ) '));
$sessionFilesData = $article['article_images'];
?>

  <!-- Main content -->
  <section class="content article edit form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Article') ?>
                          <small><?= __('Edit Article  ( ' . $article->title .' ) ') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Articles'),'escape' => false]
                  );?>
                    <?= $this->Form->postLink(
                      '<i class="fa fa-trash-o"></i>',
                      ['action' => 'delete', $article->id],
                      ['confirm' => __('Are you sure you want to delete this Article?', $article->id),'class' => 'btn btn-danger btn-xs','title' => __('Delete'),'escape' => false]
                  )?>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php
              echo $this->Form->create($article,['id' => 'article-edit-frm','type'=>'file']); ?>
              <input type="hidden" name="id" id="article_id" value="<?=$article['id']?>">
              <div class="box-body">
                <ul class="nav nav-tabs" id="articleTab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link active" data-toggle="tab" href="#default-tab" role="tab" aria-controls="nav-default" aria-selected="true"><?=__("Default");?></a>
                    </li>
                    <?php if ($articleLanguages !== false) {?>
                        <?php foreach ($articleLanguages as $articleLanguage) {?>
                            <?php if ($articleLanguage['id'] != $system_languge_id && $articleLanguage['status'] == 1) {?>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#<?=$articleLanguage['culture'];?>-tab" role="tab" aria-controls="nav-<?=$articleLanguage['culture'];?>" aria-selected="true"><?=__($articleLanguage['name']);?></a>
                                    <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.language_id', ['type' => 'hidden', 'value' => $articleLanguage['id']]);?>
                                    <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.culture', ['type' => 'hidden', 'value' => $articleLanguage['culture']]);?>
                                </li>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                </ul>
                <div class="tab-content" id="articleTabContent">
                    <div class="tab-pane fade active in" id="default-tab" role="tabpanel" aria-labelledby="nav-default-tab">
                        <?=$this->Form->control('title', ['type' => 'text']);?>
                        <?=$this->Form->control('content', ['class' => 'ckeditor']);?>
                        <?=$this->Form->control('excerpt');?>
                        <?=$this->Form->control('url', ['type' => 'text']);?>
                    </div>
                    <?php if ($articleLanguages !== false) {?>
                      <?php foreach ($articleLanguages as $articleLanguage) {?>
                        <?php if ($articleLanguage['id'] != $system_languge_id && $articleLanguage['status'] == 1) {?>
                          <div class="tab-pane fade" id="<?=$articleLanguage['culture'];?>-tab" role="tabpanel" aria-labelledby="nav-<?=$articleLanguage['culture'];?>-tab">
                            <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.id', ['type' => 'hidden']);?>
                            <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.title', ['type' => 'text', 'dir' => $articleLanguage['direction']]);?>
                            <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.content', ['class' => 'ckeditor', 'dir' => $articleLanguage['direction']]);?>
                            <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.excerpt', ['dir' => $articleLanguage['direction']]);?>
                            <?=$this->Form->control('article_translations.' . $articleLanguage['id'] . '.url', ['type' => 'text', 'dir' => $articleLanguage['direction']]);?>
                          </div>
                        <?php }?>
                      <?php }?>
                    <?php }?>
                </div>
                <?=$this->Form->control('cloud_tags',['type' => 'text','data-role'=>'tagsinput']);?>
                <?=$this->Form->control('meta_title');?>
                <?=$this->Form->control('meta_keywords');?>
                <?=$this->Form->control('meta_description');?>
                <div class="input-area file">
                  <div class="form-group">
                    <label for="header-image">Header Image <?php if ($article->header_image) { ?><a title="View" target="_blank" href="<?= $this->Url->build('/files/article/header_image/'. $article->header_image);?>"><?=$article->header_image?></a> <?php } ?></label>
                    <input type="file" name="header_image" accept="image/*" id="header-image" class="form-control" onchange="return imageFileValidation(this)">
                    <input type="hidden" name="old_header_image" value="<?=$article->header_image?>">
                    <p class="help-block">Allowed File : jpeg, jpg, png and gif</p>
                  </div>
                </div>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th colspan="4">Link</th>
                      <th width="10%">Order</th>
                      <th> Action</th>
                    </tr>                    
                  </thead>
                  <tbody id="article_links">
                    <?php if(!empty($article['article_links'])) { 
                    $countArticleLinks = count($article['article_links']);
                    foreach($article['article_links'] as $al=>$_links){ 
                    $i=$al+1;
                    ?>
                    <tr class="<?=$_links['id'];?>" id="article_links_<?=$_links['id'];?>">
                      <input type="hidden" name="article_links[<?=$i?>][id]" value="<?=$_links['id']?>">
                      <td><?=$this->Form->control('article_links['.$i.'][link_type]',['options'=>$linkType,'label'=>'Link Type','value'=>$_links['link_type']]);?> </td>
                      <td>
                        <div class="mt-call-<?=$i?> mt-custom-<?=$i?>">
                        <?=$this->Form->control('article_links['.$i.'][custom_link]', ['type' => 'text','label'=>'Custom Link','value'=>$_links['custom_link']]);?>
                      </div>
                      <div class="mt-call-<?=$i?> mt-internal-<?=$i?>">
                        <?=$this->Form->control('article_links['.$i.'][internal_link]', ['type' => 'text','label'=>'Internal Link','value'=>$_links['internal_link']]);?>
                      </div>
                      <div class="mt-call-<?=$i?> mt-article-<?=$i?>">
                        <?=$this->Form->control('article_links['.$i.'][object_id]', ['options' => $articles, 'label' => __('Article'), 'required' => 'required', 'empty'=>__('Please Select Article'),'value'=>$_links['object_id']]);?>
                      </div>
                      <div class="mt-call-<?=$i?> mt-tender-<?=$i?>">
                        <?=$this->Form->control('article_links['.$i.'][object_id]', ['options' => $tenders, 'label' => __('Tender'), 'required' => 'required', 'empty'=>__('Please Select Tender'),'value'=>$_links['object_id']]);?>
                      </div>
                      <div class="mt-call-<?=$i?> mt-swavlamban-<?=$i?>">
                        <?=$this->Form->control('article_links['.$i.'][object_id]', ['options' => $swavlambans, 'label' => __('Swavlamban'), 'required' => 'required', 'empty'=>__('Please Select Swavlamban'),'value'=>$_links['object_id']]);?>
                      </div>
                      </td>
                      <td><?=$this->Form->control('article_links['.$i.'][link_title]',['type'=>'text','label'=>'Link Text','value'=>$_links['link_title']]);?></td>
                      <td><?=$this->Form->control('article_links['.$i.'][redirection]', ['options' => $linkRedirection,'label'=>'Redirection','value'=>$_links['redirection']]);?></td>
                      <td class="content-ms"><input type="number" class="form-control" name="article_links[<?=$i?>][sort_order]" value="<?=$_links['sort_order']?>"></td>
                      <td class="content-ms">
                        <button class="btn btn-danger remove_row" value="article_links-<?=$_links['id'];?>"><i class="glyphicon glyphicon-remove"></i></button>
                      </td>
                    </tr>
                    <?php $this->append('bottom-script');?>
                    <script>
                      $(document).ready(function(){
                        $('#article-links-<?=$i?>-link-type').on('change',function(){
                          var $menu_type = $(this).val();
                          $('.mt-call-<?=$i?>').hide();
                          $('.mt-call-<?=$i?> input, .mt-call-<?=$i?> select').attr('disabled','disabled');
                          $('.mt-' + $menu_type + -<?=$i?>).show();
                          $('.mt-' + $menu_type +'-'+<?= $i?> +' input, .mt-' + $menu_type +'-'+<?=$i ?> +' select').removeAttr('disabled','disabled');
                        }).change();
                      });                        
                    </script>
                    <?php $this->end();?>
                    <?php } } else  { ?>
                    <tr class="1">
                      <td><?=$this->Form->control('article_links[1][link_type]',['options'=>$linkType,'label'=>'Link Type']);?> </td>
                      <td width="30%"><div class="mt-call mt-custom">
                        <?=$this->Form->control('article_links[1][custom_link]', ['type' => 'text','label'=>'Custom Link']);?>
                      </div>
                      <div class="mt-call mt-internal">
                        <?=$this->Form->control('article_links[1][internal_link]', ['type' => 'text','label'=>'Internal Link']);?>
                      </div>
                      <div class="mt-call mt-article">
                        <?=$this->Form->control('article_links[1][object_id]', ['options' => $articles, 'label' => __('Article'), 'required' => 'required', 'empty'=>__('Please Select Article')]);?>
                      </div>
                      <div class="mt-call mt-tender">
                        <?=$this->Form->control('article_links[1][object_id]', ['options' => $tenders, 'label' => __('Tender'), 'required' => 'required', 'empty'=>__('Please Select Tender')]);?>
                      </div>
                      <div class="mt-call mt-swavlamban">
                        <?=$this->Form->control('article_links[1][object_id]', ['options' => $swavlambans, 'label' => __('Swavlamban'), 'required' => 'required', 'empty'=>__('Please Select Swavlamban')]);?>
                      </div>
                      </td>
                      <td><?=$this->Form->control('article_links[1][link_title]',['type'=>'text','label'=>'Link Text']);?></td>
                      <td><?=$this->Form->control('article_links[1][redirection]', ['options' => $linkRedirection,'label'=>'Redirection']);?></td>
                      <td class="content-ms"><input type="number" class="form-control" name="article_links[1][sort_order]"></td>
                      <td><button class="btn btn-danger article_remove">X</button> </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <br>
                <button class="btn btn-default add_article_links"> Add Another Link</button>
                <div class="clearfix"></div>
                <br>
                <div class="panel panel-default">
                  <div class="panel-heading">Upload File</div>
                  <div class="panel-body">
                    <div id="uploaded-files">
                      <?php if (!empty($sessionFilesData)) { ?>
                        <table class="table table-bordered table-condensed table-hover">
                          <thead>
                            <tr>
                              <th>File information</th>
                              <th>Weight</th>
                              <th>Operations</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($sessionFilesData as $seskey => $sessionFilesValue) {
                              $sKey = $seskey+1;
                              $filemime = str_replace('/', '-', $sessionFilesValue['filemime']);
                              if (strpos($filemime,'image') !== false){$filemime = 'image';}
                             ?>
                            <tr class="<?=$filemime?>" id="<?=$sessionFilesValue['id']?>">
                              <td>
                                <div id="edit-field-upload-file-<?=$sKey?>-upload" class="js-form-managed-file form-managed-file">
                                  <input type="hidden" name="field_upload_file[<?=$sKey?>][id]" value="<?=$sessionFilesValue['id']?>">
                                  <input type="hidden" name="field_upload_file[<?=$sKey?>][article_id]" value="<?=$article['id']?>">
                                  <span class="upfile file--mime-<?=$filemime?> file--<?=$filemime?>"> 
                                    <a target="_blank" href="<?= $this->Url->build('/files/article/articlefiles/'. $sessionFilesValue['filename']);?>" type="<?=$filemime?>; length=<?=$sessionFilesValue['filesize']?>" class="article-item__link"><?=$sessionFilesValue['filename']?></a>
                                  </span>
                                  <input type="hidden" name="field_upload_file[<?=$sKey?>][status]" value="1">
                                  <div class="js-form-item form-item js-form-type-textfield form-type-textfield js-form-item-field-upload-file-<?=$sKey?>-description form-item-field-upload-file-<?=$sKey?>-description">
                                    <label for="edit-field-upload-file-<?=$sKey?>-description">Description</label>
                                    <input type="text" id="edit-field-upload-file-<?=$sKey?>-description" name="field_upload_file[<?=$sKey?>][description]" value="<?=$sessionFilesValue['description']?>" size="60" maxlength="128" class="form-text form-control">
                                    <div id="edit-field-upload-file-<?=$sKey?>-description--description" class="description">
                                      The description may be used as the label of the link to the file.
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td class="content-ms">
                                <div class="js-form-item form-item js-form-type-select form-type-select">
                                  <input type="number" id="edit-field-upload-file-<?=$sKey?>-weight" name="field_upload_file[<?=$sKey?>][weight]" value="<?=$sessionFilesValue['weight']?>" size="10" class="form-text form-control">
                                </div>
                              </td>
                              <td class="content-ms">
                                <button value="<?=$sessionFilesValue['id']?>" class="upload_file_remove_button btn js-form-submit form-submit btn btn-danger">Remove</button>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      <?php } ?>
                    </div>

                    <div class="col-md-5 js-form-item form-item js-form-type-managed-file">
                      <div class="row">
                        <?=$this->Form->control('article_files',['templates'=>['inputContainer' =>'<div class="input-area file" id="uploadbox">{{content}}</div>'],'type'=>'file','required'=>false,'label'=>'Add a new file','id'=>'uploadFiles']);?>
                        <span id="uploaded_image"></span>
                        <p class="help-block text-danger">Unlimited number of files can be uploaded to this field.</p>
                        <p class="help-block text-danger">2 MB limit.</p>
                        <p class="help-block text-danger">Allowed types: png jpg jpeg pdf.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <?=$this->Form->control('status');?>
            </div>
          <div class="box-footer">
            <?php 
            echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
            echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
          </div>
        <?php echo $this->Form->end();?>
      </div>
    </div>
  </section>

<?php $this->Html->script(['AdminLTE./plugins/ckeditor/ckeditor'],['block' => 'bottom-script']); ?>

<?php $this->append('bottom-script');?>
<script>
(function($){
  $(document).ready(function(){
    $('#article-links-1-link-type').on('change',function(){
      var $menu_type = $(this).val();
      $('.mt-call').hide();
      $('.mt-call input, .mt-call select').attr('disabled','disabled');
      $('.mt-' + $menu_type).show();
      $('.mt-' + $menu_type + ' input, .mt-' + $menu_type + ' select').removeAttr('disabled','disabled');
    }).change();
    
    <?php $countfr = !empty($article['article_links'])? count($article['article_links']):'1'; ?>
    var countfr = "<?php echo $countfr ?>";
    var max_article_links  = 20; 
    var article_links      = $("#article_links"); 
    var add_article_links  = $(".add_article_links");
    $(add_article_links).on('click',function(e){ 
      e.preventDefault();
      if(countfr < max_article_links){
        countfr++;
        var fr = parseInt($("tbody#article_links tr:last").attr("class"))+1;
        var fr = (isNaN(fr)) ? 1 : fr;
        $(article_links).append('<tr class="'+fr+'"> <td> <div class="input-area select"> <div class="form-group"> <label for="article-links-'+fr+'-link-type">Link Type</label> <select name="article_links['+fr+'][link_type]" id="article-links-'+fr+'-link-type" class="form-control"><option value="custom">Custom</option><option value="article">Article</option><option value="tender">Tender</option><option value="swavlamban">Swavlamban</option><option value="internal">Internal</option> </select> </div></div></td><td> <div class="mt-call-'+fr+' mt-custom-'+fr+'" style=""> <div class="input-area text required" aria-required="true"> <div class="form-group"><label for="article-links-'+fr+'-custom-link">Custom Link</label><input type="text" name="article_links['+fr+'][custom_link]" required="required" id="article-links-'+fr+'-custom-link" class="form-control" aria-required="true"></div></div></div><div class="mt-call-'+fr+' mt-internal-'+fr+'" style=""> <div class="input-area text required" aria-required="true"> <div class="form-group"><label for="article-links-'+fr+'-internal-link">Internal Link</label><input type="text" name="article_links['+fr+'][internal_link]" required="required" id="article-links-'+fr+'-internal-link" class="form-control" aria-required="true"></div></div></div><div class="mt-call-'+fr+' mt-article-'+fr+'"> <div class="input-area select required" aria-required="true"> <div class="form-group"> <label for="article-links-'+fr+'-article-id">Article</label> <select name="article_links['+fr+'][object_id]" required="required" id="article-links-'+fr+'-article-id" class="form-control" aria-required="true"> <option value="">Please Select Article</option> </select> </div></div></div><div class="mt-call-'+fr+' mt-tender-'+fr+'"> <div class="input-area select required" aria-required="true"> <div class="form-group"> <label for="article-links-'+fr+'-tender-id">Tender</label> <select name="article_links['+fr+'][object_id]" required="required" id="article-links-'+fr+'-tender-id" class="form-control" aria-required="true"> <option value="">Please Select Tender</option> </select> </div></div></div><div class="mt-call-'+fr+' mt-swavlamban-'+fr+'"> <div class="input-area select required" aria-required="true"> <div class="form-group"> <label for="article-links-'+fr+'-swavlamban-id">Swavlamban</label> <select name="article_links['+fr+'][object_id]" required="required" id="article-links-'+fr+'-swavlamban-id" class="form-control" aria-required="true"> <option value="">Please Select Swavlamban</option> </select> </div></div></div></td><td> <div class="input-area text"> <div class="form-group"><label for="article-links-'+fr+'-link-title">Link Text</label><input type="text" name="article_links['+fr+'][link_title]" id="article-links-'+fr+'-link-title" class="form-control"></div></div></td><td> <div class="input-area select"> <div class="form-group"> <label for="article-links-'+fr+'-redirection">Redirection</label> <select name="article_links['+fr+'][redirection]" id="article-links-'+fr+'-redirection" class="form-control"> <option value="self">Self</option> <option value="new-window">New Window</option> </select> </div></div></td><td class="content-ms"><input type="number" class="form-control" name="article_links['+fr+'][sort_order]"></td><td class="content-ms"><button class="btn btn-danger article_remove">X</button></td></tr>');
        $.ajax({
          type:'POST',
          async: true,
          cache: false,
          url: baseUrl + '/articles/getOptions',
          data: {
            param : 'article',
          },
          success: function(data) {
            $("#article-links-"+fr+"-article-id").append(data);
          },
          error:function (){
            alert('Something went wrong.');
          }
        });
        $.ajax({
          type:'POST',
          async: true,
          cache: false,
          url: baseUrl + '/articles/getOptions',
          data: {
            param : 'tender',
          },
          success: function(data) {
            $("#article-links-"+fr+"-tender-id").append(data);
          },
          error:function (){
            alert('Something went wrong.');
          }
        });
        $.ajax({
          type:'POST',
          async: true,
          cache: false,
          url: baseUrl + '/articles/getOptions',
          data: {
            param : 'swavlamban',
          },
          success: function(data) {
            $("#article-links-"+fr+"-swavlamban-id").append(data);
          },
          error:function (){
            alert('Something went wrong.');
          }
        });
      
        $('#article-links-'+fr+'-link-type').on('change',function(){
          var $menu_type = $(this).val();
          $('.mt-call-'+fr).hide();
          $('.mt-call-'+fr+' input, .mt-call-'+fr+' select').attr('disabled','disabled');
          $('.mt-' + $menu_type +'-' +fr).show();
          $('.mt-' + $menu_type +'-' + fr + ' input, .mt-' + $menu_type + '-' + fr +' select').removeAttr('disabled','disabled');
        }).change();

      }
    });
    $(article_links).on('click', '.article_remove', function(e) {
      e.preventDefault();       
      $(this).parent().parent().remove(); countfr--;
    });

    $(document).on('change', '#uploadFiles', function() {
      var name = document.getElementById("uploadFiles").files[0].name;
      var form_data = new FormData();
      var ext = name.split('.').pop().toLowerCase();
      $allowed = ['pdf', 'png', 'jpg', 'jpeg', 'docx', 'doc', 'xls', 'xlsx', 'ppt', 'pptx'];
      var invalidfile = name.match(/\./g).length;
      if (invalidfile>1) {
        alert("Invalid File. e.g. double dot.");
        $("#uploadFiles").val("");
      }else if ($.inArray(ext, $allowed) == -1) {
        alert("Invalid Image File");
        $("#uploadFiles").val("");
      }
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("uploadFiles").files[0]);
      var f = document.getElementById("uploadFiles").files[0];
      var fsize = f.size || f.fileSize;
      if (fsize > 52428800) {
        alert("Allowed file size exceeded. (Max. 50 MB)");
        $("#uploadFiles").val("");
      } else {
        form_data.append("article_id", $('#article_id').val());
        form_data.append("article_files", document.getElementById('uploadFiles').files[0]);
        $.ajax({
          url: baseUrl + '/articles/autoUploadFiles',
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
          },
          success: function(data) {
            $("#uploadFiles").val("");
            var dataVal = data;
            var arr = data.split('|');
            if(arr[0] == 'error') {
              alert(arr[1]);
            } else {
              $('#uploaded-files').html(dataVal);
            }
          }
        });
      }
    });

    $(".upload_file_remove_button").on("click",function(e){
      e.preventDefault();
      var article_images_id = $(this).val();
      $.ajax({
        type:'POST',
        async: false,
        cache: false,
        url: baseUrl + '/articles/deleteArticleImage',
        data: {
            id : article_images_id,
            article_id : $('#article_id').val(),
          },
        success: function(response) {
          if($.trim(response)=='success'){
            $("#"+article_images_id).remove();
          }
        },
        error : function (){
          alert('Something went wrong.');
        }
      });
    });

    if(typeof $.validator !== "undefined"){
      $("#article-edit-frm").validate({
        rules:{
          'title':{
            required: true,
          },
          'content':{
            required: function(textarea) {
              CKEDITOR.instances[textarea.id].updateElement(); // update textarea
              var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
              return editorcontent.length === 0;
            }
          }
        },
      });
    }
  });
})($);
$(document).ready(function(){
  var baseUrl = "<?php echo $this->Url->build('/admin/en', true);?>"
  $(".remove_row").on("click",function(e) {
    e.preventDefault();
    var name_id   = $(this).val();
    var table_arr = name_id.split('-');
    var row_id    = table_arr[1];
    var sub_table_name = table_arr[0];
    $.ajax({
      type:'POST',
      cache: false,
      url: baseUrl + '/articles/delete-row',
      data: {
            id : row_id,
            table_name : sub_table_name,
          },
      success: function(response) {
        if($.trim(response)=='removed'){
          $('#'+sub_table_name+'_'+row_id).remove();
        }
      },
    });
  });
});


function imageFileValidation(fileInput){
  var filePath = fileInput.value;
  var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
  var invalidfile = filePath.match(/\./g).length;
  if (invalidfile>1) {
    alert("Invalid File. e.g. double dot.");
    fileInput.value = '';
    return false;
  }else if(!allowedExtensions.exec(filePath)){
    alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
    fileInput.value = '';
    return false;
  }
}
</script>
<?php $this->end();?>
