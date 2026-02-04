<?php

namespace r3pt1s\discord\webhook\message;

use InvalidArgumentException;
use pmmp\thread\ThreadSafe;
use pmmp\thread\ThreadSafeArray;
use r3pt1s\discord\webhook\poll\Poll;

final class Message extends ThreadSafe {

    public const int MAX_CONTENT_CHARACTERS = 2000;
    public const int MAX_EMBEDS = 10;

    private string $content = "";
    private ?string $username = null;
    private ?string $avatarUrl = null;
    private bool $textToSpeech = false;

    private ThreadSafeArray $embeds;
    private ThreadSafeArray $components;
    private ThreadSafeArray $files;
    private ThreadSafeArray $attachments;
    private int $flags = 0;
    private ?string $threadName = null;
    private ThreadSafeArray $threadAppliedTags;
    private ?Poll $poll = null;

    public function __construct() {
        $this->embeds = new ThreadSafeArray();
        $this->components = new ThreadSafeArray();
        $this->files = new ThreadSafeArray();
        $this->attachments = new ThreadSafeArray();
        $this->threadAppliedTags = new ThreadSafeArray();
    }

    /**
     * Set the message content (up to 2k characters)
     * @param string $text
     * @return self
     */
    public function setContent(string $text): self {
        if (strlen($text) > self::MAX_CONTENT_CHARACTERS) $text = substr($text, 0, self::MAX_CONTENT_CHARACTERS);
        $this->content = $text;
        return $this;
    }

    public function setUsername(string $username): self {
        $this->username = $username;
        return $this;
    }

    public function setAvatarUrl(string $avatarUrl): self {
        if (filter_var($avatarUrl, FILTER_VALIDATE_URL)) $this->avatarUrl = $avatarUrl;
        else throw new InvalidArgumentException("AvatarUrl must be a valid URL");
        return $this;
    }

    public function setTextToSpeech(bool $textToSpeech): self {
        $this->textToSpeech = $textToSpeech;
        return $this;
    }

    public function addEmbed(): void {
        //TODO
    }

    public function addComponent(): void {
        //TODO
    }

    public function addFlag(MessageFlag $flag): self {
        $this->flags |= $flag->value;
        return $this;
    }

    public function setThreadName(?string $threadName): self {
        $this->threadName = $threadName;
        return $this;
    }



    public function isFlagSet(MessageFlag $flag): bool {
        return $this->flags & $flag->value;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function getAvatarUrl(): ?string {
        return $this->avatarUrl;
    }

    public function isTextToSpeech(): bool {
        return $this->textToSpeech;
    }

    public function getEmbeds(): ThreadSafeArray {
        return $this->embeds;
    }

    public function getComponents(): ThreadSafeArray {
        return $this->components;
    }

    public function getFiles(): ThreadSafeArray {
        return $this->files;
    }

    public function getAttachments(): ThreadSafeArray {
        return $this->attachments;
    }

    public function getFlags(): int {
        return $this->flags;
    }

    public function getThreadName(): ?string {
        return $this->threadName;
    }

    public function getThreadAppliedTags(): ThreadSafeArray {
        return $this->threadAppliedTags;
    }

    public function getPoll(): ?Poll {
        return $this->poll;
    }
}