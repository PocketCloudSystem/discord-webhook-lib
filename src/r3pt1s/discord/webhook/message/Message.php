<?php

namespace r3pt1s\discord\webhook\message;

use CURLFile;
use InvalidArgumentException;
use JsonException;
use LogicException;
use pocketcloud\cloud\scheduler\AsyncPool;
use pocketcloud\cloud\util\misc\Writeable;
use pocketcloud\cloud\util\promise\Promise;
use r3pt1s\discord\webhook\message\attachment\Attachment;
use r3pt1s\discord\webhook\message\component\MessageComponent;
use r3pt1s\discord\webhook\message\embed\Embed;
use r3pt1s\discord\webhook\message\mention\AllowedMention;
use r3pt1s\discord\webhook\poll\Poll;
use r3pt1s\discord\webhook\task\DiscordSendDataTask;
use r3pt1s\discord\webhook\Webhook;
use Throwable;

final class Message implements Writeable {

    public const int MAX_CONTENT_CHARACTERS = 2000;
    public const int MAX_EMBEDS = 10;

    private int $internalFileCounter = 0;

    private string $content = "";
    private ?string $username = null;
    private ?string $avatarUrl = null;
    private bool $textToSpeech = false;

    private array $embeds = [];
    private ?AllowedMention $allowedMention = null;
    private array $components = [];
    private array $files = [];
    private array $attachments = [];
    private int $flags = 0;
    private ?string $threadName = null;
    private array $threadAppliedTags = [];
    private ?Poll $poll = null;

    /**
     * @param bool $wait Waits for server confirmation of message send before response, and returns the created message body (defaults to false; when false a message that is not saved does not return an error)
     * @param string|null $threadId Send a message to the specified thread within a webhook's channel. The thread will automatically be unarchived.
     * @param bool $withComponents whether to respect the components field of the request. When enabled, allows application-owned webhooks to use all components and non-owned webhooks to use non-interactive components. (defaults to false)
     */
    public function __construct(
        private readonly bool $wait,
        private readonly ?string $threadId = null,
        private readonly bool $withComponents = false,
        private readonly ?Webhook $webhook = null
    ) {}

    public function send(): Promise {
        if ($this->webhook === null) throw new LogicException("Please create a message via Webhook->createMessage()");
        return $this->sendWithDiffWebhook($this->webhook);
    }

    public function sendWithDiffWebhook(Webhook $webhook): Promise {
        $promise = new Promise();
        AsyncPool::getInstance()->submitTask(new DiscordSendDataTask(
            $webhook->getUrl(),
            $this->wait,
            $this->threadId,
            $this->withComponents,
            serialize($this->write()),
            static function (bool|string|Throwable $response, ?int $statusCode, string $curlError, int $curlErrno) use ($promise): void {
                if ($statusCode === null) {
                    $promise->reject([$response, $statusCode, $curlError, $curlErrno]);
                    return;
                }

                if (str_starts_with((string) $statusCode, "4") || str_starts_with((string) $statusCode, "5") || $response === false) {
                    $promise->reject([$response, $statusCode, $curlError, $curlErrno]);
                    return;
                }

                $promise->resolve([$response, $statusCode, $curlError, $curlErrno]);
            }
        ));

        return $promise;
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

    /**
     * @see Embed::create()
     * @param Embed $embed
     * @return $this
     */
    public function addEmbed(Embed $embed): self {
        if (count($this->embeds) == self::MAX_EMBEDS) throw new LogicException("Failed to add embed, max amount of embeds (" . self::MAX_EMBEDS . ") reached");
        $this->embeds[] = $embed;
        return $this;
    }

    /**
     * @see AllowedMention::create()
     * @param AllowedMention $allowedMention
     * @return $this
     */
    public function setAllowedMention(AllowedMention $allowedMention): self {
        $this->allowedMention = $allowedMention;
        return $this;
    }

    public function addComponent(MessageComponent $component): self {
        $this->components[] = $component;
        return $this;
    }

    public function addFile(string $filePath, ?string $mimeType = null, ?string $postedFileName = null): self {
        if (!file_exists($filePath)) throw new InvalidArgumentException("File $filePath does not exist");
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $mimeType ?? finfo_file($finfo, $filePath);

        $attachmentId = $this->internalFileCounter++;
        $this->files[$attachmentId] = [$filePath, $mimeType, $postedFileName ??= basename($filePath)];
        $this->attachments[$attachmentId] = new Attachment($attachmentId, $postedFileName);
        return $this;
    }

    public function addFlag(MessageFlag $flag): self {
        $this->flags |= $flag->value;
        return $this;
    }

    /**
     * If set, a new thread with the applied name will be created
     * @param string|null $threadName
     * @return $this
     */
    public function setThreadName(?string $threadName): self {
        $this->threadName = $threadName;
        return $this;
    }

    /**
     * Set the tags that will be applied to the thread
     * You have to use the tag ids
     * @param array $threadAppliedTagIds
     * @return $this
     */
    public function setThreadAppliedTags(array $threadAppliedTagIds): self {
       $this->threadAppliedTags = $threadAppliedTagIds;
        return $this;
    }

    /**
     * @see Poll::create()
     * @param Poll $poll
     * @return $this
     */
    public function setPoll(Poll $poll): self {
        $this->poll = $poll;
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

    public function getEmbeds(): array {
        return $this->embeds;
    }

    public function getAllowedMention(): ?AllowedMention {
        return $this->allowedMention;
    }

    public function getComponents(): array {
        return $this->components;
    }

    public function getFiles(): array {
        return $this->files;
    }

    public function getAttachments(): array {
        return $this->attachments;
    }

    public function getFlags(): int {
        return $this->flags;
    }

    public function getThreadName(): ?string {
        return $this->threadName;
    }

    public function getThreadAppliedTags(): array {
        return $this->threadAppliedTags;
    }

    public function getPoll(): ?Poll {
        return $this->poll;
    }

    public function isWait(): bool {
        return $this->wait;
    }

    public function getThreadId(): ?string {
        return $this->threadId;
    }

    public function isWithComponents(): bool {
        return $this->withComponents;
    }

    /**
     * @throws JsonException
     */
    public function write(): array {
        $data = [
            "content" => $this->content,
            "tts" => $this->textToSpeech,
            "embeds" => array_map(fn(Embed $embed) => $embed->write(), $this->embeds)
        ];

        if ($this->username !== null) $data["username"] = $this->username;
        if ($this->avatarUrl !== null) $data["avatar_url"] = $this->avatarUrl;
        if ($this->allowedMention !== null) $data["allowed_mentions"] = $this->allowedMention->write();
        if (count($this->components) > 0) $data["components"] = array_map(fn(MessageComponent $component) => $component->write(), $this->components);
        if ($this->flags !== 0) $data["flags"] = $this->flags;
        if ($this->threadName !== null) $data["thread_name"] = $this->threadName;
        if (count($this->threadAppliedTags) > 0) $data["applied_tags"] = $this->threadAppliedTags;
        if ($this->poll !== null) $data["poll"] = $this->poll->write();
        if (count($this->files) > 0) {
            $data["attachments"] = array_map(fn(Attachment $attachment) => $attachment->write(), $this->attachments);
            $payloadJson = json_encode($data, JSON_THROW_ON_ERROR);
            $data = ["payload_json" => $payloadJson];
            $data["files"] = $this->files;
        }

        return $data;
    }

    public static function convertFilesData(array $data): array {
        foreach (($data["files"] ?? []) as $i => $fileData) {
            $data["files[$i]"] = new CURLFile(...$fileData);
        }

        if (isset($data["files"])) unset($data["files"]);
        return $data;
    }
}