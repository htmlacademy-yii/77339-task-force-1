<?php

namespace Taskforce\Service\Enum;

enum ReactionStatuses: string
{
    case Accept = 'accept';
    case Reject = 'reject';
}