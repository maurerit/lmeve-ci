<?php
/**
 * SolarSystem class file.
 *
 * PHP version 5.3
 *
 * @category IveeCore
 * @package  IveeCoreClasses
 * @author   Aineko Macx <ai@sknop.net>
 * @license  https://github.com/aineko-m/iveeCore/blob/master/LICENSE GNU Lesser General Public License
 * @link     https://github.com/aineko-m/iveeCore/blob/master/iveeCore/SolarSystem.php
 *
 */

namespace iveeCore;

/**
 * Class for representing solar systems
 * Inheritance: SolarSystem -> SdeTypeCommon
 *
 * @category IveeCore
 * @package  IveeCoreClasses
 * @author   Aineko Macx <ai@sknop.net>
 * @license  https://github.com/aineko-m/iveeCore/blob/master/LICENSE GNU Lesser General Public License
 * @link     https://github.com/aineko-m/iveeCore/blob/master/iveeCore/SolarSystem.php
 *
 */
class SolarSystem extends SdeTypeCommon
{
    /**
     * @var \iveeCore\InstancePool $instancePool used to pool (cache) SolarSystem objects
     */
    protected static $instancePool;

    /**
     * @var string $classNick holds the class short name which is used to lookup the configured FQDN classname in Config
     * (for dynamic subclassing) and is used as part of the cache key prefix for objects of this and child classes
     */
    protected static $classNick = 'SolarSystem';

    /**
     * @var int $regionID the ID of region of this SolarSystem.
     */
    protected $regionID;

    /**
     * @var int $constellationID the ID of the constellation of this SolarSystem.
     */
    protected $constellationID;

    /**
     * @var float $security the security rating of this SolarSystem.
     */
    protected $security;

    /**
     * @var int $industryIndexDate unix timstamp for the last update to industry system indices (day granularity)
     */
    protected $industryIndexDate;

    /**
     * @var array $industryIndices the system industry indices $activityID => float
     */
    protected $industryIndices = array();

    /**
     * @var array $stationIDs the IDs of Stations present in this SolarSystem
     */
    protected $stationIDs = array();

    /**
     * @var array $teamIDs the IDs of Teams active in this SolarSystem
     */
    protected $teamIDs = array();
  
    /**
     * Loads all SolarSystem names from DB to PHP
     *
     * @return void
     */
    protected static function loadNames()
    {
        //lookup SDE class
        $sdeClass = Config::getIveeClassName('SDE');

        $res = $sdeClass::instance()->query(
            "SELECT solarSystemID, solarSystemName 
            FROM mapSolarSystems;"
        );

        $namesToIds = array();
        while ($row = $res->fetch_assoc())
            $namesToIds[$row['solarSystemName']] = (int) $row['solarSystemID'];
        
        static::$instancePool->setNamesToIds($namesToIds);
    }

    /**
     * Constructor. Use \iveeCore\SolarSystem::getSolarSystem() to instantiate SolarSystem objects instead.
     *
     * @param int $id of the SolarSystem
     *
     * @return \iveeCore\SolarSystem
     * @throws \iveeCore\Exceptions\SolarSystemIdNotFoundException if solarSystemID is not found
     */
    protected function __construct($id)
    {
        $this->id = (int) $id;
        $sdeClass = Config::getIveeClassName('SDE');

        $row = $sdeClass::instance()->query(
            "SELECT regionID, constellationID, solarSystemName, security, crestIndexDate, manufacturingIndex,
                teResearchIndex, meResearchIndex, copyIndex, reverseIndex, inventionIndex
            FROM mapSolarSystems
            LEFT JOIN (
                SELECT systemID, UNIX_TIMESTAMP(date) as crestIndexDate, manufacturingIndex, teResearchIndex,
                meResearchIndex, copyIndex, reverseIndex, inventionIndex
                FROM iveeIndustrySystems
                WHERE systemID = " . $this->id . "
                ORDER BY date DESC LIMIT 1
            ) as iis ON iis.systemID = solarSystemID
            WHERE solarSystemID = " . $this->id . ";"
        )->fetch_assoc();

        if (empty($row))
            static::throwException('SystemIdNotFoundException', "SolarSystem ID=". $this->id . " not found" );

        //set data to attributes
        $this->regionID        = (int) $row['regionID'];
        $this->constellationID = (int) $row['constellationID'];
        $this->name            = $row['solarSystemName'];
        $this->security        = (float) $row['security'];
        if (isset($row['crestIndexDate']))
            $this->industryIndexDate = (int) $row['crestIndexDate'];
        if (isset($row['manufacturingIndex']))
            $this->industryIndices[1] = (float) $row['manufacturingIndex'];
        if (isset($row['teResearchIndex']))
            $this->industryIndices[3] = (float) $row['teResearchIndex'];
        if (isset($row['meResearchIndex']))
            $this->industryIndices[4] = (float) $row['meResearchIndex'];
        if (isset($row['copyIndex']))
            $this->industryIndices[5] = (float) $row['copyIndex'];
        if (isset($row['reverseIndex']))
            $this->industryIndices[7] = (float) $row['reverseIndex'];
        if (isset($row['inventionIndex']))
            $this->industryIndices[8] = (float) $row['inventionIndex'];

        //get stations in system
        $res = $sdeClass::instance()->query(
            "SELECT stationID
            FROM staStations
            WHERE solarSystemID = " . $this->id . ';'
        );

        //get stations in system
        while ($row = $res->fetch_assoc()) {
            $this->stationIDs[] = $row['stationID'];
        }

        //get teams in system
        $res = $sdeClass::instance()->query(
            "SELECT teamID
            FROM iveeTeams
            WHERE solarSystemID = " 
            . $this->id . " AND expiryTime > '" . date('Y-m-d H:i:s', time()) . "';"
        );

        while ($row = $res->fetch_assoc()) {
            $this->teamIDs[] = $row['teamID'];
        }
    }

