ALTER TABLE ms_store_sales CHANGE COLUMN `approved` status INT(1);
ALTER TABLE ms_store_sales ADD COLUMN approved_by INT;
ALTER TABLE ms_store_sales ADD COLUMN approved_at DATE NULL;