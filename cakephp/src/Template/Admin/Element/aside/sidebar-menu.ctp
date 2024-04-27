<?php
/**
 * Element : Sidebar menu
 * Sidebar menu controll all list of menu.
 * @author : Nilesh Kushvaha
 * @version : 1.1
 * @since : 24 October 2018
 */
?>
<?php
$action = !empty($this->request->getParam('action')) ? $this->request->getParam('action') : null;
$controller = !empty($this->request->getParam('controller')) ? $this->request->getParam('controller') : null;
?>
<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li>
        <?=$this->Html->link('<i class="fa fa-dashboard"></i> <span>Dashboard</span>',['controller'=>'dashboard','action'=>'index'], ['class'=> 'silvermenu', 'title' => __('Dashboard'), 'escape' => false]);?>
    </li>
    <?php 
    $i = 0;
    foreach($roledata as $navaction) { ?>
        <li class="treeview">
            <a href="#id<?= $i; ?>"><i class="fa <?=$navaction['ParentModule']['icon']?>"></i> <span><?=$navaction['ParentModule']['name']?></span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>

        <?php if(count($navaction['ChildModule'])){ ?>
        <ul class="treeview-menu" id="id<?= $i; ?>">
            <?php foreach($navaction['ChildModule'] as $Subnavaction) { 
            $cusac = lcfirst(str_replace('-', '',ucwords($Subnavaction['action'], "-")));
            $cuscont = str_replace('-', '',ucwords($Subnavaction['controller'], "-"));
            $str = '';
            if(($controller == $Subnavaction['controller'] || $controller == $cuscont) && ($action == $cusac)){
                $str="active";
            }else{
                $str="";
            } ?>
            <li>
                <?=$this->Html->link('<i class="fa fa-circle-o"></i>'.$Subnavaction['name'],['controller'=>ucfirst($Subnavaction['controller']),'action'=>strtolower($Subnavaction['action'])], ['class'=> 'silvermenu', 'title' => __($Subnavaction['name']), 'escape' => false]);?>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </li>
    <?php $i++; } ?>
	
	<?php if($this->request->session()->read('Auth.User.role_id')=='7') { ?>
        <li>
        <?= $this->Html->link('<i class="fa fa-circle-o"></i>User Mannual Intern', '/files/user_mannual/Instruction_Set_Add_New_Cooperative_NCD.pdf',['download'=>'Instruction_Set_Add_New_Cooperative_NCD.pdf' ,'escape' => false]);?>
        </li>
        <?php } ?>

        <?php if($this->request->session()->read('Auth.User.role_id')=='8') { ?>
        <li>
        <?= $this->Html->link('<i class="fa fa-circle-o"></i>User Mannual Intern', '/files/user_mannual/Instruction_Set_Add_New_Cooperative_NCD.pdf',['download'=>'Instruction_Set_Add_New_Cooperative_NCD.pdf' ,'escape' => false]);?>

        <?= $this->Html->link('<i class="fa fa-circle-o"></i>User Mannual Nodal', '/files/user_mannual/Instruction Set For Nodal Officers_NCD-2.pdf',['download'=>'Instruction Set For Nodal Officers_NCD-2.pdf' ,'escape' => false]);?>
        </li>
        <?php } ?>
           <?php if($this->request->session()->read('Auth.User.role_id')=='7' || $this->request->session()->read('Auth.User.role_id')=='8') { ?>
            <li class="treeview">
            <a href="#id4567"><i class="fa <?=$navaction['ParentModule']['icon']?>"></i> <span>DownLoad Video Tutorials </span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
            <ul class="treeview-menu" id="idid4567">
                <li>
                <?= $this->Html->link('<i class="fa fa-circle-o"></i>Software functioning video', '/files/videos/Discussion_with_the_District_ Nodal_Officers_on_software_functioning-20221227_0842-1.mp4',['download'=>'Discussion_with_the_District_ Nodal_Officers_on_software_functioning-20221227_0842-1.mp4' ,'escape' => false]);?>
                </li>
                <li>
                <?= $this->Html->link('<i class="fa fa-circle-o"></i>Clarification on technical issues', '/files/videos/Clarification_on_technical_issues_of_Software_of_National_Cooperative_Database-Nodal_Officers-202212210846-1.mp4',['download'=>'Clarification_on_technical_issues_of_Software_of_National_Cooperative_Database-Nodal_Officers-202212210846-1.mp4' ,'escape' => false]);?>
                </li>
                <li>
                <?= $this->Html->link('<i class="fa fa-circle-o"></i>Clarification on technical issues', '/files/videos/Clarification_on_technical_issues_of_Software_of_National_Cooperative_Database-Nodal_Officers-202212210513-1.mp4',['download'=>'Clarification_on_technical_issues_of_Software_of_National_Cooperative_Database-Nodal_Officers-202212210513-1.mp4' ,'escape' => false]);?>
                </li>
        </ul>
        </li>
        <?php } ?>
</ul>
