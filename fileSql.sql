ALTER TABLE notes MODIFY FOREIGN KEY (module) REFERENCES modules(id_module) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE notes MODIFY FOREIGN KEY (student) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE;