<?php

/**
 * Defines the question_info_answers class
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
 * Class question_info_answers
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_info_answers implements question_info {

    /**
     * @inheritDoc
     */
    public function get($question) {

        return get_string($amount = offlinequiz_get_amount_correct_answers($question) == 1 ?
                'questioninfocorrectanswer' : 'questioninfocorrectanswers', 'offlinequiz', $amount);
    }
}
