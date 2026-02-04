<?php

namespace r3pt1s\discord\webhook\poll;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\util\WebhookHelper;

final class Poll implements Writeable {

    /** @var int The highest possible expiry timestamp, default is 24 hours, max is 32 days */
    public const int MAX_EXPIRY_TIMESTAMP = 60 * 60 * 24 * 32;
    public const int DEFAULT_EXPIRY_TIMESTAMP = 60 * 60 * 24;

    private int $pollAnswerCounter = 1;
    private array $answers = [];

    private function __construct(
        private readonly string $question,
        private readonly ?string $expiry,
        private readonly bool $allowMultiSelect,
        private readonly ?PollLayoutType $layoutType
    ) {}

    public function addAnswer(string $answer, ?PartialEmoji $emoji = null): self {
        $this->answers[] = new PollAnswer($this->pollAnswerCounter++, $answer, $emoji);
        return $this;
    }

    public function getPollAnswerCounter(): int {
        return $this->pollAnswerCounter;
    }

    public function getAnswers(): array {
        return $this->answers;
    }

    public function getQuestion(): string {
        return $this->question;
    }

    public function getExpiry(): ?string {
        return $this->expiry;
    }

    public function isAllowMultiSelect(): bool {
        return $this->allowMultiSelect;
    }

    public function getLayoutType(): ?PollLayoutType {
        return $this->layoutType;
    }

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "question" => [
                "text" => $this->question
            ],
            "answers" => array_map(fn(PollAnswer $answer) => $answer->write(), $this->answers),
            "expiry" => $this->expiry,
            "allow_multiselect" => $this->allowMultiSelect,
            "layout_type" => ($this->layoutType ?? PollLayoutType::DEFAULT)->value
        ]);
    }

    public static function create(string $question, int $timestamp = self::DEFAULT_EXPIRY_TIMESTAMP, bool $allowMultiSelect = false, PollLayoutType $layoutType = PollLayoutType::DEFAULT): Poll {
        return new Poll($question, gmdate("c", $timestamp), $allowMultiSelect, $layoutType);
    }
}