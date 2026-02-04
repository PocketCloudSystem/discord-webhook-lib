<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use pmmp\thread\ThreadSafeArray;
use pocketcloud\cloud\exception\UnsupportedOperationException;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;
use r3pt1s\discord\webhook\message\component\misc\SectionAccessoryComponent;
use r3pt1s\discord\webhook\message\component\misc\SectionChildComponent;

final class SectionComponent extends MessageComponent implements ContainerChildComponent {

    public const int MIN_COMPONENTS = 1;
    public const int MAX_COMPONENTS = 3;

    private ThreadSafeArray $components;
    private SectionAccessoryComponent $accessory;

    private function __construct() {
        parent::__construct();
        $this->components = new ThreadSafeArray();
    }

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
            throw new UnsupportedOperationException('Your $components cannot be less than ' . self::MIN_COMPONENTS . ' or greater than ' . self::MAX_COMPONENTS);

        return [
            "components" => (array) $this->components,
            "accessory" => $this->accessory
        ];
    }

    public function getComponents(): array {
        return (array) $this->components;
    }

    public function getAccessory(): SectionAccessoryComponent {
        return $this->accessory;
    }

    public static function create(): self {
        return new self();
    }
}