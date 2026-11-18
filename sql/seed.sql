USE snabplus;

TRUNCATE TABLE applications;

INSERT INTO applications (company_name, contact_name, phone, email, status, created_at, updated_at) VALUES
('ООО «СеверТрейд»', 'Ирина Ковалёва', '+7 (921) 445-12-08', 'i.kovaleva@severtrade.ru', 'new', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('АО «БалтСнаб»', 'Артём Никитин', '+7 (812) 309-77-41', 'a.nikitin@baltsnab.ru', 'new', DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR)),
('ООО «ТехноПарк Урал»', 'Марина Соколова', '+7 (343) 268-14-90', 'm.sokolova@techpark-ural.ru', 'in_progress', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 8 HOUR)),
('ИП Громов П.А.', 'Павел Громов', '+7 (903) 112-55-73', 'gromov.pa@mail.ru', 'in_progress', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
('ООО «Логистика Плюс»', 'Елена Фёдорова', '+7 (495) 781-33-20', 'e.fedorova@logplus.ru', 'done', DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
('ЗАО «МедСнабСервис»', 'Дмитрий Орлов', '+7 (831) 422-09-65', 'd.orlov@medsnab.ru', 'done', DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
('ООО «АгроКомплекс»', 'Наталья Белова', '+7 (846) 270-48-11', 'n.belova@agrokomplex.ru', 'rejected', DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
('ООО «СитиОфис»', 'Кирилл Васильев', '+7 (495) 640-18-27', 'k.vasiliev@cityoffice.ru', 'new', DATE_SUB(NOW(), INTERVAL 30 MINUTE), DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
('АО «ПромИмпорт»', 'Ольга Смирнова', '+7 (383) 355-62-04', 'o.smirnova@promimport.ru', 'in_progress', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 12 HOUR)),
('ООО «РегионСтрой»', 'Сергей Морозов', '+7 (383) 210-77-39', 's.morozov@regionstroy.ru', 'done', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY));
