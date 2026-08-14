-- Service categories + local icon paths
-- Run this on LIVE MySQL (phpMyAdmin / MySQL client).
-- Then upload folder: public/backend/img/service-categories/

UPDATE service_categories SET name='AC Repair', icon='backend/img/service-categories/ac-repair.png', is_active=1, sort_order=1, updated_at=NOW() WHERE slug='ac-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'AC Repair', 'ac-repair', 'backend/img/service-categories/ac-repair.png', 1, 1, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='ac-repair');

UPDATE service_categories SET name='Appliance Repair', icon='backend/img/service-categories/appliance-repair.png', is_active=1, sort_order=2, updated_at=NOW() WHERE slug='appliance-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Appliance Repair', 'appliance-repair', 'backend/img/service-categories/appliance-repair.png', 1, 2, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='appliance-repair');

UPDATE service_categories SET name='Carpenter', icon='backend/img/service-categories/carpenter.png', is_active=1, sort_order=3, updated_at=NOW() WHERE slug='carpenter';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Carpenter', 'carpenter', 'backend/img/service-categories/carpenter.png', 1, 3, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='carpenter');

UPDATE service_categories SET name='CCTV Installation', icon='backend/img/service-categories/cctv-installation.png', is_active=1, sort_order=4, updated_at=NOW() WHERE slug='cctv-installation';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'CCTV Installation', 'cctv-installation', 'backend/img/service-categories/cctv-installation.png', 1, 4, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='cctv-installation');

UPDATE service_categories SET name='Cleaner', icon='backend/img/service-categories/cleaner.png', is_active=1, sort_order=5, updated_at=NOW() WHERE slug='cleaner';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Cleaner', 'cleaner', 'backend/img/service-categories/cleaner.png', 1, 5, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='cleaner');

UPDATE service_categories SET name='Curtains & Blinds', icon='backend/img/service-categories/curtains-blinds.png', is_active=1, sort_order=6, updated_at=NOW() WHERE slug='curtains-blinds';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Curtains & Blinds', 'curtains-blinds', 'backend/img/service-categories/curtains-blinds.png', 1, 6, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='curtains-blinds');

UPDATE service_categories SET name='Deep Cleaning', icon='backend/img/service-categories/deep-cleaning.png', is_active=1, sort_order=7, updated_at=NOW() WHERE slug='deep-cleaning';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Deep Cleaning', 'deep-cleaning', 'backend/img/service-categories/deep-cleaning.png', 1, 7, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='deep-cleaning');

UPDATE service_categories SET name='Drain Cleaning', icon='backend/img/service-categories/drain-cleaning.png', is_active=1, sort_order=8, updated_at=NOW() WHERE slug='drain-cleaning';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Drain Cleaning', 'drain-cleaning', 'backend/img/service-categories/drain-cleaning.png', 1, 8, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='drain-cleaning');

UPDATE service_categories SET name='Electrician', icon='backend/img/service-categories/electrician.png', is_active=1, sort_order=9, updated_at=NOW() WHERE slug='electrician';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Electrician', 'electrician', 'backend/img/service-categories/electrician.png', 1, 9, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='electrician');

UPDATE service_categories SET name='Flooring Specialist', icon='backend/img/service-categories/flooring-specialist.png', is_active=1, sort_order=10, updated_at=NOW() WHERE slug='flooring-specialist';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Flooring Specialist', 'flooring-specialist', 'backend/img/service-categories/flooring-specialist.png', 1, 10, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='flooring-specialist');

UPDATE service_categories SET name='Furniture Assembly', icon='backend/img/service-categories/furniture-assembly.png', is_active=1, sort_order=11, updated_at=NOW() WHERE slug='furniture-assembly';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Furniture Assembly', 'furniture-assembly', 'backend/img/service-categories/furniture-assembly.png', 1, 11, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='furniture-assembly');

UPDATE service_categories SET name='Gardener', icon='backend/img/service-categories/gardener.png', is_active=1, sort_order=12, updated_at=NOW() WHERE slug='gardener';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Gardener', 'gardener', 'backend/img/service-categories/gardener.png', 1, 12, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='gardener');

