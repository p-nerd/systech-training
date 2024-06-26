<?php

namespace Core;

use PDO;
use PDOStatement;

class DB
{
    public PDO $pdo;
    public PDOStatement $statement;
    public function __construct($credentials, $username = "root", $password = "")
    {
        $dsn       = "mysql:" . http_build_query($credentials, "", ";");
        $this->pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    public function query($sql, $params = []): static
    {
        $this->statement = $this->pdo->prepare($sql);
        $this->statement->execute($params);
        return $this;
    }
    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
    public function find()
    {
        return $this->statement->fetch();
    }
    public function findOrFail()
    {
        $item = $this->find();
        if (!$item) {
            abort("Item not found", 404);
        }
        return $item;
    }
    public function finds(): false|array
    {
        return $this->statement->fetchAll();
    }
}
