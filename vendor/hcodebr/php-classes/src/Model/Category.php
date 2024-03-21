<?php

namespace Hcode\Model;

use Hcode\DB\Sql;
use Hcode\Model;

class Category extends Model {

    public static function listAll() {

        $sql = new Sql();

        return $sql->select("select * from tb_categories ORDER BY idcategory");

    }

    public function save(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)",
        array (
            ":idcategory"=>$this->getidcategory(),
            ":descategory"=>$this->getdescategory()
        ));

        $this->setData($results[0]);

        Category::updateFile();

    }

    public function get($idcategory) {

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
			':idcategory' => $idcategory
		]);

		$this->setData($results[0]);
	}

	public function delete() {

		$sql = new Sql();

		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
			':idcategory' => $this->getidcategory()
		]);

        Category::updateFile();

    }

    public static function updateFile() {

        $categories = Category::listAll();

        $html = [];

        foreach ($categories as $row) {

            array_push($html, '<li><a href="/ecommerce/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');

        }

        $html_content = implode('', $html);

    // Construir o caminho completo do arquivo usando barras
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/ecommerce/vendor/views/categories-menu.html";

    // Escrever os dados no arquivo
    file_put_contents($file_path, $html_content);

    }
}
?>
