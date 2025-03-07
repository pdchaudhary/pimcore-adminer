<?php
/**
 * Copyright Blackbit digital Commerce GmbH <info@blackbit.de>
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

namespace CORS\Bundle\AdminerBundle\Model;

interface Repository
{
    public function find(array $where = [], string $order = null, int $count = null, int $offset = 0, $groupBy = null, array $columns = ['*']): array;

    /**
     * C in CRUD
     *
     * @param array $data
     * @return boolean|int
     */
    public function create(array $data);

    /**
     * U in CRUD
     *
     * @param array $data
     * @param array $where
     * @return boolean
     */
    public function update($data, $where);

    /**
     * D in CRUD
     *
     * @param array $id
     * @return int number of rows deleted
     */
    public function delete($where);

    public function deleteWhere(array $where = []);

    public function get($id): array;

    public function beginTransaction();

    public function commit();

    public function rollback();
}