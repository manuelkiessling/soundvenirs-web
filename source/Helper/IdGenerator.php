<?php

namespace Soundvenirs\Helper;

class IdGenerator
{
    protected $db;

    protected function isUnique($id)
    {
        $row = $this->db->fetchAssoc('SELECT uuid FROM sounds WHERE uuid = ?;', array($id));
        if ($row === false) {
            return true;
        } else {
            return false;
        }
    }

    protected function generateWithoutCheck()
    {
        return base_convert((string)rand(0, 2147483647), 10, 36);
    }

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param null|string $forcedId If set to an id, then this id is used - it is still tested for collisions, though
     */
    public function generate($forcedId = null)
    {
        if (is_string($forcedId)) {
            $id = $forcedId;
        } else {
            $id = $this->generateWithoutCheck();
        }
        while (!$this->isUnique($id)) {
            $id = $this->generateWithoutCheck();
        }
        return $id;
    }
}
