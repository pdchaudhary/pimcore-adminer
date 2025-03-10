<?php

namespace CORS\Bundle\AdminerBundle\Model;

interface Repository
{
    public function find(array $where = [], string $order = null, int $count = null, int $offset = 0, $groupBy = null, array $columns = ['*']): array;

    /**
     * C in CRUD.
     *
     * @return bool|int
     */
    public function create(array $data);

    /**
     * U in CRUD.
     *
     * @param array $data
     * @param array $where
     *
     * @return bool
     */
    public function update($data, $where);

    /**
     * D in CRUD.
     *
     * @param array $id
     *
     * @return int number of rows deleted
     */
    public function delete($where);

    public function deleteWhere(array $where = []);

    public function get($id): array;

    public function beginTransaction();

    public function commit();

    public function rollback();
}
