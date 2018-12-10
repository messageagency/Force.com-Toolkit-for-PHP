<?php

namespace DeveloperForce\PhpToolkit;

class SingleEmailMessage extends Email
{
  public function __construct()
  {
  }

  public function setBccAddresses($addresses)
  {
    $this->bccAddresses = $addresses;
  }
  public $ccAddresses;

  public function setCcAddresses($addresses)
  {
    $this->ccAddresses = $addresses;
  }

  public function setCharset($charset)
  {
    $this->charset = $charset;
  }

  public function setHtmlBody($htmlBody)
  {
    $this->htmlBody = $htmlBody;
  }

  public function setOrgWideEmailAddressId($orgWideEmailAddressId)
  {
    $this->orgWideEmailAddressId = $orgWideEmailAddressId;
  }

  public function setPlainTextBody($plainTextBody)
  {
    $this->plainTextBody = $plainTextBody;
  }

  public function setTargetObjectId($targetObjectId)
  {
    $this->targetObjectId = $targetObjectId;
  }

  public function setTemplateId($templateId)
  {
    $this->templateId = $templateId;
  }

  public function setToAddresses($array)
  {
    $this->toAddresses = $array;
  }

  public function setWhatId($whatId)
  {
    $this->whatId = $whatId;
  }

  public function setFileAttachments($array)
  {
    $this->fileAttachments = $array;
  }

  public function setDocumentAttachments($array)
  {
    $this->documentAttachments = $array;
  }
}
