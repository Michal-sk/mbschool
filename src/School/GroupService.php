<?php
declare(strict_types = 1);

namespace School;

use School\Model\Group;
use School\Model\Pupil;
use School\Persistence\GroupStore;
use School\Persistence\PupilStore;
use School\Model\TooManyPupilsException;
use function trigger_error;

/**
 * Class GroupService
 * Used to assign a Pupil to a Group.
 *
 * We mark all methods as private until needed public. As soon as something
 * is marked as public we will have to keep a backwards compatibility.
 *
 * @package School
 */
class GroupService
{
    /**
     * The maximum amount of Pupils allowed in a Group.
     *
     * @var int
     */
    private const MAX_PUPILS_IN_GROUP = 5;

    /**
     * @var GroupStore
     */
    private $groupStore;

    /**
     * @var PupilStore
     */
    private $pupilStore;

    /**
     * For backwards compatibility the GroupStore and PupilStore are optional.
     * But by injecting the dependencies trough the constructor we can test the service.
     *
     * GroupService constructor.
     * @param GroupStore|null $groupStore
     * @param PupilStore|null $pupilStore
     */
    public function __construct(GroupStore $groupStore = null, PupilStore $pupilStore = null)
    {
        $this->groupStore = $groupStore ?? GroupStore::instance();
        $this->pupilStore = $pupilStore ?? PupilStore::instance();
    }

    /**
     * add adds a Pupil to the Group
     *
     * @param $groupId
     * @param $pupilId
     * @throws Persistence\DatabaseException
     * @throws TooManyPupilsException
     *
     * @deprecated this method is being deprecated and will show a warning.
     * @see GroupService::assignPupilToGroup()
     */
    public function add($groupId, $pupilId): void
    {
        trigger_error('GroupService::add is deprecated. Please use GroupService::assignPupilToGroup.');

        $pupil = $this->pupilStore->find($pupilId);
        $group = $this->groupStore->find($groupId);

        $this->assignPupilToGroup($pupil, $group);

        return;
    }

    /**
     * assignPupilToGroup tries to assign the given Pupil to the given Group.
     *
     * We keep the parameter order the same as the method name describes.
     *
     * @param Pupil $pupil
     * @param Group $group
     * @throws Persistence\DatabaseException
     * @throws TooManyPupilsException
     */
    public function assignPupilToGroup(Pupil $pupil, Group $group): void
    {
        if ($this->groupIsFull($group)) {
            throw new TooManyPupilsException();
        }

        if ($this->pupilIsInGroup($pupil, $group)) {
            return;
        }

        $group->addPupil($pupil);

        $this->groupStore->persist($group);
    }

    /**
     * groupHasMaxPupils returns if the maximum amount of Pupils to a Group is reached.
     *
     * @param Group $group
     * @return bool
     */
    private function groupIsFull(Group $group): bool
    {
        $pupils = $group->getPupils() ?? [];

        return count($pupils) >= self::MAX_PUPILS_IN_GROUP;
    }

    /**
     * pupilIsInGroup returns if the Pupil is in the Group.
     *
     * @param Pupil $pupil
     * @param Group $group
     * @return bool
     */
    private function pupilIsInGroup(Pupil $pupil, Group $group): bool
    {
        $pupils = $group->getPupils() ?? [];

        foreach ($pupils as $pupilInGroup) {
            if ($pupil->getId() === $pupilInGroup->getId()) {
                return true;
            }
        }

        return false;
    }
}
