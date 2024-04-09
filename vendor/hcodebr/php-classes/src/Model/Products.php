<?php

namespace Hcode\Model;

use Hcode\DB\Sql;
use Hcode\Model;

class Products extends Model {

    public static function listAll() {

        $sql = new Sql();

        return $sql->select("select * from tb_products ORDER BY idproduct");

    }

    public function save(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)",
        array (
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));

        $this->setData($results[0]);

    }

    public function get($idproducts) {

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproducts", [
			':idproducts' => $idproducts
		]);

        
		$this->setData($results[0]);

	}

	public function delete($idproducts) {

		$sql = new Sql();

		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproducts", [
			':idproducts' => $idproducts
		]);

    }

    public function checkPhoto(){

        if (\file_exists($_SERVER['DOCUMENT_ROOT'] . \DIRECTORY_SEPARATOR .
        "res" . \DIRECTORY_SEPARATOR . 
        "site" . \DIRECTORY_SEPARATOR .
        "img" . \DIRECTORY_SEPARATOR . 
        "products" . \DIRECTORY_SEPARATOR . 
        $this->getidproduct() . '.jpg')){

            return "/ecommerce/res/site/img/products/" . $this->getidproduct() . ".jpg";

        } else {

            $url = "/ecommerce/res/site/img/product.jpg";

        }

        return $this->setdesphoto($url);

    }

    public function getValues()
    {

        $this->checkPhoto();
        
        $values = parent::getValues();

        return $values;

    }

    public function setPhoto($file) {

        $extension = explode('.', $file['name']);
        $extension = end($extension);

        switch ($extension){

            case "jpg";
            case "jpeg";
            $image = \imagecreatefromjpeg($file["tmp_name"]);
            break;

            case "gif";
            $image = \imagecreatefromgif($file["tmp_name"]);
            break; 

            case "png";
            $image = \imagecreatefrompng($file["tmp_name"]);
            break;
            
        }

            $dist = $_SERVER['DOCUMENT_ROOT']  . DIRECTORY_SEPARATOR .
            "res" . \DIRECTORY_SEPARATOR . 
            "site" . \DIRECTORY_SEPARATOR .
            "img" . \DIRECTORY_SEPARATOR . 
            "products" . \DIRECTORY_SEPARATOR . 
            $this->getidproduct() . ".jpg";

            \imagejpeg($image, $dist);
            \imagedestroy($image);

            $this->checkPhoto();

    }
  
}

?>
