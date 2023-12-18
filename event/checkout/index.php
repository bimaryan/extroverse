<?php
require_once "../../db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <title>Checkout - Shopee Style</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .checkout-container {
            margin-top: 30px;
        }

        .checkout-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .checkout-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .checkout-product {
            display: flex;
            margin-bottom: 20px;
        }

        .product-image {
            flex: 0 0 80px;
            margin-right: 20px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-details {
            flex: 1;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-price {
            color: #e44d26;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .checkout-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .checkout-button {
            padding: 10px 20px;
            background-color: #ee4d2d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .checkout-button:hover {
            background-color: #c84226;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="checkout-container">
            <div class="checkout-card">
                <div class="checkout-header">Checkout</div>

                <?php

                // Fetch products from the database
                $query = mysqli_query($koneksi, 'SELECT * FROM events');
                $products = mysqli_fetch_all($query, MYSQLI_ASSOC);

                // Display products in the checkout page
                foreach ($products as $product) {
                    echo '<div class="checkout-product">';
                    echo '<div class="product-image">';
                    echo '<img src="../buat_acara/' . $product['cover_foto'] . '" alt="' . $product['nama_acara'] . '">';
                    echo '</div>';
                    echo '<div class="product-details">';
                    echo '<div class="product-title">' . $product['nama_acara'] . '</div>';
                    echo '<div class="product-price">Rp' . $product['harga'] . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>

                <div class="checkout-actions">
                    <div class="total-price">Total: $49.98</div>
                    <button class="checkout-button">Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>