<?php

/**
 * Defines the question_info_grade class
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Juergen Zimmer <zimmerj7@univie.ac.at>
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_offlinequiz;

/**
 * Class question_info_grade
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_info_grade implements question_info {

    /** @var integer how many decimals */
    private $decimal_points;

    /**
     * question_info_grade constructor
     *
     * @param integer $decimal_points
     */
    public function __construct($decimal_points) {

        $this->decimal_points = $decimal_points;
    }

    /**
     * @inheritDoc
     */
    public function get($question) {

        return format_float($question->maxmark, $this->decimal_points) . ' '
        . ($question->maxmark == 1 ? get_string('point', 'offlinequiz') : get_string('points', 'grades'));
    }
}
