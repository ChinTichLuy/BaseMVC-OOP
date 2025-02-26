<?php

namespace App\Models;

use App\Model;

class User extends Model
{
    protected $tableName = 'users';

    public function getUserByEmail($email)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('*')
            ->from($this->tableName)
            ->where('email = :email')
            ->setParameter('email', $email);
        $result = $queryBuilder->fetchAssociative();

        return $result;
    }

    public function checkExistsEmailForCreate($email)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('COUNT(*)')
            ->from($this->tableName)
            ->where('email = :email')
            ->setParameter('email', $email);

        $result = $queryBuilder->fetchOne();

        return $result > 0;
    }
    public function checkExistsEmailForUpdate($id, $email)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('COUNT(*)')
            ->from($this->tableName)
            ->where('email = :email')
            ->andWhere('id != :id') //check cac email co id khac voi hien tai(tranh trung email)
            ->setParameter('email', $email)
            ->setParameter('id', $id);

        $result = $queryBuilder->fetchOne();

        return $result > 0;
    }
}
