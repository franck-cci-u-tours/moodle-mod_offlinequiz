<?php
// This file is part of mod_offlinequiz for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 *
 * Functions for checking and evaluting scanned answer forms and lists of participants.
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Thomas Wedekind <thomas.wedekind@univie.ac.at>
 * @copyright     2015 Academic Moodle Cooperation {@link http://www.academic-moodle-cooperation.org}
 * @since         Moodle 2.2+
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Get the question info string parametrized by offlinequiz for the given question
 *
 * @param object $offlinequiz the offline quiz
 * @param object $question the question
 *
 * @return string the info string
 */
function offlinequiz_get_question_infostring($offlinequiz, $question) {

    return \mod_offlinequiz\question_info_factory::get($offlinequiz)->get($question);
}

function offlinequiz_get_amount_correct_answers($question) {
    $answers = $question->options->answers;
    $amount = 0;
    foreach ($answers as $answer) {
        if ($answer->fraction > 0) {
            $amount = $amount + 1;
        }
    }
    return $amount;
}
