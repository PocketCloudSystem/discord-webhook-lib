<?php

namespace r3pt1s\discord\webhook\message\component;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use pmmp\thread\ThreadSafeArray;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\WebhookHelper;

abstract class MessageComponent extends ThreadSafe implements JsonSerializable {

    private ThreadSafeArray $data;

    public function __construct() {
        $this->data = ThreadSafeArray::fromArray(["type" => $this->getType()->value]);
    }

    protected function appendData(array $data): self {
        $this->data = ThreadSafeArray::fromArray(array_merge((array) $this->data, $data));
        return $this;
    }

    public function getData(): array {
        return (array) $this->data;
    }

    abstract public function getType(): ComponentType;

    abstract public function getComponentData(): array;

    public function jsonSerialize(): array {
        $this->appendData($this->getComponentData());
        return WebhookHelper::removeNullFields((array) $this->data);
    }
}