<?php
class ThemeWidgetsTest extends PHPUnit_Framework_TestCase
{
	const TMP_PATH = '../../../../../files/zend-tmp';
	const DEFAULT_URL = "http://feeds.feedburner.com/benefitsworld";
	const DEFAULT_AMOUNT_OF_ARTICLES = 2;
	const DEFAULT_CACHE_NAME = 'CacheFile';
	
	public function setUp() {
		Zend_Loader_Autoloader::getInstance();
		$this->_cache_dir = $this->mkdir();
		$this->_themeWidget = ThemeWidgets::factory(self::DEFAULT_URL, self::DEFAULT_AMOUNT_OF_ARTICLES, self::DEFAULT_CACHE_NAME);
		$this->_themeWidget->setCacheTmpPath($this->_cache_dir);				// inject new directory
		$this->_themeWidget->setSideBarImageDir('../images/sidebar-images');	// inject images for sidebar
	}
	
	public function tearDown() {
		$this->rmdir();
	}
	
	public function testConstatRssKeyExists() {
		$tw = $this->_themeWidget;
		$this->assertEquals('cachedRss', $tw::KEY__RSS_FEED);
	}
	
	public function testConstatImageKeyExists() {
		$tw = $this->_themeWidget;
		$this->assertEquals('cachedImage', $tw::KEY__DAILY_IMAGE);
	}
	
	public function testConstatDateKeyExists() {
		$tw = $this->_themeWidget;
		$this->assertEquals('cachedDate', $tw::KEY__LAST_CACHED_DATE);
	}
	
	public function testCacheKeyDoesNotExist() {
		$tw = $this->_themeWidget;
		$this->assertEquals(null, $tw->retrieve('foo'));
	}
	
	public function testRssCacheKeyFound() {
		$tw = $this->_themeWidget;
		$this->assertNotNull($tw->retrieve($tw::KEY__RSS_FEED),"Cache RSS key not found");
	}
	
	public function testImageCacheKeyFound() {
		$tw = $this->_themeWidget;
		$this->assertNotNull($tw->retrieve($tw::KEY__DAILY_IMAGE),"Cache Image key not found");
	}
	
	public function testDateCacheKeyFound() {
		$tw = $this->_themeWidget;
		$this->assertNotNull($tw->retrieve($tw::KEY__LAST_CACHED_DATE),"Cache Date key not found");
	}
	
	public function testCacheDirNotFound() {
		$tw = $this->_themeWidget;
		$tw->setCacheTmpPath('/path/to/foo');
		$this->assertEquals(null,$tw->retrieve($tw::KEY__LAST_CACHED_DATE));
	}
	
	public function testUrlIsActiveAtRecache() {}
	
	public function testUrlIsDeadAtRecache() {
		$tw = $this->_themeWidget;
		$tw->setRssUrl('http://local.foo.bar');
		$this->assertEquals(null,$tw->retrieve($tw::KEY__RSS_FEED));
	}
	
	public function testImageExistsWithCacheFile() {}
	
	public function testImageDoesNotExistWithCacheFile() {}
	
	public function testNewImageToCacheFile() {}
	
	public function testNoImageToCacheFile() {
		$tw = $this->_themeWidget;
		$tw->setSideBarImageDir('/path/to/foo');
		$this->assertEquals(null,$tw->retrieve($tw::KEY__DAILY_IMAGE));
	}
	
	public function testImageNotFoundToCacheFile() {}

	
	public function testSetDirectoryToRealDir() {
		$tw = $this->_themeWidget;
		try {
			$tw->setSideBarImageDir($this->_cache_dir);
		}
		catch (Exception $ex) {
			return;
		}
		$this->assertTrue(true);
	}
	
	public function testSetDirectoryToBadDir() {
		$tw = $this->_themeWidget;
		try {
			$tw->setSideBarImageDir('/path/to/foo');
		}
		catch (Exception $ex) {
			return;
		}
		$this->assertTrue(false);
	}
	
	// Helper Methods

    public function mkdir()
    {
        $tmp = $this->getTmpDir();
        @mkdir($tmp);
        return $tmp;
    }

    public function rmdir()
    {
        $tmpDir = $this->getTmpDir(false);

        foreach (glob("$tmpDir*") as $dirname) {
        	//@rmdir($dirname);
        	$this->deleteAll($dirname);
        }
    }
    
    protected function deleteAll($directory, $empty = false) 
    {
	    if(substr($directory,-1) == "/") {
	        $directory = substr($directory,0,-1);
	    }
	
	    if(!file_exists($directory) || !is_dir($directory)) {
	        return false;
	    } else if(!is_readable($directory)) {
	        return false;
	    } else {
	        $directoryHandle = opendir($directory);
	       
	        while ($contents = readdir($directoryHandle)) {
	            if($contents != '.' && $contents != '..') {
	                $path = $directory . "/" . $contents;
	               
	                if(is_dir($path)) {
	                    $this->deleteAll($path);
	                } else {
	                    unlink($path);
	                }
	            }
	        }
	       
	        closedir($directoryHandle);
	
	        if($empty == false) {
	            if(!rmdir($directory)) {
	                return false;
	            }
	        }
	        return true;
    	}
	} 

    public function getTmpDir($date = true)
    {
        $suffix = '';

        $tmp = self::TMP_PATH;

        if ($date) {
            $suffix = date('mdyHis');
        }
        if (is_writeable($tmp)) {
            return $tmp . DIRECTORY_SEPARATOR . 'zend_cache_tmp_dir_' . $suffix;
        } else {
            throw new Exception("no writable tmpdir found");
        }
    }
}
