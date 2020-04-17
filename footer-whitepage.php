<?php if(!defined('ROOTDOC')) die();?>
<!-- JS
================================================== -->
<?php $hcms->printActiveJSResources('footer'); ?>

<?php /* Print to Top Slider 
================================================= */
if(isset($objToTop)) echo $objToTop->toString(); ?>

</body>
</html>