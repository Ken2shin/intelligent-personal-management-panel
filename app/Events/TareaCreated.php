<?php

namespace App\Events;

use App\Models\Tarea;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TareaCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Tarea $tarea)
    {
    }
}
