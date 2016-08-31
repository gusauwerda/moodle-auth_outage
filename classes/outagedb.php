<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The DB Context to manipulate Outages. Singleton class.
 *
 * @package    auth_outage
 * @author     Daniel Thee Roperto <daniel.roperto@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_outage;

final class outagedb
{
    /**
     * @var Singleton reference created on first use.
     */
    private static $singleton = null;

    /**
     * Returns the singleton instance.
     *
     * @return The singleton object.
     */
    public static function get() {
        if (is_null(self::$singleton)) {
            self::$singleton = new outagedb();
        }
        return self::$singleton;
    }

    /**
     * @var Referente to Moodle global $DB.
     */
    private $db;

    /**
     * Private clone method to prevent cloning singleton.
     *
     * @return void
     */
    private function __clone() {
    }

    /**
     * Private unserialize method to prevent unserializing singleton.
     *
     * @return void
     */
    private function __wakeup() {
    }

    /**
     * Private constructor (singleton), use outagedb::get() instead.
     */
    private function __construct() {
        global $DB;
        $this->db = $DB;
    }

    /**
     * Converts an stdClass object to an outage.
     *
     * @param \stdClass $obj Object from DB.
     * @return outage
     */
    private function object2outage(\stdClass $obj) {
        return new outage(
            $obj->id,
            $obj->starttime,
            $obj->stoptime,
            $obj->warningminutes,
            $obj->title,
            $obj->description,
            $obj->createdby,
            $obj->modifiedby,
            $obj->lastmod
        );
    }

    /**
     * Gets all outage entries.
     */
    public function getall() {
        $outages = [];
        $rs = $this->db->get_recordset('auth_outage', null, 'starttime,stoptime,title');
        foreach ($rs as $r) {
            $outages[] = $this->object2outage($r);
        }
        $rs->close();
        return $outages;
    }
}