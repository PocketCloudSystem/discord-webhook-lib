<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\WebhookHelper;

final class MediaGalleryItem extends ThreadSafe implements JsonSerializable {

    public function __construct(
        private readonly UnfurledMediaItem $mediaItem,
        private readonly ?string $description = null,
        private readonly ?bool $spoiler = null
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

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "media" => $this->mediaItem,
            "description" => $this->description,
            "spoiler" => $this->spoiler
        ]);
    }

    public static function create(UnfurledMediaItem $mediaItem, ?string $description = null, ?bool $spoiler = null): self {
        return new self($mediaItem, $description, $spoiler);
    }
}