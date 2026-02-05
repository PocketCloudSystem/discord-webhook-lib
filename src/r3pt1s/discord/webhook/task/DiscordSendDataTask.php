<?php

namespace r3pt1s\discord\webhook\task;

use Closure;
use CURLFile;
use pocketcloud\cloud\scheduler\AsyncTask;
use r3pt1s\discord\webhook\message\Message;
use Throwable;

final class DiscordSendDataTask extends AsyncTask {

    public function __construct(
        private readonly string $url,
        private readonly bool $wait,
        private readonly ?int $threadId,
        private readonly bool $withComponents,
        private readonly string $requestData,
        private readonly ?Closure $completionCallback = null
    ) {}

    public function onRun(): void {
        $url = $this->url;
        $params = [];

        if ($this->wait) $params["wait"] = "true";
        if ($this->threadId !== null) $params["thread_id"] = $this->threadId;
        if ($this->withComponents) $params["with_components"] = "true";
        if (count($params) > 0) $url .= "?" . http_build_query($params);

        $ch = curl_init($url);

        $requestData = unserialize($this->requestData);
        $actualData = Message::convertFilesData(iterator_to_array($requestData));

        $hasFiles = false;
        foreach ($actualData as $value) {
            if ($value instanceof CURLFile) {
                $hasFiles = true;
                break;
            }
        }

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($hasFiles) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $actualData);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($actualData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        }

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        $this->setResult([$response, $responseCode, curl_error($ch), curl_errno($ch)]);
        curl_close($ch);
    }

    public function onCompletion(): void {
        $result = $this->getResult();
        if ($this->completionCallback !== null) ($this->completionCallback)(...$result);
    }

    public function onFailure(Throwable $exception): void {
        if ($this->completionCallback !== null) ($this->completionCallback)($exception, null);
    }
}