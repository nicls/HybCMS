<?php if(!defined('ROOTDOC')) die(); ?>
<!-- Primary Page Layout
================================================== -->
<div class="container">
    <section class="row" id="mainContent" itemprop="mainContentOfPage">
        <div class="col-xs-12 col-md-7">
            
            <?php /** Print FontSize Plugin
            ================================================== */
            if(isset($objFontSize) && !empty($objFontSize)) {
                echo $objFontSize->toString();
            }
            ?>
            
            <h2>Testseite CDS Desktop</h2>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>

            <?php /** Print CTAButton Plugin
            ================================================== */
            if(true === isset($objControllerCTAButton)) {
                echo $objControllerCTAButton->toString();
            }
            ?>
           
            
            <?php
            /** Begin Print Articles
              ================================================== */
            $cnt = 0;

            foreach ($arrObjArticles as &$objArt) {

                //get img in 660px width
                $primImg = null;
                if ($objArt->getPrimaryImageOfPage()) {
                    $primImg = $hcms->scaleImg($objArt->getPrimaryImageOfPage(), 660);
                }

                //build a-tag to the Article
                $articleAHrefBegin = '<a href="' 
                        . htmlentities($objArt->getUrl()) 
                        . '" title="' 
                        . htmlentities($objArt->getFirstHeadline()) . '">';
                
                //close a-tag
                $articleAHrefEnd = '</a>';

                //print every 5th article over the whole width
                $articleWidth = 'c6';
                if ($cnt++ % 3 == 0) {
                    $articleWidth = 'add-right-20';
                }

                $op = '';

                //open Article-tag
                $op .= '<article class="' . $articleWidth . ' add-bottom-20">';

                //set headline
                $op .= '<header><h2>' . $articleAHrefBegin 
                        . htmlspecialchars($objArt->getFirstHeadline()) 
                        . $articleAHrefEnd . '</h2></header>';

                if (isset($primImg) && !empty($primImg)) {

                    //open figure-tag
                    $op .= '<figure>';

                    //add image
                    $op .= $articleAHrefBegin . '<img src="' 
                            . htmlentities($primImg) . '" alt="' 
                            . htmlentities($objArt->getFirstHeadline()) 
                            . '" width="660" />' . $articleAHrefEnd;

                    //close figure tag
                    $op .= '</figure>';
                }

                //add description 
                $op .= '<p class="teaserText">' 
                        . htmlspecialchars($objArt->getDescription()) 
                        . $articleAHrefBegin 
                        . ' <i class="icon-angle-right color888"></i></a></p>';

                //close article-tag
                $op .= '</article>';

                //print article
                echo $op;

                /** End Print Articles
                  ================================================== */
            }
            ?>
            
            <p class="add-top-30">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. </p>
            <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. </p>
            <p>Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. </p>
            <p>Suspendisse in justo eu magna luctus suscipit. Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui. Praesent blandit dolor. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. Donec lacus nunc, viverra nec, blandit vel, egestas et, augue. Vestibulum tincidunt malesuada tellus. </p>
            <p>Ut ultrices ultrices enim. Curabitur sit amet mauris. Morbi in dui quis est pulvinar ullamcorper. Nulla facilisi. Integer lacinia sollicitudin massa. Cras metus. Sed aliquet risus a tortor. Integer id quam. Morbi mi. Quisque nisl felis, venenatis tristique, dignissim in, ultrices sit amet, augue. Proin sodales libero eget ante. Nulla quam. Aenean laoreet. </p>

            <?php
                $objRating->toString();            
            ?>

        </div><!-- end .col-xs-12 .col-md-7 -->

    <?php
    /* include Footer
     * ================================================== */
    require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>  
        
    </section><!-- end section.row -->
</div><!-- end div.container -->