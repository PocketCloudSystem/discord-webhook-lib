<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\component\misc\ActionRowChildComponent;
use r3pt1s\discord\webhook\message\component\misc\ButtonStyle;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\SectionAccessoryComponent;

final class ButtonComponent extends MessageComponent implements ActionRowChildComponent, SectionAccessoryComponent {

    private function __construct(
        ?string $customId,
        private readonly ButtonStyle $style,
        private readonly array $buttonData = []
    ) {
        parent::__construct();
        if ($customId !== null) $this->appendData(["custom_id" => $customId]);
        $this->appendData(["style" => $this->style->value]);
    }

    public function getType(): ComponentType {
        return ComponentType::BUTTON;
    }

    public function getComponentData(): array {
        return $this->buttonData;
    }

    public function getButtonData(): array {
        return $this->buttonData;
    }

    public function getStyle(): ButtonStyle {
        return $this->style;
    }

    public static function primary(string $customId, ?string $label, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::PRIMARY, ["label" => $label, "emoji" => $emoji?->write(), "disabled" => $disabled]);
    }

    public static function secondary(string $customId, ?string $label, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::SECONDARY, ["label" => $label, "emoji" => $emoji?->write(), "disabled" => $disabled]);
    }

    public static function success(string $customId, ?string $label, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::SUCCESS, ["label" => $label, "emoji" => $emoji?->write(), "disabled" => $disabled]);
    }

    public static function danger(string $customId, ?string $label, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self($customId, ButtonStyle::DANGER, ["label" => $label, "emoji" => $emoji?->write(), "disabled" => $disabled]);
    }

    public static function link(string $url, ?string $label, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self(null, ButtonStyle::LINK, ["url" => $url, "label" => $label, "emoji" => $emoji?->write(), "disabled" => $disabled]);
    }

    public static function premium(string $skuId, ?string $label, ?PartialEmoji $emoji = null, ?bool $disabled = null): ButtonComponent {
        return new self(null, ButtonStyle::PREMIUM, ["sku_id" => $skuId, "label" => $label, "emoji" => $emoji?->write(), "disabled" => $disabled]);
    }
}