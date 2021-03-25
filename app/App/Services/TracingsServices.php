<?php namespace App\Services;
use Exceptions\TasksException;
use App\Models\Task;
use Carbon\Carbon;
class TracingsServices
{

    private $task;

    public function __construct()
    {
        $this->tasks = new Task();
    }

    public function storeTracingsWithTasks(array $tasks, int $id)
    {
        $task = [];
        for ($i = 0; $i < count($tasks); $i++) {
            $task[] = [
                'Description' => $tasks[$i]['Description'],
                'Status' => $tasks[$i]['Status'],
                'TracingsId' => $id,
                'TypesTasksId' => $tasks[$i]['TypesTasksId'],
                'DeadLine' => $tasks[$i]['DeadLine'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        $responseInsert = $this->tasks::insert($task);
        if (!$responseInsert) {
            throw new TasksException('Ha ocurrido un error', 500);
        }
    }

    public function __destruct()
    {
        $this->tasks = null;
    }
}