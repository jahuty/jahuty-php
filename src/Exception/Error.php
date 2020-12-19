<?php

namespace Jahuty\Exception;

use Jahuty\Resource\Problem;

class Error extends \Exception
{
    private $problem;

    public function __construct(Problem $problem)
    {
        $this->problem = $problem;
        $this->message = "The API responded with " . $problem->getStatus()
            . ", " . $problem->getType() . ": '" . $problem->getDetail() ."'";
    }

    public function getProblem(): Problem
    {
        return $this->problem;
    }
}
