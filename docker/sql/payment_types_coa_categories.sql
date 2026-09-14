-- Adds matching Chart of Accounts (subsidiary-account level, type=6) entries
-- for the 9 new WING BANK payment types, so the bcash/teller "Confirm Payment"
-- flow (which looks up a coa_categories row whose `name` matches the selected
-- payment type text) stops silently failing for these.
--
-- Field values mirror an existing working entry (id 92346, "000815756 ABA
-- Revenue (USD) - East Sihanouk Park (ESP)"): parent_id 50 ("Current Accounts
-- (Nostro) with Banks rated AAA to AA-"), nbc_code 115100, currency 2 (USD),
-- sector_id 01. `name` must exactly match the payment type text used in
-- tb_payment_types for the lookup (LIKE match) to succeed.
--
-- NOTE: this mirrors the structural shape of a working entry so the payment
-- flow stops crashing; it has not been reviewed by an accountant for correct
-- chart-of-accounts classification. Run once per environment.

INSERT INTO `tb_coa_categories` (`parent_id`, `nbc_code`, `account_code`, `name`, `currency`, `sector_id`, `description`, `type`, `symbol`) VALUES
(50, '115100', '115102-01-2001', 'ESP-103286601 WING BANK-East Sihanouk Park', 2, '01', 'ESP-103286601 WING BANK-East Sihanouk Park', '6', ''),
(50, '115100', '115102-01-2002', 'EKC-103276014 WING BANK-East Kean Svay City', 2, '01', 'EKC-103276014 WING BANK-East Kean Svay City', '6', ''),
(50, '115100', '115102-01-2003', 'ENC-103275978 WING BANK-East Natural City', 2, '01', 'ENC-103275978 WING BANK-East Natural City', '6', ''),
(50, '115100', '115102-01-2004', 'ELH-104126619 WING BANK-East Land and Home', 2, '01', 'ELH-104126619 WING BANK-East Land and Home', '6', ''),
(50, '115100', '115102-01-2005', 'EMC-100736314 WING BANK-East Mini Condo', 2, '01', 'EMC-100736314 WING BANK-East Mini Condo', '6', ''),
(50, '115100', '115102-01-2006', 'ESC-103286557 WING BANK-East Sihnauok City', 2, '01', 'ESC-103286557 WING BANK-East Sihnauok City', '6', ''),
(50, '115100', '115102-01-2007', 'EPL-105137596 WING BANK-East Prime Land', 2, '01', 'EPL-105137596 WING BANK-East Prime Land', '6', ''),
(50, '115100', '115102-01-2008', 'ESS-105137804 WING BANK-East SenSok Condominium', 2, '01', 'ESS-105137804 WING BANK-East SenSok Condominium', '6', ''),
(50, '115100', '115102-01-2009', 'EDH-105137842 WING BANK-East Svay Chrum', 2, '01', 'EDH-105137842 WING BANK-East Svay Chrum', '6', '');
