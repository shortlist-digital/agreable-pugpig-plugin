<?php

require_once 'pugpig_wordpress_mock.php';
require_once 'pugpig_article_rewrite.php';

class PugpigProxiedInlineImageURLReplacementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testImageReplacementNormal($body, $prefix, $expected)
    {
    	$this->assertEquals($expected, \pugpig_replace_image_urls($body, $prefix));
    }

    public function dataProvider()
    {
    	$prefix = 'PugpigReplaceImageURLsTest';
      return array(
          array('', $prefix, ''),
          array('<img src="http://pugpig.com/banana.jpg">', $prefix, '<img src="../..' . strrchr(WP_CONTENT_URL, '/') . '/PugpigReplaceImageURLsTest/aHR0cDovL3B1Z3BpZy5jb20vYmFuYW5hLmpwZw__.jpeg">')
        );
    }
}

?>
