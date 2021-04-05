<?php namespace App\Services;
use Illuminate\Database\QueryException;
use App\Models\Task;
use App\Models\Tracing;
use Exceptions\TasksException;
class TasksServices
{

    private $contact;

    public function __construct()
    {
        $this->tracing = new Tracing();
    }

    public function getTracingByContact(int $idContact): Tracing
    {
        try {
            return $this->tracing->where('ContactsId', $idContact)->get('Id')->first();
        } catch (QueryException $e) {
            throw new TasksException('TAREAS_SERVICE_ERR CONTACT', 500);
        }
    }

    public function __destruct()
    {
        $this->tracing = null;
    }
}