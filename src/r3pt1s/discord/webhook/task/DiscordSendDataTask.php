<?php

namespace r3pt1s\discord\webhook\task;

use Closure;
use pocketcloud\cloud\scheduler\AsyncTask;
use r3pt1s\discord\webhook\message\Message;

final class DiscordSendDataTask extends AsyncTask {

    public function __construct(
        private readonly string $url,
        private readonly Message $message,
        private readonly ?Closure $completionCallback = null
    ) {}

    public function onRun(): void {
        $url = $this->url;
        $params = [];

        if ($this->message->isWait()) $params["wait"] = "true";
        if ($this->message->getThreadId() !== null) $params["thread_id"] = $this->message->getThreadId();
        if ($this->message->isWithComponents()) $params["with_components"] = "true";
        if (!empty($params)) $url .= "?" . http_build_query($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->message));
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        if (empty($this->message->getFiles())) curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $this->setResult([curl_exec($ch), curl_getinfo($ch, CURLINFO_RESPONSE_CODE)]);
        curl_close($ch);
    }

    public function onCompletion(): void {
        $result = $this->getResult();
        if ($this->completionCallback !== null) ($this->completionCallback)(...$result);
    }
}