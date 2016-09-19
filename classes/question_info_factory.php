<?php

/**
 * Defines the question_info_factory class
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
 * Class question_info_factory
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_info_factory {

    const LIST_SEPARATOR = ', ';

    const OPEN_BRACKET = '(';
    const CLOSE_BRACKET = ')';

    /**
     * @param object $offlinequiz
     */
    public static function get($offlinequiz) {

        $list = [];
        switch ($offlinequiz->showquestioninfo) {

            case OFFLINEQUIZ_QUESTIONINFO_QTYPE:
                $list[] = new question_info_qtype();
                break;

            case OFFLINEQUIZ_QUESTIONINFO_ANSWERS:
                $list[] = new question_info_answers();
                break;

            default:
                throw new \ErrorException('You found a bug'); // Never reached
        }

        if ($offlinequiz->showgrades) {

            $list[] = new question_info_grade($offlinequiz->decimalpoints);
        }

        if (is_null($info = array_shift($list))) {

            // Another option is to return null and have the caller test the result
            return new question_info_nop();
        }

        while ($list) {

            $info = new question_info_list($info, self::LIST_SEPARATOR, array_shift($list));
        }

        return new question_info_brackets(self::OPEN_BRACKET, $info, self::CLOSE_BRACKET);
    }
}
