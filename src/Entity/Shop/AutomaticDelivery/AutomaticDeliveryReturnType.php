<?php

namespace App\Entity\Shop\AutomaticDelivery;

class AutomaticDeliveryReturnType
{
    private AutomaticDeliveryResult $result;
    private ?string $message;
    private ?string $errorCode;

    public function __construct(AutomaticDeliveryResult $result, ?string $message, ?string $errorCode) {
        $this->result = $result;
        $this->message = $message;
        $this->errorCode = $errorCode;
    }

    public function getResult(): AutomaticDeliveryResult
    {
        return $this->result;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getMessageWithErrorCode(): ?string
    {
        if(is_null($this->message)) return null;
        return "$this->message (code : ".($this->errorCode ?? "-").")";
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}