<?php

namespace r3pt1s\discord\webhook\message\component;

use JsonSerializable;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\WebhookHelper;

abstract class MessageComponent implements JsonSerializable {

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

    public function jsonSerialize(): array {
        $this->appendData($this->getComponentData());
        return WebhookHelper::removeNullFields($this->data);
    }
}