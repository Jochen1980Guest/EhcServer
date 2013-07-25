<?php

/*

Build-Skript 

Version 1.0. - 24.07.2013

 */

class FtpBuild {
	
	// -----------------------------------------------------------------------------
	// Anmerkungen:
	// cd in Verzeichnis C:\xampp\htdocs\EhcServer, dann php build.php aufrufen;
	// PHP-Skriptlaufzeit erfolgt lokal ohne Zeitlimit, auf dem Server 30 Sekunden;
	
	// -----------------------------------------------------------------------------
	// Konfiguration
	private $version = "";
	private $date = "";
	private $pathToServerRoot = "";
	private $ftpHost = "";
	private $ftpPort = "";
	private $ftpUser = "";
	private $ftpPass = "";
	private $dbHost = "";
	private $dbName = "";
	private $dbUser = "";
	private $dbPass = "";
	
	public function __construct(){
		$ftpConfig = new FtpConfig();
		$this->version = $ftpConfig->getVersion();
		$this->date = $ftpConfig->getDate();
		$this->pathToServerRoot = $ftpConfig->getPathToServerRoot();
		$this->ftpHost = $ftpConfig->getFtpHost();
		$this->ftpPort = $ftpConfig->getFtpPort();
		$this->ftpUser = $ftpConfig->getFtpUser();
		$this->ftpPass = $ftpConfig->getFtpPass();
		$this->dbHost = $ftpConfig->getDbHost();
		$this->dbName = $ftpConfig->getDbName();
		$this->dbUser = $ftpConfig->getDbUser();
		$this->dbPass = $ftpConfig->getDbPass();
	}
	
	public function build(){
		// teste FTP-Verbindung
		$this->testFtpConnection();
		//$this->copyProjectToServer();
		//$this->callScriptURL();
	}
	
	public function testFtpConnection(){
		// Verbindung aufbauen
		$conn_id = ftp_connect($this->ftpHost) or die("Couldn't connect to $this->ftpHost");
		if (@ftp_login($conn_id, $this->ftpUser, $this->ftpPass)) {
			echo "* Angemeldet als $this->ftpUser@$this->ftpHost\n";
			echo "* Aktuelles Verzeichnis: " . ftp_pwd($conn_id) . "\n";
		} else {
			echo "* Anmeldung als $this->ftpUser nicht möglich\n";
		}
		ftp_close($conn_id);
	}
	
	private function copyProjectToServer(){
		$conn_id = ftp_connect($this->ftpHost);
		if (@ftp_login($conn_id, $this->ftpUser, $this->ftpPass)) {
			echo "* Kopiere Projekt auf den Server ... \n";
			$this->copyZendSkeletonApplicationToServer($conn_id);
		} else {
			die("* Kopieren des Projekts auf den Server nicht möglich.\n");
		}
		ftp_close($conn_id);
	}
	
	private function callScriptURL(){
		echo "* Rufe build.php auf dem Server auf ... \n";
		$curl = curl_init("http://demo.ehome-center.de/build.php");
		curl_exec($curl);
		echo "* build.php serverseitig abgearbeitet! \n";
	}
	
