<?php
/**
 * Created by PhpStorm.
 * User: romankyshnir
 * Date: 5/8/18
 * Time: 14:55
 */
namespace App;

use PDO;
use PDOException;

class Price {
    protected $DBH;

    public function __construct() {
        require_once __DIR__."/../config/db.php";

        try {
            $this->DBH = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
            $this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            file_put_contents(__DIR__.'/../log/PDOErrors.txt',date('Y-m-d H:i').' [Error] '.$e->getMessage()."\r\n",FILE_APPEND);
        }
    }

    public function index() {
        try {
            $selectPrice = $this->DBH->prepare("
            SELECT
              title,
              description,
              t3.id as doc_number,
              datetime,
              IF (t3.status = 1, price, '') as price
            FROM
              (SELECT * FROM DocPriceBody ORDER BY id DESC) as t1
            JOIN
              (Product as t2, DocPrice as t3)
              ON
                (t1.product_id = t2.id
                 AND
                 t1.doc_id = t3.id)
            WHERE
              t2.status = 0
              AND
              datetime >= '2018-05-06 00:00:00'
            GROUP BY
              product_id;
            ");
            $selectPrice->execute();
            $result = $selectPrice->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            file_put_contents(__DIR__.'/../log/PDOErrors.txt',date('Y-m-d H:i').' [Error] '.$e->getMessage()."\r\n",FILE_APPEND);
        }

        return false;
    }
}