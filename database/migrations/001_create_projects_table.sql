CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT
);

INSERT INTO projects (title, description) VALUES
    ('Projekt A', 'Opis projektu A'),
    ('Projekt B', 'Opis projektu B'),
    ('Projekt C', 'Opis projektu C');