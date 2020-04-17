
<nav class="navbar navbar-default remove-bottom" role="navigation">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand <?php echo $hcms->isCurrDoc('/admin/index.php'); ?>" href="/admin/">Übersicht</a>
    </div>
    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">    
        <ul class="nav navbar-nav">     
            
            <!-- Section Articles -->
            <li class="dropdown">
                <a href="/admin/settings/" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/admin/settings/index.php'); ?>" data-toggle="dropdown">Settings <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/admin/article/addUser.php'); ?>"><a href="/admin/settings/addUser.php">User hinzufügen</a></li>
                </ul>
            </li>               
            
            <!-- Section Articles -->
            <li class="dropdown">
                <a href="/admin/article/" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/admin/article/'); ?>" data-toggle="dropdown">Article <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/admin/article/addArticle.php'); ?>"><a href="/admin/article/addArticle.php">Article hinzufügen</a></li>
                    <li class="<?php echo $hcms->isCurrDoc('/admin/article/comments.php'); ?>"><a href="/admin/article/comments.php">Kommentare</a></li>
                </ul>
            </li>                      
        
            <!-- Section Plugins -->
            <li class="dropdown">
                <a href="/admin/plugins" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/admin/plugins/'); ?>" data-toggle="dropdown">Plugins <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/admin/plugins?name=comptable&action=manageComptables'); ?>"><a href="/admin/plugins?name=comptable&action=manageComptables">Manage Comptables</a></li>
                    <li class="<?php echo $hcms->isCurrDoc('/admin/plugins?name=poll&action=managePolls'); ?>"><a href="/admin/plugins?name=poll&action=managePolls">Manage Polls</a></li>
                    <li class="<?php echo $hcms->isCurrDoc('/admin/plugins?name=comments&action=manageComments'); ?>"><a href="/admin/plugins?name=comments&action=manageComments">Manage Comments</a></li>
                    <li class="<?php echo $hcms->isCurrDoc('/admin/plugins?name=news&action=manageNews'); ?>"><a href="/admin/plugins?name=news&action=manageNews">Manage News</a></li>
                </ul>
            </li>     
            
        </ul>        
    </div><!-- /.navbar-collapse -->    
</nav>