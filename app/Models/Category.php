<?php

namespace App\Models;

use App\Model;

class Category extends Model
{
    protected $tableName = 'categories';

    public function all()
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select('*')->from($this->tableName);

        return $stmt->fetchAllAssociative();
    }
}
