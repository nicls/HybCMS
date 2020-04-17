<?php if(!defined('ROOTDOC')) die();?>
<nav class="navbar navbar-default remove-bottom" role="navigation">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand <?php echo $hcms->isCurrDoc('/index.php'); ?>" href="/">
            <?php echo htmlspecialchars($hcms->getString('homepage')); ?>
        </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">    
        <ul class="nav navbar-nav">
            <li class="<?php echo $hcms->isCurrDoc('/taxonomy.html'); ?>"><a href="/taxonomy.html">Taxonomy</a></li>
            
            <!-- Plugins -->
            <li class="dropdown">
                <a href="/plugins/index.html" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/plugins/index.html'); ?>" data-toggle="dropdown">Plugins <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/comptable.html'); ?>"><a href="/plugins/comptable.html">Comptable</a></li>                    
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/poll.html'); ?>"><a href="/plugins/poll.html">Poll</a></li>                    
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/readdetection.html'); ?>"><a href="/plugins/readdetection.html">ReadDetection</a></li>                    
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/bgImgSlideShow.html'); ?>"><a href="/plugins/bgImgSlideShow.html">BGImgSlideShow</a></li>                    
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/comments.html'); ?>"><a href="/plugins/comments.html">Comments</a></li>                                        
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/smb.html'); ?>"><a href="/plugins/smb.html">Social Media Buttons</a></li>                                        
                    <li class="<?php echo $hcms->isCurrDoc('/plugins/news.html'); ?>"><a href="/plugins/news.html">News</a></li>                                        
                </ul>
            </li>   
            
            <!-- Moudles -->
            <li class="dropdown">
                <a href="/modules/index.html" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/plugins/index.html'); ?>" data-toggle="dropdown">Modules <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/modules/mobile-first.html'); ?>"><a href="/modules/mobile-first.html">Mobile First</a></li>                     
                    <li class="<?php echo $hcms->isCurrDoc('/modules/images.html'); ?>"><a href="/modules/images.html">Image Module</a></li>                                         
                    <li class="<?php echo $hcms->isCurrDoc('/modules/amazon-api.html'); ?>"><a href="/modules/amazon-api.html">Amazon API</a></li>
                </ul>
            </li>            

            <li class="dropdown">
                <a href="/cateins/index.html" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/cateins/index.html'); ?>" data-toggle="dropdown">Category One <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/cateins/cat-1-1.html'); ?>"><a href="/cateins/cat-1-1.html">Subcategory One</a></li>
                    <li class="<?php echo $hcms->isCurrDoc('/cateins/cat-1-2.html'); ?>"><a href="/cateins/cat-1-2.html">Subcategory Two</a></li>
                </ul>
            </li>
            
            <li class="dropdown">
                <a href="/catzwei/index.html" class="dropdown-toggle <?php echo $hcms->isCurrDoc('/catzwei/index.html'); ?>" data-toggle="dropdown">Category Two <i class="caret"></i></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo $hcms->isCurrDoc('/catzwei/cat-2-1.html'); ?>"><a href="/catzwei/cat-2-1.html">Subcategory One</a></li>
                    <li class="<?php echo $hcms->isCurrDoc('/catzwei/cat-2-2.html'); ?>"><a href="/catzwei/cat-2-2.html">Subcategory Two</a></li>
                </ul>
            </li>            
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>