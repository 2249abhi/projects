<!-- Navbar Start -->
<nav class="wrapper nav-wrapper">
  <div class="container nav-container">
    <div class="login-link"></div>
    <!--Main nav goes here-->
    <div id="main-menu">
      <div class="menu-block-wrapper menu-block-2 menu-name-main-menu parent-mlid-0 menu-level-1">
        <ul class="menu" id="nav">
          <li class="menu__item is-active is-leaf first leaf active menu-mlid-218"><a href="<?=$this->Url->build(['controller'=>'home','action'=>'index'])?>" class="menu__link active" role="link">Home</a></li>
          <li class="menu__item is-parent is-leaf leaf has-children menu-mlid-2626   "><a href="#"  class="menu__link" role="link">Operational Structure</a> </li>
          <li class="menu__item is-expanded expanded menu-mlid-3501          "><a href="#"  class="menu__link" role="link">Notifications</a> </li>
          <li class="menu__item is-expanded expanded menu-mlid-951"><a href="#" class="menu__link" role="link"> Training and Demonstration </a> </li>
          <li class="menu__item is-expanded expanded menu-mlid-3498"><?php echo $this->Html->link('GIS',['controller' => 'gis', 'action' => 'index'], array('class' => 'menu__link', 'role' => 'link'));?> </li>
          <li class="menu__item is-expanded expanded menu-mlid-3498"><a href="#"  class="menu__link" role="link">Contact Us</a> </li>
         
          <li class="menu__item is-expanded expanded menu-mlid-3463"><a href="<?=$this->Url->build(['controller'=>'users','action'=>'login','prefix'=>'admin'])?>" role="link">Login </a> </li>
        </ul>
      </div>
    </div>
  </div>
</nav>