<?php
/**
 * @copyright  2019 Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty\Exception;

use Exception as PHPException;
use Jahuty\Jahuty\Data\Problem;

/**
 * Thrown when the API responds with anything but 200.
 */
class NotOk extends PHPException
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
