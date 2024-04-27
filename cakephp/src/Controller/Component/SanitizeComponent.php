<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Datasource\ConnectionManager;

/**
 * Washes strings from unwanted noise.
 *
 * Helpful methods to make unsafe strings usable.
 *
 *
 * @author        Ashik Patel
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Data Sanitization.
 *
 * Removal of alphanumeric characters, SQL-safe slash-added strings, HTML-friendly strings,
 * and all of the above on arrays.
 *
 */
class SanitizeComponent extends Component {

/**
 * Removes any non-alphanumeric characters.
 *
 * @param string $string String to sanitize
 * @param array $allowed An array of additional characters that are not to be removed.
 * @return string Sanitized string
 */
	public function paranoid($string, $allowed = array()) {
		$allow = null;
		if (!empty($allowed)) {
			foreach ($allowed as $value) {
				$allow .= "\\$value";
			}
		}

		if (!is_array($string)) {
			return preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $string);
		}

		$cleaned = array();
		foreach ($string as $key => $clean) {
			$cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $clean);
		}

		return $cleaned;
	}

/**
 * Makes a string SQL-safe.
 *
 * @param string $string String to sanitize
 * @param string $connection Database connection being used
 * @return string SQL safe string
 */
	public function escape($string, $connection = 'default') {
		if (is_numeric($string) || $string === null || is_bool($string)) {
			return $string;
		}
		// $db = ConnectionManager::get($connection);
		// $string = $db->query($string, 'string');
		// $start = 1;
		// if ($string{0} === 'N') {
		// 	$start = 2;
		// }
		//return substr(substr($string, $start), 0, -1);
		return $string;
	}

/**
 * Returns given string safe for display as HTML. Renders entities.
 *
 * strip_tags() does not validating HTML syntax or structure, so it might strip whole passages
 * with broken HTML.
 *
 * ### Options:
 *
 * - remove (boolean) if true strips all HTML tags before encoding
 * - charset (string) the charset used to encode the string
 * - quotes (int) see http://php.net/manual/en/function.htmlentities.php
 * - double (boolean) double encode html entities
 *
 * @param string $string String from where to strip tags
 * @param array $options Array of options to use.
 * @return string Sanitized string
 */
	public function html($string, $options = array()) {
		
		$defaults = array(
			'remove' => false,
			'charset' => 'UTF-8',
			'quotes' => ENT_QUOTES,
			'double' => true
		);

		$options += $defaults;

		if ($options['remove']) {
			$string = strip_tags($string);
		}

		return htmlentities($string, $options['quotes'], $options['charset'], $options['double']);
	}

/**
 * Strips extra whitespace from output
 *
 * @param string $str String to sanitize
 * @return string whitespace sanitized string
 */
	public function stripWhitespace($str) {
		return preg_replace('/\s{2,}/u', ' ', preg_replace('/[\n\r\t]+/', '', $str));
	}

/**
 * Strips image tags from output
 *
 * @param string $str String to sanitize
 * @return string Sting with images stripped.
 */
	public function stripImages($str) {
		$preg = array(
			'/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i' => '$1$3$5<br />',
			'/(<img[^>]+alt=")([^"]*)("[^>]*>)/i' => '$2<br />',
			'/<img[^>]*>/i' => ''
		);

		return preg_replace(array_keys($preg), array_values($preg), $str);
	}

/**
 * Strips scripts and stylesheets from output
 *
 * @param string $str String to sanitize
 * @return string String with <link>, <img>, <script>, <style> elements and html comments removed.
 */
	public function stripScripts($str) {
		$regex =
			'/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|' .
			'<img[^>]*>|style="[^"]*")|' .
			'<script[^>]*>.*?<\/script>|' .
			'<style[^>]*>.*?<\/style>|' .
			'<!--.*?-->/is';
		return preg_replace($regex, '', $str);
	}

/**
 * Strips extra whitespace, images, scripts and stylesheets from output
 *
 * @param string $str String to sanitize
 * @return string sanitized string
 */
	public function stripAll($str) {
		return $this->stripScripts(
			$this->stripImages(
				$this->stripWhitespace($str)
			)
		);
	}

/**
 * Strips the specified tags from output. First parameter is string from
 * where to remove tags. All subsequent parameters are tags.
 *
 * Ex.`$clean = $this->stripTags($dirty, 'b', 'p', 'div');`
 *
 * Will remove all `<b>`, `<p>`, and `<div>` tags from the $dirty string.
 *
 * @param string $str String to sanitize.
 * @return string sanitized String
 */
	public function stripTags($str) {
		$params = func_get_args();

		for ($i = 1, $count = count($params); $i < $count; $i++) {
			$str = preg_replace('/<' . $params[$i] . '\b[^>]*>/i', '', $str);
			$str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
		}
		return $str;
	}

/**
 * Sanitizes given array or value for safe input. Use the options to specify
 * the connection to use, and what filters should be applied (with a boolean
 * value). Valid filters:
 *
 * - odd_spaces - removes any non space whitespace characters
 * - encode - Encode any html entities. Encode must be true for the `remove_html` to work.
 * - dollar - Escape `$` with `\$`
 * - carriage - Remove `\r`
 * - unicode -
 * - escape - Should the string be SQL escaped.
 * - backslash -
 * - remove_html - Strip HTML with strip_tags. `encode` must be true for this option to work.
 *
 * @param string|array $data Data to sanitize
 * @param string|array $options If string, DB connection being used, otherwise set of options
 * @return mixed Sanitized data
 */
	public function clean($data, $options = array()) {
		if (empty($data)) {
			return $data;
		}

		if (!is_array($options)) {
			$options = array('connection' => $options);
		}

		$options += array(
			'connection' => 'default',
			'odd_spaces' => true,
			'remove_html' => false,
			'encode' => true,
			'dollar' => true,
			'carriage' => true,
			'unicode' => true,
			'escape' => true,
			'backslash' => true
		);

		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$data[$key] = $this->clean($val, $options);
			}
			return $data;
		}

		if ($options['odd_spaces']) {
			$data = str_replace(chr(0xCA), '', $data);
		}
		if ($options['encode']) {
			$data = $this->html($data, array('remove' => $options['remove_html']));
		}
		if ($options['dollar']) {
			$data = str_replace("\\\$", "$", $data);
		}
		if ($options['carriage']) {
			$data = str_replace("\r", "", $data);
		}
		if ($options['unicode']) {
			$data = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $data);
		}
		if ($options['escape']) {
			$data = $this->escape($data, $options['connection']);
		}
		if ($options['backslash']) {
			$data = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $data);
		}
		return $data;
	}
}