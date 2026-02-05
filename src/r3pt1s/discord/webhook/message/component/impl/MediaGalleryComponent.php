<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use InvalidArgumentException;
use LogicException;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\ContainerChildComponent;
use r3pt1s\discord\webhook\message\component\misc\MediaGalleryItem;
use r3pt1s\discord\webhook\message\component\misc\UnfurledMediaItem;

final class MediaGalleryComponent extends MessageComponent implements ContainerChildComponent {

    public const int MAX_DESCRIPTION_LENGTH = 1024;
    public const int MIN_ITEMS = 1;
    public const int MAX_ITEMS = 10;

    private array $items = [];

    public function addItem(UnfurledMediaItem|string $urlOrMediaItem, ?string $description = null,  ?bool $spoiler = null): self {
        if ($description !== null && strlen($description) > self::MAX_DESCRIPTION_LENGTH) throw new InvalidArgumentException("Your description is too big");
        $mediaItem = is_string($urlOrMediaItem) ? UnfurledMediaItem::create($urlOrMediaItem) : $urlOrMediaItem;
        $this->items[] = new MediaGalleryItem($mediaItem, $description, $spoiler);
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::MEDIA_GALLERY;
    }

    public function getComponentData(): array {
        $itemsCount = count($this->items);
        if ($itemsCount < self::MIN_ITEMS || $itemsCount > self::MAX_ITEMS)
            throw new LogicException('Your $items cannot be less than ' . self::MIN_ITEMS . ' or greater than ' . self::MAX_ITEMS);

        return [
            "items" => array_map(fn(MediaGalleryItem $item) => $item->write(), $this->items),
        ];
    }

    public function getItems(): array {
        return $this->items;
    }

    public static function create(): self {
        return new self();
    }
}