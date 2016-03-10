<?php
/**
 * Albums model.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Model;

use Silex\Application;

class AlbumsModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $_db
     */
    protected $_db;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    /**
     * Gets all albums.
     *
     * @access public
     * @return array Result
     */
    public function getAll()
    {
        $query = 'SELECT id, title, artist FROM albums';
        return $this->_db->fetchAll($query);
    }

    /**
     * Gets single album data.
     *
     * @access public
     * @param integer $id Record Id
     * @return array Result
     */
    public function getAlbumToView($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = 'SELECT name, title, artist
                      FROM albums, categories
                      WHERE categories.id = category_id
                      AND albums.id = ?';
            return $this->_db->fetchAssoc($query, array((int)$id));
        } else {
            return array();
        }
    }

    /**
     * Gets single album data.
     *
     * @access public
     * @param integer $id Record Id
     * @return array Result
     */
    public function getAlbum($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = 'SELECT albums.id, category_id, title, artist
                      FROM albums 
                      LEFT JOIN categories 
                      ON categories.id = category_id 
                      WHERE albums.id= ?';
            return $this->_db->fetchAssoc($query, array((int)$id));
        } else {
            return array();
        }
    }

    /**
     * Get all albums on page.
     *
     * @access public
     * @param integer $page Page number
     * @param integer $limit Number of records on single page
     * @retun array Result
     */
    public function getAlbumsPage($page, $limit)
    {
        $query = 'SELECT albums.id, name as category_id, title, artist 
                  FROM albums
                  LEFT JOIN categories
                  ON categories.id = category_id 
                  LIMIT :start, :limit';
        $statement = $this->_db->prepare($query);
        $statement->bindValue('start', ($page-1)*$limit, \PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return !$result ? array() : $result;
    }

    /**
     * Counts album pages.
     *
     * @access public
     * @param integer $limit Number of records on single page
     * @return integer Result
     */
    public function countAlbumsPages($limit)
    {
        $pagesCount = 0;
        $sql = 'SELECT COUNT(*) as pages_count FROM albums';
        $result = $this->_db->fetchAssoc($sql);
        if ($result) {
            $pagesCount =  ceil($result['pages_count']/$limit);
        }
        return $pagesCount;
    }

    /**
     * Returns current page number.
     *
     * @access public
     * @param integer $page Page number
     * @param integer $pagesCount Number of all pages
     * @return integer Page number
     */
    public function getCurrentPageNumber($page, $pagesCount)
    {
        return (($page <= 1) || ($page > $pagesCount)) ? 1 : $page;
    }

     /* Save album.
     *
     * @access public
     * @param array $album Album data
     * @retun mixed Result
     */
    public function saveAlbum($album)
    {
        if (isset($album['id'])
            && ($album['id'] != '')
            && ctype_digit((string)$album['id'])) {
            // update record
            $id = $album['id'];
            unset($album['id']);
            return $this->_db->update('albums', $album, array('id' => $id));
        } else {
            // add new record
            return $this->_db->insert('albums', $album);
        }
    }

     /* Delete album.
     *
     * @access public
     * @param array $album Album data
     * @retun mixed Result
     */
    public function deleteAlbum($album)
    {
        if (isset($album['id'])
            && ($album['id'] != '')
            && ctype_digit((string)$album['id'])) {
            // delete record
            $id = $album['id'];
            return $this->_db->delete('albums', array('id' => $id));
        }
    }

    /**
     * Gets albums for pagination.
     *
     * @access public
     * @param integer $page Page number
     * @param integer $limit Number of records on single page
     *
     * @return array Result
     */
     public function getPaginatedAlbums($page, $limit)
     {
         $pagesCount = $this->countAlbumsPages($limit);
         $page = $this->getCurrentPageNumber($page, $pagesCount);
         $albums = $this->getAlbumsPage($page, $limit);
         return array(
             'albums' => $albums,
             'paginator' => array('page' => $page, 'pagesCount' => $pagesCount)
         );
     }

}