	private function copyZendSkeletonApplicationToServer($conn_id){
		// precondition: connection available 
		
		// Verzeichniswechsel in ServerRoot 
		ftp_chdir($conn_id, $this->pathToServerRoot);
		
		// copy file: build.php to build.php
		$build = "build.php";
		// ftp_put mit connection, remote_file, file, FTP_ASCII
		if (ftp_put($conn_id, $build, $build, FTP_ASCII)) {
			echo "* $build erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $build \n";
		}
		/*
		// copy file: composer.json to composer.json
		$composerJson = "composer.json";
		if (ftp_put($conn_id, $composerJson, $composerJson, FTP_ASCII)) {
			echo "* $composerJson erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $composerJson \n";
		}
		
		// copy file: composer.lock to composer.lock
		$composerLock = "composer.lock";
		if (ftp_put($conn_id, $composerLock, $composerLock, FTP_ASCII)) {
			echo "* $composerLock erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $composerLock \n";
		}
		
		// copy file: composer.phar to composer.phar
		$composerPhar = "composer.phar";
		if (ftp_put($conn_id, $composerPhar, $composerPhar, FTP_ASCII)) {
			echo "* $composerPhar erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $composerPhar \n";
		}
		
		// copy file: init_autoloader.php to init_autoloader.php
		$initAutoloader = "init_autoloader.php";
		if (ftp_put($conn_id, $initAutoloader, $initAutoloader, FTP_ASCII)) {
			echo "* $initAutoloader erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $initAutoloader \n";
		}
		
		// copy file: LICENSE.txt to LICENSE.txt
		$license = "LICENSE.txt";
		if (ftp_put($conn_id, $license, $license, FTP_ASCII)) {
			echo "* $license erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $license \n";
		}
		
		// copy file: README.md to README.md
		$readme = "README.md";
		if (ftp_put($conn_id, $readme, $readme, FTP_ASCII)) {
			echo "* $readme erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $readme \n";
		}
		
		// create dir: config and set rights
		@ftp_mkdir($conn_id, 'config');
		
		// copy file: config/application.config.php to config/application.config.php
		$applicationConfig = "config/application.config.php";
		if (ftp_put($conn_id, $applicationConfig, $applicationConfig, FTP_ASCII)) {
			echo "* $applicationConfig erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $applicationConfig \n";
		}
		
		// create dir: config/autoload
		@ftp_mkdir($conn_id, 'config/autoload');
		
		// copy file: config/autoload/global.php to config/autoload/global.php
		$global = "config/autoload/global.php";
		if (ftp_put($conn_id, $global, $global, FTP_ASCII)) {
			echo "* $global erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $global \n";
		}
		
		// copy file: config/autoload/local.php.dist to config/autoload/local.php.dist
		$local = "config/autoload/local.php.dist";
		if (ftp_put($conn_id, $local, $local, FTP_ASCII)) {
			echo "* $local erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $local \n";
		}
		
		// create dir: data
		@ftp_mkdir($conn_id, 'data');
		
		// create dir: data
		@ftp_mkdir($conn_id, 'data/cache');
		
		// create dir: data/logs
		@ftp_mkdir($conn_id, 'data/logs');
		
		// copy file: data/logs/application.log to data/logs/application.log
		$applicationLog = "data/logs/application.log";
		if (ftp_put($conn_id, $applicationLog, $applicationLog, FTP_ASCII)) {
			echo "* $applicationLog erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $applicationLog \n";
		}
		
		// copy file: data/logs/applicationTest.log to data/logs/applicationTest.log
		$applicationLogTest = "data/logs/applicationTest.log";
		if (ftp_put($conn_id, $applicationLogTest, $applicationLogTest, FTP_ASCII)) {
			echo "* $applicationLogTest erfolgreich hochgeladen\n";
		} else {
			echo "* Ein Fehler trat beim Hochladen von $applicationLogTest \n";
		}
		
		// create dir: data/module
		@ftp_mkdir($conn_id, 'module');
		
		// create dir: data/module
		@ftp_mkdir($conn_id, 'module/Application');
		
		// create dir: data/module
		@ftp_mkdir($conn_id, 'module/ZfcBase');
		
		// create dir: data/module
		@ftp_mkdir($conn_id, 'module/ZfcUser');
		
		// create dir: data/module
		@ftp_mkdir($conn_id, 'public');
		
		// create dir: data/module
		@ftp_mkdir($conn_id, 'vendor');
		
		// vendor-Verzeichnis rekursiv auf den FTP kopieren
		// siehe http://stackoverflow.com/questions/927341/upload-entire-directory-via-php-ftp
		
		// Verzeichniswechsel
		//echo "* Verzeichniswechsel ...\n";
		//ftp_chdir($conn_id, "config");
		//echo "* Aktuelles Verzeichnis - Server: " . ftp_pwd($conn_id) . "\n";
		//echo "* Aktuelles Verzeichnis - Lokal: " . getcwd() . "\n";
		//chmod('config',0644);
		
		// TODO: change content in global.php
		//$contentGlobal = $this->getContentOfGlobal();
		
		*/
		
		// Rufe Skript via Curl im Browser auf
		
		
		// Schlafe 10 Sekunden
		
		
		// Starte Selenium-Tests
		// Tests
		
	}
	
	private function copyZendFrameworkToServer(){
		
	}
	
	private function deleteBuildScript($conn_id){
		
	}
	
	private function printFilesOfCurrentFtpDir($conn_id){
		$dir = ftp_pwd ($ftp);
		echo "* Aktuelles Verzeichnis: " . ftp_pwd($conn_id) . "\n";
		$list = ftp_rawlist ($conn_id, $dir);
		print_r ($list);
	}
	
	// Accessors 
	public function getVersion(){
		return $this->version;
	}
	
	public function getDate(){
		return $this->date;
	}
}

// Prozedur

// TODO Unterscheidung ob es lokal oder via curl gecallt wird;
// http://coderscult.com/how-to-post-data-with-curl-in-php/

echo "\n";
echo "************************************************************************\n";
echo "* \n";
$obj = new Build();
echo "* Build Skript Version " .  $obj->getVersion() . ", " . $obj->getDate() . ";\n";
echo "* \n";
$obj->build();
echo "************************************************************************\n";



// SQL-Statements
/*

drop table user;
drop table resident;
drop table room;

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `humidity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `resident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `room` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room` (`room`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `room` (`id`, `name`, `humidity`) VALUES
(1, 'Schlafzimmer', 65),
(2, 'Wohnzimmer', 70);

INSERT INTO `resident` (`id`, `name`, `room`) VALUES
(1, 'Anton', 1),
(2, 'Berta', 1);

*/
?>