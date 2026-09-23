-- Read-only inspection query for the Teller "Cashier Receipt Report - Details"
-- page/export (TellerController@teller_receipt_detail, route: teller_receipt_detail).
--
-- Purpose: let you eyeball the exact rows a yearly export would produce,
-- directly in the DB, without going through the app. Mirrors the same
-- LEFT JOINs, WHERE filters and derived columns (last loan payment,
-- admin fee, paid amount, grand totals) that the controller/Blade views
-- compute in PHP.
--
-- This file is ONE single, self-contained SELECT statement — no session
-- variables, no SET, no second statement after it, nothing else to run.
-- Select the whole file and run it as-is in any SQL client.
--
-- Output columns are ordered/labeled to match the on-screen report table
-- exactly (No, Transaction Date, Receipt No, Customer ID, Customer Name,
-- Project, Unit, Methode, Payment Type, Description, PMT.No, PMT Date,
-- Interest, Principal, Other Fee, Paid Amount, Teller Name, Teller Status),
-- with the report header's totals (receipt_count / total_paid_amount_signed
-- / grand_total_cash_in_only) trailing at the end — same repeated numbers
-- on every row, just look at row 1.
--
-- Table names use the `tb_` prefix because the app's default mysql
-- connection sets 'prefix' => 'tb_' (html/config/database.php) — Eloquent
-- model classes reference the unprefixed name (e.g. TillTransaction's
-- $table = 'till_transaction') but the actual physical table is
-- `tb_till_transaction`. Same for every other table joined below.
--
-- To change filters: edit the literal values inside BOTH "p" sub-selects
-- below (1 of 2, for the detail rows; 2 of 2, for the totals) — keep them
-- identical, otherwise the totals won't match the detail rows shown.
-- Leave a filter as NULL to skip it, same as leaving the report's form
-- field blank.

SELECT
    (@rn := @rn + 1)                                                AS `No`,
    DATE_FORMAT(d.tranx_time, '%d-%b-%Y')                           AS `Transaction Date`,
    d.receipt_no                                                    AS `Receipt No`,
    d.customer_id                                                   AS `Customer ID`,
    d.client_name                                                   AS `Customer Name`,
    d.project_name                                                  AS `Project`,
    d.unit_code                                                     AS `Unit`,
    d.methode                                                       AS `Methode`,
    d.deposit_type                                                  AS `Payment Type`,
    d.description                                                   AS `Description`,
    d.effective_pmt_no                                              AS `PMT.No`,
    DATE_FORMAT(COALESCE(d.effective_pmt_date, d.tranx_time), '%d-%b-%Y') AS `PMT Date`,
    d.effective_interest                                            AS `Interest`,
    d.effective_principal                                           AS `Principal`,
    d.admin_fee                                                     AS `Other Fee`,
    d.paid_amount                                                   AS `Paid Amount`,
    d.username                                                      AS `Teller Name`,
    IF(d.approve_status = 1, 'Authorized', 'Unauthorized')          AS `Teller Status`,

    -- report header totals (same numbers on every row — see note above)
    d.receipt_count               AS receipt_count,
    d.total_paid_amount_signed    AS total_paid_amount_signed,
    d.grand_total_cash_in_only    AS grand_total_cash_in_only

