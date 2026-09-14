-- Schema sync: brings UAT/production in line with the local schema.
-- Discovered 2026-09-14 when addBcash silently failed because
-- tb_till_transaction.receipt_no did not exist on UAT.
-- Run once per environment. Check each column does not already exist first.

ALTER TABLE tb_till_transaction ADD COLUMN receipt_no VARCHAR(50) NULL;
ALTER TABLE tb_transactions_posting ADD COLUMN methode VARCHAR(255) NULL;
ALTER TABLE tb_loan_history ADD COLUMN interest_status VARCHAR(20) NULL;
ALTER TABLE tb_loan_history ADD COLUMN principal_status VARCHAR(20) NULL;
ALTER TABLE tb_projects ADD COLUMN project_code VARCHAR(20) NULL;

-- Missing tables follow (paste CREATE TABLE blocks from missing_tables.sql):
