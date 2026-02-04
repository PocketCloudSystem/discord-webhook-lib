<?php

namespace r3pt1s\discord\webhook\message\component;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\util\WebhookHelper;

abstract class MessageComponent implements Writeable {

    private array $data;

    public function __construct() {
        $this->data = ["type" => $this->getType()->value];
    }

    protected function appendData(array $data): self {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function getData(): array {
        return $this->data;
    }

    abstract public function getType(): ComponentType;

    abstract public function getComponentData(): array;

    public function write(): array {
        $this->appendData($this->getComponentData());
        return WebhookHelper::removeNullFields($this->data);
    }
}