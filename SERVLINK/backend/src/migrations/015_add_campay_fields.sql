ALTER TABLE transactions
  ADD COLUMN IF NOT EXISTS campay_reference TEXT,
  ADD COLUMN IF NOT EXISTS phone TEXT,
  ADD COLUMN IF NOT EXISTS campay_status TEXT,
  ADD COLUMN IF NOT EXISTS paid_at TIMESTAMPTZ;

CREATE INDEX IF NOT EXISTS idx_transactions_campay_ref ON transactions(campay_reference);
