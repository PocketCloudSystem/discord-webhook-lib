<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ActionRowChildComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;

final class ActionRowComponent extends MessageComponent implements ContainerChildComponent {

    private array $components = [];

    public function addComponent(ActionRowChildComponent $component): self {
        $this->components[] = $component;
        return $this;
    }

    public function addComponents(ActionRowChildComponent ...$components): self {
        foreach ($components as $component) $this->addComponent($component);
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::ACTION_ROW;
    }

    public function getComponentData(): array {
        return ["components" => array_map(fn(MessageComponent $component) => $component->write(), $this->components)];
    }

    public function getComponents(): array {
        return $this->components;
    }

    public static function create(): self {
        return new self();
    }

    public static function with(ActionRowChildComponent ...$components): self {
        return self::create()->addComponents(...$components);
    }
}