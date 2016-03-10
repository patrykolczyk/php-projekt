<?php
/**
 * Categories model.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Model;

use Silex\Application;

class CategoriesModel
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
     * Gets all categories.
     *
     * @access public
     * @return array Result
     */
    public function getAll()
    {
        $query = 'SELECT id, name FROM categories';
        return $this->_db->fetchAll($query);
    }

    /**
     * Gets single category data.
     *
     * @access public
     * @param integer $id Record Id
     * @return array Result
     */
    public function getCategory($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = 'SELECT id, name FROM categories WHERE id= ?';
            return $this->_db->fetchAssoc($query, array((int)$id));
        } else {
            return array();
        }
    }

    /**
     * Counts data.
     *
     * @access public
     * @param integer $id Record Id
     * @return array Result
     */
    public function countRecords($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = 'SELECT count(*) as counter
                      FROM albums 
                      WHERE category_id = ?';
            return $this->_db->fetchAssoc($query, array((int)$id));
        } else {
            return array();
        }
    }


     /* Save album.
     *
     * @access public
     * @param array $album Album data
     * @retun mixed Result
     */
    public function saveCategory($category)
    {
        if (isset($category['id'])
            && ($category['id'] != '')
            && ctype_digit((string)$category['id'])) {
            // update record
            $id = $category['id'];
            unset($category['id']);
            return $this->_db->update('categories', $category, array('id' => $id));
        } else {
            // add new record
            return $this->_db->insert('categories', $category);
        }
    }

     /* Delete album.
     *
     * @access public
     * @param array $category Category data
     * @retun mixed Result
     */
    public function deleteCategory($category)
    {
        if (isset($category['id'])
            && ($category['id'] != '')
            && ctype_digit((string)$category['id'])) {
            // delete record
            $id = $category['id'];
            return $this->_db->delete('categories', array('id' => $id));
        }
    }

}

