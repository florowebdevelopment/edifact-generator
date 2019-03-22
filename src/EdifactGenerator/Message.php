<?php

namespace Florowebdevelopment\EdifactGenerator;

class Message
{
    protected $messageID = null;
    protected $messageContent = null;
    protected $messageType = null;
    protected $aComposed = [];

    public function __construct($identifier, $version, $release = null, $controllingAgency = null, $messageID = null, $association = null)
    {
        $this->messageType = [$identifier, $version];

        if ($release !== null) {
            $this->messageType[] = $release;
        }

        if ($release !== null) {
            $this->messageType[] = $controllingAgency;
        }

        if ($association !== null) {
            $this->messageType[] = $association;
        }

        if ($messageID === null) {
            $this->messageID = 'M'.strtoupper(uniqid());
        } else {
            $this->messageID = $messageID;
        }
    }

    /**
     * Get Composed.
     *
     * @return array $this->aComposed
     */
    public function getComposed(): array
    {
        return $this->aComposed;
    }

    /**
     * Set Composed.
     *
     * @param array $aComposed
     */
    public function setComposed(array $aComposed): void
    {
        $this->aComposed = $aComposed;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode    (1001)
     * @param mixed $sDocumentIdentifier  (1004)
     *
     * @return self $this
     */
    public function compose(?string $sMessageFunctionCode, ?string $sDocumentNameCode, ?string $sDocumentIdentifier): self
    {
        $aComposed = [];

        // Message Header
        $aComposed[] = ['UNH', $this->messageID, $this->messageType];

        // Segments

        foreach ($this->messageContent as $i) {
            $aComposed[] = $i;
        }

        // Message Trailer
        $aComposed[] = ['UNT', (2 + count($this->messageContent)), $this->messageID];

        $this->setComposed($aComposed);

        return $this;
    }
}