    /**
     * Gets regionID of SolarSystem
     * 
     * @return int
     */
    public function getRegionID()
    {
        return $this->regionID;
    }

    /**
     * Gets constellationID of SolarSystem
     * 
     * @return int
     */
    public function getConstellationID()
    {
        return $this->constellationID;
    }

    /**
     * Gets security rating of SolarSystem
     * 
     * @return float
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Gets IDs of Stations in SolarSystem
     * 
     * @return array
     */
    public function getStationIDs()
    {
        return $this->stationIDs;
    }
    
    /**
     * Gets Stations in SolarSystem
     * 
     * @return array
     */
    public function getStations()
    {
        $stations = array();
        $stationClass = Config::getIveeClassName("Station");
        foreach ($this->getStationIDs() as $stationID)
            $stations[$stationID] = $stationClass::getStation($stationID);
        return $stations;
    }

    /**
     * Gets IDs of Teams in SolarSystem
     * 
     * @return array
     */
    public function getTeamIDs()
    {
        return $this->teamIDs;
    }
    
    /**
     * Gets Teams in SolarSystem
     * 
     * @return array
     */
    public function getTeams()
    {
        $teams = array();
        $teamClass = Config::getIveeClassName("Team");
        foreach ($this->getTeamIDs() as $teamID)
            $teams[$teamID] = $teamClass::getTeam($teamID);
        return $teams;
    }

    /**
     * Gets unix timstamp for the last update to industry system indices (day granularity)
     * 
     * @return int
     */
    public function getIndustryIndexDate()
    {
        if ($this->industryIndexDate > 0) {
            return $this->industryIndexDate;
        } else {
            static::throwException(
                'NoSystemDataAvailableException', 
                'No CREST system data available for SolarSystem ID=' . $this->id
            );
        }
    }

    /**
     * Gets industry indices of SolarSystem
     * 
     * @param int $maxIndexDataAge maximum index data age in seconds, optional
     * 
     * @return array in the form activityID => float
     * @throws \iveeCore\Exceptions\CrestDataTooOldException if given max index data age is exceeded
     */
    public function getIndustryIndices($maxIndexDataAge = null)
    {
        if ($maxIndexDataAge > 0 AND ($this->industryIndexDate + $maxIndexDataAge) < time())
            static::throwException('CrestDataTooOldException', 'Index data for ' . $this->getName() . ' is too old');

        return $this->industryIndices;
    }

    /**
     * Gets industry indices of SolarSystem
     * 
     * @param int $activityID the ID of the activity to get industry index for
     * @param int $maxIndexDataAge maximum index data age in seconds, optional
     * 
     * @return float
     * @throws \iveeCore\Exceptions\ActivityIdNotFoundException if no index data is found for activityID in this system
     */
    public function getIndustryIndexForActivity($activityID, $maxIndexDataAge = null)
    {
        if (isset($this->industryIndices[$activityID])) {
            if ($maxIndexDataAge > 0 AND ($this->industryIndexDate + $maxIndexDataAge) < time())
                static::throwException('CrestDataTooOldException', 'Index data for ' . $this->getName() . ' is too old');
            return $this->industryIndices[$activityID];
        } else {
            static::throwException(
                'ActivityIdNotFoundException', 
                'No industry index data found for activity ID=' . (int) $activityID
            );
        }
    }

    /**
     * Sets industry indices. Useful for wormhole systems or what-if scenarios. If called, industryIndexDate is updated.
     * 
     * @param array $indices must be in the form activityID => float
     * 
     * @return void
     */
    public function setIndustryIndices(array $indices)
    {
        $this->industryIndexDate = time();
        $this->industryIndices = $indices;
    }

    /**
     * Returns an IndustryModifier object for a POS in this system
     *
     * @param float $tax set on the POS
     *
     * @return \iveeCore\IndustryModifier
     */
    public function getIndustryModifierForPos($tax)
    {
        $industryModifierClass = Config::getIveeClassName('IndustryModifier');
        return $industryModifierClass::getBySystemIdForPos($this->id, $tax);
    }

    /**
     * Returns an IndustryModifier object for all NPC stations in this system
     *
     * @return \iveeCore\IndustryModifier
     */
    public function getIndustryModifierForAllNpcStations()
    {
        $industryModifierClass = Config::getIveeClassName('IndustryModifier');
        return $industryModifierClass::getBySystemIdForAllNpcStations($this->id);
    }
}
