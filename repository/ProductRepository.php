<?php

namespace app\repository;
use app\entity\Product;

class ProductRepository
{
    public static function getProductBuId($id)
    {
        return Product::find()
            ->where(['id' => $id])
            ->one();
    }
}