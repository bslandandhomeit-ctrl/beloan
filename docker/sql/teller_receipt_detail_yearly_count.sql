-- Fast row-count check for the Teller "Cashier Receipt Report - Details"
-- yearly export, before running the full detail query
-- (teller_receipt_detail_yearly_report.sql), which joins many more tables
-- and can be slow on a large production dataset.
--
-- This drops every join that doesn't affect the row count (clients,
-- projects, units, unit_types, till_account, users) and keeps only
-- drawdown_account -> loans -> loan_payments, which IS needed: the
-- loan_payments self-join can fan out a transaction row into duplicates
-- when a loan has tied "last payment" records (see the tiebreaker note
-- below) — same logic as the full report, just without the unrelated
-- lookup joins.
--
-- One single, self-contained SELECT statement. Edit the year (and
-- optional filters) in the literal values below.

SELECT COUNT(*) AS transaction_count
FROM tb_till_transaction tt
LEFT JOIN tb_drawdown_account da ON da.id = tt.drawdown_acc_id
LEFT JOIN tb_loans           lo ON lo.drawdown_acc = da.account_no
LEFT JOIN tb_loan_payments last_lp
       ON last_lp.loan_id = lo.id
LEFT JOIN tb_loan_payments newer_lp
       ON newer_lp.loan_id = last_lp.loan_id
      AND (newer_lp.payment_month > last_lp.payment_month
           OR (newer_lp.payment_month = last_lp.payment_month
               AND newer_lp.repayment_date > last_lp.repayment_date)
           OR (newer_lp.payment_month = last_lp.payment_month
               AND newer_lp.repayment_date = last_lp.repayment_date
               AND newer_lp.id > last_lp.id))
WHERE tt.approve_status = 1
  AND tt.tranx_time >= '2026-01-01 00:00:00' AND tt.tranx_time < '2027-01-01 00:00:00'   -- ---- EDIT YEAR HERE ----
  AND newer_lp.loan_id IS NULL;
