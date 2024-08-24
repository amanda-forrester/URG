-- Create the parts table
CREATE TABLE parts (
    id SERIAL PRIMARY KEY,
    part_name VARCHAR(50),
    vehicle_make VARCHAR(50),
    vehicle_model VARCHAR(50),
    date_added DATE,
    status VARCHAR(20)
);

-- Create the requests table
CREATE TABLE requests (
    id SERIAL PRIMARY KEY,
    part_id INT REFERENCES parts(id),
    request_date DATE,
    fulfilled BOOLEAN
);

-- Inserting more data into the 'parts' table
INSERT INTO parts (part_name, vehicle_make, vehicle_model, date_added, status) VALUES
('Alternator', 'Toyota', 'Camry', '2024-07-01', 'available'),
('Radiator', 'Honda', 'Civic', '2024-06-15', 'sold'),
('Transmission', 'Ford', 'F-150', '2024-05-10', 'available'),
('Brake Pads', 'Chevrolet', 'Impala', '2024-08-01', 'sold'),
('Alternator', 'Toyota', 'Corolla', '2024-04-20', 'sold'),
('Radiator', 'Ford', 'Mustang', '2024-07-25', 'available'),
('Transmission', 'Chevrolet', 'Tahoe', '2024-06-01', 'sold'),
('Air Filter', 'Nissan', 'Altima', '2024-07-10', 'available'),
('Battery', 'Tesla', 'Model S', '2024-08-05', 'available'),
('Tire', 'BMW', '3 Series', '2024-05-15', 'sold'),
('Headlight', 'Honda', 'Accord', '2024-06-25', 'available'),
('Windshield', 'Toyota', 'RAV4', '2024-07-15', 'available'),
('Exhaust', 'Ford', 'Explorer', '2024-06-20', 'sold'),
('Transmission', 'Honda', 'CR-V', '2024-08-03', 'available'),
('Brake Pads', 'Volkswagen', 'Jetta', '2024-06-30', 'sold'),
('Alternator', 'Ford', 'Focus', '2024-07-22', 'available'),
('Battery', 'Chevrolet', 'Volt', '2024-05-25', 'sold'),
('Radiator', 'Nissan', 'Rogue', '2024-06-18', 'sold'),
('Exhaust', 'Chevrolet', 'Malibu', '2024-07-05', 'available'),
('Windshield', 'Ford', 'Fusion', '2024-07-20', 'available'),
('Tire', 'Subaru', 'Outback', '2024-08-06', 'sold'),
('Headlight', 'Jeep', 'Wrangler', '2024-07-28', 'available'),
('Battery', 'Honda', 'Fit', '2024-06-10', 'available'),
('Air Filter', 'Ford', 'Escape', '2024-07-01', 'sold'),
('Brake Pads', 'Toyota', 'Prius', '2024-08-07', 'available');

-- Inserting more data into the 'requests' table
INSERT INTO requests (part_id, request_date, fulfilled) VALUES
(1, '2024-07-02', false),  
(2, '2024-06-16', true),   
(3, '2024-06-20', false),  
(5, '2024-04-25', true),   
(5, '2024-04-30', true),   
(6, '2024-07-26', false),  
(4, '2024-08-02', true),   
(7, '2024-06-05', true),   
(8, '2024-07-12', false),  
(9, '2024-08-07', true),   
(10, '2024-05-17', true),  
(11, '2024-06-27', false), 
(12, '2024-07-18', false), 
(13, '2024-06-22', true),  
(14, '2024-08-04', false), 
(15, '2024-07-01', true),  
(16, '2024-07-23', false), 
(17, '2024-05-28', true),  
(18, '2024-06-19', true),  
(19, '2024-07-06', false), 
(20, '2024-07-21', false), 
(21, '2024-08-08', true),  
(22, '2024-07-30', false), 
(23, '2024-06-12', false), 
(24, '2024-07-03', true),  
(25, '2024-08-08', false);


