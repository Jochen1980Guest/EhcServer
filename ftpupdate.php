<?php

require_once 'ftpconfig.php';

class FtpUpdate {
	
	// -----------------------------------------------------------------------------
	// Anmerkungen:
	// Lokal wird in Eclipse entwickelt, das Skript zur Web-Aktualisierung gerufen;
	// cd in Verzeichnis C:\xampp\htdocs\EhcServer, dann php ftpudate.php aufrufen;
	// PHP-Skriptlaufzeit erfolgt lokal ohne Zeitlimit, auf dem Server 30 Sekunden;
	// Dateien auf dem Ziel-FTP-Ordner werden mit lokalem Workspace synchronisiert;
	
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
	
	public function update(){
		// teste FTP-Verbindung
		$this->testFtpConnection();
		$this->copyProjectToServer();
	}
	
	public function testFtpConnection(){
		// Verbindung aufbauen
		echo "* Anmeldeversuch als $this->ftpUser@$this->ftpHost\n";
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
			$this->copyFilesToServer($conn_id);
		} else {
			die("* Kopieren des Projekts auf den Server nicht möglich.\n");
		}
		ftp_close($conn_id);
	}
	
	private function copyFilesToServer($conn_id){
		
		// Verzeichniswechsel in ServerRoot 
		ftp_chdir($conn_id, $this->pathToServerRoot);
		
		// delete former files in root
		$rootFiles = array(
			"composer.json",
			"composer.lock",
			"composer.phar",
			"ftpbuild.php",
			"ftpconfig.php",
			"ftpupdate.php",
			"init_autoloader.php",
			"LICENSE.txt",
			"README.md",
			"composer.lock",
		);
		
		foreach($rootFiles as $file){
			// Loeschen
			if (ftp_delete($conn_id, $file)) {
				echo "* $file erfolgreich geloescht.\n";
			} else {
				echo "* Ein Fehler trat beim Loeschen von $file auf.\n";
			}
			
			// Hochladen
			if (ftp_put($conn_id, $file, $file, FTP_ASCII)) {
				echo "* $file erfolgreich hochgeladen.\n";
			} else {
				echo "* Ein Fehler trat beim Hochladen von $file auf.\n";
			}
		}
		
		// config, autoload ordner 
		$configFiles = array(
				"config/autoload/global.php",
				"config/autoload/local.php.dist",
				"config/autoload/zfcuser.global.php",
				"application.config.php",
		);
		
		foreach($configFiles as $file){
			// Loeschen
			if (ftp_delete($conn_id, $file)) {
				echo "* $file erfolgreich geloescht.\n";
			} else {
				echo "* Ein Fehler trat beim Loeschen von $file auf.\n";
			}

			// Hochladen
			if (ftp_put($conn_id, $file, $file, FTP_ASCII)) {
				echo "* $file erfolgreich hochgeladen.\n";
			} else {
				echo "* Ein Fehler trat beim Hochladen von $file auf.\n";
			}
		}
		
		// glob() returns all file-names of a matching pattern as an array
		// basename() return the last part of a filename
		foreach (glob("module/*.*") as $filename){
			if (ftp_delete($conn_id, basename($filename))) {
				echo "* " . basename($filename) . " erfolgreich geloescht.\n";
			} else {
				echo "* Ein Fehler trat beim Loeschen von " . basename($filename) . " auf.\n";
			}
			
			// Hochladen
			if (ftp_put($conn_id, basename($filename), basename($filename), FTP_ASCII)) {
				echo "* " . basename($filename) . " erfolgreich hochgeladen.\n";
			} else {
				echo "* Ein Fehler trat beim Hochladen von " . basename($filename) . " auf.\n";
			}
		}
		
	}
	
	
	// Accessors 
	public function getVersion(){
		return $this->version;
	}
	
	public function getDate(){
		return $this->date;
	}
}

// Start der Prozedur
echo "\n";
echo "************************************************************************\n";
echo "* \n";
$obj = new FtpUpdate();
echo "* Update Skript Version " .  $obj->getVersion() . ", " . $obj->getDate() . ";\n";
echo "* \n";
$obj->update();
echo "************************************************************************\n";

?>