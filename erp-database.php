<?php
/**
 * Plugin Name: ERP Database
 * Description: Helper plugin for managing custom ERP database tables.
 * Version: 1.1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

class ERP_Database_Plugin {
    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'create_tables']);
    }

    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $tables = [];
        $prefix = $wpdb->prefix . 'starerp_';

        $tables['products'] = "CREATE TABLE IF NOT EXISTS {$prefix}products (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            sku VARCHAR(50) NOT NULL,
            price DECIMAL(10,2) DEFAULT 0.00,
            stock INT(11) DEFAULT 0,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $tables['locations'] = "CREATE TABLE {$prefix}locations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables['stores'] = "CREATE TABLE {$prefix}stores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            location_id INT,
            manager VARCHAR(100),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables['product_categories'] = "CREATE TABLE {$prefix}product_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT
        ) $charset_collate;";

        $tables['product_types'] = "CREATE TABLE {$prefix}product_types (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT
        ) $charset_collate;";

        $tables['product_units'] = "CREATE TABLE {$prefix}product_units (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            abbreviation VARCHAR(20) NOT NULL
        ) $charset_collate;";

        $tables['stock_in'] = "CREATE TABLE {$prefix}stock_in (
            id INT AUTO_INCREMENT PRIMARY KEY,
            store_id INT NOT NULL,
            reference_no VARCHAR(50),
            stock_in_date DATE,
            remarks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables['stock_in_details'] = "CREATE TABLE {$prefix}stock_in_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stock_in_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            unit_price DECIMAL(10,2) DEFAULT 0.00
        ) $charset_collate;";

        $tables['stock_out'] = "CREATE TABLE {$prefix}stock_out (
            id INT AUTO_INCREMENT PRIMARY KEY,
            store_id INT NOT NULL,
            reference_no VARCHAR(50),
            stock_out_date DATE,
            remarks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $tables['stock_out_details'] = "CREATE TABLE {$prefix}stock_out_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stock_out_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            unit_price DECIMAL(10,2) DEFAULT 0.00
        ) $charset_collate;";

        foreach ($tables as $sql) {
            dbDelta($sql);
        }

        // Seed master tables
        $wpdb->insert("{$prefix}locations", ['name' => 'Main Warehouse', 'address' => 'Central Industrial Area']);
        $wpdb->insert("{$prefix}stores", ['name' => 'Downtown Store', 'location_id' => 1, 'manager' => 'John Doe']);
        $wpdb->insert("{$prefix}product_categories", ['name' => 'Electronics', 'description' => 'Electronic items']);
        $wpdb->insert("{$prefix}product_types", ['name' => 'Retail', 'description' => 'Retail distribution']);
        $wpdb->insert("{$prefix}product_units", ['name' => 'Piece', 'abbreviation' => 'pc']);

        // Seed products
        $product_table = $prefix . 'products';
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $product_table");
        if (!$exists) {
            $samples = [
                ['name' => 'Sample Product 1', 'sku' => 'SP1001', 'price' => 19.99, 'stock' => 50, 'description' => 'A sample product for testing.'],
                ['name' => 'Sample Product 2', 'sku' => 'SP1002', 'price' => 29.95, 'stock' => 30, 'description' => 'Another sample product.'],
                ['name' => 'Bluetooth Speaker', 'sku' => 'SP1003', 'price' => 49.99, 'stock' => 20, 'description' => 'Portable speaker with Bluetooth connectivity.'],
                ['name' => 'Wireless Mouse', 'sku' => 'SP1004', 'price' => 15.50, 'stock' => 100, 'description' => 'Ergonomic wireless mouse with USB receiver.'],
                ['name' => 'Gaming Keyboard', 'sku' => 'SP1005', 'price' => 59.95, 'stock' => 25, 'description' => 'Mechanical keyboard with RGB backlight.'],
                ['name' => 'USB-C Hub', 'sku' => 'SP1006', 'price' => 22.75, 'stock' => 40, 'description' => 'Multi-port USB-C hub for laptops.'],
                ['name' => 'Noise Cancelling Headphones', 'sku' => 'SP1007', 'price' => 99.90, 'stock' => 10, 'description' => 'Over-ear headphones with noise cancellation.']
            ];
            foreach ($samples as $product) {
                $wpdb->insert($product_table, $product);
            }
        }

        // Seed stock_in + details if empty
        $stock_in_table = $prefix . 'stock_in';
        if (!$wpdb->get_var("SELECT COUNT(*) FROM $stock_in_table")) {
            $wpdb->insert($stock_in_table, [
                'store_id' => 1,
                'reference_no' => 'SI-001',
                'stock_in_date' => '2025-05-01',
                'remarks' => 'First stock in'
            ]);
            $stock_in_id = $wpdb->insert_id;
            $wpdb->insert($prefix . 'stock_in_details', [
                'stock_in_id' => $stock_in_id,
                'product_id' => 1,
                'quantity' => 10,
                'unit_price' => 19.99
            ]);
            $wpdb->insert($prefix . 'stock_in_details', [
                'stock_in_id' => $stock_in_id,
                'product_id' => 2,
                'quantity' => 5,
                'unit_price' => 29.95
            ]);
        }
    }
}

new ERP_Database_Plugin();