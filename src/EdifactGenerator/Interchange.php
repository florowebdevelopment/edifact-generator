<?php

namespace Florowebdevelopment\EdifactGenerator;

class Interchange
{
    private $interchangeCode;
    private $sender;
    private $receiver;
    private $date;
    private $time;
    private $charset;
    private $messages;
    private $composed;

    public function __construct($sender, $receiver, $date = null, $time = null, $interchangeCode = null)
    {
        $this->messages = [];

        if ($interchangeCode === null) {
            $this->interchangeCode = 'I'.strtoupper(uniqid());
        } else {
            $this->interchangeCode = $interchangeCode;
        }

        $this->sender=$sender;
        $this->receiver=$receiver;

        if ($date === null) {
            $this->date = date('ymd');
        } else {
            $this->date = $date;
        }

        if ($time === null) {
            $this->time = date('Hi');
        } else {
            $this->time = $time;
        }

        $this->charset = ['UNOA', 2];
    }

    public function setCharset($identifier, $version)
    {
        $this->charset = [$identifier, $version];
        return $this;
    }

    public function addMessage($msg)
    {
        $this->messages[] = $msg;

        return $this;
    }

    public function compose()
    {
        $temp = [];
        $temp[] = ['UNB', $this->charset, $this->sender, $this->receiver, [$this->date, $this->time], $this->interchangeCode];
        foreach ($this->messages as $msg) {
            foreach ($msg->getComposed() as $i) {
                $temp[] = $i;
            }
        }
        $temp[] = ['UNZ', count($this->messages), $this->interchangeCode];
        $this->composed = $temp;

        return $this;
    }

    public function getComposed()
    {
        if ($this->composed === null) {
            $this->compose();
        }

        return $this->composed;
    }
}
