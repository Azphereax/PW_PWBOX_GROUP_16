<?php

namespace SallePW\Controller;

use SallePW\View\Renderer;
use SallePW\View\RenderException;


class PostTaskController
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * PostController constructor.
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function  indexAction()
    {
        try {
            echo $this->renderer->render('post_task');
            http_response_code(200);
        }catch (RenderException $e) {
            echo "An unexpected error has occurred, please try it again later.";
            http_response_code(500);
        }
    }
	
	public function  Get_Data()
    {
        try {
			$Exec_task= new \SallePW\Model\Services\PostTaskService(new \SallePW\Model\MySQLTaskRepository());
            $Exec_task->execute(new \SallePW\Model\Task(0,filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS),$task_value = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS)));
			echo $this->renderer->render('post_task');

            http_response_code(200);
        }catch (RenderException $e) {
            echo "An unexpected error has occurred, please try it again later.";
            http_response_code(500);
        }
    }
}
