<?php

/**
 * Defines the question_info_qtype class
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
 * Class question_info_qtype
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_info_qtype implements question_info {

    /**
     * @inheritDoc
     */
    public function get($question) {

        $is_known = qtype_compatible::is_known($qtype = $question->qtype);

        $component = $is_known ? 'offlinequiz' : 'qtype_' . $qtype;

        $prefix = $is_known ? $qtype . '_' : 'offlinequiz_';
        // TODO use qtype_enabled -- Franck
        if (get_config('offlinequiz', 'enable_' . $qtype) == 'singlemulti') {

            $prefix .= $question->options->single ? 'single_' : 'multi_';
        }

        return get_string($prefix . 'info', $component);
    }
}
