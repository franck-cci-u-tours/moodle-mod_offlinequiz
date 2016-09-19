<?php

/**
 * Defines the question_info_decorator abstract class
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
 * Class question_info_decorator, base of the decorator
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class question_info_decorator implements question_info {

    /** @var question_info */
    protected $question_info;

    /**
     * question_info_decorator constructor
     *
     * @param question_info $question_info what to decorate
     */
    public function __construct(question_info $question_info) {

        $this->question_info = $question_info;
    }

    /**
     * @inheritDoc
     */
    abstract public function get($question);
}
