-- Create transactions table if it doesn't exist
CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    amount DECIMAL(20,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create indexes for faster queries
CREATE INDEX IF NOT EXISTS idx_transactions_user_id ON transactions(user_id);
CREATE INDEX IF NOT EXISTS idx_transactions_type ON transactions(type);
CREATE INDEX IF NOT EXISTS idx_transactions_created_at ON transactions(created_at);

-- Add balance and active_deposit columns to users table if they don't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS balance DECIMAL(20,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS active_deposit DECIMAL(20,2) DEFAULT 0.00;

-- Add updated_at and rejection_reason columns to investments table if they don't exist
ALTER TABLE investments 
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS rejection_reason TEXT,
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'pending'; 