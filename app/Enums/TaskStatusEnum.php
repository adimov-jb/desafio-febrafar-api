<?php
namespace App\Enums;

enum TaskStatusEnum: string
{
    case OPEN = 'open';
    case CONCLUDED = 'concluded';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => __('Aberto'),
            self::CONCLUDED => __('Concluído'),
        };
    }
}