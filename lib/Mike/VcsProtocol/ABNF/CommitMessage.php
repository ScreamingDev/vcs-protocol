<?php

namespace Mike\VcsProtocol\ABNF;


class CommitMessage
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getTitle()
    {
        $pos = strpos($this->getMessage(), "\n\n");

        if (false === $pos) {
            return $this->getMessage();
        }

        return substr($this->getMessage(), 0, $pos);
    }

    public function getMessage()
    {
        return $this->message;
    }
}