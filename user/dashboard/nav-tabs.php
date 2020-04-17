<?php if(!defined('ROOTDOC')) die();?>
<ul class="nav nav-tabs">
    
  <li class="<?php echo $hcms->isCurrDoc('/user/dashboard/index.html'); ?>">
      <a href="/user/dashboard/index.html">Dashboard</a></li>
  
  <li class="<?php echo $hcms->isCurrDoc('/user/dashboard/profile.html'); ?>">
      <a href="/user/dashboard/profile.html">Profil</a></li>
  
  <?php if(true === isset($_SESSION['type']) && $_SESSION['type'] === 'registered') :?>
  <li class="<?php echo $hcms->isCurrDoc('/user/dashboard/password.html'); ?>">
      <a href="/user/dashboard/password.html">Passwort</a></li>
      
  <?php endif; ?>
</ul>