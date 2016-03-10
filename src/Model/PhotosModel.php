<?php
/**
 * Photos model.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Model;

use Silex\Application;

/**
 * Class PhotosModel.
 *
 * @category Epi
 * @package Model
 * @use Silex\Application
 */
class PhotosModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    /**
     * Save image.
     *
     * @access public
     * @param array $image Image data from request
     * @param string $mediaPath Path to media folder on disk
     * @throws \PDOException
     * @return mixed Result
     */
    public function saveImage($image, $mediaPath)
    {
        try {
            $originalFilename = $image['image']->getClientOriginalName();
            $newFilename = $this->createName($originalFilename);
            $image['image']->move($mediaPath, $newFilename);
            $this->saveFilename($newFilename);
            return true;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Save filename in database.
     *
     * @access protected
     * @param string $name Filename
     * @return mixed Result
     */
    protected function saveFilename($name)
    {
        return $this->db->insert('files', array('name' => $name));
    }

    /**
     * Creates random filename.
     *
     * @access protected
     * @param string $name Source filename
     *
     * @return string Result
     */
    protected function createName($name)
    {
        $newName = '';
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $newName = $this->createRandomString(32) . '.' . $ext;

        while (!$this->isUniqueName($newName)) {
            $newName = $this->createRandomString(32) . '.' . $ext;
        }

        return $newName;
    }

    /**
     * Creates random string.
     *
     * @acces protected
     * @param integer $length String length
     *
     * @return string Result
     */
    protected function createRandomString($length)
    {
        $string = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $string .= $keys[array_rand($keys)];
        }
        return $string;
    }

    /**
     * Checks if filename is unique.
     *
     * @access protected
     * @param string $name Name
     * @return bool Result
     */
    protected function isUniqueName($name)
    {
        try {
            $query = '
              SELECT
                COUNT(*) AS files_count
              FROM
                files
              WHERE
                name = :name
              ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('name', $name, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $result = current($result);
            return !$result['files_count'];
        } catch (\PDOException $e) {
            throw $e;
        }
    }



}
