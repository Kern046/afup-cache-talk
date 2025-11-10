<?php

declare(strict_types=1);

namespace App\Enum;

enum RentStatus: string
{
    case WaitingForRetrieval = 'waiting_for_retrieval';
    case Rented = 'rented';
    case Overdue = 'overdue';
    case Completed = 'completed';
}