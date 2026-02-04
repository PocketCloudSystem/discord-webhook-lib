<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;
use r3pt1s\discord\webhook\message\component\misc\UnfurledMediaItem;

/**
 * The File component only supports using the attachment:// protocol in @see UnfurledMediaItem
 * To use this component in messages you must send the message flag 1 << 15 (IS_COMPONENTS_V2) which can be activated on a per-message basis.
 */
final class FileComponent extends MessageComponent implements ContainerChildComponent {

    private function __construct(
        private readonly UnfurledMediaItem $mediaItem,
        private readonly ?bool $spoiler = null
    ) {
        parent::__construct();
    }

    public function getType(): ComponentType {
        return ComponentType::FILE;
    }

    public function getComponentData(): array {
        return [
            "file" => $this->mediaItem->write(),
            "spoiler" => $this->spoiler
        ];
    }

    public function getMediaItem(): UnfurledMediaItem {
        return $this->mediaItem;
    }

    public function getSpoiler(): ?bool {
        return $this->spoiler;
    }
    public static function create(UnfurledMediaItem|string $attachmentFileNameOrMediaItem, ?bool $spoiler = null): self {
        $mediaItem = is_string($attachmentFileNameOrMediaItem) ? UnfurledMediaItem::create((str_starts_with($attachmentFileNameOrMediaItem, "attachment://") ? "" : "attachment://") . $attachmentFileNameOrMediaItem) : $attachmentFileNameOrMediaItem;
        return new self($mediaItem, $spoiler);
    }
}