UPDATE service_categories SET name='Generator Repair', icon='backend/img/service-categories/generator-repair.png', is_active=1, sort_order=13, updated_at=NOW() WHERE slug='generator-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Generator Repair', 'generator-repair', 'backend/img/service-categories/generator-repair.png', 1, 13, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='generator-repair');

UPDATE service_categories SET name='Geyser / Water Heater', icon='backend/img/service-categories/geyser-water-heater.png', is_active=1, sort_order=14, updated_at=NOW() WHERE slug='geyser-water-heater';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Geyser / Water Heater', 'geyser-water-heater', 'backend/img/service-categories/geyser-water-heater.png', 1, 14, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='geyser-water-heater');

UPDATE service_categories SET name='Glass Repair', icon='backend/img/service-categories/glass-repair.png', is_active=1, sort_order=15, updated_at=NOW() WHERE slug='glass-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Glass Repair', 'glass-repair', 'backend/img/service-categories/glass-repair.png', 1, 15, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='glass-repair');

UPDATE service_categories SET name='Handyman', icon='backend/img/service-categories/handyman.png', is_active=1, sort_order=16, updated_at=NOW() WHERE slug='handyman';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Handyman', 'handyman', 'backend/img/service-categories/handyman.png', 1, 16, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='handyman');

UPDATE service_categories SET name='Home Automation', icon='backend/img/service-categories/home-automation.png', is_active=1, sort_order=17, updated_at=NOW() WHERE slug='home-automation';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Home Automation', 'home-automation', 'backend/img/service-categories/home-automation.png', 1, 17, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='home-automation');

UPDATE service_categories SET name='HVAC Technician', icon='backend/img/service-categories/hvac-technician.png', is_active=1, sort_order=18, updated_at=NOW() WHERE slug='hvac-technician';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'HVAC Technician', 'hvac-technician', 'backend/img/service-categories/hvac-technician.png', 1, 18, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='hvac-technician');

UPDATE service_categories SET name='Interior Design', icon='backend/img/service-categories/interior-design.png', is_active=1, sort_order=19, updated_at=NOW() WHERE slug='interior-design';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Interior Design', 'interior-design', 'backend/img/service-categories/interior-design.png', 1, 19, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='interior-design');

UPDATE service_categories SET name='Lock Installation', icon='backend/img/service-categories/lock-installation.png', is_active=1, sort_order=20, updated_at=NOW() WHERE slug='lock-installation';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Lock Installation', 'lock-installation', 'backend/img/service-categories/lock-installation.png', 1, 20, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='lock-installation');

UPDATE service_categories SET name='Locksmith', icon='backend/img/service-categories/locksmith.png', is_active=1, sort_order=21, updated_at=NOW() WHERE slug='locksmith';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Locksmith', 'locksmith', 'backend/img/service-categories/locksmith.png', 1, 21, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='locksmith');

UPDATE service_categories SET name='Mason', icon='backend/img/service-categories/mason.png', is_active=1, sort_order=22, updated_at=NOW() WHERE slug='mason';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Mason', 'mason', 'backend/img/service-categories/mason.png', 1, 22, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='mason');

UPDATE service_categories SET name='Microwave Repair', icon='backend/img/service-categories/microwave-repair.png', is_active=1, sort_order=23, updated_at=NOW() WHERE slug='microwave-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Microwave Repair', 'microwave-repair', 'backend/img/service-categories/microwave-repair.png', 1, 23, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='microwave-repair');

UPDATE service_categories SET name='Moving / Relocation', icon='backend/img/service-categories/moving-relocation.png', is_active=1, sort_order=24, updated_at=NOW() WHERE slug='moving-relocation';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Moving / Relocation', 'moving-relocation', 'backend/img/service-categories/moving-relocation.png', 1, 24, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='moving-relocation');

UPDATE service_categories SET name='Painter', icon='backend/img/service-categories/painter.png', is_active=1, sort_order=25, updated_at=NOW() WHERE slug='painter';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Painter', 'painter', 'backend/img/service-categories/painter.png', 1, 25, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='painter');

UPDATE service_categories SET name='Pest Control', icon='backend/img/service-categories/pest-control.png', is_active=1, sort_order=26, updated_at=NOW() WHERE slug='pest-control';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Pest Control', 'pest-control', 'backend/img/service-categories/pest-control.png', 1, 26, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='pest-control');

