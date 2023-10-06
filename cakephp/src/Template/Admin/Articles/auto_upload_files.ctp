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
      <?php foreach ($sessionFilesData as $key => $sessionFilesValue) { ?>
      <tr class="<?=$sessionFilesValue['filemime']?>">
        <td>
          <div id="edit-field-upload-file-0-upload" class="js-form-managed-file form-managed-file">
            <?php 
              $filemime = str_replace('/', '-', $sessionFilesValue['filemime']);
              if (strpos($filemime,'image') !== false){$filemime = 'image';}
            ?>
            <input type="hidden" name="field_upload_file[0][id]" value="<?=$sessionFilesValue['id']?>">
            <input type="hidden" name="field_upload_file[0][article_id]" value="<?=@$article['id']?>">
            <span class="upfile file--mime-<?=$filemime?> file--<?=$filemime?>"> 
              <a target="_blank" href="<?= $this->Url->build('/files/article/articlefiles/'. $sessionFilesValue['filename']);?>" type="<?=$filemime?>; length=<?=$sessionFilesValue['filesize']?>" class="article-item__link"><?=$sessionFilesValue['filename']?></a>
            </span>
            <input type="hidden" name="field_upload_file[0][display]" value="1">
            <div class="js-form-item form-item js-form-type-textfield form-type-textfield js-form-item-field-upload-file-0-description form-item-field-upload-file-0-description">
              <label for="edit-field-upload-file-0-description">Description</label>
              <input type="text" id="edit-field-upload-file-0-description" name="field_upload_file[0][description]" value="<?=$sessionFilesValue['description']?>" size="60" maxlength="128" class="form-text form-control">
              <div id="edit-field-upload-file-0-description--description" class="description">
                The description may be used as the label of the link to the file.
              </div>
            </div>
          </div>
        </td>
        <td class="content-ms">
          <div class="js-form-item form-item js-form-type-select form-type-select">
            <input type="number" id="edit-field-upload-file-0-weight" name="field_upload_file[0][weight]" value="<?=$sessionFilesValue['weight']?>" size="10" class="form-text form-control">
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