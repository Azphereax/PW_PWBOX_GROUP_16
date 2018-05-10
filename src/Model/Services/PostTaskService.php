<?php

namespace SallePW\Model\Services;

use SallePW\Model\Task;
use SallePW\Model\TaskRepository;

class PostTaskService
{
    /**
     * @var TaskRepository
     */
    private $repository;

    /**
     * SaveTaskService constructor.
     * @param TaskRepository $repository
     */
    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Task $task)
    {
        $this->repository->save($task);
    }
}
