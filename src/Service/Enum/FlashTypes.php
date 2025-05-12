<?php

namespace Taskforce\Service\Enum;

enum FlashTypes: string
{
    use EnumToArray;

    case Success = 'success';
    case Error = 'error';
}