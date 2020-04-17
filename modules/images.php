<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Image Module', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de'));

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);


    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles('latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
            \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php');
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row">
        <article class="col-xs-12 col-md-7" id="mainContent">
            <h1>Image Module</h1>
  
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>default (70)</td>
                    <td>904</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 904)); ?>" 
                                 alt="Frau" />                   
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
                
                
            </figure>  
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>70</td>
                    <td>903</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 903, 70)); ?>" 
                                 alt="Frau" />
                    <noscript>
                        <img class="img-responsive" itemprop="thumbnail"
                            src="<?php echo htmlentities(
                                    $hcms->scaleImg('/images/frau-1024x768.jpg', 904, 70)); ?>" 
                                    alt="Frau" />
                    </noscript>                    
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>             
        
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>60</td>
                    <td>902</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 902, 60)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>        
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>50</td>
                    <td>901</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 901, 50)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>40</td>
                    <td>900</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 900, 40)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>30</td>
                    <td>899</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 899, 30)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>    
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>20</td>
                    <td>898</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 898, 20)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>10</td>
                    <td>897</td>
                    <td>auto</td>
                    <td>jpg</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.jpg" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.jpg', 897, 10)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>  
            
            <!-- PNG -->
            <!-- === -->
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>default (70)</td>
                    <td>904</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 904)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>70</td>
                    <td>903</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 903, 70)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>             
        
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>60</td>
                    <td>902</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 902, 60)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>        
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>50</td>
                    <td>901</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 901, 50)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>40</td>
                    <td>900</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 900, 40)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>30</td>
                    <td>899</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 899, 30)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>    
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>20</td>
                    <td>898</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 898, 20)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure>   
            
            <table class="table">
                <tr>
                    <th>Quality</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Filetype</th>
                </tr>
                <tr>
                    <td>10</td>
                    <td>897</td>
                    <td>auto</td>
                    <td>png</td>
                </tr>
            </table>
            <figure class="add-bottom-20" itemscope itemtype="http://schema.org/diagram">
                <a class="lightbox-gallery" href="/images/frau-1024x768.png" 
                   title="Frau" 
                   itemprop="contentUrl">
                    <img class="img-responsive" itemprop="thumbnail"
                         hyb-ll-src="<?php echo htmlentities(
                                 $hcms->scaleImg('/images/frau-1024x768.png', 897, 10)); ?>" 
                                 alt="Frau" />
                </a>
                <figcaption itemprop="caption">Frau</figcaption>
            </figure> 
        </article>        

        <?php
        /* include Footer
         * ================================================== */
        require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php');
        ?>  
    </div><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
?>