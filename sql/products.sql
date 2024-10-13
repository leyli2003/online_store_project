CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    stock INT NOT NULL,
    normal_price DECIMAL(10, 2) NOT NULL,
    discounted_price DECIMAL(10, 2) NOT NULL,
    expiration_date DATE NOT NULL,
    image_url VARCHAR(255),
    category VARCHAR(255) NOT NULL,
    user_id INT NOT NULL
);

INSERT INTO products (
    title, stock, normal_price, discounted_price, expiration_date, image_url, category, user_id
) VALUES
('Toblerone 100gr', 25, 20.00, 12.00, '2024-06-30', 'images/toblerone.jpg', 'Snacks', 5),
('Coca-Cola 1L', 100, 3.00, 2.00, '2024-07-15', 'images/cocacola.jpg', 'Beverages', 6),
('Milk 1L', 30, 4.50, 3.00, '2024-06-25', 'images/milk.jpg', 'Dairy', 5),
('Frozen Pizza', 40, 15.00, 10.00, '2024-08-05', 'images/pizza.jpg', 'Frozen Foods', 6),
('Oreo Cookies', 50, 6.00, 4.50, '2024-05-30', 'images/oreo.jpg', 'Snacks', 5),
('Pepsi 2L', 75, 5.00, 3.50, '2024-09-10', 'images/pepsi.jpg', 'Beverages', 6),
('Bread Loaf', 60, 2.50, 1.75, '2024-05-22', 'images/bread.jpg', 'Bakery', 5),
('Vanilla Ice Cream', 40, 10.00, 7.00, '2024-10-15', 'images/icecream.jpg', 'Frozen Foods', 6),
('Chicken 1kg', 30, 50.00, 37.50, '2024-11-25', 'images/chicken.jpg', 'Meat', 5),
('Cheese 500gr', 20, 25.00, 20.00, '2024-06-18', 'images/cheese.jpg', 'Dairy', 6),
('Yogurt 1L', 40, 3.50, 2.80, '2024-12-01', 'images/yogurt.jpg', 'Dairy', 5),
('Fish Fillets 1kg', 25, 60.00, 45.00, '2024-07-20', 'images/fish.jpg', 'Seafood', 6),
('Pasta 500gr', 80, 3.00, 2.00, '2024-11-01', 'images/pasta.jpg', 'Pantry', 5),
('Rice 1kg', 60, 4.00, 3.00, '2024-12-15', 'images/rice.jpg', 'Pantry', 6),
('Tomato Sauce', 50, 5.00, 3.50, '2024-06-20', 'images/tomato_sauce.jpg', 'Pantry', 5),
('Cheddar Cheese 200gr', 40, 8.00, 5.00, '2024-07-30', 'images/cheddar.jpg', 'Dairy', 6),
('Sour Cream 250ml', 35, 4.00, 2.50, '2024-10-25', 'images/sour_cream.jpg', 'Dairy', 5),
('Orange Juice 1L', 90, 6.00, 4.00, '2024-08-12', 'images/orange_juice.jpg', 'Beverages', 6),
('Apple Juice 1L', 85, 5.50, 4.00, '2024-09-05', 'images/apple_juice.jpg', 'Beverages', 5),
('Green Tea 100 bags', 70, 15.00, 10.00, '2024-05-18', 'images/green_tea.jpg', 'Beverages', 6),
('Coffee 500gr', 60, 20.00, 15.00, '2024-10-09', 'images/coffee.jpg', 'Beverages', 5),
('Chocolate Cake', 40, 25.00, 18.00, '2024-08-22', 'images/chocolate_cake.jpg', 'Bakery', 6),
('Baguette', 55, 2.00, 1.50, '2024-07-14', 'images/baguette.jpg', 'Bakery', 5),
('Croissant 10 pcs', 45, 10.00, 7.50, '2024-11-29', 'images/croissant.jpg', 'Bakery', 6),
('Salmon Fillet 1kg', 35, 50.00, 40.00, '2024-09-17', 'images/salmon.jpg', 'Seafood', 5),
('Tuna Steak 1kg', 30, 45.00, 35.00, '2024-12-05', 'images/tuna.jpg', 'Seafood', 6),
('Shrimp 1kg', 40, 35.00, 28.00, '2024-08-19', 'images/shrimp.jpg', 'Seafood', 5),
('Potato Chips', 75, 3.50, 2.50, '2024-07-08', 'images/potato_chips.jpg', 'Snacks', 6);
