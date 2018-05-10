<?php

namespace \Slim\View;

class Renderer
{
    /**
     * @param string $file
     * @param array $data
     * @return string
     * @throws RenderException
     */
    function render($file, $data = []): string
    {
        if (is_array($data) && !empty($data)) {
            extract($data);
        }
        $fileToRender = __DIR__ . "/templates/" . $file . ".php";
        if (!file_exists($fileToRender)) {
            throw new RenderException("The specified file is not valid");
        }
        ob_start();
        include_once $fileToRender;
        return ob_get_clean();
    }
}
