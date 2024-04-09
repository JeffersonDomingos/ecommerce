<?php

use Hcode\Model\User;
use Hcode\Model\Products;
use Hcode\PageAdmin;

$app->get("/admin/products/", function(){

    User::verifyLogin();

    $products = Products::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", [
        "products"=>$products
    ]);

});

$app->get("/admin/products/create", function(){

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");


});

$app->post("/admin/products/create/", function(){

    User::verifyLogin();

   $product = new Products();

   $product->setData($_POST);

   $product->save();
 
   header("Location: /ecommerce/admin/products");
   exit;

});

$app->get("/admin/products/:idproduct/", function($idproduct){

    User::verifyLogin();

    $product = new Products();

    $product->get((int)$idproduct);

    $page = new PageAdmin();

    $page->setTpl("products-update", [
        'product'=>$product->getValues()
]);

});


$app->post("/admin/products/:idproduct/", function($idproduct){

    User::verifyLogin();

    $product = new Products();

    $product->get((int)$idproduct);

    $product->setData($_POST);

    $product->save();

    if($_FILES["file"]["name"] !== "") $product->setPhoto($_FILES["file"]); 

    header('Location: /ecommerce/admin/products');
    exit;

});

$app->get("/admin/products/:idproducts/delete/", function($idproduct){

        User::verifyLogin();
    
        $product = new Products();
    
        $product->get((int)$idproduct);
    
        $product->delete($idproduct);
     
        header("Location: /ecommerce/admin/products/");
        exit;
    
    });




?>