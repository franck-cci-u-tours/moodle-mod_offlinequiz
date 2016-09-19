<?php

/**
 * Defines the offlinequiz qtype_compatible class
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
 * Class qtype_compatible
 *
 * @package       mod
 * @subpackage    offlinequiz
 * @author        Franck Branjonneau
 * @copyright     Université de Tours
 * @since         Moodle 3.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_compatible {

    /** @var qtype_compatible singleton instance */
    static private $instance = null;

    /** @var string[] known compatible question types except description */
    const KNOWN_COMPATIBLE_QTYPES = ['multichoice' => 'singlemulti', 'multichoiceset' => 'multi'];

    /** @var  string[] installed compatible question types */
    private $compatible_qtypes;

    /**
     * Initialize the single instance if needed and return it
     *
     * @return qtype_compatible the single instance
     */
    static public function instance() {

        if (is_null(self::$instance)) {

            self::$instance = new qtype_compatible();
        }

        return self::$instance;
    }

    /**
     * Get the compatible qtypes
     *
     * @return \string[]
     */
    public function get() {

        return $this->compatible_qtypes;
    }

    /**
     * Is a qtype known to be compatible?
     *
     * @param $qtype
     *
     * @return bool
     */
    public static function is_known($qtype) {

        return array_key_exists($qtype, self::KNOWN_COMPATIBLE_QTYPES);
    }

    private function __construct() {

        global $CFG;

        $question_path = $CFG->dirroot . '/question/type/';
        include_once $question_path . 'multichoice/question.php';

        $string_manager = get_string_manager();
        $compatible_qtypes = &$this->compatible_qtypes;
        foreach (\core_plugin_manager::instance()->get_enabled_plugins('qtype') as $qtype) {

            if (self::is_known($qtype)) {

                $compatible_qtypes[$qtype] = self::KNOWN_COMPATIBLE_QTYPES[$qtype];

            } elseif (!is_null($configuration = $this->configuration($question_path, $qtype, $string_manager))) {

                $compatible_qtypes[$qtype] = $configuration;
            }
        }
    }

    /**
     * Find out if a question type is configured
     *
     * A question type is configured iif:
     * -- either it defines the string with key 'offlinequiz_info' and the 'qtype_{question type name}_question' class which derives
     * from one of 'qtype_multichoice_single_question' and 'qtype_multichoice_multi_question' classes;
     * -- or it defines the string with key 'offlinequiz_single_info' and the 'qtype_{question type name}_single_question' class
     * which derives from 'qtype_multichoice_single_question' class or (inclusive) it defines the string with key
     * 'offlinequiz_multi_info' and the 'qtype_{question type name}_multi_question' class which derives from
     * 'qtype_multichoice_multi_question' class.
     *
     * @param $question_path
     * @param $qtype
     * @param $string_manager
     * @return null|string null, if it is not configured; one of single, multi and singlemulti if it is
     */
    private function configuration($question_path, $qtype, $string_manager) {

        // *REQUIRED*
        global $CFG;

        $question_filename = $question_path . $qtype . '/question.php';
        if (file_exists($question_filename)) {

            include_once $question_filename;

            $component = 'qtype_' . $qtype;

            if (class_exists($class_name = $component . '_question')) {

                return $this->question_class_configuration(new \ReflectionClass($class_name), $component, $string_manager);
            }

            $single = ($single_exists = class_exists($class_name = $component . '_single_question'))
            && $this->question_class_configuration_single(new \ReflectionClass($class_name), $component, $string_manager) ?
                    'single' : null;
            $multi = ($multi_exists = class_exists($class_name = $component . '_multi_question'))
            && $this->question_class_configuration_multi(new \ReflectionClass($class_name), $component, $string_manager) ?
                    'multi' : null;

            // Sanity check
            if (($single && !$multi && ($this->is_configured_multi($component, $string_manager) || $multi_exists))
                    || ($multi && !$single && ($this->is_configured_single($component, $string_manager) || $single_exists))
            ) {

                $this->misconfigured($component);
            }

            return ($result = $single . $multi) ? $result : null;
        }

        return null;
    }

    /**
     * @param \ReflectionClass $class
     * @param $component
     * @param \core_string_manager $string_manager
     * @return null|string
     */
    private function question_class_configuration(\ReflectionClass $class, $component, \core_string_manager $string_manager) {

        if ($configured = $this->is_configured($component, $string_manager)) {

            $single = $class->isSubclassOf('qtype_multichoice_single_question') ? 'single' : null;
            $multi = $class->isSubclassOf('qtype_multichoice_multi_question') ? 'multi' : null;
        }

        // Sanity check
        if (($this->is_configured_single($component, $string_manager) || $this->is_configured_multi($component, $string_manager))
                || ($configured && !($single || $multi))
        ) {

            $this->misconfigured($component);
        }

        return $configured ? $single . $multi : null;
    }

    /**
     * @param \ReflectionClass $class
     * @param string $component
     * @param \core_string_manager $string_manager
     *
     * @return string
     */
    private function question_class_configuration_single(\ReflectionClass $class, $component,
            \core_string_manager $string_manager) {

        $single = ($configured_single = $this->is_configured_single($component, $string_manager)) &&
        $class->isSubclassOf('qtype_multichoice_single_question') ? 'single' : null;

        // Sanity check
        if ($this->is_configured($component, $string_manager) || ($configured_single && !$single)) {

            $this->misconfigured($component);
        }

        return $single;
    }

    /**
     * @param \ReflectionClass $class
     * @param string $component
     * @param \core_string_manager $string_manager
     *
     * @return string
     */
    private function question_class_configuration_multi(\ReflectionClass $class, $component, \core_string_manager $string_manager) {

        $multi = ($configured_multi = $this->is_configured_multi($component, $string_manager))
        && $class->isSubclassOf('qtype_multichoice_multi_question') ? 'multi' : null;

        // Sanity check
        if ($this->is_configured($component, $string_manager) || ($configured_multi && !$multi)) {

            $this->misconfigured($component);
        }

        return $multi;
    }

    /**
     * @param string $component
     * @param \core_string_manager $string_manager
     *
     * @return bool
     */
    private function is_configured($component, \core_string_manager $string_manager) {

        return $string_manager->string_exists('offlinequiz_info', $component);
    }

    /**
     * @param string $component
     * @param \core_string_manager $string_manager
     *
     * @return bool
     */
    private function is_configured_single($component, \core_string_manager $string_manager) {

        return $string_manager->string_exists('offlinequiz_single_info', $component);
    }

    /**
     * @param string $component
     * @param \core_string_manager $string_manager
     *
     * @return bool
     */
    private function is_configured_multi($component, \core_string_manager $string_manager) {

        return $string_manager->string_exists('offlinequiz_multi_info', $component);
    }

    /**
     * A misconfigured qtype was found, state it
     *
     * @param string $component
     */
    private function misconfigured($component) {

        print_error('qtype_misconfigured', 'quizoffline', '', $component);
    }
}
