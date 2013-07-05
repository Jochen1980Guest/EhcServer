<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UtilitiesController extends AbstractActionController{
	
	private $pathToLogFile;
	private $testResults;
	
	public function __construct(){
		$this->pathToLogFile = APP_ROOT . '/data/logs/application.log';
	}
	
	public function clearLogAction(){
		// check user session
		if (!$this->zfcUserAuthentication()->hasIdentity()) {
			return $this->redirect()->toRoute('zfcuser/login');
		}
		$path = $this->pathToLogFile;
		$this->clearFileContents($path);
		// set first log message
		$logMessage = "Systemnachrichten wurden gelöscht!";
		$this->log($logMessage);
		return new ViewModel();
	}
	
    public function indexAction(){
    	// check user session
    	if (!$this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute('zfcuser/login');
    	}
    	$this->layout()->pageTitle 		= "Utilities | Index";
    	return new ViewModel();
    }
    
    public function testAction(){
    	if (!$this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute('zfcuser/login');
    	}
    	$this->runTestSuites();
    	return new ViewModel( array(
    		'testResults' => $this->testResults
    	));
    }
    
    public function showDocumentationAction(){
    	return new ViewModel();
    }
    
    public function readLastLinesOfFile($path, $numberOfLinesToRead){
    	$lines = file($path, FILE_IGNORE_NEW_LINES);
    	$sumOfLines = count($lines);
    	$result = array();
    	for ($i = 0; $i < $numberOfLinesToRead; $i++){
    		if ($i < $sumOfLines){
    			$result[] = $lines[$i];
    		} else {
    			break;
    		}
    	}
    	return $result;
    }
    
    public function clearFileContents($path){
    	file_put_contents($path, "");
    }
    
    public function createFile($path, $content){
    	file_put_contents($path, $content);
    }
    
    public function log($logMessage){
    	$logFile = $this->pathToLogFile;
    	$formatter = new \Zend\Log\Formatter\Simple('%timestamp% - %priorityName% (%priority%), %message% %extra%', 'Y-m-d H:i:s');
    	$writer = new \Zend\Log\Writer\Stream($logFile);
    	$writer->setFormatter($formatter);
    	$logger = new \Zend\Log\Logger();
    	$logger->addWriter($writer);
    	$logger->log(\Zend\Log\Logger::INFO, $logMessage);
    }
    
    public function translateKey($key, $locale) { // public due to unit test
    	$value = "NoTranslationValue";
    	if ($locale == "de"){
    		$translatedArray = require_once( APP_ROOT . '/module/Application/language/de.php' );
    		$value = $translatedArray[$key];
    	} else if ($locale == "en"){
    		$translatedArray = require_once( APP_ROOT . '/module/Application/language/en.php' );
    		$value = $translatedArray[$key];
    	} else {}
    	return $value;
    }
    
    public function runTestSuites(){
    	$this->testResults = array();
    	$this->testUtilitiesReadLastLinesOfFile();
    	$this->testUtilitiesTranslateKey();
    }
    
    public function testUtilitiesReadLastLinesOfFile(){
    	// Test-1
    	$resultTestUtilitiesReadLastLinesOfFile = array();
    	$resultTestUtilitiesReadLastLinesOfFile['name'] = 'testUtilitiesReadLastLinesOfFile';
    	$path = APP_ROOT . '/data/logs/applicationTest.log';
    	$content = "2013-05-20 13:08:44 - INFO (6): TestAction\n2013-05-20 13:08:46 - INFO (6): TestAction";
    	$this->createFile($path, $content);
    	$exp = "2013-05-20 13:08:44 - INFO (6): TestAction";
    	$actArray = $this->readLastLinesOfFile($path, 1);
    	$act = $actArray[0];
    	if ($exp == $act){
    		$resultTestUtilitiesReadLastLinesOfFile['result'] = true;
    	} else {
    		$resultTestUtilitiesReadLastLinesOfFile['result'] = false;
    	}
    	$this->testResults[] = $resultTestUtilitiesReadLastLinesOfFile;
    }
    
    public function testUtilitiesTranslateKey(){
    	// Test-1
    	$resultTestUtilitiesTranslateKey = array();
    	$resultTestUtilitiesTranslateKey['name'] = 'testUtilitiesTranslateKey_1';
    	$exp = "Wert";
    	$act = $this->translateKey("key","de");
    	if ($exp == $act){
    		$resultTestUtilitiesTranslateKey['result'] = true;
    	} else {
    		$resultTestUtilitiesTranslateKey['result'] = false;
    	}
    	$this->testResults[] = $resultTestUtilitiesTranslateKey;
    	// Test-2
    	$resultTestUtilitiesTranslateKey2 = array();
    	$resultTestUtilitiesTranslateKey2['name'] = 'testUtilitiesTranslateKey_2';
    	$exp = "value";
    	$act = $this->translateKey("key","en");
    	if ($exp == $act){
    		$resultTestUtilitiesTranslateKey2['result'] = true;
    	} else {
    		$resultTestUtilitiesTranslateKey2['result'] = false;
    	}
    	$this->testResults[] = $resultTestUtilitiesTranslateKey2;
    }
    
}
