<?php

/**
 * Defines the question_info_brackets class
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_offlinequiz;

/**
 * Class question_info_brackets
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_info_brackets extends question_info_decorator {

    /** @var string open bracket */
    private $open;

    /** @var string close bracket */
    private $close;

    /**
     * question_info_brackets constructor
     *
     * @param string $open open bracket
     * @param question_info $question_info
     * @param string $close close bracket
     */
    public function __construct($open, question_info $question_info, $close) {

        parent::__construct($question_info);

        $this->open = $open;
        $this->close = $close;
    }

    /**
     * @inheritDoc
     */
    public function get($question) {

        return $this->open . $this->question_info->get($question) . $this->close;
    }
}
