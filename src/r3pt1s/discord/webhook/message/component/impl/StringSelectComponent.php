<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use InvalidArgumentException;
use LogicException;
use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\message\component\CustomComponent;
use r3pt1s\discord\webhook\message\component\misc\ActionRowChildComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentConstants;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\SelectOption;

final class StringSelectComponent extends CustomComponent implements ActionRowChildComponent {

    public const int MAX_OPTIONS = 25;

    private array $options = [];

    private function __construct(
        string $customId,
        private readonly ?string $placeholder = null,
        private readonly ?int $minValues = null,
        private readonly ?int $maxValues = null,
        private readonly ?bool $required = null,
        private readonly ?bool $disabled = null
    ) {
        parent::__construct($customId);

        if ($this->placeholder !== null && strlen($this->placeholder) > ComponentConstants::MAX_PLACEHOLDER_LENGTH)
            throw new InvalidArgumentException('$placeholder length is too large, max length is ' . ComponentConstants::MAX_PLACEHOLDER_LENGTH);

        if ($this->minValues !== null && ($this->minValues < ComponentConstants::MIN_MIN_VALUES || $this->minValues > ComponentConstants::MAX_MIN_VALUES))
            throw new InvalidArgumentException('$minValues cannot be less than ' . ComponentConstants::MIN_MIN_VALUES . ' or greater than ' . ComponentConstants::MAX_MIN_VALUES);

        if ($this->maxValues !== null && ($this->maxValues < ComponentConstants::MIN_MAX_VALUES || $this->maxValues > ComponentConstants::MAX_MAX_VALUES))
            throw new InvalidArgumentException('$maxValues cannot be less than ' . ComponentConstants::MIN_MIN_VALUES . ' or greater than ' . ComponentConstants::MAX_MIN_VALUES);
    }

    public function addOption(string $label, string $value, ?string $description = null, ?PartialEmoji $emoji = null, ?bool $default = null): self {
        if (count($this->options) == self::MAX_OPTIONS) throw new LogicException("Failed to add option, max amount of options (" . self::MAX_OPTIONS . ") reached");
        $this->options[] = SelectOption::create($label, $value, $description, $emoji, $default);
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::STRING_SELECT;
    }

    public function getComponentData(): array {
        return [
            "placeholder" => $this->placeholder,
            "options" => array_map(fn(SelectOption $option) => $option->write(), $this->options),
            "min_values" => $this->minValues,
            "max_values" => $this->maxValues,
            "required" => $this->required,
            "disabled" => $this->disabled
        ];
    }

    public function getOptions(): array {
        return $this->options;
    }

    public function getPlaceholder(): ?string {
        return $this->placeholder;
    }

    public function getMinValues(): ?int {
        return $this->minValues;
    }

    public function getMaxValues(): ?int {
        return $this->maxValues;
    }

    public function getRequired(): ?bool {
        return $this->required;
    }

    public function getDisabled(): ?bool {
        return $this->disabled;
    }

    public static function create(string $customId, ?string $placeholder = null, ?int $minValues = null, ?int $maxValues = null, ?bool $required = null, ?bool $disabled = null): self {
        return new self($customId, $placeholder, $minValues, $maxValues, $required, $disabled);
    }
}