<?php
require_once('GShoppingContent.php');
ini_set('display_errors', 1);
class Shopping {

    private $_client = false;

    public function __construct($codigoMerchant = false, $email = false, $senha = false)
    {

        if ($codigoMerchant && $email && $senha)
        {
            $creds = array(
                "merchantId" => $codigoMerchant,
                "email" => $email,
                "password" => $senha
            );

            $this->login($creds);

            return true;
        } else {
            throw new Exception('Não foi possível autenticar a conta');
        }
    }

    public function insert($sku = false, $title = false, $description = false, $link = false, $image = false,  $price = false, $adult = 'false', $stock = 'in stock')
    {
        try {
            $this->insertProduct($sku, $title, $description, $link, $image, $price, $adult, $stock);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function listAll($maxResults = false)
    {
        if($maxResults)
            $feed = $this->_client->getProducts($maxResults);
        else
            $feed = $this->_client->getProducts();

        $products = $feed->getProducts();

        return $products;
    }

    public function countProducts()
    {
        $allProducts = $this->_client->getProducts();

        return count($allProducts->getProducts());
    }

    public function delete($product = false){
        if($product)
            $client->deleteProduct($product);
        else
            return false;

        return true;
    }

    private function insertProduct($sku, $title, $description, $link, $image, $price, $adult, $stock)
    {
        $product = new GSC_Product();

        try {
            $product->setContentLanguage('PT-BR');
            $product->setTargetCountry('BR');
            $product->setSKU($sku);
            $product->setProductLink($link);
            $product->setTitle($title);

            if($description){
                $product->setDescription($description);
            }

            $product->setImageLink($image);
            $product->setBrand(0);
            $product->setMpn($sku);
            $product->setPrice($price, "BRL");
            $product->setAdult($adult);
            $product->setAvailability($stock);
            $product->setCondition("new");

            $this->_client->insertProduct($product);
        } catch(Exception $e) {
            var_dump($e);exit;
        }
    }

    public function update($editLink = false, $sku = false, $title = false, $description = false, $link = false, $image = false,  $price = false, $adult = 'false', $stock = 'in stock')
    {
        $product = new GSC_Product();
        $type = "application/atom+xml";

        try {
            $product->setContentLanguage('PT-BR');
            $product->setTargetCountry('BR');
            $product->setSKU($sku);
            $product->setProductLink($link);
            $product->setTitle($title);

            if($description){
                $product->setDescription($description);
            }

            $product->setImageLink($image);
            $product->setBrand(0);
            $product->setMpn($sku);
            $product->setPrice($price, "BRL");
            $product->setAdult($adult);
            $product->setAvailability($stock);
            $product->setCondition("new");
            $product->setEditLink($editLink, $type);

            $this->_client->updateProduct($product);
        } catch(Exception $e) {
            $teste = array($sku, $title, $description,$link,$image,$price,$adult,$stock);
            var_dump($teste);exit;
        }
    }

    public function getCadastrados(){

        $array = array();
        foreach ($this->listAll() as $lista)
        {
            $array[$lista->getSKU()] = $lista;
        }

        return $array;
    }

    private function login($information)
    {
        $this->_client = new GSC_Client($information["merchantId"]);
        $this->_client->login($information["email"], $information["password"]);
    }
}