FROM (
    SELECT
        tt.tranx_time,
        tt.receipt_no,
        cl.cus_acc                                                      AS customer_id,
        cl.client_name,
        pr.short_code                                                   AS project_name,
        un.code                                                         AS unit_code,
        tt.methode,
        tt.deposit_type,
        tt.description,
        tt.approve_status,
        us.name                                                         AS username,

        -- effective PMT no / date / interest / principal:
        -- same rule the Blade view applies for deposit_type='Loan Installment'
        -- AND type='Cash Deposit' rows: use the transaction's own pmt_* values
        -- when principal is set on the row itself, otherwise fall back to the
        -- loan's last payment record. All other rows show 0 / NULL, same as the app.
        CASE
            WHEN tt.deposit_type = 'Loan Installment' AND tt.type = 'Cash Deposit' THEN
                CASE WHEN tt.principal IS NOT NULL AND tt.principal <> 0 THEN tt.pmt_no ELSE last_lp.payment_month END
            ELSE NULL
        END                                                              AS effective_pmt_no,
        CASE
            WHEN tt.deposit_type = 'Loan Installment' AND tt.type = 'Cash Deposit' THEN
                CASE WHEN tt.principal IS NOT NULL AND tt.principal <> 0 THEN tt.pmt_date ELSE last_lp.repayment_date END
            ELSE NULL
        END                                                              AS effective_pmt_date,
        CASE
            WHEN tt.deposit_type = 'Loan Installment' AND tt.type = 'Cash Deposit' THEN
                ROUND(CASE WHEN tt.principal IS NOT NULL AND tt.principal <> 0 THEN tt.interest ELSE last_lp.paid_interest END, 2)
            ELSE 0
        END                                                              AS effective_interest,
        CASE
            WHEN tt.deposit_type = 'Loan Installment' AND tt.type = 'Cash Deposit' THEN
                ROUND(CASE WHEN tt.principal IS NOT NULL AND tt.principal <> 0 THEN tt.principal ELSE last_lp.paid_principal END, 2)
            ELSE 0
        END                                                              AS effective_principal,

        -- admin fee: sum of fee_charges rows whose note mentions "Admin Fee"
        -- (fee_charges.loan_id actually stores till_transaction.id — see
        -- TillTransaction::feecharge() — matches the PHP preg_match('/Admin Fee/i', ...))
        IFNULL(fc.admin_fee, 0)                                         AS admin_fee,

        -- paid amount: cash_in normally, negative cash_out for withdrawals
        CASE
            WHEN tt.type = 'Withdraw' THEN -1 * IFNULL(tt.cash_out, 0)
            ELSE IFNULL(tt.cash_in, 0)
        END                                                              AS paid_amount,

        -- report header totals, computed once as a single row and joined onto
        -- every detail row below. Standalone (not correlated to "p" above) so it
        -- works without MySQL 8's LATERAL derived tables — that's why its filter
        -- literals are a separate copy that must be kept in sync with "p" above.
        agg.receipt_count,
        agg.total_paid_amount_signed,
        agg.grand_total_cash_in_only

    FROM (
        -- ---- EDIT HERE (1 of 2) ----
        SELECT
            2026   AS report_year,
            NULL   AS customer_id,     -- drawdown_account.client_id
            NULL   AS project_id,      -- drawdown_account.project_id
            NULL   AS company_id,      -- projects.company_id
            NULL   AS company_type,    -- till_transaction.deposit_company (exact match, e.g. 'Real Estate')
            NULL   AS teller_user_id,  -- users.id (the teller's assign_user_id)
            NULL   AS methode,         -- 'Cash In Vault' | 'Cash on Hand-Teller' | 'Bank Transfer' | 'Bank China' | any exact methode string
            NULL   AS deposit_type,    -- e.g. 'Loan Installment', 'Deposit', 'Down-Payment', ...
            NULL   AS search_term      -- free-text, matches description/deposit_company/dealer/dealer_en/client_name/contract_id/cus_acc/unit_type/unit_code
    ) p
    CROSS JOIN tb_till_transaction tt
    LEFT JOIN tb_drawdown_account da ON da.id = tt.drawdown_acc_id
    LEFT JOIN tb_loans           lo ON lo.drawdown_acc = da.account_no
    LEFT JOIN tb_till_account    ta ON ta.id = tt.till_account_id
    LEFT JOIN tb_users           us ON us.id = ta.assign_user_id
    LEFT JOIN tb_clients         cl ON cl.id = da.client_id
    LEFT JOIN tb_projects        pr ON pr.id = da.project_id
    LEFT JOIN tb_units           un ON un.id = da.unit_id
    LEFT JOIN tb_unit_types      ut ON ut.id = da.unit_type_id

    -- last payment per loan (mirrors LoanPayments::orderBy(payment_month DESC,
    -- repayment_date DESC)->first() done per loan in the controller — greatest-
    -- n-per-group via self-LEFT-JOIN, no window functions needed).
    -- The "OR ... newer_lp.id > last_lp.id" tiebreaker is required: without it,
    -- two loan_payments rows tied on the same payment_month+repayment_date
    -- BOTH survive the anti-join below, silently duplicating every transaction
    -- linked to that loan. PHP's ->get()->first() never has this problem
    -- because it always returns exactly one row regardless of ties.
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

    -- admin fee total per transaction
    LEFT JOIN (
        SELECT loan_id, SUM(charge_amount) AS admin_fee
        FROM tb_fee_charges
        WHERE note REGEXP 'Admin Fee'
        GROUP BY loan_id
    ) fc ON fc.loan_id = tt.id

    CROSS JOIN (
        -- ---- EDIT HERE (2 of 2) — keep identical to the block above ----
        SELECT
            COUNT(*)                                                                                        AS receipt_count,
            SUM(CASE WHEN tt2.type = 'Withdraw' THEN -1 * IFNULL(tt2.cash_out, 0) ELSE IFNULL(tt2.cash_in, 0) END) AS total_paid_amount_signed,
            SUM(IFNULL(tt2.cash_in, 0))                                                                     AS grand_total_cash_in_only
        FROM (
            SELECT
                2026   AS report_year,
                NULL   AS customer_id,
                NULL   AS project_id,
                NULL   AS company_id,
                NULL   AS company_type,
                NULL   AS teller_user_id,
                NULL   AS methode,
                NULL   AS deposit_type,
                NULL   AS search_term
        ) p2
        CROSS JOIN tb_till_transaction tt2
        LEFT JOIN tb_drawdown_account da2 ON da2.id = tt2.drawdown_acc_id
        LEFT JOIN tb_loans           lo2 ON lo2.drawdown_acc = da2.account_no
        LEFT JOIN tb_till_account    ta2 ON ta2.id = tt2.till_account_id
        LEFT JOIN tb_users           us2 ON us2.id = ta2.assign_user_id
        LEFT JOIN tb_clients         cl2 ON cl2.id = da2.client_id
        LEFT JOIN tb_projects        pr2 ON pr2.id = da2.project_id
        LEFT JOIN tb_units           un2 ON un2.id = da2.unit_id
        LEFT JOIN tb_unit_types      ut2 ON ut2.id = da2.unit_type_id
        WHERE tt2.approve_status = 1
          AND tt2.tranx_time >= CONCAT(p2.report_year, '-01-01 00:00:00')
          AND tt2.tranx_time <  CONCAT(p2.report_year + 1, '-01-01 00:00:00')
          AND (p2.customer_id    IS NULL OR da2.client_id = p2.customer_id)
          AND (p2.project_id     IS NULL OR da2.project_id = p2.project_id)
          AND (p2.company_id     IS NULL OR pr2.company_id = p2.company_id)
          AND (p2.company_type   IS NULL OR tt2.deposit_company = p2.company_type)
          AND (p2.teller_user_id IS NULL OR us2.id = p2.teller_user_id)
          AND (p2.deposit_type   IS NULL OR tt2.deposit_type LIKE CONCAT('%', p2.deposit_type))
          AND (
                p2.methode IS NULL
                OR (
                     p2.methode = 'Bank Transfer'
                     AND tt2.methode NOT IN ('Cash on Hand-Teller', 'Lay Sreyleak', 'Cash In Vault')
                   )
                OR (p2.methode = 'Bank China' AND tt2.methode LIKE '%Lay Sreyleak')
                OR (p2.methode NOT IN ('Bank Transfer', 'Bank China') AND tt2.methode LIKE CONCAT('%', p2.methode))
              )
          AND (
                p2.search_term IS NULL
                OR tt2.description     LIKE CONCAT('%', p2.search_term, '%')
                OR tt2.deposit_company LIKE CONCAT('%', p2.search_term, '%')
                OR pr2.dealer_en       LIKE CONCAT('%', p2.search_term, '%')
                OR pr2.dealer          LIKE CONCAT('%', p2.search_term, '%')
                OR cl2.client_name     LIKE CONCAT('%', p2.search_term, '%')
                OR lo2.contract_id     LIKE CONCAT('%', p2.search_term, '%')
                OR cl2.cus_acc         LIKE CONCAT('%', p2.search_term, '%')
                OR ut2.name            LIKE CONCAT('%', p2.search_term, '%')
                OR un2.code            LIKE CONCAT('%', p2.search_term, '%')
              )
    ) agg

    WHERE tt.approve_status = 1
      AND tt.tranx_time >= CONCAT(p.report_year, '-01-01 00:00:00')
      AND tt.tranx_time <  CONCAT(p.report_year + 1, '-01-01 00:00:00')
      AND newer_lp.loan_id IS NULL                                -- keeps only the latest payment row per loan
      AND (p.customer_id    IS NULL OR da.client_id = p.customer_id)
      AND (p.project_id     IS NULL OR da.project_id = p.project_id)
      AND (p.company_id     IS NULL OR pr.company_id = p.company_id)
      AND (p.company_type   IS NULL OR tt.deposit_company = p.company_type)
      AND (p.teller_user_id IS NULL OR us.id = p.teller_user_id)
      AND (p.deposit_type   IS NULL OR tt.deposit_type LIKE CONCAT('%', p.deposit_type))
      AND (
            p.methode IS NULL
            OR (
                 p.methode = 'Bank Transfer'
                 AND tt.methode NOT IN ('Cash on Hand-Teller', 'Lay Sreyleak', 'Cash In Vault')
               )
            OR (p.methode = 'Bank China' AND tt.methode LIKE '%Lay Sreyleak')
            OR (p.methode NOT IN ('Bank Transfer', 'Bank China') AND tt.methode LIKE CONCAT('%', p.methode))
          )
      AND (
            p.search_term IS NULL
            OR tt.description     LIKE CONCAT('%', p.search_term, '%')
            OR tt.deposit_company LIKE CONCAT('%', p.search_term, '%')
            OR pr.dealer_en       LIKE CONCAT('%', p.search_term, '%')
            OR pr.dealer          LIKE CONCAT('%', p.search_term, '%')
            OR cl.client_name     LIKE CONCAT('%', p.search_term, '%')
            OR lo.contract_id     LIKE CONCAT('%', p.search_term, '%')
            OR cl.cus_acc         LIKE CONCAT('%', p.search_term, '%')
            OR ut.name            LIKE CONCAT('%', p.search_term, '%')
            OR un.code            LIKE CONCAT('%', p.search_term, '%')
          )
    ORDER BY tt.tranx_time
) d
CROSS JOIN (SELECT @rn := 0) rn_init
ORDER BY d.tranx_time;
