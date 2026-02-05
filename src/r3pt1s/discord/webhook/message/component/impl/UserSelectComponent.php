<?php

namespace r3pt1s\discord\webhook\message\component\impl;

use LogicException;
use r3pt1s\discord\webhook\message\component\CustomComponent;
use r3pt1s\discord\webhook\message\component\misc\ActionRowChildComponent;
use r3pt1s\discord\webhook\message\component\misc\ComponentType;
use r3pt1s\discord\webhook\message\component\misc\DefaultValueRepresentationType;
use r3pt1s\discord\webhook\message\component\misc\SelectDefaultValue;

final class UserSelectComponent extends CustomComponent implements ActionRowChildComponent {

    private array $defaultValues = [];

    private function __construct(
        string $customId,
        private readonly ?string $placeholder = null,
        private readonly ?int $minValues = null,
        private readonly ?int $maxValues = null,
        private readonly ?bool $required = null,
        private readonly ?bool $disabled = null
    ) {
        parent::__construct($customId);
    }

    public function addDefaultValue(string $userId): self {
        $this->defaultValues[] = SelectDefaultValue::create($userId, DefaultValueRepresentationType::USER);
        return $this;
    }

    public function getType(): ComponentType {
        return ComponentType::USER_SELECT;
    }

    public function getComponentData(): array {
        $defValuesCount = count($this->defaultValues);
        if ($this->minValues !== null && $defValuesCount < $this->minValues)
            throw new LogicException('Your $defaultValues cannot be less than ' . $this->minValues);

        if ($this->maxValues !== null && $defValuesCount > $this->maxValues)
            throw new LogicException('Your $defaultValues cannot be greater than ' . $this->maxValues);

        return [
            "default_values" => array_map(fn(SelectDefaultValue $dV) => $dV->write(), $this->defaultValues),
            "placeholder" => $this->placeholder,
            "min_values" => $this->minValues,
            "max_values" => $this->maxValues,
            "required" => $this->required,
            "disabled" => $this->disabled
        ];
    }

    public function getDefaultValues(): array {
        return $this->defaultValues;
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