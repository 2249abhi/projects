<?php
/**
  * @var \App\View\AppView $this
  * @author Nilesh Kumar
  */
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Rolespermission'), ['action' => 'edit', $rolespermission->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Rolespermission'), ['action' => 'delete', $rolespermission->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rolespermission->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Rolespermissions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Rolespermission'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="rolespermissions view large-9 medium-8 columns content">
    <h3><?= h($rolespermission->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Module') ?></th>
            <td><?= h($rolespermission->module) ?></td>
        </tr>
        <tr>
            <th><?= __('Moduletask') ?></th>
            <td><?= h($rolespermission->moduletask) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($rolespermission->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Rid') ?></th>
            <td><?= $this->Number->format($rolespermission->rid) ?></td>
        </tr>
    </table>
</div>
