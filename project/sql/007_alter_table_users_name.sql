ALTER TABLE Users ADD COLUMN first_name varchar(20) default '';
ALTER TABLE Users ADD COLUMN last_name varchar(20) default '';
ALTER TABLE Users ADD COLUMN privacy varchar(8) default 'private';