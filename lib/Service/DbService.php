<?php

namespace OCA\Joplin\Service;

use OCP\IDBConnection;

class DbService {

	private $db_;

	public function __construct(IDBConnection $db) {
		$this->db_ = $db;
	}

	public function fetchAll($sql, $params = []) {
		$statement = $this->db_->prepare($sql);
		foreach ($params as $k => $v) $statement->bindValue($k, $v);
		$statement->execute();
		$r = $statement->fetchAll();
		return $r ? $r : [];
	}

	public function fetchOne($sql, $params = []) {
		if (strpos(strtolower($sql), 'limit 1') === false) $sql .= ' LIMIT 1';
		$statement = $this->db_->prepare($sql);
		foreach ($params as $k => $v) $statement->bindValue($k, $v);
		$statement->execute();
		$r = $statement->fetch(\PDO::FETCH_ASSOC);
		return $r ? $r : null;
	}

	public function insert($tableName, $model) {
		$sqlColumns = [];
		$sqlValues = [];
		foreach ($model as $k => $v) {
			$sqlColumns[] = '`' . $k . '`';
			$sqlValues[] = ':' . $k;
		}

		$sql = 'INSERT INTO *PREFIX*' . $tableName . ' (' . implode(', ', $sqlColumns) . ') VALUES (' . implode(', ', $sqlValues) .')';

		$statement = $this->db_->prepare($sql);
		foreach ($model as $k => $v) $statement->bindValue($k, $v);
		return $statement->execute();
	}

	public function update($tableName, $model, $conditions) {
		$values = [];
		$conditionStrings = [];
		foreach ($model as $k => $v) $values[] = "`$k` = :$k";
		foreach ($conditions as $k => $v) $conditionStrings[] = "`$k` = :$k";

		$sql = 'UPDATE *PREFIX*' . $tableName . ' SET ' . implode(', ', $values) . ' WHERE ' . implode(' AND ', $conditionStrings);

		$statement = $this->db_->prepare($sql);
		foreach ($model as $k => $v) $statement->bindValue($k, $v);
		foreach ($conditions as $k => $v) $statement->bindValue($k, $v);
		return $statement->execute();
	}

}