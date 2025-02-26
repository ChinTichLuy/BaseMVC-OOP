<?php

namespace App\Models;

use App\Model;

class Product extends Model
{
    protected $tableName = 'products';

    public function all()
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select(
            'p.id',
            'p.name',
            'p.img_thumbnail',
            'p.created_at',
            'p.updated_at',
            'c.name c_name'

        )
            ->from($this->tableName, 'p')
            ->join('p', 'categories', 'c', 'p.category_id = c.id');

        return $stmt->fetchAllAssociative();
    }

    public function find($id)
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select(
            'p.id',
            'p.category_id',
            'p.name',
            'p.img_thumbnail',
            'p.description',
            'p.created_at',
            'p.updated_at',
            'c.name c_name'

        )
            ->from($this->tableName, 'p')
            ->join('p', 'categories', 'c', 'p.category_id = c.id')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $stmt->fetchAssociative();
    }

    public function add($data)
    {
        $this->connection->insert($this->tableName,$data);
    }
    public function update($id, $data)
    {
        $this->connection->update($this->tableName,$data,['id'=>$id]);
    }
    public function delete($id)
    {
        $this->connection->delete($this->tableName,['id'=>$id]);
    }
}