UPDATE service_categories SET name='Plumber', icon='backend/img/service-categories/plumber.png', is_active=1, sort_order=27, updated_at=NOW() WHERE slug='plumber';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Plumber', 'plumber', 'backend/img/service-categories/plumber.png', 1, 27, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='plumber');

UPDATE service_categories SET name='Refrigerator Repair', icon='backend/img/service-categories/refrigerator-repair.png', is_active=1, sort_order=28, updated_at=NOW() WHERE slug='refrigerator-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Refrigerator Repair', 'refrigerator-repair', 'backend/img/service-categories/refrigerator-repair.png', 1, 28, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='refrigerator-repair');

UPDATE service_categories SET name='Roofer', icon='backend/img/service-categories/roofer.png', is_active=1, sort_order=29, updated_at=NOW() WHERE slug='roofer';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Roofer', 'roofer', 'backend/img/service-categories/roofer.png', 1, 29, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='roofer');

UPDATE service_categories SET name='Smart Home', icon='backend/img/service-categories/smart-home.png', is_active=1, sort_order=30, updated_at=NOW() WHERE slug='smart-home';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Smart Home', 'smart-home', 'backend/img/service-categories/smart-home.png', 1, 30, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='smart-home');

UPDATE service_categories SET name='Solar Installation', icon='backend/img/service-categories/solar-installation.png', is_active=1, sort_order=31, updated_at=NOW() WHERE slug='solar-installation';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Solar Installation', 'solar-installation', 'backend/img/service-categories/solar-installation.png', 1, 31, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='solar-installation');

UPDATE service_categories SET name='Solar Panel Installer', icon='backend/img/service-categories/solar-panel-installer.png', is_active=1, sort_order=32, updated_at=NOW() WHERE slug='solar-panel-installer';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Solar Panel Installer', 'solar-panel-installer', 'backend/img/service-categories/solar-panel-installer.png', 1, 32, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='solar-panel-installer');

UPDATE service_categories SET name='Tiler', icon='backend/img/service-categories/tiler.png', is_active=1, sort_order=33, updated_at=NOW() WHERE slug='tiler';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Tiler', 'tiler', 'backend/img/service-categories/tiler.png', 1, 33, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='tiler');

UPDATE service_categories SET name='TV Installation', icon='backend/img/service-categories/tv-installation.png', is_active=1, sort_order=34, updated_at=NOW() WHERE slug='tv-installation';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'TV Installation', 'tv-installation', 'backend/img/service-categories/tv-installation.png', 1, 34, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='tv-installation');

UPDATE service_categories SET name='Washing Machine Repair', icon='backend/img/service-categories/washing-machine-repair.png', is_active=1, sort_order=35, updated_at=NOW() WHERE slug='washing-machine-repair';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Washing Machine Repair', 'washing-machine-repair', 'backend/img/service-categories/washing-machine-repair.png', 1, 35, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='washing-machine-repair');

UPDATE service_categories SET name='Waterproofing', icon='backend/img/service-categories/waterproofing.png', is_active=1, sort_order=36, updated_at=NOW() WHERE slug='waterproofing';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Waterproofing', 'waterproofing', 'backend/img/service-categories/waterproofing.png', 1, 36, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='waterproofing');

UPDATE service_categories SET name='Water Tank Service', icon='backend/img/service-categories/water-tank-service.png', is_active=1, sort_order=37, updated_at=NOW() WHERE slug='water-tank-service';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Water Tank Service', 'water-tank-service', 'backend/img/service-categories/water-tank-service.png', 1, 37, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='water-tank-service');

UPDATE service_categories SET name='Welder', icon='backend/img/service-categories/welder.png', is_active=1, sort_order=38, updated_at=NOW() WHERE slug='welder';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'Welder', 'welder', 'backend/img/service-categories/welder.png', 1, 38, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='welder');

UPDATE service_categories SET name='WiFi / Internet', icon='backend/img/service-categories/wifi-internet.png', is_active=1, sort_order=39, updated_at=NOW() WHERE slug='wifi-internet';
INSERT INTO service_categories (name, slug, icon, is_active, sort_order, created_at, updated_at)
SELECT 'WiFi / Internet', 'wifi-internet', 'backend/img/service-categories/wifi-internet.png', 1, 39, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM service_categories WHERE slug='wifi-internet');
