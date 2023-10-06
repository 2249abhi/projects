<?php
/**
 * @author \Nilesh Kushvaha
 * @version \1.1
 * @since \October 29, 2018, 7:05 am
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->assign('title',__('Users'));
$this->Breadcrumbs->add(__('User Log'));
?>
  <!-- Main content -->
  <section class="content user index">
    <div class="row">
      <div class="col-xs-12">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <div class="box box-primary">
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('Users') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('Last Login') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('IP Address') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $paginatorInformation = $this->Paginator->params();
                $pageOffset=($paginatorInformation['page']-1);
                $perPage = $paginatorInformation['perPage'];
                $counter = ($pageOffset*$perPage);
                $i = 1;
                foreach ($adminLog as $user): ?>
                  <tr>
                    <td><?= $this->Number->format($i+$counter) ?></td>
                    <td><?= h($user->user->name) ?></td>
                    <td><?= date('Y-m-d H:i A',strtotime($user->logtime)) ?></td>
                    <td><?= inet_ntop(stream_get_contents($user->ipaddress)) ?></td>
                  </tr>
                  <?php $i++;
                  endforeach; ?>
                  <?php if(count($adminLog) == 0):?>
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