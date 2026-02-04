<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use pmmp\thread\ThreadSafeArray;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;

final class ContainerComponent extends MessageComponent {

    private ThreadSafeArray $components;

    private function __construct(
        private readonly ?int $accentColor = null,
        private readonly ?bool $spoiler = null
    ) {
        parent::__construct();
        $this->components = new ThreadSafeArray();
    }

    public function addComponent(ContainerChildComponent $component): self {
        $this->components[] = $component;
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::CONTAINER;
    }

    public function getComponentData(): array {
        return [
            "components" => (array) $this->components,
            "accent_color" => $this->accentColor,
            "spoiler" => $this->spoiler
        ];
    }

    public function getComponents(): array {
        return (array) $this->components;
    }

    public function getAccentColor(): ?int {
        return $this->accentColor;
    }

    public function getSpoiler(): ?bool {
        return $this->spoiler;
    }

    public static function create(?int $accentColor = null, ?bool $spoiler = null): self {
        return new self($accentColor, $spoiler);
    }
}