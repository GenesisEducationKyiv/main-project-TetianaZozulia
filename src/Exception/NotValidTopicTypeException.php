<?php

declare(strict_types=1);

namespace App\Exception;

class NotValidTopicTypeException extends \InvalidArgumentException
{
    public function __construct($topicType)
    {
        $message = sprintf('Topic type "%s" is undefined.', $topicType);
        parent::__construct($message);
    }
}
