<?php

/**
 * Defines the offlinequiz qtype_enabled class
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
 * Class qtype_enabled
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_enabled {

    /** @var qtype_enabled singleton instance */
    static private $instance = null;

    /** @var [string] compatibles and enabled question types */
    private $enabled_qtypes;

    /**
     * Initialize the single instance if needed and return it
     *
     * @return qtype_compatible the single instance
     */
    static public function instance() {

        if (is_null(self::$instance)) {

            self::$instance = new qtype_enabled();
        }

        return self::$instance;
    }

    /**
     * Get the enabled qtypes
     *
     * @return string[]
     */
    public function get() {

        return $this->enabled_qtypes;
    }

    /**
     * Is a qtype enabled?
     *
     * @param $qtype the qtype to test
     *
     * @return bool
     */
    public function is_enabled($qtype) {

        return in_array($qtype, $this->get());
    }

    /**
     * Is a qtype the description qtype or enabled?
     *
     * @param $qtype the qtype to test
     *
     * @return bool
     */
    public function is_description_or_enabled($qtype) {

        return $qtype == 'description' || $this->is_enabled($qtype);
    }

    private function __construct() {

        global $DB;

        // Sanity check. At least one compatible question type *has* to be enabled
        if (empty(qtype_compatible::instance()->get())) {

            print_error('error', 'offlinequiz');
        }

        $this->enabled_qtypes = array_map(
                function($name) {

                    return substr($name, 7);
                },
                $DB->get_fieldset_select('config_plugins', 'name',
                        'plugin = \'offlinequiz\' AND name LIKE \'enable_%\' AND value != \'0\''));
    }
}
