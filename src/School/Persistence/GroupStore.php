<?php

namespace School\Persistence;

use School\Model\Group;

class GroupStore
{
    public static function instance()
    {
        return new GroupStore();
    }

    /**
     * @param int $id
     * @throws DatabaseException
     * @return Group
     */
    public function find($id)
    {
        throw new DatabaseException;
    }

    public function persist(Group $group)
    {
        throw new DatabaseException;
    }
}
