<?php
namespace GA\Bundle\GitAPIBundle\Entity;


/**
 *  The error entity that's being returned on unsuccessful requests.
 */

class Error
{
    /**
     *  The error's code.
     */
    public $code;

    /**
     *  The error's message.
     */
    public $message;

    /**
     *  The raw error returned by another interface (in case the error can't be specified in $message).
     */
    public $raw;

    public function __construct($code,
                                $message,
                                $raw = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->raw = $raw;
    }

    /**
     *  Returns the error as an array.
     */
    public function toArray()
    {
        return ['type' => "error",
                'code' => $this->code,
                'message' => $this->message,
                'raw' => $this->raw,];
    }
}
