<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use LogicException;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;
use r3pt1s\discord\webhook\message\component\misc\SectionAccessoryComponent;
use r3pt1s\discord\webhook\message\component\misc\SectionChildComponent;

final class SectionComponent extends MessageComponent implements ContainerChildComponent {

    public const int MIN_COMPONENTS = 1;
    public const int MAX_COMPONENTS = 3;

    private array $components = [];
    private SectionAccessoryComponent $accessory;

    public function addComponent(SectionChildComponent $component): self {
        $this->components[] = $component;
        return $this;
    }

    public function setAccessory(SectionAccessoryComponent $accessory): self {
        $this->accessory = $accessory;
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::SECTION;
    }

    public function getComponentData(): array {
        if (count($this->components) < self::MIN_COMPONENTS || count($this->components) > self::MAX_COMPONENTS)
            throw new LogicException('Your $components cannot be less than ' . self::MIN_COMPONENTS . ' or greater than ' . self::MAX_COMPONENTS);

        return [
            "components" => array_map(fn(MessageComponent $dV) => $dV->write(), $this->components),
            "accessory" => $this->accessory->write()
        ];
    }

    public function getComponents(): array {
        return $this->components;
    }

    public function getAccessory(): SectionAccessoryComponent {
        return $this->accessory;
    }

    public static function create(): self {
        return new self();
    }
}