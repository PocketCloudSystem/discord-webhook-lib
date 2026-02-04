<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use pmmp\thread\ThreadSafeArray;
use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\message\component\CustomComponent;
use r3pt1s\discord\webhook\message\component\misc\ActionRowChildComponent;
use r3pt1s\discord\webhook\message\component\misc\ButtonStyle;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\SectionAccessoryComponent;

final class ButtonComponent extends CustomComponent implements ActionRowChildComponent, SectionAccessoryComponent {

    private ThreadSafeArray $buttonData;

    private function __construct(
        string $customId,
        private readonly ButtonStyle $style,
        array $buttonData
    ) {
        parent::__construct($customId);
        $this->appendData(["style" => $this->style->value]);
        $this->buttonData = ThreadSafeArray::fromArray($buttonData);
    }

    public function getType(): ComponentType {
        return ComponentType::BUTTON;
    }

    public function getComponentData(): array {
        return (array) $this->buttonData;
    }

    public function getButtonData(): ThreadSafeArray {
        return $this->buttonData;
    }

    public function getStyle(): ButtonStyle {
        return $this->style;
    }

    public static function primary(string $customId, ?string $label = null, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::PRIMARY, ["label" => $label, "emoji" => $emoji, "disabled" => $disabled]);
    }

    public static function secondary(string $customId, ?string $label = null, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::SECONDARY, ["label" => $label, "emoji" => $emoji, "disabled" => $disabled]);
    }

    public static function success(string $customId, ?string $label = null, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::SUCCESS, ["label" => $label, "emoji" => $emoji, "disabled" => $disabled]);
    }

    public static function danger(string $customId, ?string $label = null, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::DANGER, ["label" => $label, "emoji" => $emoji, "disabled" => $disabled]);
    }

    public static function link(string $url, ?string $label = null, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self("no_custom_id_applied", ButtonStyle::LINK, ["url" => $url, "label" => $label, "emoji" => $emoji, "disabled" => $disabled]);
    }

    public static function premium(string $skuId, ?string $label = null, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self("no_custom_id_applied", ButtonStyle::PREMIUM, ["sku_id" => $skuId, "label" => $label, "emoji" => $emoji, "disabled" => $disabled]);
    }
}