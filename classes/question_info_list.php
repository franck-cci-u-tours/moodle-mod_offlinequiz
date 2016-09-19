<?php

/**
 * Defines the question_info_list class
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
 * Class question_info_list
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_info_list extends question_info_decorator {

    /** @var string separator of the list */
    private $separator;

    /** @var question_info tail of the list */
    private $tail;

    /**
     * question_info_list constructor
     *
     * @param question_info $head head of the list
     * @param string $separator list separator of the list
     * @param question_info $tail tail of the list
     */
    public function __construct(question_info $head, $separator, question_info $tail) {

        parent::__construct($head);

        $this->separator = $separator;
        $this->tail = $tail;
    }

    /**
     * @inheritDoc
     */
    public function get($question) {

        return $this->question_info->get($question) . $this->separator . $this->tail->get($question);
    }
}
