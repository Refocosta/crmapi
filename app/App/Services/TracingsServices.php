<?php namespace App\Services;
use App\Models\Tracing;
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
                'TypesTasksId' => 1,
                'DeadLine' => $tasks[$i]['DeadLine'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        $responseInsert = $this->tasks::insert($task);
        print_r($responseInsert);
    }

    public function __destruct()
    {
        $this->tasks = null;
    }
}