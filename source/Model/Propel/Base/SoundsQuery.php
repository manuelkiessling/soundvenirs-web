<?php

namespace Soundvenirs\Model\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Soundvenirs\Model\Propel\Sounds as ChildSounds;
use Soundvenirs\Model\Propel\SoundsQuery as ChildSoundsQuery;
use Soundvenirs\Model\Propel\Map\SoundsTableMap;

/**
 * Base class that represents a query for the 'sounds' table.
 *
 *
 *
 * @method     ChildSoundsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSoundsQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildSoundsQuery orderByLat($order = Criteria::ASC) Order by the lat column
 * @method     ChildSoundsQuery orderByLong($order = Criteria::ASC) Order by the long column
 * @method     ChildSoundsQuery orderByMp3url($order = Criteria::ASC) Order by the mp3url column
 *
 * @method     ChildSoundsQuery groupById() Group by the id column
 * @method     ChildSoundsQuery groupByTitle() Group by the title column
 * @method     ChildSoundsQuery groupByLat() Group by the lat column
 * @method     ChildSoundsQuery groupByLong() Group by the long column
 * @method     ChildSoundsQuery groupByMp3url() Group by the mp3url column
 *
 * @method     ChildSoundsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSoundsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSoundsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSounds findOne(ConnectionInterface $con = null) Return the first ChildSounds matching the query
 * @method     ChildSounds findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSounds matching the query, or a new ChildSounds object populated from the query conditions when no match is found
 *
 * @method     ChildSounds findOneById(string $id) Return the first ChildSounds filtered by the id column
 * @method     ChildSounds findOneByTitle(string $title) Return the first ChildSounds filtered by the title column
 * @method     ChildSounds findOneByLat(double $lat) Return the first ChildSounds filtered by the lat column
 * @method     ChildSounds findOneByLong(double $long) Return the first ChildSounds filtered by the long column
 * @method     ChildSounds findOneByMp3url(string $mp3url) Return the first ChildSounds filtered by the mp3url column
 *
 * @method     ChildSounds[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSounds objects based on current ModelCriteria
 * @method     ChildSounds[]|ObjectCollection findById(string $id) Return ChildSounds objects filtered by the id column
 * @method     ChildSounds[]|ObjectCollection findByTitle(string $title) Return ChildSounds objects filtered by the title column
 * @method     ChildSounds[]|ObjectCollection findByLat(double $lat) Return ChildSounds objects filtered by the lat column
 * @method     ChildSounds[]|ObjectCollection findByLong(double $long) Return ChildSounds objects filtered by the long column
 * @method     ChildSounds[]|ObjectCollection findByMp3url(string $mp3url) Return ChildSounds objects filtered by the mp3url column
 * @method     ChildSounds[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SoundsQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Soundvenirs\Model\Propel\Base\SoundsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'soundvenirs', $modelName = '\\Soundvenirs\\Model\\Propel\\Sounds', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSoundsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSoundsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSoundsQuery) {
            return $criteria;
        }
        $query = new ChildSoundsQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSounds|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SoundsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SoundsTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSounds A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ID, TITLE, LAT, LONG, MP3URL FROM sounds WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSounds $obj */
            $obj = new ChildSounds();
            $obj->hydrate($row);
            SoundsTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSounds|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SoundsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SoundsTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById('fooValue');   // WHERE id = 'fooValue'
     * $query->filterById('%fooValue%'); // WHERE id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $id The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $id)) {
                $id = str_replace('*', '%', $id);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SoundsTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SoundsTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the lat column
     *
     * Example usage:
     * <code>
     * $query->filterByLat(1234); // WHERE lat = 1234
     * $query->filterByLat(array(12, 34)); // WHERE lat IN (12, 34)
     * $query->filterByLat(array('min' => 12)); // WHERE lat > 12
     * </code>
     *
     * @param     mixed $lat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterByLat($lat = null, $comparison = null)
    {
        if (is_array($lat)) {
            $useMinMax = false;
            if (isset($lat['min'])) {
                $this->addUsingAlias(SoundsTableMap::COL_LAT, $lat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lat['max'])) {
                $this->addUsingAlias(SoundsTableMap::COL_LAT, $lat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SoundsTableMap::COL_LAT, $lat, $comparison);
    }

    /**
     * Filter the query on the long column
     *
     * Example usage:
     * <code>
     * $query->filterByLong(1234); // WHERE long = 1234
     * $query->filterByLong(array(12, 34)); // WHERE long IN (12, 34)
     * $query->filterByLong(array('min' => 12)); // WHERE long > 12
     * </code>
     *
     * @param     mixed $long The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterByLong($long = null, $comparison = null)
    {
        if (is_array($long)) {
            $useMinMax = false;
            if (isset($long['min'])) {
                $this->addUsingAlias(SoundsTableMap::COL_LONG, $long['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($long['max'])) {
                $this->addUsingAlias(SoundsTableMap::COL_LONG, $long['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SoundsTableMap::COL_LONG, $long, $comparison);
    }

    /**
     * Filter the query on the mp3url column
     *
     * Example usage:
     * <code>
     * $query->filterByMp3url('fooValue');   // WHERE mp3url = 'fooValue'
     * $query->filterByMp3url('%fooValue%'); // WHERE mp3url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mp3url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function filterByMp3url($mp3url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mp3url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mp3url)) {
                $mp3url = str_replace('*', '%', $mp3url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SoundsTableMap::COL_MP3URL, $mp3url, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSounds $sounds Object to remove from the list of results
     *
     * @return $this|ChildSoundsQuery The current query, for fluid interface
     */
    public function prune($sounds = null)
    {
        if ($sounds) {
            $this->addUsingAlias(SoundsTableMap::COL_ID, $sounds->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sounds table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SoundsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SoundsTableMap::clearInstancePool();
            SoundsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SoundsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SoundsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SoundsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SoundsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SoundsQuery
