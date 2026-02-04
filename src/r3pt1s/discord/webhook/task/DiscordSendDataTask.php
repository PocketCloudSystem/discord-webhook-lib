<?php

namespace r3pt1s\discord\webhook\task;

use Closure;
use pocketcloud\cloud\scheduler\AsyncTask;

final class DiscordSendDataTask extends AsyncTask {

    public function __construct(
        private readonly string $url,
        private readonly bool $wait,
        private readonly ?int $threadId,
        private readonly bool $withComponents,
        private readonly string $jsonString,
        private readonly bool $applyHeader,
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data = $this->jsonString);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        if ($this->applyHeader) curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $this->setResult([curl_exec($ch), curl_getinfo($ch, CURLINFO_RESPONSE_CODE), $data]);
        curl_close($ch);
    }

    public function onCompletion(): void {
        $result = $this->getResult();
        if ($this->completionCallback !== null) ($this->completionCallback)(...$result);
    }
}