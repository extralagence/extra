<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extra_datetime' ) ) {

    /**
     * Main ReduxFramework_custom_field class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extra_datetime extends ReduxFramework {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {


            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }

            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults = array(
                'options'           => array(),
                'stylesheet'        => '',
                'output'            => true,
                'enqueue'           => true,
                'enqueue_frontend'  => true
            );
            $this->field = wp_parse_args( $this->field, $defaults );

        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            // No errors please
            $defaults = array(
                'date'        => '',
                'time'        => '',
                'timestamp'       => ''
            );

             $this->value = wp_parse_args( $this->value, $defaults );

            $placeholder = ( isset( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_attr( $this->field['placeholder'] ) . '" ' : '';
            echo '<div class="redux-extra-datetime-wrapper">';
            echo '<input type="text" id="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][date]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[date]"' . $placeholder . 'value="' . $this->value["date"] . '" class="redux-extra-datetime-date ' . $this->field['class'] . '" />';
            echo '<input type="hidden" id="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][timestamp]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[timestamp]"' . $placeholder . 'value="' . $this->value["timestamp"] . '" class="redux-extra-datetime-timestamp" />';
            echo '</div>';
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            $extension = ReduxFramework_extension_extra_datetime::getInstance();

            // Jquery datepicker
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style( 'redux-extra-datetime-jquery-ui-css',
                $this->extension_url . 'css/smoothness/jquery-ui-1.10.4.custom.min.css',
                time(),
                true
            );

            // Timepicker locale
            $locale = explode('_', get_locale())[0];
            wp_enqueue_script(
                'jquery-ui-datepicker-locale',
                $this->extension_url . 'js/i18n/datepicker-' . $locale . '.js',
                array( 'jquery' ),
                time(),
                true
            );

            // Timepicker
            wp_enqueue_script(
                'jquery-ui-timepicker',
                $this->extension_url . 'js/jquery-ui-timepicker-addon.js',
                array( 'jquery' ),
                time(),
                true
            );

            // Timepicker locale
            wp_enqueue_script(
                'jquery-ui-timepicker-locale',
                $this->extension_url . 'js/i18n/jquery-ui-timepicker-' . $locale . '.js',
                array( 'jquery' ),
                time(),
                true
            );

            //JS
            wp_localize_script( 'redux-extra-datetime-js', 'redux_extra_datetime_js_lang', array(
                'date_format' => dateformat_to_js(get_option( 'date_format' )),
                'time_format' => dateformat_to_js(get_option( 'time_format' ))
            ));
            wp_enqueue_script(
                'redux-extra-datetime-js',
                $this->extension_url . 'js/field_extra_datetime.js',
                array( 'jquery' ),
                time(),
                true
            );

            // CSS
            wp_enqueue_style(
                'redux-extra-datetime-css',
                $this->extension_url . 'css/field_extra_datetime.less',
                time(),
                true
            );

        }

        /**
         * Output Function.
         *
         * Used to enqueue to the front-end
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function output() {

            if ( $this->field['enqueue_frontend'] ) {
            }

        }

    }
}
