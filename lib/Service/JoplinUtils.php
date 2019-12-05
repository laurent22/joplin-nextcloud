<?php

namespace OCA\Joplin\Service;

class JoplinUtils {

	const TYPE_NOTE = 1;
	const TYPE_FOLDER = 2;

	static private function isoDateToMilliseconds($date) {
		$d = date_parse($date);
		$ms = strtotime($date);
		$ms *= 1000;
		$ms += $d['fraction'] * 1000;
		return (int)$ms;
	}

	static private function milliseconds() {
		return round(microtime(true) * 1000);
	}

	static private function millisecondsToIsoDate($ms) {
		$s = date('c', $ms / 1000);
		$remain = str_pad($ms % 1000, 3, '0', STR_PAD_LEFT);
		return str_replace('+00:00', '.' . $remain, $s) . 'Z';
	}

	static public function serializeItem($item) {
		$output = [];

		if ($item['type_'] === 1 || $item['type_'] === 2) {
			$output['title'] = isset($item['title']) ? $item['title'] : '';
		}

		if ($item['type_'] === 1) {
			$output['body'] = isset($item['body']) ? $item['body'] : '';
		}

		$output['props'] = [];

		foreach ($item as $k => $v) {
			if (in_array($k, ['title', 'body', 'fileId_'])) continue;
			$output['props'][] = $k . ': ' . self::serialize_format($k, $v);
		}

		$temp = [];
		if ($item['type_'] === 1 || $item['type_'] === 2) $temp[] = isset($item['title']) ? $item['title'] : '';
		if ($item['type_'] === 1) $temp[] = isset($item['body']) ? $item['body'] : '';
		if (count($output['props'])) $temp[] = implode("\n", $output['props']);

		return implode("\n\n", $temp);
	}

	static public function unserializeItem($content) {
		$lines = explode("\n", $content);
		$output = [];
		$state = 'readingProps';
		$body = [];

		for ($i = count($lines) - 1; $i >= 0; $i--) {
			$line = $lines[$i];

			if ($state === 'readingProps') {
				$line = trim($line);

				if ($line === '') {
					$state = 'readingBody';
					continue;
				}

				$p = strpos($line, ':');
				if ($p === false) throw new \Exception("Invalid property format: $line: $content");
				$key = trim(substr($line, 0, $p));
				$value = trim(substr($line, $p + 1));
				$output[$key] = $value;
			} else if ($state === 'readingBody') {
				array_unshift($body, $line);
			}
		}

		if (!isset($output['type_'])) throw new \Exception("Missing required property: type_: $content");
		$output['type_'] = (int)$output['type_'];

		if (count($body)) {
			$title = array_shift($body);
			array_shift($body);
			$output['title'] = $title;
		}
		
		if ($output['type_'] === 1) $output['body'] = implode("\n", $body);
		
		// TODO:
		// const ItemClass = this.itemClass(output.type_);
		// output = ItemClass.removeUnknownFields(output);

		foreach ($output as $k => $v) {
			$output[$k] = self::unserialize_format($k, $v);
		}

		return $output;
	}

	static private function unserialize_format($propName, $propValue) {
		if ($propName[strlen($propName) - 1] === '_') return $propValue; // Private property

		if (in_array($propName, ['created_time', 'updated_time', 'user_created_time', 'user_updated_time'])) {
			if (!$propValue) return 0;
			$propValue = self::isoDateToMilliseconds($propValue);
		} else {
			//propValue = Database.formatValue(ItemClass.fieldType(propName), propValue);
		}

		return $propValue;
	}

	static private function serialize_format($propName, $propValue) {
		if (in_array($propName, ['created_time', 'updated_time', 'sync_time', 'user_updated_time', 'user_created_time'])) {
			if (!$propValue) return '';
			$propValue = self::millisecondsToIsoDate($propValue);
		} else if ($propValue === null) {
			$propValue = '';
		}

		return $propValue;
	}

}
