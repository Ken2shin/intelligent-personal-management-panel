<?php

namespace App\Events;

use App\Models\Transaccion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Transaccion $transaccion)
    {
    }
}
