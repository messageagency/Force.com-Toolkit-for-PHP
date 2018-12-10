<?php

namespace DeveloperForce\PhpToolkit\Email;

class MassEmailMessage extends Email
{
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    public function setWhatIds($array)
    {
        $this->whatIds = $array;
    }

    public function setTargetObjectIds($array)
    {
        $this->targetObjectIds = $array;
    }
}
