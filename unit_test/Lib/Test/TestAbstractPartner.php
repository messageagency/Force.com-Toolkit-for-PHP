<?php
abstract class Lib_Test_TestAbstractPartner extends Lib_Test_TestAbstract
{
	protected $_wsdlName = 'partner';
	protected $_wsdlVersion = '20';
	
	public function __construct($soapDir)
	{
		parent::__construct($soapDir);
		require_once ($this->_soapDir . '/PartnerClient.php');
		require_once ($this->_soapDir . '/SforceHeaderOptions.php');
		require_once ($this->_soapDir . '/MetadataClient;
				
		$this->_mySforceConnection = new SforcePartnerClient();
		$this->_mySoapClient = $this->_mySforceConnection->createConnection($this->getWSDL());
		$this->_mylogin = $this->_mySforceConnection->login($this->_userName, $this->_password);
		
		$_SESSION['location'] = $this->_mySforceConnection->getLocation();
		$_SESSION['sessionId'] = $this->_mySforceConnection->getSessionId();
		$_SESSION['wsdl'] = $this->getWSDL();
	}
	
	public function getWSDLName() {
		return $this->_wsdlName;
	}
	
	public function getWSDLVersion()
	{
		return $this->_wsdlVersion;
	}
	
	public function getWSDL() {
		return "{$this->_soapDir}/{$this->_wsdlName}{$this->_wsdlVersion}.wsdl.xml";
	}
}
