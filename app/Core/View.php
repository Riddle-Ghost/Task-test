<?php

namespace App\Core;

use Twig\Environment;

class View
{
	/**
     * @var Environment
     */
	private $twig;
	
	/**
     * View constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
	}
	
	/**
     * Собирает данные из шаблона.
     *
     * @param string $name
     * @param array  $data
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function generate(string $name, array $data = [])
    {

        return $this->twig->render($name . '.twig', ['data' => $data]);
    }
}
