<?php

namespace HybridCMS\Test\HybridCMS\Modules;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp() 
    {
        $this->fixture = null;
        $this->fixture = new \HybridCMS\Modules\Image('/var/www/HybCMS/HybridCMS/Test/images/frau-1024x768.jpg');
    }
    
    public function tearDown() 
    {
            unset($this->fixture);
    }
    
    public function testGetTitle() 
    {        
        for($i = 0; $i<1000; $i++)
        {
            //$this->fixture = new \HybridCMS\Modules\Image('/var/www/HybCMS/HybridCMS/Test/images/frau-1024x768.jpg');
            $path = $this->fixture->crop(200, 200);            
        }
        
    }    
}
?>