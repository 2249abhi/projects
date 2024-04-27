<?php
/**
  * @var \App\View\AppView $this
  * @author Nilesh Kumar
  */
?>
<section class="content">
    <div class="contentbox">
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $rolespermission->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $rolespermission->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Rolespermissions'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="rolespermissions form large-9 medium-8 columns content">
    <?= $this->Form->create($rolespermission) ?>
    <fieldset>
        <legend><?= __('Edit Rolespermission') ?></legend>
        <?php
            echo $this->Form->input('rid');
            echo $this->Form->input('module');
            echo $this->Form->input('moduletask');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
</div>
</div>
