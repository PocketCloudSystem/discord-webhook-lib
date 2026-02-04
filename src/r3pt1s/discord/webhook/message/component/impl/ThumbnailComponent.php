<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\SectionAccessoryComponent;
use r3pt1s\discord\webhook\message\component\misc\UnfurledMediaItem;

final class ThumbnailComponent extends MessageComponent implements SectionAccessoryComponent {

    private function __construct(
        private readonly UnfurledMediaItem $mediaItem,
        private readonly ?string $description = null,
        private readonly ?bool $spoiler = null
    ) {
        parent::__construct();
    }

    public function getType(): ComponentType {
        return ComponentType::THUMBNAIL;
    }

    public function getComponentData(): array {
        return [
            "media" => $this->mediaItem->write(),
            "description" => $this->description,
            "spoiler" => $this->spoiler
        ];
    }

    public function getMediaItem(): UnfurledMediaItem {
        return $this->mediaItem;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getSpoiler(): ?bool {
        return $this->spoiler;
    }

    public static function create(UnfurledMediaItem|string $urlOrMediaItem, ?string $description = null, ?bool $spoiler = null): self {
        $mediaItem = is_string($urlOrMediaItem) ? UnfurledMediaItem::create($urlOrMediaItem) : $urlOrMediaItem;
        return new self($mediaItem, $description, $spoiler);
    }
}