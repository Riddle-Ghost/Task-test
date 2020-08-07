<?php

namespace App\Core;

abstract class Model
{

	protected $table;
	protected $pdo;

	public function __construct()
	{
		$this->pdo = Db::getInstance();
		
	}

	public function find(int $id)
	{
		
		$table = $this->table;

		$sql = "SELECT * FROM $table
            WHERE `id`= :id
            LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		if ( $stmt->rowCount() < 1 ) {
			return false;
		}

		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	public function all()
	{
		
		$table = $this->table;

		$sql = "SELECT * FROM $table";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();

		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

	public function paginate(int $limit, int $page = 1, string $sort = 'id', string $order = 'asc')
	{
		
		$offset = ($page - 1) * $limit;

		$table = $this->table;

		$sql = "SELECT * FROM $table
				ORDER BY $sort $order
				LIMIT :limit OFFSET :offset";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			'limit' => $limit,
			'offset' => $offset,
		]);
		
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

	public function create(array $array)
	{
		
		$table = $this->table;

		$keys = array_keys($array);

		$cols_string = "`" . implode("`, `", $keys) . "`";
		$values_string = ":" . implode(", :", $keys);

		$sql = "INSERT INTO $table($cols_string)
                  VALUES ($values_string)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($array);

		return $this->pdo->lastInsertId();
	}

	public function update($id, array $array)
	{
		
		$table = $this->table;

		$keys = array_keys($array);

		$cols_string = '';

		foreach ($keys as $key) {
			$cols_string .= "`$key` = :$key, ";
		}

		$cols_string = substr($cols_string, 0, -2);

		$array['id'] = $id;
		
		$sql = "UPDATE $table SET $cols_string WHERE `id` = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($array);

		return $this->pdo->lastInsertId();
	}

	public function delete(int $id)
	{
		
		$table = $this->table;

		$sql = "DELETE FROM $table WHERE id =:id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		if( !$stmt->rowCount() ) {
			return false;
		}

		return true;
	}

	public function count()
	{

		$table = $this->table;

		$sql = "SELECT COUNT(*) FROM $table";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);

		$count = $row['COUNT(*)'];

		return $count;
	}

	public static function __callStatic($method, $parameters)
	{
		$method = ltrim($method, '_');
		return (new static)->$method(...$parameters);
	}
}
