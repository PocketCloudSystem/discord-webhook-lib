<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class MediaGalleryItem implements Writeable {

    public function __construct(
        private UnfurledMediaItem $mediaItem,
        private ?string $description = null,
        private ?bool $spoiler = null
    ) {}

    public function getMediaItem(): UnfurledMediaItem {
        return $this->mediaItem;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getSpoiler(): ?bool {
        return $this->spoiler;
    }

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "media" => $this->mediaItem->write(),
            "description" => $this->description,
            "spoiler" => $this->spoiler
        ]);
    }

    public static function create(UnfurledMediaItem $mediaItem, ?string $description = null, ?bool $spoiler = null): self {
        return new self($mediaItem, $description, $spoiler);
    }
}