<?php
App::import('Helper', 'Time');
class MzTimeHelper extends TimeHelper {
/**
 * modified version of timehelpers function (with less words)
 */
/**
 * Returns either a relative date or a formatted date depending
 * on the difference between the current time and given datetime.
 * $datetime should be in a <i>strtotime</i> - parsable format, like MySQL's datetime datatype.
 *
 * ### Options:
 *
 * - `format` => a fall back format if the relative time is longer than the duration specified by end
 * - `end` => The end of relative time telling
 * - `userOffset` => Users offset from GMT (in hours)
 *
 * Relative dates look something like this:
 *	3 weeks, 4 days ago
 *	15 seconds ago
 *
 * Default date formatting is d/m/yy e.g: on 18/2/09
 *
 * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
 * like 'Posted ' before the function output.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param array $options Default format if timestamp is used in $dateString
 * @return string Relative time string.
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function timeAgoInWords($dateTime, $options = array()) {
		$userOffset = null;
		if (is_array($options) && isset($options['userOffset'])) {
			$userOffset = $options['userOffset'];
		}
		$now = time();
		if (!is_null($userOffset)) {
			$now = $this->convert(time(), $userOffset);
		}
		$inSeconds = $this->fromString($dateTime, $userOffset);
		$backwards = ($inSeconds > $now);

		$format = 'j/n/y';
		$end = '+1 month';

		if (is_array($options)) {
			if (isset($options['format'])) {
				$format = $options['format'];
				unset($options['format']);
			}
			if (isset($options['end'])) {
				$end = $options['end'];
				unset($options['end']);
			}
		} else {
			$format = $options;
		}

		if ($backwards) {
			$futureTime = $inSeconds;
			$pastTime = $now;
		} else {
			$futureTime = $now;
			$pastTime = $inSeconds;
		}
		$diff = $futureTime - $pastTime;

		// If more than a week, then take into account the length of months
		if ($diff >= 604800) {
			$current = array();
			$date = array();

			list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

			list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
			$years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

			if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
				$months = 0;
				$years = 0;
			} else {
				if ($future['Y'] == $past['Y']) {
					$months = $future['m'] - $past['m'];
				} else {
					$years = $future['Y'] - $past['Y'];
					$months = $future['m'] + ((12 * $years) - $past['m']);

					if ($months >= 12) {
						$years = floor($months / 12);
						$months = $months - ($years * 12);
					}

					if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
						$years --;
					}
				}
			}

			if ($future['d'] >= $past['d']) {
				$days = $future['d'] - $past['d'];
			} else {
				$daysInPastMonth = date('t', $pastTime);
				$daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

				if (!$backwards) {
					$days = ($daysInPastMonth - $past['d']) + $future['d'];
				} else {
					$days = ($daysInFutureMonth - $past['d']) + $future['d'];
				}

				if ($future['m'] != $past['m']) {
					$months --;
				}
			}

			if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
				$months = 11;
				$years --;
			}

			if ($months >= 12) {
				$years = $years + 1;
				$months = $months - 12;
			}

			if ($days >= 7) {
				$weeks = floor($days / 7);
				$days = $days - ($weeks * 7);
			}
		} else {
			$years = $months = $weeks = 0;
			$days = floor($diff / 86400);

			$diff = $diff - ($days * 86400);

			$hours = floor($diff / 3600);
			$diff = $diff - ($hours * 3600);

			$minutes = floor($diff / 60);
			$diff = $diff - ($minutes * 60);
			$seconds = $diff;
		}
		$relativeDate = '';
		$diff = $futureTime - $pastTime;

		if ($diff > abs($now - $this->fromString($end))) {
			$relativeDate = sprintf(__('on %s',true), date($format, $inSeconds));
		} else {
			if ($years > 0) {
				// years and months
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d year', '%d years', $years, true), $years);
				$relativeDate .= $months > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d month', '%d months', $months, true), $months) : '';
			//	$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d week', '%d weeks', $weeks, true), $weeks) : '';
			//	$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d day', '%d days', $days, true), $days) : '';
			} elseif (abs($months) > 0) {
				// months, weeks
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d month', '%d months', $months, true), $months);
				$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d week', '%d weeks', $weeks, true), $weeks) : '';
			//	$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d day', '%d days', $days, true), $days) : '';
			} elseif (abs($weeks) > 0) {
				// weeks
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d week', '%d weeks', $weeks, true), $weeks);
		   //     $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d day', '%d days', $days, true), $days) : '';
			} elseif (abs($days) > 0) {
				// days
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d day', '%d days', $days, true), $days);
			//	$relativeDate .= $hours > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d hour', '%d hours', $hours, true), $hours) : '';
			} elseif (abs($hours) > 0) {
				// hours
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d hour', '%d hours', $hours, true), $hours);
			//	$relativeDate .= $minutes > 0 ? ($relativeDate ? ', ' : '') . sprintf(__n('%d minute', '%d minutes', $minutes, true), $minutes) : '';
			} elseif (abs($minutes) > 0) {
				// minutes only
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d minute', '%d minutes', $minutes, true), $minutes);
			} else {
				// seconds only
				$relativeDate .= ($relativeDate ? ', ' : '') . sprintf(__n('%d second', '%d seconds', $seconds, true), $seconds);
			}

			if (!$backwards) {
				$relativeDate = sprintf(__('%s ago', true), $relativeDate);
			}
		}
		return $relativeDate;
	}

}
?>