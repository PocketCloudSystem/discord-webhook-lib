<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;
use r3pt1s\discord\webhook\message\component\misc\SectionChildComponent;

final class TextDisplayComponent extends MessageComponent implements SectionChildComponent, ContainerChildComponent {

    private function __construct(private readonly string $content) {
        parent::__construct();
    }

    public function getType(): ComponentType {
        return ComponentType::TEXT_DISPLAY;
    }

    public function getComponentData(): array {
        return ["content" => $this->content];
    }

    public function getContent(): string {
        return $this->content;
    }

    public static function create(string $content): self {
        return new self($content);
    }
}