<?php

namespace SallePW\Model;

interface TaskRepository
{
    public function save(Task $task);
}
