<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;

final class SeparatorComponent extends MessageComponent implements ContainerChildComponent {

    public function __construct(
        private readonly ?bool $divider = null,
        private readonly ?int $spacing = null
    ) {
        parent::__construct();
    }

    public function getType(): ComponentType {
        return ComponentType::SEPARATOR;
    }

    public function getComponentData(): array {
        return [
            "divider" => $this->divider,
            "spacing" => $this->spacing
        ];
    }

    public function getDivider(): ?bool {
        return $this->divider;
    }

    public function getSpacing(): ?int {
        return $this->spacing;
    }

    public static function create(?bool $divider = null, ?int $spacing = null): self {
        return new self($divider, $spacing);
    }
}