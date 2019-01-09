<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;

$app->get("/admin/categories", function(){

	//verifica se a pessoa esta logada ou não
	User::verifyLogin();

	$categories = Category::listAll();//uma classe Category com o metodo ListAll

	$page = new PageAdmin();
	$page->setTpl("categories", [
		'categories'=>$categories//o template recebe um array
	]);


});

$app->get("/admin/categories/create", function(){//rota para criar categoria
	//verifica se a pessoa esta logada ou não
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("categories-create");
});



$app->post("/admin/categories/create", function(){//rota para criar categoria
	//verifica se a pessoa esta logada ou não
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	header('Location: /admin/categories');
	exit;
});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){
	//verifica se a pessoa esta logada ou não
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory); //metodo para carregar o objto pra ter certeza que ele ainda existe
	$category->delete();
	header('Location: /admin/categories');
	exit;
});


$app->get("/admin/categories/:idcategory", function($idcategory){
	//verifica se a pessoa esta logada ou não
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();
	$page->setTpl("categories-update", [
		'category'=>$category->getValues()
	]);
});

$app->post("/admin/categories/:idcategory", function($idcategory){
	//verifica se a pessoa esta logada ou não
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;
});


$app->get("/categories/:idcategory", function($idcategory){
	
	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", [
		'category'=>$category->getValues(),
		'products'=>[]
	]);

});

$app->get("/admin/categories/:idcategory/products", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("categories-products", [
		'category'=>$category->getValues(),
		'productsRelated'=>$category->getProducts(),
		'productsNotRelated'=>$category->getProducts(false)
	]);
});
$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$product = new Product();
	$product->get((int)$idproduct);
	$category->addProduct($product);
	header("Location: /admin/categories/".$idcategory."/products");
	exit;
});
$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$product = new Product();
	$product->get((int)$idproduct);
	$category->removeProduct($product);
	header("Location: /admin/categories/".$idcategory."/products");
	exit;
});


?>