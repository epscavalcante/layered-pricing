DROP TABLE IF EXISTS layers;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS prices;

CREATE TABLE layers (
    id TEXT PRIMARY KEY,
    layer_id TEXT DEFAULT NULL,
    code TEXT NOT NULL,
    type TEXT NOT NULL,
    discount_type TEXT DEFAULT NULL,
    discount_value INTEGER DEFAULT NULL,
    description TEXT DEFAULT NULL,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

CREATE TABLE products (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

CREATE TABLE prices (
    id TEXT PRIMARY KEY,
    product_id TEXT NOT NULL,
    layer_id TEXT NOT NULL,
    value_cents INTEGER NOT NULL,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

-- Dados iniciais
INSERT INTO layers (id, code, description, type) VALUES
('01J8M5R4G2R7D6ZP8R1B7QH8YJ', 'DEFAULT_LAYER', 'Tabela base de exemplo', 'NORMAL');

INSERT INTO products (id, name) VALUES
('01J8M5R6T9Q9H8X7L2E9ZP4K8C', 'Produto A'),
('01J8M5R7B5P3F4M6Q8C9W1E2ZK', 'Produto B'),
('01J8M5R8L7D1V2K3N5P4F6Y9XJ', 'Produto C');

INSERT INTO prices (id, product_id, layer_id, value_cents) VALUES
('01J8M5R9P8L2K3F4M7N1ZQ5H6X', '01J8M5R6T9Q9H8X7L2E9ZP4K8C', '01J8M5R4G2R7D6ZP8R1B7QH8YJ', 1000),
('01J8M5RAP3F4M6Q8C9W1E2ZK7L', '01J8M5R7B5P3F4M6Q8C9W1E2ZK', '01J8M5R4G2R7D6ZP8R1B7QH8YJ', 2500),
('01J8M5RBL7D1V2K3N5P4F6Y9XJ', '01J8M5R8L7D1V2K3N5P4F6Y9XJ', '01J8M5R4G2R7D6ZP8R1B7QH8YJ', 4500);
