<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:12 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Module $module
 */
if(@$modulesData->id){ $title="Edit"; }else{ $title="Add New"; }
$this->assign('title',__($title. ' Module'));
$this->Html->addCrumb(__('Module'),['action'=>'index']);
$this->Html->addCrumb(__($title. ' Module'));
?>

  <!-- Main content -->
  <section class="content module add form">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">
            <?= __('Module') ?>
            <small><?= __('Add New Module') ?></small>
          </h3>
          <div class="box-tools pull-right">
              <?=$this->Html->link(
                      '<i class="fa fa-arrow-circle-left"></i>',
                      ['action' => 'index'],
                      ['class' => 'btn btn-info btn-xs','title' => __('Back to Modules'),'escape' => false]
                  );?>
            </div>
          </div>
          <!-- /.box-header -->
          <?php echo $this->element('validation'); ?>
          <!-- form start -->
    <?= $this->Form->create($modulesData,['id' =>'addmodule','class' => 'form-horizontal']); ?>
    <div class="box-body">
		<div class="form-group">
		    <label for="pid" class="col-sm-2 control-label">Select Module <span class="important">*</span> </label>
		    <div class="col-sm-5">
		    	<select name="pid" id="pid"  required title="Select parent module" class="form-control">
                    <option value="0">ROOT</option>
                    <?= $this->OptionsData->list_toOption($moduleResult, $modulesData['pid']) ?>
	          </select>
		    </div>
		</div>

		<div class="form-group childmodule">
		    <label for="cid" class="col-sm-2 control-label">Child Module <span class="important">*</span> </label>
		    <div class="col-sm-5">
		    	<?php echo $this->Form->select('cid', $moduleResult,[
		    		'title'=>'Select child module',
		    		'value'=>$modulesData['pid'],
		    		'id'=>'cid',
		    		'empty' => 'ROOT'
		    	]); ?>
		    </div>
		</div>

		<div class="form-group">
			<label for="agency_name" class="col-sm-2 control-label">Module Name <span class="important">*</span> </label>	
			<div class="col-sm-6">
			<?php echo $this->Form->input('name',['label'=>false,'maxlength'=>'255','placeholder'=>'Enter Module Name','class'=>'form-control','required'=>true]); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Module Description </label>	
			<div class="col-sm-5">
			<?php echo $this->Form->textarea('description',['label'=>false,'id'=>'description','maxlength'=>'255','placeholder'=>'Enter Module Description','title'=>'Enter Module Description','class'=>'form-control']); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="agency_name" class="col-sm-2 control-label">Controller Name <span class="important">*</span> </label>	
			<div class="col-sm-6">
			<?php echo $this->Form->input('controller',['label'=>false,'maxlength'=>'40','placeholder'=>'Enter Controller Name','class'=>'form-control','required'=>true]); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="agency_name" class="col-sm-2 control-label">Action Name <span class="important">*</span> </label>	
			<div class="col-sm-6">
			<?php echo $this->Form->input('action',['label'=>false,'maxlength'=>'40','placeholder'=>'Enter Action Name','class'=>'form-control','required'=>true]); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="agency_name" class="col-sm-2 control-label">Font Awesome Icon </label>	
			<div class="col-sm-6">
			<?php echo $this->Form->input('icon',['type'=>'text', 'label'=>false,'id'=>'depth', 'placeholder'=>'Enter Font Awesome Icon class', 'title'=>'Font Awesome Icon class required', 'class'=>'form-control', 'required'=>false]); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="agency_name" class="col-sm-2 control-label">Navigation Position <span class="important">*</span> </label>	
			<div class="col-sm-6">
			<?php echo $this->Form->input('depth',['label'=>false,'id'=>'depth','min'=>'1','placeholder'=>'Enter Navigation Position','class'=>'form-control','required'=>true]); ?>
			</div>
		</div>
		</div>
	<div class="box-footer">
      <?php 
      echo $this->Form->button(__('Submit'),['class' => 'btn btn-primary']);
      echo $this->Html->link(__('Cancel'),['action' => 'index'],['class' => 'btn btn-danger mx-1']); ?>
    </div>
      <?php echo $this->Form->end();?>
    </div>
      </div>
    </div>
</section>

<?php $this->append('bottom-script');?>
<script type="text/javascript">
	// for form validation
	var container = $('div.errorlist');
	// validate the form when it is submitted
	var validator = $("#addmodule").validate({
		errorContainer: container,
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		rules: { 
			name : {
				required: true,
			},
			controller : {
				required: true,
			},
			action : {
				required: true,
			},
			depth : {
				required: true,
			}
		},                             
		messages: {
			name : {
				required: "Enter Module Name",
			},
			controller : {
				required: "Enter Controller Name",
			},
			action : {
				required: "Enter Action Name",
			},
			depth : {
				required: "Enter Action Navigation Position",
			}
		}
	});
	
	if ($('form#addmodule select[name="pid"]').val() == 0) {
        $('form#addmodule input[name="controller"], form#addmodule input[name="action"]').attr('required', false);
        $('form#addmodule input[name="controller"], form#addmodule input[name="action"]').attr('disabled', true);
    }

    if ($('form#addmodule select[name="pid"]').val() != 0) {
        $('form#addmodule input[name="icon"]').attr('disabled', true);
    }

    $('form#addmodule select[name="pid"]').on('change', function () {
        if ($(this).val() == 0) {
            $('form#addmodule input[name="controller"], form#addmodule input[name="action"]').attr('required', false);
            $('form#addmodule input[name="controller"], form#addmodule input[name="action"]').attr('disabled', true);
            $('form#addmodule input[name="icon"]').attr('disabled', false);
        } else {
            $('form#addmodule input[name="controller"], form#addmodule input[name="action"]').attr('required', true);
            $('form#addmodule input[name="controller"], form#addmodule input[name="action"]').attr('disabled', false);
            $('form#addmodule input[name="icon"]').attr('disabled', true);
        }
    });

$('.childmodule').hide();
$('#pid').on('change',function(){
	var baseUrl = "<?php echo $this->Url->build('/admin/modules', true);?>";   
	var pid = $(this).val();	
	$.ajax({
		type:'POST',
		async: true,
		cache: false,
		url: baseUrl + '/getChildModule',
		data: {
			pid : pid,
		},
		beforeSend: function(){
           $(".loading").css("display", "block");
	   	},
		success: function(data) {
		   var response=$.parseJSON(data);
			if(response.status == 'true'){
			 	$('#cid').html(response.data);
			 	$('.childmodule').show();
			} else {
			 	$('.childmodule').hide();
			}
			$(".loading").css("display", "none");
		},
		error:function (data){
			alert('You are logged out. Please login again');
			window.location = "<?php echo $this->Url->build('admin/users/login', true);?>";
		},
	});	
})
$(document).ajaxSend(function(e, xhr, settings) {
	xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
});

</script>
<?php $this->end();?>