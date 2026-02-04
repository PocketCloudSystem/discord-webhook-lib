<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use pmmp\thread\ThreadSafeArray;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ActionRowChildComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;

final class ActionRowComponent extends MessageComponent implements ContainerChildComponent {

    private ThreadSafeArray $components;

    private function __construct() {
        parent::__construct();
        $this->components = new ThreadSafeArray();
    }

    public function addComponent(ActionRowChildComponent $component): self {
        $this->components[] = $component;
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::ACTION_ROW;
    }

    public function getComponentData(): array {
        return ["components" => (array) $this->components];
    }

    public function getComponents(): array {
        return (array) $this->components;
    }

    public static function create(): self {
        return new self();
    }
}