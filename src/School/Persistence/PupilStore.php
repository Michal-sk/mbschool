<?php

namespace School\Persistence;

use School\Model\Pupil;

class PupilStore
{
    public static function instance()
    {
        return new PupilStore();
    }

    /**
     * @param int $id
     * @throws DatabaseException
     * @return Pupil
     */
    public function find($id)
    {
        throw new DatabaseException;
    }

    public function persist(Pupil $pupil)
    {
        throw new DatabaseException;
    }
}
